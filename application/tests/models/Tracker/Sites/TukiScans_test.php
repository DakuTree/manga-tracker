<?php

/**
 * @coversDefaultClass TukiScans
 * @group FoolSlide
 */
class TukiScans_test extends SiteTestCase {
	public function test_success() {
		$testSeries = [
			'madoromi-chan' => 'Madoromi-chan ga Iku.',
		];
		$this->_testSiteSuccessRandom($testSeries);
	}
	public function test_failure() {
		$this->_testSiteFailure('Bad Status Code (404)');
	}

	public function test_custom() {
		$this->_testSiteCustom();
	}
}
