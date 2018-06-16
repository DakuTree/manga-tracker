<?php

class Generic_Helper_test extends TestCase {
	public function setUp() {
		$this->resetInstance();
		$this->CI->load->helper('generic_helper');
	}

	public function test_view_exists_true() : void {
		$result = view_exists('FrontPage');
		$this->assertTrue($result);
	}

	public function test_view_exists_false() : void {
		$result = view_exists('AnInvalidPage');
		$this->assertFalse($result);
	}

	public function test_get_time_class_day() : void {
		$result = get_time_class('today');
		$this->assertEquals('sprite-day', $result);
	}
	public function test_get_time_class_week() : void {
		$result = get_time_class('2 weeks ago');
		$this->assertEquals('sprite-week', $result);
	}
	public function test_get_time_class_month() : void {
		$result = get_time_class('35 days ago');
		$this->assertEquals('sprite-month', $result);
	}
	public function test_get_time_class_fail() : void {
		$result = get_time_class('invalid');
		$this->assertEquals('sprite-error', $result);
	}
}
