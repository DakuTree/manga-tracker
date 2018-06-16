<?php declare(strict_types=1); defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @coversDefaultClass Login
 */
class Login_test extends TestCase {
	public function test_uri() : void {
		$this->request('GET', 'user/login');
		$this->assertResponseCode(200);
	}

	public function test_index_logged_in() : void {
		//user is already logged in, redirect to dashboard
		$this->request->setCallablePreConstructor(
			function () {
				$ion_auth = $this->getMock_ion_auth_logged_in();
				load_class_instance('ion_auth', $ion_auth);
			}
		);

		$this->request('GET', 'user/login');
		$this->assertRedirect('/');
	}

	public function test_index_not_logged_in() : void {
		//user isn't logged in, so show login form
		$output = $this->request('GET', 'user/login');
		$this->assertTitle($output, 'Login');
	}

	public function test_login_validation_pass_user_pass() : void {
		//user isn't logged in, form is valid, login is valid, redirect the user (no referral)
		$this->markTestIncomplete('This test is broken for now.');

		$this->request->setCallable(
			function ($CI) {
				$validation = $this->getDouble(
					'CI_Form_validation',
					['set_rules' => NULL, 'run' => TRUE]
				);

				$this->verifyInvokedMultipleTimes($validation, 'set_rules', 2);
				$this->verifyInvokedOnce($validation, 'run');
				//$this->verifyNeverInvoked($validation, 'reset_validation');

				$CI->form_validation = $validation;
			}
		);
		$this->request->addCallable(
			function ($CI) {
				$user = $this->getDouble(
					'User_Model',
					['find_email_from_identity' => 'foobar']
				);

				$this->verifyInvokedOnce($user, 'find_email_from_identity');

				$CI->User = $user;
			}
		);
		$this->request->addCallable(
			function ($CI) {
				$auth = $this->getDouble(
					'Ion_auth_model',
					['login' => TRUE]
				);

				$this->verifyInvokedOnce($auth, 'login');

				$CI->ion_auth = $auth;
			}
		);

		$this->request('POST', 'user/login', ['identity' => 'foo@bar.com']);
		$this->assertRedirect('user/dashboard');
	}

	public function test_login_validation_pass_user_pass_and_referred() : void {
		//user isn't logged in, form is valid, login is valid, redirect the user to referred url
		$this->markTestIncomplete('This test is broken for now.');

		$this->request->setCallable(
			function ($CI) {
				$validation = $this->getDouble(
					'CI_Form_validation',
					['set_rules' => NULL, 'run' => TRUE]
				);

				$this->verifyInvokedMultipleTimes($validation, 'set_rules', 2);
				$this->verifyInvokedOnce($validation, 'run');
				//$this->verifyNeverInvoked($validation, 'reset_validation');

				$CI->form_validation = $validation;
			}
		);
		$this->request->addCallable(
			function ($CI) {
				$user = $this->getDouble(
					'User_Model',
					['find_email_from_identity' => 'foobar']
				);

				$this->verifyInvokedOnce($user, 'find_email_from_identity');

				$CI->User = $user;
			}
		);
		$this->request->addCallable(
			function ($CI) {
				$auth = $this->getDouble(
					'Ion_auth_model',
					['login' => TRUE]
				);

				$this->verifyInvokedOnce($auth, 'login');

				$CI->ion_auth = $auth;
			}
		);
		$this->request->addCallable(
			function ($CI) {
				// Get mock object
				$session = $this->getDouble(
					'CI_Session',
					['flashdata' => 'foo/bar']
				);
				$this->verifyInvokedOnce($session, 'flashdata', ['referred_from']);

				$CI->session = $session;
			}
		);

		$this->request('POST', 'user/login', ['identity' => 'foo@bar.com']);
		$this->assertRedirect('foo/bar');
	}

	public function test_login_validation_fail() : void {
		//user isn't logged in, validation failed, this is happens on the first time loading the page
		$this->request->setCallable(
			function ($CI) {
				// Get mock object
				$validation = $this->getDouble(
					'CI_Form_validation',
					['set_rules' => NULL, 'run' => FALSE, 'set_value' => '']
				);
				// Verify invocations
				$this->verifyInvokedMultipleTimes($validation, 'set_rules', 2);
				$this->verifyInvokedOnce($validation, 'run');
				//$this->verifyNeverInvoked($validation, 'reset_validation');
				$this->verifyInvoked($validation, 'set_value', ['identity']);
				// Inject mock object
				$CI->form_validation = $validation;
			}
		);
		$output = $this->request('POST', 'user/login');
		$this->assertContains('name="identity" value="" id="identity"', $output);
	}

	public function test_login_validation_pass_but_login_fail() : void {
		//user isn't logged in, tried to login, validation succeeded but login failed
		$this->request->setCallable(
			function ($CI) {
				// Get mock object
				$validation = $this->getDouble(
					'CI_Form_validation',
					['set_rules' => NULL, 'run' => TRUE, 'set_value' => 'foo@bar.com']
				);
				// Verify invocations
				$this->verifyInvokedMultipleTimes($validation, 'set_rules', 2);
				$this->verifyInvokedOnce($validation, 'run');
				//$this->verifyNeverInvoked($validation, 'reset_validation');
				$this->verifyInvoked($validation, 'set_value', ['identity']);
				// Inject mock object
				$CI->form_validation = $validation;
			}
		);
		$output = $this->request('POST', 'user/login', ['identity' => 'foo@bar.com']);
		$this->assertContains('name="identity" value="foo@bar.com" id="identity"', $output);
	}
}
