<?php

class Signup_test extends TestCase {
	public function test_uri() {
		$this->request('GET', 'user/signup');
		$this->assertResponseCode(200);

		$this->request->setCallable(
			function ($CI) {
				$auth = $this->getDouble('Auth_Model', ['verification_check' => 'foobar']);
				$this->verifyInvokedOnce($auth, 'verification_check');
				$CI->Auth = $auth;
			}
		);
		$this->request('GET', 'user/signup/authcode');
		$this->assertResponseCode(200);
	}

	public function test_index_logged_in() {
		//user is already logged in, redirect to dashboard
		$this->request->setCallablePreConstructor(
			function () {
				$ion_auth = $this->getMock_ion_auth_logged_in();
				load_class_instance('ion_auth', $ion_auth);
			}
		);

		$this->request('GET', 'user/signup');
		$this->assertRedirect('/');
	}

	public function test_index_not_logged_in() {
		//user isn't logged in, so show signup form
		$output = $this->request('GET', 'user/signup');
		$this->assertContains('<title>Manga Tracker - Signup</title>', $output);
	}

	public function test_signup_p1_validation_pass_verification_pass() {
		$this->request->setCallable(
			function ($CI) {
				$validation = $this->getDouble(
					'CI_Form_validation',
					['set_rules' => NULL, 'run' => TRUE]
				);

				$this->verifyInvokedMultipleTimes($validation, 'set_rules', 1);
				$this->verifyInvokedOnce($validation, 'run');
				//$this->verifyNeverInvoked($validation, 'reset_validation');

				$CI->form_validation = $validation;
			}
		);
		$this->request->addCallable(
				function ($CI) {
					$auth = $this->getDouble('Auth_Model', ['verification_start' => TRUE]);
					$this->verifyInvokedOnce($auth, 'verification_start');
					$CI->Auth = $auth;
				}
		);

		$output = $this->request('POST', 'user/signup', array(
			'email' => 'foo@bar.com'
		));
		$this->assertContains('We have sent an verification email to "foo@bar.com".', $output);
	}
	public function test_signup_p1_validation_pass_verification_fail() {
		$this->request->setCallable(
				function ($CI) {
					$validation = $this->getDouble(
							'CI_Form_validation',
							['set_rules' => NULL, 'run' => TRUE]
					);

					$this->verifyInvokedMultipleTimes($validation, 'set_rules', 1);
					$this->verifyInvokedOnce($validation, 'run');
					//$this->verifyNeverInvoked($validation, 'reset_validation');

					$CI->form_validation = $validation;
				}
		);
		$this->request->addCallable(
				function ($CI) {
					$auth = $this->getDouble('Auth_Model', ['verification_start' => FALSE]);
					$this->verifyInvokedOnce($auth, 'verification_start');
					$CI->Auth = $auth;
				}
		);

		$output = $this->request('POST', 'user/signup', array(
				'email' => 'foo@bar.com'
		));
		$this->assertContains('Before we start the signup, we need to verify your email.', $output);
	}
	public function test_signup_p1_validation_fail() {
		$this->request->setCallable(
			function ($CI) {
				$validation = $this->getDouble(
					'CI_Form_validation',
					['set_rules' => NULL, 'run' => FALSE, 'set_value' => '']
				);

				$this->verifyInvokedMultipleTimes($validation, 'set_rules', 1);
				$this->verifyInvokedOnce($validation, 'run');
				//$this->verifyNeverInvoked($validation, 'reset_validation');
				// Inject mock object
				$CI->form_validation = $validation;
			}
		);

		$output = $this->request('POST', 'user/signup');
		$this->assertContains('Before we start the signup, we need to verify your email.', $output);
	}

	public function test_signup_p2_verification_pass_validation_pass_register_pass() {
		//form was valid, and register was successful, user is redirected to dashboard

		$this->request->setCallable(
			function ($CI) {
				$auth = $this->getDouble('Auth_Model', ['verification_check' => 'foo@bar.com']);
				$this->verifyInvokedOnce($auth, 'verification_check');
				$CI->Auth = $auth;
			}
		);
		$this->request->addCallable(
			function ($CI) {
				$validation = $this->getDouble(
					'CI_Form_validation',
					['set_rules' => NULL, 'run' => TRUE]
				);

				$this->verifyInvokedMultipleTimes($validation, 'set_rules', 4);
				$this->verifyInvokedOnce($validation, 'run');
				//$this->verifyNeverInvoked($validation, 'reset_validation');

				$CI->form_validation = $validation;
			}
		);
		$this->request->addCallable(
			function ($CI) {
				$auth = $this->getDouble(
					'Ion_auth_model',
					['register' => TRUE]
				);

				$this->verifyInvokedOnce($auth, 'register');

				$CI->ion_auth = $auth;
			}
		);

		$this->request('POST', 'user/signup/foobarauthcode');
		$this->assertRedirect('/help');
	}
	public function test_signup_p2_verification_pass_validation_pass_register_fail() {
		//form was valid, and register was unsuccessful, reshow form
		$this->request->setCallable(
			function ($CI) {
				$auth = $this->getDouble('Auth_Model', ['verification_check' => 'foo@bar.com']);
				$this->verifyInvokedOnce($auth, 'verification_check');
				$CI->Auth = $auth;
			}
		);
		$this->request->addCallable(
			function ($CI) {
				$validation = $this->getDouble(
					'CI_Form_validation',
					['set_rules' => NULL, 'run' => TRUE, 'set_value' => 'foobar']
				);

				$this->verifyInvokedMultipleTimes($validation, 'set_rules', 4);
				$this->verifyInvokedOnce($validation, 'run');
				//$this->verifyNeverInvoked($validation, 'reset_validation');
				$this->verifyInvoked($validation, 'set_value');
				// Inject mock object
				$CI->form_validation = $validation;
			}
		);
		$this->request->addCallable(
			function ($CI) {
				$auth = $this->getDouble(
					'Ion_auth_model',
					['register' => FALSE, 'errors' => FALSE, 'logged_in' => FALSE]
				);

				$this->verifyInvokedOnce($auth, 'register');

				$CI->ion_auth = $auth;
			}
		);

		$output = $this->request('POST', 'user/signup/foobarauthcode');
		$this->assertContains('name="username" value="foobar" id="username"', $output);
	}
	public function test_signup_p2_verification_pass_validation_fail() {
		//validation failed, this is probably the first time the user visits the page, show form
		$this->request->setCallable(
			function ($CI) {
				$auth = $this->getDouble('Auth_Model', ['verification_check' => 'foo@bar.com']);
				$this->verifyInvokedOnce($auth, 'verification_check');
				$CI->Auth = $auth;
			}
		);
		$this->request->addCallable(
			function ($CI) {
				$validation = $this->getDouble(
					'CI_Form_validation',
					['set_rules' => NULL, 'run' => FALSE, 'set_value' => '']
				);

				$this->verifyInvokedMultipleTimes($validation, 'set_rules', 4);
				$this->verifyInvokedOnce($validation, 'run');
				//$this->verifyNeverInvoked($validation, 'reset_validation');
				$this->verifyInvoked($validation, 'set_value');
				// Inject mock object
				$CI->form_validation = $validation;
			}
		);

		$output = $this->request('POST', 'user/signup/foobarauthcode');
		$this->assertContains('name="username" value="" id="username"', $output);
	}
	public function test_signup_p2_verification_fail() {
		//validation failed, this is probably the first time the user visits the page, show form
		$this->request->setCallable(
			function ($CI) {
				$auth = $this->getDouble('Auth_Model', ['verification_check' => FALSE]);
				$this->verifyInvokedOnce($auth, 'verification_check');
				$CI->Auth = $auth;
			}
		);

		$this->request('POST', 'user/signup/foobarauthcode');
		$this->assertRedirect('user/signup');
	}
}
