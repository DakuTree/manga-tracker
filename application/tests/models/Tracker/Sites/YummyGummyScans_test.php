<?php

/**
 * @coversDefaultClass YummyGummyScans
 * @group FoolSlide
 */
class YummyGummyScans_test extends SiteTestCase {
	public function test_success() {
		$testSeries = [
			'celestial-destroyer'          => 'Celestial Destroyer',
			'hinmin-choujin-kanenashi-kun' => 'Hinmin Choujin Kanenashi-kun',
			'maou-no-hisho'                => 'Maou no Hisho'
		];
		$this->_testSiteSuccessRandom($testSeries);
	}
	public function test_failure() {
		$this->_testSiteFailure('Bad Status Code (404)');
	}
}
