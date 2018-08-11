<?php

/**
 * @coversDefaultClass WoweScans
 * @group              myMangaReaderCMS
 */
class WoweScans_test extends SiteTestCase {
	public function test_success() {
		$testSeries = [
			'red-spirit'     => 'Red Spirit',
			'dusk-howler'    => 'Dusk Howler',
			'munchkin-quest' => 'Munchkin Quest'
		];
		$this->_testSiteSuccessRandom($testSeries);
	}
	public function test_failure() {
		$this->_testSiteFailure('Bad Status Code (500)');
	}

	public function test_custom() {
		$this->_testSiteCustom();
	}
}
