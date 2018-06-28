<?php

class User_Model_test extends TestCase {
	/** @var $User_Model User_Model */
	private $User_Model;

	public function setUp() {
		$this->resetInstance();

		$this->User_Model = new User_Model();
	}

	public function test_init_model_while_logged_in() {

	}

	public function test_logged_in_true() {
		$User_Model = $this->User_Model;

		$ion_auth = $this->getMockBuilder('ion_auth')
			->disableOriginalConstructor()
			->getMock();
		$ion_auth->method('logged_in')->willReturn(TRUE);

		$User_Model->ion_auth = $ion_auth;

		$result = $User_Model->logged_in();
		$this->assertTrue($result);
	}
	public function test_logged_in_false() {
		$User_Model = $this->User_Model;

		$ion_auth = $this->getMockBuilder('ion_auth')
		                 ->disableOriginalConstructor()
		                 ->getMock();
		$ion_auth->method('logged_in')->willReturn(FALSE);

		$User_Model->ion_auth = $ion_auth;

		$result = $User_Model->logged_in();
		$this->assertFalse($result);
	}

	public function test_login_redirect() {
		$this->User_Model->login_redirect();

		$result = $this->CI->session->flashdata('referred_from');

		//FIXME: We should probably be checking against a custom set current_url()
		$this->assertEquals("https://test.trackr.moe/", $result);
	}

	public function test_username_exists_true() {
		$User_Model = $this->User_Model;

		// Create mock object for CI_DB_result
		$db_result = $this->getMock_CI_DB_result(['num_rows' => 1]);

		// Create mock object for CI_DB
		$db = $this->getMock_CI_DB($db_result);

		$User_Model->db = $db;

		$result = $User_Model->username_exists('EXAMPLE_USER');
		$this->assertTrue($result);
	}
	public function test_username_exists_false() {
		$User_Model = $this->User_Model;

		// Create mock object for CI_DB_result
		$db_result = $this->getMock_CI_DB_result(['num_rows' => 0]);

		// Create mock object for CI_DB
		$db = $this->getMock_CI_DB($db_result);

		$User_Model->db = $db;

		$result = $User_Model->username_exists('TEST_USER');
		$this->assertFalse($result);
	}

	public function test_find_email_from_identity_using_username_true() {
		$User_Model = $this->User_Model;

		// Create mock object for CI_DB_result
		$db_result = $this->getMock_CI_DB_result(['num_rows' => 1, 'row' => 'test@test.test']);

		// Create mock object for CI_DB
		$db = $this->getMock_CI_DB($db_result);

		$User_Model->db = $db;

		$result = $User_Model->find_email_from_identity('TEST_USER');
		$this->assertEquals("test@test.test", $result);
	}
	public function test_find_email_from_identity_using_username_false() {
		$User_Model = $this->User_Model;

		// Create mock object for CI_DB_result
		$db_result = $this->getMock_CI_DB_result(['num_rows' => 0]);

		// Create mock object for CI_DB
		$db = $this->getMock_CI_DB($db_result);

		$User_Model->db = $db;

		$result = $User_Model->find_email_from_identity('TEST_USER');
		$this->assertFalse($result);
	}
	public function test_find_email_from_identity_using_email() {
		$User_Model = $this->User_Model;

		$result = $User_Model->find_email_from_identity('test@test.test');
		$this->assertEquals('test@test.test', $result);
	}
	
	public function test_get_user_by_username_exists() {
		$User_Model = $this->User_Model;

		// Create mock object for CI_DB_result
		$db_result = $this->getMock_CI_DB_result(['num_rows' => 1, 'row' => ['id' => '1', 'username' => 'TEST_USER']]);

		// Create mock object for CI_DB
		$db = $this->getMock_CI_DB($db_result);

		$User_Model->db = $db;

		$result = $User_Model->get_user_by_username('TEST_USER');
		$this->assertEquals(['id' => '1', 'username' => 'TEST_USER'], $result);
	}
	public function test_get_user_by_username_no_exists() {
		$User_Model = $this->User_Model;

		// Create mock object for CI_DB_result
		$db_result = $this->getMock_CI_DB_result(['num_rows' => 0]);

		// Create mock object for CI_DB
		$db = $this->getMock_CI_DB($db_result);

		$User_Model->db = $db;

		$result = $User_Model->get_user_by_username('TEST_USER');
		$this->assertNull($result);
	}

	public function test_get_gravatar_url() {
		$User_Model = $this->User_Model;

		$result = $User_Model->getGravatarURL();

		//NOTE: Gravatar lib changes between using http & https depending on what is being used, tests only use http though.
		$this->assertRegExp('/^http:\/\/www\.gravatar\.com\/avatar\/[a-z0-9]+\.png\?s=[0-9]+\&d=[a-z]/i', $result);
	}
}
