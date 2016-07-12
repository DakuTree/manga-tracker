<?php

class TestCase extends CIPHPUnitTestCase {
	//private static $migrate = false;

	//public static function setUpBeforeClass() {
	//	parent::setUpBeforeClass();
	//
	//	// Run migrations once
	//	if (!self::$migrate) {
	//		$CI =& get_instance();
	//		$CI->load->database();
	//		$CI->load->library('migration');
	//		if ($CI->migration->current() === false) {
	//			throw new RuntimeException($CI->migration->error_string());
	//		}
	//
	//		self::$migrate = true;
	//	}
	//}
	//public static function tearDownAfterClass() {
	//	parent::tearDownAfterClass();
	//
	//	$CI =& get_instance();
	//	$CI->load->database();
	//	$CI->load->library('migration');
	//	$CI->migration->version(0);
	//}

	public function getMock_ion_auth_logged_in() {
		$ion_auth = $this->getMockBuilder('Ion_auth')
		                 ->disableOriginalConstructor()
		                 ->getMock();

		$ion_auth->expects($this->at(0))
		         ->method('logged_in')
		         ->willReturn(TRUE);
		$ion_auth->expects($this->at(1))
		         ->method('logged_in')
		         ->willReturn(TRUE);

		$ion_auth->expects($this->any())
		         ->method($this->anything())
		         ->will($this->returnSelf());
		
		return $ion_auth;
	}

	public function getMock_CI_DB_result(array $methods) {
		$db_result = $this->getMockBuilder('CI_DB_result')
		                  ->disableOriginalConstructor()
		                  ->getMock();

		foreach ($methods as $method => $return) {
			$db_result->method($method)->willReturn($return);
		}
		return $db_result;
	}

	public function getMock_CI_DB($return) {
		$db = $this->getMockBuilder('CI_DB')
		           ->disableOriginalConstructor()
		           ->setMethods(array('select', 'from', 'where', 'get'))
		           ->getMock();

		$db->expects($this->at(3))
		   ->method('get')
		   ->willReturn($return);

		$db->expects($this->any())
		   ->method($this->anything())
		   ->will($this->returnSelf());

		return $db;
	}

	//extra functions
	public function markTestNotImplemented() {
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}
}
