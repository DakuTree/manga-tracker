<?php

class Logout_test extends TestCase {
	public function test_uri() {
		//this test also works as a basic controller check
		$this->request('GET', 'user/logout');
		$this->assertResponseCode(302); //logout always redirects to root page
	}

	//TODO (CHECK): Is there any reason for other tests here?
}
