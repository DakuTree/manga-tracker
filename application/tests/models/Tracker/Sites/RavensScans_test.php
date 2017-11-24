<?php

/**
 * @coversDefaultClass RavensScans
 * @group FoolSlide
 */
class RavensScans_test extends SiteTestCase {
	public function test_success() {
		$testSeries = [
			'yakushoku_distopiary__gesellshaft_blume'                         => 'Yakushoku Distopiary - Gesellshaft Blume',
			'taisho_wotome_otogibanashi'                                      => 'Taisho Wotome Otogibanashi',
			'rezero_kara_hajimeru_isekai_seikatsu__daisanshou__truth_of_zero' => 'Re:Zero kara Hajimeru Isekai Seikatsu - Daisanshou - Truth of Zero',
			'overlord'                                                        => 'Overlord',
			'story_of_a_certain_burned_girl'                                  => 'Story of a Certain Burned Girl',

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
