<?php

/**
 * @coversDefaultClass SodaScans
 * @group Roku
 */
class SodaScans_test extends SiteTestCase {
	public function test_success() {
		$testSeries = [
			'Lovely-Again-Today'             => 'Lovely Again Today',
			'Perfect-Classroom'              => 'Perfect Classroom',
			'Watashi-wa-Kimi-wo-Nakasetai'   => 'Watashi wa Kimi wo Nakasetai'
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
