<?php

/**
 * @coversDefaultClass HelveticaScans
 * @group FoolSlide
 */
class HelveticaScans_test extends SiteTestCase {
	public function test_success() {
		$testSeries = [
			'mousou-telepathy'                    => 'Mousou Telepathy',
			'grand-blue'                          => 'Grand Blue',
			'kumika-no-mikaku'                    => 'Kumika no Mikaku',
			'mousou-telepathy-twitter-extras-art' => 'Mousou Telepathy: Twitter Extras & Art',
			'kings-viking'                        => 'Kings\' Viking'
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
