<?php

class MY_Form_validation_test extends TestCase {
	private $form_validation;

	public function setUp() {
		$this->resetInstance();
		$this->CI->load->library('form_validation');
		$this->form_validation = $this->CI->form_validation;
	}

	// A valid username is between 4 & 15 characters, and contains only [a-zA-Z0-9_-]
	public function test_valid_username_pass() {
		//username is valid, return true
		$result = $this->form_validation->valid_username('FooBar');
		$this->assertTrue($result);
	}
	public function test_valid_username_fail_case_1() {
		//username is invalid (too short), return false
		$result = $this->form_validation->valid_username('Foo');
		$this->assertFalse($result);
	}
	public function test_valid_username_fail_case_2() {
		//username is invalid (too long), return false
		$result = $this->form_validation->valid_username('FooBarFooBarFooBar');
		$this->assertFalse($result);
	}
	public function test_valid_username_fail_case_3() {
		//username is invalid (invalid characters), return false
		$result = $this->form_validation->valid_username('フーバル');
		$this->assertFalse($result);
	}

	//A valid password is between 6 & 64 characters, any characters are allowed.
	public function test_valid_password_pass() {
		//password is valid, return true
		$result = $this->form_validation->valid_password('FooBar2');
		$this->assertTrue($result);
	}
	public function test_valid_password_fail_1() {
		//password is invalid (too short), return false
		$result = $this->form_validation->valid_password('Foo');
		$this->assertEquals('The password is too short!', $this->get_error_message('valid_password'));
		$this->assertFalse($result);
	}
	public function test_valid_password_fail_2() {
		//password is invalid (too long), return false
		$result = $this->form_validation->valid_password('FooBarFooBarFooBarFooBarFooBarFooBarFooBarFooBarFooBarFooBarFooBarFooBar');
		$this->assertEquals('The password is too long!', $this->get_error_message('valid_password'));
		$this->assertFalse($result);
	}

	public function test_is_unique_username_pass() {
		//username is unique, return true

		$validation = $this->getDouble(
				'MY_Form_validation',
				['is_unique' => TRUE]
		);
		$this->verifyInvokedOnce($validation, 'is_unique');

		$result = $validation->is_unique_username('FooBar');
		$this->assertTrue($result);
	}
	public function test_is_unique_username_fail() {
		//username already exists, return false

		$validation = $this->getDouble(
				'MY_Form_validation',
				['is_unique' => FALSE]
		);
		$this->verifyInvokedOnce($validation, 'is_unique');

		$result = $validation->is_unique_username('FooBar');
		$this->assertEquals('The username already exists in our database.', $this->get_error_message('is_unique_username', $validation));
		$this->assertFalse($result);
	}

	public function test_is_unique_email_pass() {
		//email is unique, return true

		$validation = $this->getDouble(
				'MY_Form_validation',
				['is_unique' => TRUE]
		);
		$this->verifyInvokedOnce($validation, 'is_unique');

		$result = $validation->is_unique_email('foo@bar.com');
		$this->assertTrue($result);
	}
	public function test_is_unique_email_fail() {
		//email already exists, return false

		$validation = $this->getDouble(
				'MY_Form_validation',
				['is_unique' => FALSE]
		);
		$this->verifyInvokedOnce($validation, 'is_unique');

		$result = $validation->is_unique_email('foo@bar.com');
		$this->assertEquals('The email already exists in our database.', $this->get_error_message('is_unique_email', $validation));
		$this->assertFalse($result);
	}

	public function test_isRuleValid_pass() {
		//rule exists and has no errors, returns true

		//FIXME: This really isn't the best way to check this...
		$validation = $this->getDouble(
				'MY_Form_validation',
				['has_rule' => TRUE, 'error_array' => []]
		);
		$this->verifyInvokedOnce($validation, 'has_rule');

		$result = $validation->isRuleValid('valid_username');
		$this->assertTrue($result);
	}
	public function test_isRuleValid_fail_1() {
		//rule exists but has errors, returns false

		//FIXME: This really isn't the best way to check this...
		$validation = $this->getDouble(
				'MY_Form_validation',
				['has_rule' => TRUE, 'error_array' => ['valid_username' => 'Username is invalid format.']]
		);
		$this->verifyInvokedOnce($validation, 'has_rule');
		$this->verifyInvokedOnce($validation, 'error_array');

		$result = $validation->isRuleValid('valid_username');
		$this->assertFalse($result);
	}
	public function test_isRuleValid_fail_2() {
		//rule does not exist, returns false

		//FIXME: This really isn't the best way to check this...
		$validation = $this->getDouble(
				'MY_Form_validation',
				['has_rule' => FALSE]
		);
		$this->verifyInvokedOnce($validation, 'has_rule');

		$result = $validation->isRuleValid('valid_username');
		$this->assertFalse($result);
	}

	//utility functions
	function get_error_message(/*str*/$field, /*obj*/ $customObj = FALSE) {
		$obj = $customObj ?: $this->form_validation;
		$error_messages = ReflectionHelper::getPrivateProperty(
				$obj,
				'_error_messages'
		);

		return $error_messages[$field];
	}
}
