<?php

/**
 * @coversDefaultClass OneTimeScans
 */
class OneTimeScans_test extends SiteTestCase {
	public function test_success() {
		$testSeries = [
			'eromanga_sensei'                                                 => 'Eromanga Sensei',
			'accel_world__dural__magisa_garden'                               => 'Accel World / Dural - Magisa Garden',
			'mushoku_tensei__isekai_ittara_honki_dasu'                        => 'Mushoku Tensei - Isekai Ittara Honki Dasu',
			'rezero_kara_hajimeru_isekai_seikatsu__daisanshou__truth_of_zero' => 'Re:Zero kara Hajimeru Isekai Seikatsu - Daisanshou - Truth of Zero',
			'tate_no_yuusha_no_nariagari'                                     => 'Tate no Yuusha no Nariagari'
		];
		$this->_testSiteSuccessRandom($testSeries);
	}
	public function test_failure() {
		$this->_testSiteFailure('Bad Status Code (404)');
	}
}
