<?php

/**
 * @coversDefaultClass MangaDex
 */
class MangaDex_test extends SiteTestCase {
	public function test_success() {
		$testSeries = [
			'19657' => 'Zannen Jokanbu Black General-san',
			'18302' => 'Isekai Izakaya "Nobu"',
			'21139' => 'She Gets Girls Everyday.',
			'13408' => 'Chichi to Hige-Gorilla to Watashi'
		];
		$this->_testSiteSuccessRandom($testSeries);
	}
	public function test_failure() {
		$this->_testSiteFailure('Failure string matched');
	}
}
