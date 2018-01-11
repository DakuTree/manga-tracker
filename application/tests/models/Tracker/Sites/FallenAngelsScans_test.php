<?php

/**
 * @coversDefaultClass FallenAngelsScans
 */
class FallenAngelsScans_test extends SiteTestCase {
	public function test_success() {
		$testSeries = [
			'my-hero-academia'             => 'My Hero Academia',
			'chronos-ruler'                => 'Chronos Ruler',
			'plunderer'                    => 'Plunderer',
			'to-loveru-darkness'           => 'To Love-Ru Darkness',
			'platinum-end'                 => 'Platinum End'
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
