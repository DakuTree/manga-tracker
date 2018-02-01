<?php

/**
 * @coversDefaultClass ReadMangaToday
 */
class ReadMangaToday_test extends SiteTestCase {
	public function test_success() {
		$testSeries = [
			'fairy-tail'              => 'Fairy Tail',
			'chio-chan-no-tsuugakuro' => 'Chio-chan no Tsuugakuro',
			'tokyo_ghoul_re'          => 'Tokyo Ghoul:re',
			'nanatsu-no-taizai'       => 'Nanatsu no Taizai',
			'boku-no-hero-academia'   => 'Boku no Hero Academia'
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
