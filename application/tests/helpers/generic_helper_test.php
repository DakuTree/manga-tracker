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

	public function test_get_time_class_day() {
		$result = get_time_class('today');
		$this->assertEquals('sprite-day', $result);
	}
	public function test_get_time_class_week() {
		$result = get_time_class('2 weeks ago');
		$this->assertEquals('sprite-week', $result);
	}
	public function test_get_time_class_month() {
		$result = get_time_class('35 days ago');
		$this->assertEquals('sprite-month', $result);
	}
	public function test_get_time_class_fail() {
		$result = get_time_class('invalid');
		$this->assertEquals('sprite-error', $result);
	}
}
