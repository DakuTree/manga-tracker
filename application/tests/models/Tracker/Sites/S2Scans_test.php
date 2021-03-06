<?php

/**
 * @coversDefaultClass S2Scans
 * @group FoolSlide
 */
class S2Scans_test extends SiteTestCase {
	public function test_success() {
		$testSeries = [
			'black-torch'   => 'Black Torch',
			'denpa-kyoushi' => 'Denpa Kyoushi',
			'dimension-w'   => 'Dimension W',
			'kurosagi'      => 'Kurosagi'
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
