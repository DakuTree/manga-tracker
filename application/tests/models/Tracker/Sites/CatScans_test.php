<?php

/**
 * @coversDefaultClass CatScans
 * @group FoolSlide
 */
class CatScans_test extends SiteTestCase {
	public function test_success() {
		//NOTE: It appears that CatScans routinely removes older chapters which may cause these to fail.
		$testSeries = [
			'magika-no-kenshi-to-shoukan-maou' => 'Magika no Kenshi to Shoukan Maou',
			'clockwork-planet'                 => 'Clockwork Planet',
			'gamers'                           => 'Gamers!',
			'busou-shoujo-machiavellism'       => 'Busou Shoujo Machiavellism',
			'kudamimi-no-neko'                 => 'Kudamimi no Neko',

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
