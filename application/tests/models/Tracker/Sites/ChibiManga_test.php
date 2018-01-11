<?php

/**
 * @coversDefaultClass ChibiManga
 */
class ChibiManga_test extends SiteTestCase {
	public function test_success() {
		$testSeries = [
			'hanikamu-honey'    => 'Hanikamu Honey',
			'christmas-candy'   => 'Christmas Candy',
			'daisuki-no-charge' => 'Daisuki no Charge',
			'animal-panic'      => 'Animal Panic',
			'akuma-to-duet'     => 'Akuma to Duet'
		];
		$this->_testSiteSuccessRandom($testSeries);
	}
	public function test_failure() {
		$this->_testSiteFailure('Bad Status Code (500)');
	}

	public function test_custom() {
		$this->_testSiteCustom();
	}
}
