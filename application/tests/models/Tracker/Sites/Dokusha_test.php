<?php

/**
 * @coversDefaultClass Dokusha
 * @group FoolSlide
 */
class Dokusha_test extends SiteTestCase {
	public function test_success() {
		$testSeries = [
			'okusan'              => 'Oku-san',
			'ai__pato'            => 'AI - Pato!',
			'system_engineer'     => 'System Engineer',
			'himawari'            => 'Himawari!',
			'watashi_no_oniichan' => 'Watashi no Oniichan',
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
