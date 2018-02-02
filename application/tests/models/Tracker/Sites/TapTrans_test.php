<?php

/**
 * @coversDefaultClass TapTrans
 * @group FoolSlide
 */
class TapTrans_test extends SiteTestCase {
	public function test_success() {
		$testSeries = [
			'my_angel_ayase_ga_konna_ni_kawaii' => 'My Angel Ayase ga Konna ni Kawaii.',
			'koyomi_sandwich'                   => 'Koyomi Sandwich!',
			'lisbeth_edition'                   => 'Lisbeth Edition',
			'renai_3jigen_debut'                => 'Renai 3-Jigen Debut',
			'rain_train'                        => 'Rain Train',
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
