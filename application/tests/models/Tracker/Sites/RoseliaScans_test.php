<?php

/**
 * @coversDefaultClass RoseliaScans
 * @group FoolSlide
 */
class RoseliaScans_test extends SiteTestCase {
	public function test_success() {
		$testSeries = [
			'hatoful_boyfriend'   => 'Hatoful Boyfriend ',
			'hakushaku_to_yousei' => 'Hakushaku to Yousei',
			'kouen_park'          => 'Kouen (Park)',
			'captain_alice'       => 'Captain Alice ',
			'hyouka'              => 'Hyouka',
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
