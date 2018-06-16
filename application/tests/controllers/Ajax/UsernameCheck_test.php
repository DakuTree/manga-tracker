<?php declare(strict_types=1); defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @coversDefaultClass UsernameCheck
 */
class UsernameCheck_test extends TestCase {
	public function test_username_check_get() : void {
		$this->request('GET', '/ajax/username_check');
		$this->assertResponseCode(404);
	}
	public function test_username_check_post() : void {
		$this->request('POST', '/ajax/username_check');
		$this->assertResponseCode(400); //This is valid, since is normally requires param
	}
}
