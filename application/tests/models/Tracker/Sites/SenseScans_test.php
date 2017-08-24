<?php

class SenseScans_test extends SiteTestCase {
	public function test_success() {
		$testSeries = [
			'to_you_the_immortal'                => 'To You, The Immortal',
			'magi__labyrinth_of_magic'           => 'Magi - Labyrinth of Magic',
			'youkai_apaato_no_yuuga_na_nichijou' => 'Youkai Apaato no Yuuga na Nichijou',
			'kino_no_tabi'                       => 'Kino no Tabi',
			'shoukoku_no_altair'                 => 'Shoukoku no Altair'
		];
		$this->_testSiteSuccessRandom($testSeries);
	}
	public function test_failure() {
		$this->_testSiteFailure('Bad Status Code (404)');
	}
}
