<?php declare(strict_types=1); defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @coversDefaultClass Signup
 */
class Signup_test extends TestCase {
	public function test_signup_logged_in() : void {
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
	public function test_signup_not_logged_in() : void {
		//user isn't logged in, so show signup form
		$output = $this->request('GET', 'user/signup');
		$this->assertTitle($output, 'Signup');
	}

	public function test_signup_p1_validation_pass_verification_pass() : void {
		$this->request->setCallable(
			function ($CI) {
				$validation = $this->getDouble(
					'MY_Form_validation',
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
					$auth = $this->getDouble('Auth_Model', ['verificationStart' => TRUE]);
					$this->verifyInvokedOnce($auth, 'verificationStart');
					$CI->Auth = $auth;
				}
		);

		$output = $this->request('POST', 'user/signup', array(
			'email' => 'foo@bar.com'
		));
		$this->assertContains('We have sent an verification email to "foo@bar.com".', $output);
	}
	public function test_signup_p1_validation_pass_verification_fail() : void {
		$this->request->setCallable(
				function ($CI) {
					$validation = $this->getDouble(
							'MY_Form_validation',
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
					$auth = $this->getDouble('Auth_Model', ['verificationStart' => FALSE]);
					$this->verifyInvokedOnce($auth, 'verificationStart');
					$CI->Auth = $auth;
				}
		);

		$output = $this->request('POST', 'user/signup', array(
				'email' => 'foo@bar.com'
		));
		$this->assertContains('Before we start the signup, we need to verify your email.', $output);
	}
	public function test_signup_p1_validation_fail() : void {
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

	public function test_signup_p2_verification_pass_validation_pass_register_pass() : void {
		//form was valid, and register was successful, user is redirected to dashboard

		$this->request->setCallable(
			function ($CI) {
				$auth = $this->getDouble('Auth_Model', ['verificationCheck' => 'foo@bar.com']);
				$this->verifyInvokedOnce($auth, 'verificationCheck');
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

		$this->request('POST', 'user/signup/foobarauthcode', ['username' => 'test_user']);
		$this->assertRedirect('/profile/test_user');
	}
	public function test_signup_p2_verification_pass_validation_pass_register_fail() : void {
		//form was valid, and register was unsuccessful, reshow form
		$this->request->setCallable(
			function ($CI) {
				$auth = $this->getDouble('Auth_Model', ['verificationCheck' => 'foo@bar.com']);
				$this->verifyInvokedOnce($auth, 'verificationCheck');
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
					['register' => FALSE, 'errors' => FALSE, 'logged_in' => FALSE, 'messages' => [] /*This fixes a weird bug*/]
				);

				$this->verifyInvokedOnce($auth, 'register');

				$CI->ion_auth = $auth;
			}
		);

		$output = $this->request('POST', 'user/signup/foobarauthcode');
		$this->assertContains('name="username" value="foobar" id="username"', $output);
	}
	public function test_signup_p2_verification_pass_validation_fail() : void {
		//validation failed, this is probably the first time the user visits the page, show form
		$this->request->setCallable(
			function ($CI) {
				$auth = $this->getDouble('Auth_Model', ['verificationCheck' => 'foo@bar.com']);
				$this->verifyInvokedOnce($auth, 'verificationCheck');
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
	public function test_signup_p2_verification_fail() : void {
		//validation failed, this is probably the first time the user visits the page, show form
		$this->request->setCallable(
			function ($CI) {
				$auth = $this->getDouble('Auth_Model', ['verificationCheck' => FALSE]);
				$this->verifyInvokedOnce($auth, 'verificationCheck');
				$CI->Auth = $auth;
			}
		);

		$this->request('POST', 'user/signup/foobarauthcode');
		$this->assertRedirect('user/signup');
	}
}
