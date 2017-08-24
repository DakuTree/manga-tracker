<?php

/**
 * @coversDefaultClass MerakiScans
 */
class MerakiScans_test extends SiteTestCase {
	public function test_success() {
		$testSeries = [
			'ninja-shinobu-san-no-junjou'  => 'Ninja Shinobu-chan no Junjou',
			'red-storm'                    => 'Red Storm',
			'clover-tetsuhiro-hirakawa'    => 'Clover (TETSUHIRO Hirakawa)',
			'the-mythical-realm'           => 'The Mythical Realm',
			'the-great-wife'               => 'The Great Wife'
		];
		$this->_testSiteSuccessRandom($testSeries);
	}
	public function test_failure() {
		$this->_testSiteFailure('Bad Status Code (404)');
	}
}
