<?php

class Tracker_Title_Model_test extends TestCase {
	/**
	 * @var Tracker_Title_Model $title
	 */
	private $title;

	public function setUp() {
		$this->resetInstance();
		$this->CI->load->model('Tracker/Tracker_Title_Model');
		$this->title = $this->CI->Tracker_Title_Model;

	}

	public function test_getSiteDataFromURL_pass() {
		$data = $this->title->getSiteDataFromURL('mangafox.me');
		$this->assertInternalType('object', $data);
	}
	public function test_getSiteDataFromURL_fail() {
		$data = $this->title->getSiteDataFromURL('baddomain.tld');
		$this->assertNull($data);
	}
}
