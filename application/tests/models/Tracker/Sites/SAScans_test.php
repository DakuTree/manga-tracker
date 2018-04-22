<?php

/**
 * @coversDefaultClass SAScans
 * @group              myMangaReaderCMS
 */
class ChibiManga_test extends SiteTestCase {
	public function test_success() {
		$testSeries = [
			'manuke-na-fps-player-ga-isekai-e-ochita-baai'                  => 'Manuke na FPS Player ga Isekai e Ochita Baai',
			'overlord'                                                      => 'Overlord',
			'tensei-ouji--wa-daraketai'                                     => 'Tensei Ouji wa Daraketai',
			'tensei-shitara-dragon-no-tamago-datta-saikyou-igai-mezasa-nee' => 'Tensei Shitara Dragon no Tamago Datta - Saikyou Igai Mezasa Nee'
		];
		$this->_testSiteSuccessRandom($testSeries);
	}
	public function test_failure() {
		$this->_testSiteFailure('Bad Status Code (500)');
	}

	public function test_custom() {
		$this->_testSiteCustom();
	}
}
