<?php

/**
 * @coversDefaultClass PsychoPlay
 */
class PsychoPlay_test extends SiteTestCase {
	public function test_success() {
		$testSeries = [
			'Domestic-na-Kanojo'             => 'Domestic na Kanojo',
			'Rengoku-Deadroll'               => 'Rengoku Deadroll',
			'Tsumi-to-Kai'                   => 'Tsumi to Kai',
			'Sensei-no-Yasashii-Koroshikata' => 'Sensei no Yasashii Koroshikata',
			'Shackles'                       => 'Shackles'
		];
		$this->_testSiteSuccessRandom($testSeries);
	}
	public function test_failure() {
		$this->_testSiteFailure('Bad Status Code (404)');
	}
}
