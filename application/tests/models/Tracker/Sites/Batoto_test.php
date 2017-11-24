<?php

/**
 * @coversDefaultClass Batoto
 */
class Batoto_test extends SiteTestCase {
	public function test_success() {
		$this->skipTravis('Missing required cookies.');

		$testSeries = [
			'17709:--:English' => 'Kumo desu ga, nani ka?',
			'718:--:English'   => 'AKB49 - Renai Kinshi Jourei',
			'3996:--:English'  => 'Akatsuki no Yona',
			'12619:--:English' => 'Ojojojo',
			'10271:--:English' => 'Ballroom e Youkoso'
		];
		$this->_testSiteSuccessRandom($testSeries);
	}
	public function test_failure() {
		$this->_testSiteFailure('Bad Status Code (404)', '00000:--:bad_lang');
	}

	public function test_custom() {
		$this->skipTravis('We need login data to make this work properly which we can\'t provide to Travis');
		$this->_testSiteCustom();
	}
}
