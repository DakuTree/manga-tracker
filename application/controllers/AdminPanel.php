<?php declare(strict_types=1); defined('BASEPATH') OR exit('No direct script access allowed');

class AdminPanel extends Admin_Controller {
	public function __construct() {
		parent::__construct();

		$this->load->library('table');

		$this->load->helper('form');
		$this->load->library('form_validation');
	}

	public function index() : void {
		$this->header_data['title'] = 'Admin Panel';
		$this->header_data['page']  = 'admin-panel';

		$this->body_data['complete_list'] = array_merge([['id', 'site_class', 'url']], $this->_list_complete_titles());
		$this->body_data['id_sql']        = 'SELECT * FROM `tracker_titles` WHERE id IN('.implode(',', array_column($this->body_data['complete_list'], 'id')).')';

		$template = array(
			'table_open' => '<table class="table table-striped">'
		);

		$this->table->set_template($template);
		$this->_render_page('AdminPanel');
	}

	public function update_normal() {
		set_time_limit(0);

		ob_start();
		$this->Tracker->admin->updateLatestChapters();
		ob_end_clean();

		$this->_redirect('Normal Update complete.');
	}
	public function update_custom() {
		set_time_limit(0);
		ob_start();
		$this->Tracker->admin->updateCustom();
		ob_end_clean();

		$this->_redirect('Custom Update complete.');
	}
	public function update_titles() {
		set_time_limit(0);
		$this->Tracker->admin->updateTitles();

		$this->_redirect('(Actual) Titles updated.');
	}
	public function update_mal_id() {
		set_time_limit(0);
		$this->_update_mal_backend();

		$this->_redirect('MAL Backend IDs updated.');
	}
	public function populate_db() {
		if(ENVIRONMENT === 'development') {
			//Populate list
			$randomUpdateData = [
				['mangadex.org', '18806:--:English', '12612:--:v1/c1'],
				['helveticascans.com', 'mousou-telepathy', 'en/0/1'],
				['elpsycongroo.tk', 'otomedanshi', 'en/1/1']
			];
			foreach($randomUpdateData as $updateData) {
				$updateData[] = TRUE; //Active marker
				$this->Tracker->list->update($this->User->id, ...$updateData);

			}

			//Populate favorites
			$randomFavouriteData = [
				['mangadex.org', '18806:--:English', '306123:--:c19', 10],
				['helveticascans.com', 'mousou-telepathy', 'en/0/564', NULL],
				['elpsycongroo.tk', 'otomedanshi', 'en/2/239', NULL]
			];
			foreach($randomFavouriteData as $favouriteData) {
				$favouriteData[] = FALSE;
				$this->Tracker->favourites->set($this->User->id, ...$favouriteData);
			}

			$this->_redirect('Populated Dev DB with data.');
		} else {
			$this->_redirect('Populate Dev DB failed as ENVIRONMENT is not development.');
		}
	}

	private function _redirect(string $message) : void {
		$this->session->set_flashdata('notices', $message);
		redirect(site_url('admin_panel'));
	}

	private function _list_complete_titles() {
		$query = $this->db->select('tracker_titles.id, tracker_sites.site_class, tracker_titles.title, tracker_titles.title_url')
		                  ->from('tracker_chapters')
		                  ->join('tracker_titles', 'tracker_chapters.title_id = tracker_titles.id', 'left')
		                  ->join('tracker_sites', 'tracker_sites.id = tracker_titles.site_id', 'left')
		                  ->like('tracker_chapters.tags', 'complete')
		                  ->where('tracker_titles.status', 0)
		                  ->get();

		$completeList = [];
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$data = [
					'id'         => $row->id,
					'site_class' => $row->site_class,
					'url'        => "<a href='".$this->Tracker->sites->{$row->site_class}->getFullTitleURL($row->title_url)."'>{$row->title}</a>"
				];
				$completeList[] = $data;
			}
		}

		return $completeList;
	}

	private function _update_mal_backend() : void {
		//Would prefer to use the query generator here, but don't think it's possible with what I'd like to do here.

		//Set backend MAL id if more than one person has it set as the same ID.
		//- This should be bumped up as we get more users to avoid abuse.
		$this->db->query('
			UPDATE
				tracker_titles dest,
				(
					SELECT tt.id, tc.mal_id
					FROM `tracker_chapters` tc
					LEFT JOIN `tracker_titles` tt ON tt.`id` = tc.`title_id`
					WHERE tt.mal_id IS NULL AND tc.mal_id IS NOT NULL
					GROUP BY tt.id, tc.mal_id
					HAVING COUNT(tc.mal_id) > 1
				) src
			SET dest.mal_id = src.mal_id
			WHERE dest.id = src.id
		');

		//Set backend MAL id if an admin has it set.
		//TODO: Preferably we'd have a trusted users group, but that is for later down the line...
		$this->db->query('
			UPDATE
				tracker_titles dest,
				(
					SELECT tt.id, tc.mal_id
					FROM `tracker_chapters` tc
					LEFT JOIN `tracker_titles` tt ON tt.`id` = tc.`title_id`
					LEFT JOIN `auth_users_groups` aug ON tc.`user_id` = aug.`user_id`
					WHERE tc.mal_id IS NOT NULL
					AND aug.`group_id` = 1
				) src
			SET dest.mal_id = src.mal_id
			WHERE dest.id = src.id
		');
	}
}
