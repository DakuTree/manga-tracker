<?php

/**
 * @coversDefaultClass WebToons
 */
class WebToons_test extends SiteTestCase {
	public function test_success() {
		$testSeries = [
			'93:--:en:--:girls-of-the-wilds:--:action' => 'Girls of the Wild\'s',
			'700:--:en:--:nano-list:--:action'         => 'Nano List',
			'88:--:en:--:the-gamer:--:fantasy'         => 'The Gamer',
			'666:--:en:--:super-secret:--:romance'     => 'Super Secret',
			'87:--:en:--:noblesse:--:fantasy'          => 'Noblesse'
		];
		$this->_testSiteSuccessRandom($testSeries);
	}
	public function test_failure() {
		$this->markTestSkipped('WebToons doesn\'t support parseTitleDataDOM yet, which makes failure testing not work'); //FIXME: See note
		//$this->_testSiteFailure('WebToons', 'Bad Status Code (404)', '0:--:en:--:-:--:-');
	}

	public function test_custom() {
		$this->skipTravis('We need login data to make this work properly which we can\'t provide to Travis');
		$this->_testSiteCustom();
	}
}
