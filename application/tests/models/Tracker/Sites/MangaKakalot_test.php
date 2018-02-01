<?php

/**
 * @coversDefaultClass MangaKakalot
 */
class MangaKakalot_test extends SiteTestCase {
	public function test_success() {
		$testSeries = [
			'tales_of_demons_and_gods'                       => 'Tales of Demons and Gods',
			'tamen_de_gushi'                                 => 'Tamen De Gushi',
			'mousou_telepathy'                               => 'Mousou Telepathy',
			'tomochan_wa_onnanoko'                           => 'Tomo-chan wa Onnanoko!',
			'read_historys_strongest_disciple_kenichi_manga' => 'History\'s Strongest Disciple Kenichi'
		];
		$this->_testSiteSuccessRandom($testSeries);
	}
	public function test_failure() {
		$this->_testSiteFailure('Failure string matched');
	}

	public function test_custom() {
		$this->_testSiteCustom();
	}
}
