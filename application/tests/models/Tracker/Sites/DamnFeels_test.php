<?php

/**
 * @coversDefaultClass DamnFeels
 * @group FoolSlide
 */
class DamnFeels_test extends SiteTestCase {
	public function test_success() {
		$testSeries = [
			'hoshigami'                       => 'Hoshigami-kun wa Douka Shiteiru',
			'kujira-no-kora-wa-sajou-ni-utau' => 'Kujira no Kora wa Sajou ni Utau',
			'tclp'                            => 'Tsubaki-chou Lonely Planet',
			'hirunaka-no-ryuusei'             => 'Hirunaka no Ryuusei',
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
