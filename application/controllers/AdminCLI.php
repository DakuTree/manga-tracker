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
	 * Used to check for, and update series with new chapters.
	 * Called via: public/index.php admin/update_series
	 *
	 * This is called via a cron job every 6 hours.
	 * Series are only checked if they haven't been updated in 16+ hours (unless they are marked as complete, to which they are only checked once a week).
	 */
	public function updateSeries() {
		print "Environment: ".ENVIRONMENT."\n";
		$this->Tracker->admin->updateLatestChapters();
	}

	/**
	 * Used to check for, and update titles with new chapters from a site following list.
	 * Called via: public/index.php admin/update_series_custom
	 *
	 * This is called via a cron job every hour.
	 * Series will always be updated if they can be. For more info see: https://github.com/DakuTree/manga-tracker/issues/78
	 * FIXME: The entire implementation of this is an utter mess.
	 **/
	public function updateSeriesCustom() {
		$this->Tracker->admin->updateCustom();
	}

	public function refollowCustom() {
		$this->Tracker->admin->refollowCustom();
	}

	public function testSite($type, $site, $extra = NULL) {
		print "Testing site\n";
		switch($type) {
			case 'update':
				if(!is_null($extra )) {
					print_r($this->Tracker->sites->{$site}->getTitleData($extra));
				}
				break;

			case 'custom_update':
				print_r($this->Tracker->sites->{$site}->doCustomUpdate());
				break;

			case 'force_update':
				print_r($this->Tracker->admin->updateAllTitlesBySite($site, $extra));
				break;

			default:
				print "Missing parameters.";
				break;
		}
		print "\n";
	}
}
