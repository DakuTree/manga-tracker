<?php

/**
 * @coversDefaultClass Mangazuki
 * @group              myMangaReaderCMS
 */
class Mangazuki_test extends SiteTestCase {
	public function test_success() {
		$testSeries = [
			'que-sera-sera'          => 'Que Sera, Sera',
			'brawling-go'            => 'Brawling GO!',
			'minamotokun-monogatari' => 'Minamoto-kun Monogatari',
			'ghost-love'             => 'Ghost Love',
			'hcampus'                => 'H-Campus'
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
