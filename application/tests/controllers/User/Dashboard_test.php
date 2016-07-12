<?php

class Dashboard_test extends TestCase {
	public function test_uri() {
		//we to fake login here to avoid redirect
		$this->markTestIncomplete('This test is broken for now.');
		$this->request->setCallablePreConstructor(
			function () {
				$ion_auth = $this->getMock_ion_auth_logged_in();
				load_class_instance('ion_auth', $ion_auth);
			}
		);

		$this->request('GET', 'user/dashboard');
		$this->assertResponseCode(200);
	}

	public function test_index_logged_in() {
		//user is logged in, dashboard is visible
		$this->markTestIncomplete('This test is broken for now.');
		$this->request->setCallablePreConstructor(
			function () {
				$ion_auth = $this->getMock_ion_auth_logged_in();
				load_class_instance('ion_auth', $ion_auth);
			}
		);

		$output = $this->request('GET', 'user/dashboard');
		$this->assertContains('This is the user dashboard.', $output);
	}

	public function test_index_not_logged_in() {
		//user isn't logged in and tries to access dashboard, should redirect to user/login.
		$this->request('GET', 'user/dashboard');
		$this->assertRedirect('user/login');
	}
}
