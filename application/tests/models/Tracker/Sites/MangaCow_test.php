<?php

class MangaCow_test extends SiteTestCase {
	public function test_success() {
		$testSeries = [
			'The_scholars_reincarnation'       => 'The Scholar\'s Reincarnation',
			'the_legendary_moonlight_sculptor' => 'The Legendary Moonlight Sculptor',
			'action_idols'                     => 'Action Idols: Age of Young Dragons',
			'the-great-conqueror'              => 'The Great Conqueror',
			'wizardly_tower'                   => 'Wizardly Tower'
		];
		$this->_testSiteSuccessRandom($testSeries);
	}
	public function test_failure() {
		$this->_testSiteFailure('Bad Status Code (404)');
	}
}
