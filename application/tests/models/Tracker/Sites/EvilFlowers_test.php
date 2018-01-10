<?php

/**
 * @coversDefaultClass EvilFlowers
 * @group FoolSlide
 */
class EvilFlowers_test extends SiteTestCase {
	public function test_success() {
		$testSeries = [
			'door_to_heaven'           => 'Door to Heaven',
			'dont_touch_me'            => 'Don\'t Touch Me!',
			'candy_doll'               => 'Candy Doll',
			'ageha'                    => 'Ageha',
			'kimi_ni_moete_ii_desu_ka' => 'Kimi ni Moete Ii Desu Ka',
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
