<?php

/**
 * @coversDefaultClass HotChocolateScans
 * @group FoolSlide
 */
class HotChocolateScans_test extends SiteTestCase {
	public function test_success() {
		$testSeries = [
			'beastars'               => 'Beastars',
			'dfrag'                  => 'D-Frag!',
			'konjiki_no_moji_tsukai' => 'Konjiki no Moji Tsukai'
		];
		$this->_testSiteSuccessRandom($testSeries);
	}
	public function test_failure() {
		$this->_testSiteFailure('Bad Status Code (404)');
	}
}
