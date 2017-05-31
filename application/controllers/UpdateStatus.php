<?php declare(strict_types=1); defined('BASEPATH') OR exit('No direct script access allowed');

class UpdateStatus extends MY_Controller {
	public function __construct() {
		parent::__construct();

		$this->load->library('table');
	}

	public function index() : void {
		$this->header_data['title'] = "Update Status";
		$this->header_data['page']  = "update_status";

		$this->body_data['updates'] = array_merge([['Site', 'Title']], $this->_get_updates());

		$template = array(
			'table_open' => '<table class="table table-striped">'
		);

		$this->table->set_template($template);
		$this->_render_page("UpdateStatus");
	}

	private function _get_updates() {
		$update_time = $this->Tracker->admin->getNextUpdateTime("%H:%i");
		$this->body_data['update_time'] = explode(':', $update_time);
		//FIXME: This is just a copy/paste of the query in the admin model. Maybe we should just have a way to grab this normally?
		// @formatter:off
		$query = $this->db
			->select('
				tracker_titles.title,
				tracker_titles.title_url,
				tracker_sites.site_class,
				from_unixtime(MAX(auth_users.last_login)) AS timestamp
			')
			->from('tracker_titles')
			->join('tracker_sites', 'tracker_sites.id = tracker_titles.site_id', 'left')
			->join('tracker_chapters', 'tracker_titles.id = tracker_chapters.title_id', 'left')
			->join('auth_users', 'tracker_chapters.user_id = auth_users.id', 'left')
			->where('tracker_sites.status', 'enabled')
			->group_start()
				//Check if title is marked as on-going...
				->where('tracker_titles.status', 0)
				//AND matches one of where queries below
				->group_start()
					//Then check if it's NULL (only occurs for new series)
					->where('latest_chapter', NULL)
					//OR if it hasn't updated within the past 12 hours AND isn't a custom update site
					->or_group_start()
						->where('tracker_sites.use_custom', 'N')
						->where("last_checked < DATE_SUB(DATE_ADD(NOW(), INTERVAL '{$update_time}' HOUR_MINUTE), INTERVAL 12 HOUR)")
					->group_end()
					//OR it is a custom update site and hasn't updated within the past 72 hours
					->or_where("last_checked < DATE_SUB(DATE_ADD(NOW(), INTERVAL '{$update_time}' HOUR_MINUTE), INTERVAL 72 HOUR)")
				->group_end()
			->group_end()
			->or_group_start()
				//Check if title is marked as complete...
				->where('tracker_titles.status', 1)
				//Then check if it hasn't updated within the past week
				->where("last_checked < DATE_SUB(DATE_ADD(NOW(), INTERVAL '{$update_time}' HOUR_MINUTE), INTERVAL 1 WEEK)")
			->group_end()
			//Status 2 (One-shot) & 255 (Ignore) are both not updated intentionally.

			->group_by('tracker_titles.id, tracker_chapters.active')
			//Check if the series is actually being tracked by someone
			->having('timestamp IS NOT NULL')
			//AND if it's currently marked as active by the user
			->having('tracker_chapters.active', 'Y')
			//AND if they have been active in the last 120 hours (5 days)
			->having("timestamp > DATE_SUB(DATE_ADD(NOW(), INTERVAL '{$update_time}' HOUR_MINUTE), INTERVAL 120 HOUR)")
			->order_by('tracker_titles.title', 'ASC')
			->get();
		// @formatter:on

		$resultArr = [];
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$resultArr[] = [
					'site'           => $row->site_class,
					'full_title_url' => "<a href='".$this->Tracker->sites->{$row->site_class}->getFullTitleURL($row->title_url)."'>{$row->title}</a>"
				];
			}
		}

		return $resultArr;
	}
}
