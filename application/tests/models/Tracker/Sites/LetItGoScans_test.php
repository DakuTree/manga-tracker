<?php

/**
 * @coversDefaultClass LetItGoScans
 * @group FoolSlide
 */
class LetItGoScans_test extends SiteTestCase {
	public function test_success() {
		$testSeries = [
			'the-grim'                                                 => 'The Grim',
			'saikin-kono-sekai-wa-watashi-dake-no-mono-ni-narimashita' => 'Saikin Kono Sekai wa Watashi Dake no Mono ni Narimashita',
			'kunoichi-no-ichi'                                         => 'Kunoichi no Ichi',
			'the-six'                                                  => 'The Six',
			'kirei-ni-shite-moraemasu-ka'                              => 'Kirei ni shite moraemasu ka',
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
