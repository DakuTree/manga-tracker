<?php declare(strict_types=1); defined('BASEPATH') OR exit('No direct script access allowed');

include_once APPPATH.'models/Tracker/Tracker_Base_Model.php';
foreach (glob(APPPATH.'models/Tracker/*.php') as $filename) {
	/** @noinspection PhpIncludeInspection */
	include_once $filename;
}
class Tracker_Model extends Tracker_Base_Model {
	public $title;
	public $list;
	public $tag;
	public $favourites;
	public $category;
	public $portation;
	public $admin;
	public $stats;
	public $bug;

	public function __construct() {
		parent::__construct();

		//Modules
		$this->title      = new Tracker_Title_Model();
		$this->list       = new Tracker_List_Model();
		$this->favourites = new Tracker_Favourites_Model();
		$this->tag        = new Tracker_Tag_Model();
		$this->category   = new Tracker_Category_Model();
		$this->portation  = new Tracker_Portation_Model();
		$this->admin      = new Tracker_Admin_Model();
		$this->stats      = new Tracker_Stats_Model();
		$this->bug        = new Tracker_Bug_Model();
	}
}
