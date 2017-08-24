<?php

/**
 * @coversDefaultClass KireiCake
 * @group FoolSlide
 */
class KireiCake_test extends SiteTestCase {
	public function test_success() {
		$testSeries = [
			'helck'                                         => 'helck',
			'the_sister_of_the_woods_with_a_thousand_young' => 'The Sister of the Woods with a Thousand Young',
			'worlds_end_harem'                              => 'World\'s End Harem',
			'arachnid'                                      => 'Arachnid',
			'sweet_dreams_in_the_demon_castle'              => 'Sweet Dreams in the Demon Castle'
		];
		$this->_testSiteSuccessRandom($testSeries);
	}
	public function test_failure() {
		$this->_testSiteFailure('Bad Status Code (404)');
	}
}
