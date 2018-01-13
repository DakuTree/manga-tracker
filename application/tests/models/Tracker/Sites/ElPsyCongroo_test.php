<?php

/**
 * @coversDefaultClass ElPsyCongroo
 * @group FoolSlide
 */
class ElPsyCongroo_test extends SiteTestCase {
	public function test_success() {
		$testSeries = [
			'bokutachi-otoko-no-ko'   => 'Bokutachi Otoko no Ko',
			'ikinokore-shachiku-chan' => 'Ikinokore! Shachiku-chan',
			'otomedanshi'             => 'Otome Danshi ni Koisuru Otome',
			'oreaitsu'                => 'Ore ga Fujoshi de Aitsu ga YuriOta de',
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
