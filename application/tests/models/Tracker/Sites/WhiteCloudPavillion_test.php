<?php

/**
 * @coversDefaultClass WhiteCloudPavillion
 */
class WhiteCloudPavillion_test extends SiteTestCase {
	public function test_success() {
		$testSeries = [
			'autophagy-regulation'         => 'Autophagy Regulation',
			'i-the-female-robot'           => 'I, The Female Robot',
			'shen-yin-wang-zuo'            => 'Shen Yin Wang Zuo'
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
