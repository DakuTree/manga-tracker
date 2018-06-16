<?php declare(strict_types=1); defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @coversDefaultClass About
 */
class About_test extends TestCase {
	public function test_about() : void {
		$output = $this->request('GET', '/about');
		$this->assertTitle($output, 'About');
	}

	public function test_terms() : void {
		$output = $this->request('GET', '/about/terms');
		$this->assertTitle($output, 'Terms of Service');
	}
}
