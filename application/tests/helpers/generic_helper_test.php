<?php

class Generic_Helper_test extends TestCase {
	public function setUp() {
		$this->resetInstance();
		$this->CI->load->helper('generic_helper');
	}

	public function test_view_exists_true() {
		$result = view_exists('FrontPage');
		$this->assertTrue($result);
	}

	public function test_view_exists_false() {
		$result = view_exists('AnInvalidPage');
		$this->assertFalse($result);
	}
}
