<?php

/**
 * @coversDefaultClass ForgottenScans
 * @group FoolSlide
 */
class ForgottenScans_test extends SiteTestCase {
	public function test_success() {
		$testSeries = [
			'black_dog'        => 'Black Dog',
			'kochikame'        => 'Kochikame',
			'bowling_king'     => 'Bowling King  ',
			'chibi_marukochan' => 'Chibi Maruko-Chan',
			'the_doraemons'    => 'The Doraemons',
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
