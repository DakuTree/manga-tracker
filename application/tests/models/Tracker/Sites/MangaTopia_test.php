<?php

/**
 * @coversDefaultClass MangaTopia
 * @group FoolSlide
 */
class MangaTopia_test extends SiteTestCase {
	public function test_success() {
		$testSeries = [
			'innocent'           => 'Innocent',
			'inu_yashiki'        => 'Inu Yashiki',
			'terra_formars'      => 'Terra ForMars',
			'sousei_no_onmyouji' => 'Sousei no Onmyouji',
			'yume_onna'          => 'Yume Onna',
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
