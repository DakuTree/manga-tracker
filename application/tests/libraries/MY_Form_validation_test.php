<?php

class MY_Form_validation_test extends TestCase {
	/** @var $form_validation MY_Form_validation */
	private $form_validation;

	public function __construct() {
		parent::__construct();
	}

	public function setUp() {
		$this->resetInstance();
		$this->CI->load->library('form_validation');
		$this->form_validation = $this->CI->form_validation;
	}

	// A valid username is between 4 & 15 characters, and contains only [a-zA-Z0-9_-]
	public function test_valid_username_pass() : void {
		//username is valid, return true
		$result = $this->form_validation->valid_username('FooBar');
		$this->assertTrue($result);
	}
	public function test_valid_username_fail_case_1() : void {
		//username is invalid (too short), return false
		$result = $this->form_validation->valid_username('Foo');
		$this->assertFalse($result);
	}
	public function test_valid_username_fail_case_2() : void {
		//username is invalid (too long), return false
		$result = $this->form_validation->valid_username('FooBarFooBarFooBar');
		$this->assertFalse($result);
	}
	public function test_valid_username_fail_case_3() : void {
		//username is invalid (invalid characters), return false
		$result = $this->form_validation->valid_username('フーバル');
		$this->assertFalse($result);
	}

	//A valid password is between 6 & 64 characters, any characters are allowed.
	public function test_valid_password_pass() : void {
		//password is valid, return true
		$result = $this->form_validation->valid_password('FooBar2');
		$this->assertTrue($result);
	}
	public function test_valid_password_fail_1() : void {
		//password is invalid (too short), return false
		$result = $this->form_validation->valid_password('Foo');
		$this->assertEquals('The password is too short!', $this->get_error_message('valid_password'));
		$this->assertFalse($result);
	}
	public function test_valid_password_fail_2() : void {
		//password is invalid (too long), return false
		$result = $this->form_validation->valid_password('FooBarFooBarFooBarFooBarFooBarFooBarFooBarFooBarFooBarFooBarFooBarFooBar');
		$this->assertEquals('The password is too long!', $this->get_error_message('valid_password'));
		$this->assertFalse($result);
	}

	public function test_is_unique_username_pass() : void {
		//username is unique, return true

		$validation = $this->getDouble(
				'MY_Form_validation',
				['is_unique' => TRUE]
		);
		$this->verifyInvokedOnce($validation, 'is_unique');

		$result = $validation->is_unique_username('FooBar');
		$this->assertTrue($result);
	}
	public function test_is_unique_username_fail() : void {
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

	public function test_is_unique_email_pass() : void {
		//email is unique, return true

		$validation = $this->getDouble(
				'MY_Form_validation',
				['is_unique' => TRUE]
		);
		$this->verifyInvokedOnce($validation, 'is_unique');

		$result = $validation->is_unique_email('foo@bar.com');
		$this->assertTrue($result);
	}
	public function test_is_unique_email_fail() : void {
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

	public function test_is_valid_json_pass() : void {
		//json is valid, return true
		$result = $this->form_validation->is_valid_json('{"foo":"bar"}');
		$this->assertTrue($result);
	}
	public function test_is_valid_json_fail_1() : void {
		//json is not json, return false
		$result = $this->form_validation->is_valid_json('i_am_a_bad_string');
		$this->assertFalse($result);
	}
	public function test_is_valid_json_fail_2() : void {
		//json is json but still has error (in this case, bad utf8), return false
		$result = $this->form_validation->is_valid_json('{"foo": "'."\xB1\x31".'"}');
		$this->assertFalse($result);
	}

	public function test_is_valid_tag_string_pass_1() {
		//tag string is empty, return true
		$result = $this->form_validation->is_valid_tag_string('');
		$this->assertTrue($result);
	}
	public function test_is_valid_tag_string_pass_2() {
		//tag string is max length, return true
		$result = $this->form_validation->is_valid_tag_string('nski5zusfopc1e8fxguykxa9nt9wj7rtqe8xnkhaqmjgpyywkp3izocdyxaxval4qo1oapfjjs3ejglvqakqf4n92sbiwosvcakgky6mfitvw8ruoj9yqpp4xel8syfvuofo5657mcuz9lxaukautp2vzzj26vyufvvxdxwudqbhhzgdufq9hljg5vq7vb4rsocqvc6mohwqkk9yipvekaqhvj17xbv3xp0iq8vqlexibaa4k0qzpblkpefxzfy');
		$this->assertTrue($result);
	}
	public function test_is_valid_tag_string_pass_3() {
		//tag string contains valid special characters, return true
		$result = $this->form_validation->is_valid_tag_string('this,is-a,vali_d,tagstr1ng');
		$this->assertTrue($result);
	}
	public function test_is_valid_tag_string_fail_1() {
		//tag string contains invalid special characters, return true
		$result = $this->form_validation->is_valid_tag_string('bad tag');
		$this->assertFalse($result);
	}
	public function test_is_valid_tag_string_fail_2() {
		//tag string contains invalid special characters, return true
		$result = $this->form_validation->is_valid_tag_string('thisistoolongnski5zusfopc1e8fxguykxa9nt9wj7rtqe8xnkhaqmjgpyywkp3izocdyxaxval4qo1oapfjjs3ejglvqakqf4n92sbiwosvcakgky6mfitvw8ruoj9yqpp4xel8syfvuofo5657mcuz9lxaukautp2vzzj26vyufvvxdxwudqbhhzgdufq9hljg5vq7vb4rsocqvc6mohwqkk9yipvekaqhvj17xbv3xp0iq8vqlexibaa4k0qzpblkpefxzfy');
		$this->assertFalse($result);
	}

	public function test_is_valid_category_pass() {
		//category is valid, return true

		//TODO: Testing this is tricky since we need the user to have enabled one of the custom categories.
		$this->markTestNotImplemented();
	}
	public function test_is_valid_category_fail_1() {
		//category is invalid, return false
		$result = $this->form_validation->is_valid_category('bad_category');
		$this->assertFalse($result);
	}
	public function test_is_valid_category_fail_2() {
		//category is valid but user does not have it enabled, return false
		$result = $this->form_validation->is_valid_category('custom_3');
		$this->assertFalse($result);
	}

	public function test_not_contains_pass() : void {
		//string does not contain string, return true
		$result = $this->form_validation->not_contains('i am a string', 'foobar');
		$this->assertTrue($result);
	}
	public function test_not_contains_fail() : void {
		//string contains string, return false
		$result = $this->form_validation->not_contains('i am a foobar', 'foobar');
		$this->assertFalse($result);
	}

	public function test_is_valid_option_value_pass() : void {
		//option value is valid, return true
		$result = $this->form_validation->is_valid_option_value('alphabetical', 'list_sort_type');
		$this->assertTrue($result);
	}
	public function test_is_valid_option_value_fail() : void {
		//option value is invalid, return false
		$result = $this->form_validation->is_valid_option_value('rainbow', 'theme');
		$this->assertFalse($result);
	}

	public function test_isRuleValid_pass() : void {
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
	public function test_isRuleValid_fail_1() : void {
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
	public function test_isRuleValid_fail_2() : void {
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
