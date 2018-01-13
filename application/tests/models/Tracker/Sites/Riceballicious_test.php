<?php

/**
 * @coversDefaultClass Riceballicious
 * @group FoolSlide
 */
class Riceballicious_test extends SiteTestCase {
	public function test_success() {
		$testSeries = [
			'sword_art_online__fairy_dance' => 'Sword Art Online - Fairy Dance',
			'deus_x_machina'                => 'Deus x Machina',
			'suashi_no_meteorite'           => 'Suashi no Meteorite',
			'black_rockchan'                => 'Black Rock-chan',
			'kono_oneesan_wa_fiction_desu'  => 'Kono Onee-san wa Fiction desu!?',
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
