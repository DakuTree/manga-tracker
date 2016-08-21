<?php

class URI_test extends TestCase {
	public function test_index() {
		$output = $this->request('GET', '/');
		$this->assertContains('<title>Manga Tracker - Index</title>', $output);
	}

	public function test_signup() {
		$output = $this->request('GET', '/user/signup');
		$this->assertContains('<title>Manga Tracker - Signup</title>', $output);
	}
	public function test_signup_continued() {
		$this->request('GET', '/user/signup/bad_code');
		$this->assertRedirect(base_url('user/signup'));
	}

	public function test_forgot_password() {
		$output = $this->request('GET', '/user/forgot_password');
		$this->assertContains('<title>Manga Tracker - Forgot Password</title>', $output);
	}
	public function test_forgot_password_continued() {
		$this->request('GET', '/user/reset_password/bad_code');
		$this->assertRedirect(base_url('/user/forgot_password'));
	}

	public function test_login() {
		$output = $this->request('GET', '/user/login');
		$this->assertContains('<title>Manga Tracker - Login</title>', $output);
	}
	public function test_logout() {
		$this->request('GET', '/user/logout');
		$this->assertRedirect(base_url('/'));
	}

	public function test_user_options() {
		$this->request('GET', '/user/options');
		$this->assertRedirect(base_url('/user/login'));
	}
}
