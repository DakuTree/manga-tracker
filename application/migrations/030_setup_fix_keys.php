<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Setup_Fix_Keys extends CI_Migration {
	public function __construct() {
		parent::__construct();
		$this->load->dbforge();
	}

	public function up() {
		// auth_users
		$this->db->trans_start();
		$this->db->query('ALTER TABLE `auth_users_groups` DROP FOREIGN KEY `FK_auth_users_groups_auth_groups`');
		$this->db->query('ALTER TABLE `auth_users_groups` ADD CONSTRAINT `FK_auth_users_groups_auth_groups` FOREIGN KEY (`group_id`) REFERENCES `auth_groups`(`id`) ON DELETE NO ACTION ON UPDATE CASCADE');
		$this->db->query('ALTER TABLE `auth_users_groups` DROP FOREIGN KEY `FK_auth_users_groups_auth_users`');
		$this->db->query('ALTER TABLE `auth_users_groups` ADD CONSTRAINT `FK_auth_users_groups_auth_users` FOREIGN KEY (`user_id`) REFERENCES `auth_users`(`id`) ON DELETE NO ACTION ON UPDATE CASCADE');
		$this->db->trans_complete();

		// tracker_chapters
		$this->db->trans_start();
		$this->db->query('ALTER TABLE `tracker_chapters` DROP FOREIGN KEY `FK_tracker_chapters_auth_users`');
		$this->db->query('ALTER TABLE `tracker_chapters` ADD CONSTRAINT `FK_tracker_chapters_auth_users` FOREIGN KEY (`user_id`) REFERENCES `auth_users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE');
		$this->db->query('ALTER TABLE `tracker_chapters` DROP FOREIGN KEY `FK_tracker_chapters_tracker_titles`');
		$this->db->query('ALTER TABLE `tracker_chapters` ADD CONSTRAINT `FK_tracker_chapters_tracker_titles` FOREIGN KEY (`title_id`) REFERENCES `tracker_titles`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE');
		$this->db->trans_complete();

		// tracker_favourites
		$this->db->trans_start();
		$this->db->query('ALTER TABLE `tracker_favourites` DROP FOREIGN KEY `FK_tracker_favourites_tracker_chapters`');
		$this->db->query('ALTER TABLE `tracker_favourites` ADD CONSTRAINT `FK_tracker_favourites_tracker_chapters` FOREIGN KEY (`chapter_id`) REFERENCES `tracker_chapters`(`id`) ON DELETE NO ACTION ON UPDATE CASCADE');
		$this->db->trans_complete();

		// tracker_titles
		$this->db->trans_start();
		$this->db->query('ALTER TABLE `tracker_titles` DROP FOREIGN KEY `FK_tracker_titles_tracker_sites`');
		$this->db->query('ALTER TABLE `tracker_titles` ADD CONSTRAINT `FK_tracker_titles_tracker_sites` FOREIGN KEY (`site_id`) REFERENCES `tracker_sites`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE');
		$this->db->trans_complete();

		// tracker_titles_history
		$this->db->trans_start();
		$this->db->query('ALTER TABLE `tracker_titles_history` DROP FOREIGN KEY `FK_tracker_titles_history_tracker_titles`');
		$this->db->query('ALTER TABLE `tracker_titles_history` ADD CONSTRAINT `FK_tracker_titles_history_tracker_titles` FOREIGN KEY (`title_id`) REFERENCES `tracker_titles`(`id`) ON DELETE CASCADE ON UPDATE CASCADE');
		$this->db->trans_complete();

		// tracker_user_history
		$this->db->trans_start();
		$this->db->query('ALTER TABLE `tracker_user_history` DROP FOREIGN KEY `FK_tracker_user_history_tracker_chapters`');
		$this->db->query('ALTER TABLE `tracker_user_history` ADD CONSTRAINT `FK_tracker_user_history_tracker_chapters` FOREIGN KEY (`chapter_id`) REFERENCES `tracker_chapters`(`id`) ON DELETE CASCADE ON UPDATE CASCADE');
		$this->db->trans_complete();

		// tracker_user_notices
		$this->db->trans_start();
		$this->db->query('ALTER TABLE `tracker_user_notices` DROP FOREIGN KEY `FK_tracker_user_notices_auth_users`');
		$this->db->query('ALTER TABLE `tracker_user_notices` ADD CONSTRAINT `FK_tracker_user_notices_auth_users` FOREIGN KEY (`user_id`) REFERENCES `auth_users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE');
		$this->db->query('ALTER TABLE `tracker_user_notices` DROP FOREIGN KEY `FK_tracker_user_notices_tracker_notices`');
		$this->db->query('ALTER TABLE `tracker_user_notices` ADD CONSTRAINT `FK_tracker_user_notices_tracker_notices` FOREIGN KEY (`hidden_notice_id`) REFERENCES `tracker_notices`(`id`) ON DELETE CASCADE ON UPDATE CASCADE');
		$this->db->trans_complete();

		// tracker_user_options
		$this->db->trans_start();
		$this->db->query('ALTER TABLE `user_options` DROP FOREIGN KEY `FK_user_options_auth_users`');
		$this->db->query('ALTER TABLE `user_options` ADD CONSTRAINT `FK_user_options_auth_users` FOREIGN KEY (`user_id`) REFERENCES `auth_users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE');
		$this->db->trans_complete();
	}

	public function down() {}
}
