<?php

class URI_test extends TestCase {
	/******** GENERAL/LOGGED OUT TESTS ************/
	public function test_index() {
		$output = $this->request('GET', '/');
		$this->assertRedirect(base_url('/user/login'));
		//$this->assertContains('<title>Manga Tracker - Index</title>', $output);
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

	public function test_username_check_get() {
		$this->request('GET', '/ajax/username_check');
		$this->assertResponseCode(404);
	}
	public function test_username_check_post() {
		$this->request('POST', '/ajax/username_check');
		$this->assertResponseCode(400); //This is valid, since is normally requires param
	}

	public function test_get_apikey_get() {
		$this->request('GET', '/ajax/get_apikey');
		$this->assertResponseCode(404);
	}
	public function test_get_apikey_post() {
		$this->request('POST', '/ajax/get_apikey');
		$this->assertResponseCode(400); //This is valid, since requires login
	}

	public function test_update_inline_get() {
		$this->request('GET', '/ajax/update_inline');
		$this->assertResponseCode(404);
	}
	public function test_update_inline_post() {
		$this->request('POST', '/ajax/update_inline');
		$this->assertRedirect(base_url('/user/login'));
	}

	public function test_delete_inline_get() {
		$this->request('GET', '/ajax/delete_inline');
		$this->assertResponseCode(404);
	}
	public function test_delete_inline_post() {
		$this->request('POST', '/ajax/delete_inline');
		$this->assertRedirect(base_url('/user/login'));
	}

	public function test_tag_update_get() {
		$this->request('GET', '/ajax/tag_update');
		$this->assertResponseCode(404);
	}
	public function test_tag_update_post() {
		$this->request('POST', '/ajax/tag_update');
		$this->assertRedirect(base_url('/user/login'));
	}

	public function test_set_category_get() {
		$this->request('GET', '/ajax/set_category');
		$this->assertResponseCode(404);
	}
	public function test_set_category_post() {
		$this->request('POST', '/ajax/set_category');
		$this->assertRedirect(base_url('/user/login'));
	}

	public function test_export_list_get() {
		$this->request('GET', '/export_list');
		$this->assertRedirect(base_url('/user/login'));
	}
	public function test_export_list_post() {
		$this->request('POST', '/export_list');
		$this->assertRedirect(base_url('/user/login'));
	}
	public function test_import_list_get() {
		$this->request('GET', '/import_list');
		$this->assertResponseCode(404);
	}
	public function test_import_list_post() {
		$this->request('POST', '/ajax/set_category');
		$this->assertRedirect(base_url('/user/login'));
	}

	public function test_userscript_update_get() {
		$this->request('GET', '/ajax/userscript/update');
		$this->assertResponseCode(404);
	}
	public function test_userscript_update_post() {
		$this->request('POST', '/ajax/userscript/update');
		$this->assertResponseCode(400);
	}

	public function test_about() {
		$output = $this->request('GET', '/about');
		$this->assertContains('<title>Manga Tracker - About</title>', $output);
	}

	public function test_cli_migrate() {
		$this->request('GET', '/admin/migrate');
		$this->assertResponseCode(200);
	}
}
