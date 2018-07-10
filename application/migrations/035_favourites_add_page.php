<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Favourites_Add_Page extends CI_Migration {
	public function __construct() {
		parent::__construct();
		$this->load->dbforge();
	}

	public function up() {
		$fields = array(
			'page' => array(
				'type'     => 'smallint',
				'unsigned' => TRUE,

				'null'    => TRUE,
				'default' => NULL
			)
		);
		$this->dbforge->add_column('tracker_favourites', $fields);

		$this->db->query('ALTER TABLE `tracker_favourites` DROP INDEX `chapter_id_2`, ADD UNIQUE `chapter_id_2` (`chapter_id`, `chapter`, `page`);');
	}

	public function down() {
		$this->dbforge->drop_column('tracker_favourites', 'page');
		$this->db->query('ALTER TABLE `tracker_favourites` DROP INDEX `chapter_id_2`, ADD UNIQUE `chapter_id_2` (`chapter_id`, `chapter`);');
	}
}
