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
		$this->assertResponseCode(302);
		//TODO: We should verify the redirected URL
	}

	public function test_forgot_password() {
		$output = $this->request('GET', '/user/forgot_password');
		$this->assertContains('<title>Manga Tracker - Forgot Password</title>', $output);
	}
	public function test_forgot_password_continued() {
		$this->request('GET', '/user/reset_password/bad_code');
		$this->assertResponseCode(302);
		//TODO: We should verify the redirected URL
	}

	public function test_login() {
		$output = $this->request('GET', '/user/login');
		$this->assertContains('<title>Manga Tracker - Login</title>', $output);
	}
	public function test_logout() {
		$this->request('GET', '/user/logout');
		$this->assertResponseCode(302);
		//TODO: We should verify the redirected URL
	}

	public function test_user_options() {
		$this->request('GET', '/user/options');
		$this->assertResponseCode(302);
		//TODO: We should verify the redirected URL
	}
}
