<?php declare(strict_types=1); defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @coversDefaultClass Logout
 */
class Logout_test extends TestCase {
	public function test_uri() : void {
		//this test also works as a basic controller check
		$this->request('GET', 'user/logout');
		$this->assertRedirect('/'); //logout always redirects to root page
	}

	//TODO (CHECK): Is there any reason for other tests here?
}
