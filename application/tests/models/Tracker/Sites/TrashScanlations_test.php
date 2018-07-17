<?php

/**
 * @coversDefaultClass TrashScanlations
 * @group WPManga
 */
class TrashScanlations_test extends SiteTestCase {
	public function test_success() {
		$testSeries = [
			'very-pure'     => 'Very Pure',
			'holy-ancestor' => 'Holy Ancestor'
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
