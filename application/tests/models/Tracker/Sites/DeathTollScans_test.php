<?php

/**
 * @coversDefaultClass DeathTollScans
 */
class DeathTollScans_test extends SiteTestCase {
	public function test_success() {
		$testSeries = [
			'destroy_and_revolution'           => 'Destroy and Revolution',
			'ouroboros'                        => 'Ouroboros',
			'sprite'                           => 'Sprite',
			'suicide_island'                   => 'Suicide Island',
			'god_you_bastard_i_wanna_kill_you' => 'God, you bastard, I wanna kill you!'
		];
		$this->_testSiteSuccessRandom($testSeries);
	}
	public function test_failure() {
		$this->_testSiteFailure('Bad Status Code (404)');
	}
}
