<?php

/**
 * @coversDefaultClass NaniScans
 * @group FoolSlide
 */
class NaniScans_test extends SiteTestCase {
	public function test_success() {
		$testSeries = [
			'asmodeus-wa-akiramenai'    => 'Asmodeus wa Akiramenai',
			'henkyou-no-roukishi'       => 'Henkyou no Roukishi - Bard Roen',
			'the-faraway-paladin'       => 'The Faraway Paladin',
			'isekai-tensei-ni-kansha-o' => 'Isekai Tensei ni Kansha o',
			'buchimaru-chaos'           => 'Buchimaru Chaos',
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
