<?php declare(strict_types=1); defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @coversDefaultClass AdminCLI
 */
class AdminCLI_test extends TestCase {
	public function test_cli_migrate() : void {
		$this->request('GET', '/admin/migrate');
		$this->assertResponseCode(200);
	}
}
