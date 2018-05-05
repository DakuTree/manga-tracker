<?php

/**
 * @coversDefaultClass ZeroScans
 * @group WPManga
 */
class ZeroScans_test extends SiteTestCase {
	public function test_success() {
		$testSeries = [
			'all-heavenly-days'  => 'All Heavenly Days',
			'peerless-alchemist' => 'Peerless Alchemist'
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
