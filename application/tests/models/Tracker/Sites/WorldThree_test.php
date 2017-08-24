<?php

/**
 * @coversDefaultClass WorldThree
 * @group FoolSlide
 */
class WorldThree_test extends SiteTestCase {
	public function test_success() {
		$testSeries = [
			'asayake_wa_koganeiro' => 'Asayake wa Koganeiro',
			'black_bullet'         => 'Black Bullet',
			'dont_cry_girl'        => 'Don\'t Cry, Girl'
		];
		$this->_testSiteSuccessRandom($testSeries);
	}
	public function test_failure() {
		$this->_testSiteFailure('Bad Status Code (404)');
	}
}
