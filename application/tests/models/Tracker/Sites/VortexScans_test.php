<?php

/**
 * @coversDefaultClass VortexScans
 * @group FoolSlide
 */
class VortexScans_test extends SiteTestCase {
	public function test_success() {
		$testSeries = [
			'necossas_six'             => 'Necossas Six',
			'minamotokun_monogatari__' => 'Minamoto-kun Monogatari  ',
			'yumekui_merry'            => 'Yumekui Merry',
			'zelphy_of_the_aion'       => 'Zelphy of the Aion',
			'dragons_rioting'          => 'Dragons Rioting',
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
