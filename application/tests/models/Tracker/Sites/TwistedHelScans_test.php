<?php

/**
 * @coversDefaultClass TwistedHelScans
 * @group FoolSlide
 */
class TwistedHelScans_test extends SiteTestCase {
	public function test_success() {
		$testSeries = [
			'sekisei_inko'                     => 'Sekisei Inko',
			'zerozaki_soushikis_humanity_test' => 'Zerozaki Soushiki\'s Humanity Test ',
			'worldend_debugger'                => 'Worldend: Debugger',
			'area_51'                          => 'Area 51 ',
			'grateful_dead_3'                  => 'Grateful Dead',
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
