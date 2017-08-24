<?php

/**
 * @coversDefaultClass JaiminisBox
 */
class JaiminisBox_test extends SiteTestCase {
	public function test_success() {
		$this->skipTravis('Travis\'s PHP Curl ver. doesn\'t seem to play nice with SSL.');

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
		$this->skipTravis('Travis\'s PHP Curl ver. doesn\'t seem to play nice with SSL.');
		$this->_testSiteFailure('Bad Status Code (404)');
	}
}
