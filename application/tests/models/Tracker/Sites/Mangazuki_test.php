<?php

class Mangazuki_test extends SiteTestCase {
	public function test_success() {
		$testSeries = [
			'Dead-Tube'                     => 'DEAD Tube',
			'Hajimete-no-Gal'               => 'Hajimete no Gal',
			'Nidome-no-Jinsei-wo-Isekai-de' => 'Nidome no Jinsei wo Isekai de',
			'Toki-Doki'                     => 'Toki Doki',
			'Sports-Girl'                   => 'Sports Girl'
		];
		$this->_testSiteSuccessRandom($testSeries);
	}
	public function test_failure() {
		$this->_testSiteFailure('Bad Status Code (404)');
	}
}
