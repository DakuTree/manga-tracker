<?php

/**
 * @coversDefaultClass DokiFansubs
 * @group FoolSlide
 */
class DokiFansubs_test extends SiteTestCase {
	public function test_success() {
		$this->skipTravisSSL();

		$testSeries = [
			'sui_youbi'                                            => 'Sui Youbi',
			'henjyo__hen_na_jyoshi_kousei_amaguri_senko'           => 'Henjyo – Hen na Jyoshi Kousei Amaguri Senko',
			'kabe_ni_marycom'                                      => 'Kabe ni Mary.com',
			'goblin_is_very_strong'                                => 'Goblin Is Very Strong',
			'hitoribocchi_no_oo_seikatsu'                          => 'Hitoribocchi no OO Seikatsu'
		];
		$this->_testSiteSuccessRandom($testSeries);
	}
	public function test_failure() {
		$this->skipTravis('Travis\'s PHP Curl ver. doesn\'t seem to play nice with SSL.');
		$this->_testSiteFailure('Bad Status Code (404)');
	}

	public function test_custom() {
		$this->_testSiteCustom();
	}
}
