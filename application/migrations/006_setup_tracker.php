<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Setup_Tracker extends CI_Migration {
	public function __construct() {
		parent::__construct();
		$this->load->dbforge();
	}

	public function up() {
		/*** TABLES ***/
		// Table structure for table 'tracker_sites'
		$this->dbforge->add_field(array(
			'id' => array(
				'type'           => 'MEDIUMINT',
				'constraint'     => '8',
				'unsigned'       => TRUE,
				'auto_increment' => TRUE
			),
			'site' => array(
				'type'       => 'VARCHAR',
				'constraint' => '255',
				'null'       => FALSE,
				'unique'     => TRUE
			),
			'site_class' => array(
				'type'       => 'VARCHAR',
				'constraint' => '255',
				'null'       => FALSE,
				'unique'     => TRUE
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('tracker_sites');

		// Table structure for table 'tracker_titles'
		$this->dbforge->add_field(array(
			'id' => array(
				'type'           => 'MEDIUMINT',
				'constraint'     => '8',
				'unsigned'       => TRUE,
				'auto_increment' => TRUE
			),
			'site_id' => array(
				'type'       => 'MEDIUMINT',
				'constraint' => '8',
				'unsigned'   => TRUE,
				'null'       => FALSE
				//FOREIGN KEY
			),
			'title' => array(
				'type'       => 'VARCHAR',
				'constraint' => '255',
				'null'       => FALSE
			),
			'title_url' => array(
				'type'       => 'VARCHAR',
				'constraint' => '255',
				'null'       => FALSE
			),
			'latest_chapter' => array(
				'type'       => 'VARCHAR',
				'constraint' => '255',
				'null'       => TRUE
			),
			//'last_updated' => array(
			//	'type' => 'TIMESTAMP',
			//	'null' => FALSE,
			//	//'default'   => 'CURRENT_TIMESTAMP',
			//	//'on_update' => 'CURRENT_TIMESTAMP' //This is auto-added by CI
			//)
			'`last_updated` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP',
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('site_id');
		$this->dbforge->add_key('title');
		$this->dbforge->add_key('title_url');
		$this->dbforge->add_key('latest_chapter');
		$this->dbforge->add_key('last_updated');
		$this->dbforge->create_table('tracker_titles');


		// Table structure for table 'tracker_chapters'
		$this->dbforge->add_field(array(
			'id' => array(
				'type'           => 'MEDIUMINT',
				'constraint'     => '8',
				'unsigned'       => TRUE,
				'auto_increment' => TRUE
			),
			'user_id' => array(
				'type'       => 'MEDIUMINT',
				'constraint' => '8',
				'unsigned'   => TRUE,
				'null'       => FALSE
				//FOREIGN KEY
			),
			'title_id' => array(
				'type'       => 'MEDIUMINT',
				'constraint' => '8',
				'unsigned'   => TRUE,
				'null'       => FALSE
				//FOREIGN KEY
			),
			'current_chapter' => array(
				'type'       => 'VARCHAR',
				'constraint' => '255',
				'null'       => TRUE
			),
			'last_updated' => array(
				'type' => 'TIMESTAMP',
				'null' => FALSE,
				//'default'   => 'CURRENT_TIMESTAMP',
				//'on_update' => 'CURRENT_TIMESTAMP' //This is auto-added by CI
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('last_updated');
		$this->dbforge->create_table('tracker_chapters');

		/*** Unique/Foreign Keys ***/
		//For whatever reason, dbforge lacks a unique/foreign key function.
		$this->db->query('ALTER TABLE `tracker_titles` ADD UNIQUE INDEX (`site_id`, `title_url`)');
		$this->db->query('ALTER TABLE `tracker_chapters` ADD UNIQUE INDEX (`user_id`, `title_id`)');

		$this->db->query('
			ALTER TABLE `tracker_titles`
			ADD CONSTRAINT `FK_tracker_titles_tracker_sites` FOREIGN KEY (`site_id`) REFERENCES `tracker_sites`(`id`) ON UPDATE NO ACTION ON DELETE NO ACTION;'
		);
		$this->db->query('
			ALTER TABLE `tracker_chapters`
			ADD CONSTRAINT `FK_tracker_chapters_auth_users` FOREIGN KEY (`user_id`) REFERENCES `auth_users`(`id`) ON UPDATE NO ACTION ON DELETE NO ACTION,
			ADD CONSTRAINT `FK_tracker_chapters_tracker_titles` FOREIGN KEY (`title_id`) REFERENCES `tracker_titles` (`id`) ON UPDATE NO ACTION ON DELETE NO ACTION;'
		);

		/*** TABLE DATA ***/
		// Dumping data for table 'tracker_sites'
		//FIXME: This feels like a terrible way of trying to keep this up to date.
		//       We <should> create a new migration for every new site, but that is a massive pain to do and honestly we can just do a SQL query instead.
		//       It may be worth including some kind of $hostname var in each Site Model, and trying to generate it via that. Tis an idea.
		$typesData = array(
			array(
				'id'         => '1',
				'site'       => 'mangafox.me',
				'site_class' => 'MangaFox'
			),
			array(
				'id'         => '2',
				'site'       => 'www.mangahere.co',
				'site_class' => 'MangaHere'
			),
			array(
				'id'         => '3',
				'site'       => 'bato.to',
				'site_class' => 'Batoto'
			),
			array(
				'id'         => '4',
				'site'       => 'dynasty-scans.com',
				'site_class' => 'DynastyScans'
			),
			array(
				'id'         => '5',
				'site'       => 'www.mangapanda.com',
				'site_class' => 'MangaPanda'
			),
			array(
				'id'         => '6',
				'site'       => 'mangastream.com',
				'site_class' => 'MangaStream'
			),
			array(
				'id'         => '7',
				'site'       => 'www.webtoons.com',
				'site_class' => 'WebToons'
			),
			array(
				'id'         => '8',
				'site'       => 'kissmanga.com',
				'site_class' => 'KissManga',
				'status'     => 'disabled'
			),
			array(
				'id'         => '9',
				'site'       => 'reader.kireicake.com',
				'site_class' => 'KireiCake'
			),
			array(
				'id'         => '10',
				'site'       => 'gameofscanlation.moe',
				'site_class' => 'GameOfScanlation'
			),
			array(
				'id'         => '11',
				'site'       => 'mngcow.co',
				'site_class' => 'MangaCow'
			),
			array(
				'id'         => '12',
				'site'       => 'reader.seaotterscans.com',
				'site_class' => 'SeaOtterScans'
			),
			array(
				'id'         => '13',
				'site'       => 'helveticascans.com',
				'site_class' => 'HelveticaScans'
			),
			array(
				'id'         => '14',
				'site'       => 'reader.sensescans.com',
				'site_class' => 'SenseScans'
			),
			array(
				'id'         => '15',
				'site'       => 'jaiminisbox.com',
				'site_class' => 'JaiminisBox'
			),
			array(
				'id'         => '16',
				'site'       => 'kobato.hologfx.com',
				'site_class' => 'DokiFansubs'
			),
			array(
				'id'         => '17',
				'site'       => 'www.demonicscans.com',
				'site_class' => 'DemonicScans'
			)
		);
		$this->db->insert_batch('tracker_sites', $typesData);
	}

	public function down() {
		$this->db->query('SET FOREIGN_KEY_CHECKS=0;');
		$this->dbforge->drop_table('tracker_sites', TRUE);
		$this->dbforge->drop_table('tracker_titles', TRUE);
		$this->dbforge->drop_table('tracker_chapters', TRUE);
		$this->db->query('SET FOREIGN_KEY_CHECKS=1;');
	}
}
