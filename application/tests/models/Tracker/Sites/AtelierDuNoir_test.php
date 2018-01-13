<?php

/**
 * @coversDefaultClass AtelierDuNoir
 * @group FoolSlide
 */
class AtelierDuNoir_test extends SiteTestCase {
	public function test_success() {
		$testSeries = [
			'skyscraper-blues'                         => 'Skyscraper Blues',
			'the-ripe-planet'                          => 'The Ripe Planet',
			'twinkling-stars'                          => 'Twinkling Stars',
			'sugar-lump'                               => 'Sugar Lump',
			'the-girl-in-the-diary-of-unrequited-love' => 'The Girl in the Diary of Unrequited Love',
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
