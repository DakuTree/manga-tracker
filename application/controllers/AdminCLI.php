<?php defined('BASEPATH') OR exit('No direct script access allowed');

class AdminCLI extends CLI_Controller {
	public function __construct() {
		parent::__construct();
	}

	public function index() {
		print "ERROR: This is an invalid route";
	}

	/**
	 * Used to update the site migration version.
	 * Called via public/index.php admin/migrate
	 */
	public function migrate() {
		$this->load->library('migration');

		if(!$this->migration->current()) {
			show_error($this->migration->error_string());
		}
		//TODO: Expand on this - http://avenir.ro/the-migrations-in-codeigniter-or-how-to-have-a-git-for-your-database/
	}

	/**
	 * Used to check for, and update titles with new chapters.
	 * Called via: public/index.php admin/update_titles
	 *
	 * This is called via a cron job every 6 hours.
	 * Titles are only checked if they haven't been updated in 16+ hours (unless they are marked as complete, to which they are only checked once a week).
	 */
	public function updateTitles() {
		$this->Tracker->updateLatestChapters();
	}

	/**
	 * Used to check for, and update titles with new chapters from a site following list.
	 * Called via: public/index.php admin/update_titles_custom
	 *
	 * This is called via a cron job every hour.
	 * Titles will always be updated if they can be. For more info see: https://github.com/DakuTree/manga-tracker/issues/78
	 * FIXME: The entire implementation of this is an utter mess.
	 **/
	public function updateTitlesCustom() {
		$this->Tracker->updateCustom();
	}

	public function test() {
		//print_r($this->Tracker->sites->{'GameOfScanlation'}->getTitleData('legendary-moonlight-sculptor.99'));
		//$this->Tracker->sites->{'Batoto'}->getTitleData('718:--:English', TRUE);
		$this->Tracker->sites->{'Batoto'}->doCustomUpdate();
	}
}
