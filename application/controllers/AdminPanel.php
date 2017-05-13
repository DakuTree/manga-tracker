<?php declare(strict_types=1); defined('BASEPATH') OR exit('No direct script access allowed');

class AdminPanel extends Admin_Controller {
	public function __construct() {
		parent::__construct();

		$this->load->library('table');

		$this->load->helper('form');
		$this->load->library('form_validation');
	}

	public function index() {
		$this->header_data['title'] = "Admin Panel";
		$this->header_data['page']  = "admin-panel";

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
		$this->Tracker->admin->updateLatestChapters();
	}
	public function update_custom() {
		set_time_limit(0);
		$this->Tracker->admin->updateCustom();
	}
	public function update_titles() {
		set_time_limit(0);
		$this->Tracker->admin->updateTitles();
	}
	public function convert_mal_tags() {
		set_time_limit(0);
		$this->_update_mal_id();
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

	private function _update_mal_id() : void {
		$query = $this->db->select('id, tags')
		                  ->from('tracker_chapters')
		                  ->where('tags REGEXP "[[:<:]]mal:([0-9]+|none)[[:>:]]"', NULL, FALSE)
		                  ->where('mal_id', NULL)
		                  ->get();


		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				preg_match('/\\bmal:([0-9]+|none)\\b/', $row->tags, $matches);

				if(!empty($matches)) {
					$malID = ($matches[1] !== 'none' ? $matches[1] : '0');

					$this->db->set(['mal_id' => $malID, 'last_updated' => NULL])
					         ->where('id', $row->id)
					         ->update('tracker_chapters');
				}
			}
		}
	}
}
