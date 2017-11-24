<?php

/**
 * @coversDefaultClass SeaOtterScans
 * @group FoolSlide
 */
class SeaOtterScans_test extends SiteTestCase {
	public function test_success() {
		$testSeries = [
			'marry_me'                                                 => 'Marry Me!',
			'chuusotsu_worker_kara_hajimeru_koukou_seikatsu_roudousha' => 'Chuusotsu Worker kara Hajimeru Koukou Seikatsu Roudousha',
			'boku_to_rune_to_aoarashi'                                 => 'Boku to rune to Aoarashi',
			'kuhime'                                                   => 'Kuhime',
			'taishau_wotome_otogibanashi'                              => 'Taishau Wotome Otogibanashi'
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
