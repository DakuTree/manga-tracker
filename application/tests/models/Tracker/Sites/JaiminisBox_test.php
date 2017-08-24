<?php

class JaiminisBox_test extends SiteTestCase {
	public function test_success() {
		$this->skipTravisSSL();

		$testSeries = [
			'black_clover'          => 'Black Clover',
			'one-piece-2'           => 'One Piece',
			'hungry-marie'          => 'Hungry Marie',
			're-monster'            => 're: monster',
			'boku-no-hero-academia' => 'Boku no Hero Academia'
		];
		$this->_testSiteSuccessRandom($testSeries);
	}
	public function test_failure() {
		$this->skipTravisSSL();
		$this->_testSiteFailure('Bad Status Code (404)');
	}
}
