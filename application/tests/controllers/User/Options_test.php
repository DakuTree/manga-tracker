<?php declare(strict_types=1); defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @coversDefaultClass Options
 */
class Options_test extends TestCase {
	public function test_user_options() : void {
		$this->request('GET', '/user/options');
		$this->assertRedirect(base_url('/user/login'));
	}
	//TODO: Other tests
}
