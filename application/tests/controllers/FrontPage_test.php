<?php declare(strict_types=1); defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @coversDefaultClass FrontPage
 */
class FrontPage_test extends TestCase {
	public function test_frontpage() : void {
		$output = $this->request('GET', '/');
		$this->assertContains('<title>Manga Tracker</title>', $output);
	}
}
