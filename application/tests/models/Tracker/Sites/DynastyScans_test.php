<?php

/**
 * @coversDefaultClass DynastyScans
 */
class DynastyScans_test extends SiteTestCase {
	public function test_success_1() { //Test Series
		$this->skipTravis('Travis\'s PHP Curl ver. doesn\'t seem to play nice with SSL.');

		$testSeries = [
			'qualia_the_purple:--:0'       => 'Qualia the Purple',
			'a_kiss_and_a_white_lily:--:0' => 'A Kiss And A White Lily',
			'smiling_broadly:--:0'         => 'Smiling Broadly'
		];
		$this->_testSiteSuccessRandom($testSeries);
	}
	public function test_success_2() { //Test Oneshot
		$this->skipTravis("Travis's PHP Curl ver. doesn't seem to play nice with SSL.");

		$testSeries = [
			'afterschool_girl:--:1'     => 'Afterschool Girl',
			'moon_and_sunflower:--:1'   => 'Moon and Sunflower',
			'a_secret_on_the_lips:--:1' => 'A Secret on the Lips'
		];
		$this->_testSiteSuccessRandom($testSeries);
	}
	public function test_failure() {
		$this->markTestNotImplemented();
	}

	public function test_custom() {
		$this->_testSiteCustom();
	}
}
