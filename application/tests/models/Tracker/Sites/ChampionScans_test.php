<?php

/**
 * @coversDefaultClass ChampionScans
 * @group FoolSlide
 */
class S2Scans_test extends SiteTestCase {
	public function test_success() {
		$testSeries = [
			'no-game-no-life' => 'No Game No Life',
			'legend'          => 'Legend',
			'exterminator'    => 'Exterminator',
			'red-night-eve'   => 'Red Night Eve'
		];
		$this->_testSiteSuccessRandom($testSeries);
	}
	public function test_failure() {
		$this->_testSiteFailure('Bad Status Code (404)');
	}
}
