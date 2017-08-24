<?php

/**
 * @coversDefaultClass WhiteCloudPavillion
 */
class WhiteCloudPavillion_test extends SiteTestCase {
	public function test_success() {
		$testSeries = [
			'autophagy_regulation'         => 'Autophagy Regulation',
			'i_the_female_robot'           => 'I, The Female Robot',
			'shen_yin_wang_zuo'            => 'Shen Yin Wang Zuo'
		];
		$this->_testSiteSuccessRandom($testSeries);
	}
	public function test_failure() {
		$this->_testSiteFailure('Bad Status Code (404)');
	}
}
