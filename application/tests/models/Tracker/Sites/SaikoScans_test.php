<?php

/**
 * @coversDefaultClass SaikoScans
 * @group FoolSlide
 */
class SaikoScans_test extends SiteTestCase {
	public function test_success() {
		$testSeries = [
			'gentleman-devil'                   => 'Gentleman Devil',
			'black-dreams'                      => 'Black Dreams',
			'ramen-daisuki-koizumi-san'         => 'Ramen Daisuki Koizumi-san',
			'mister-ajikko'                     => 'Mister Ajikko',
			'boku-dake-shitteru-ichinomiya-san' => 'Boku Dake Shitteru Ichinomiya-san',
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
