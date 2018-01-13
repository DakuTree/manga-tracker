<?php

/**
 * @coversDefaultClass DKThiasScans
 * @group FoolSlide
 */
class DKThiasScans_test extends SiteTestCase {
	public function test_success() {
		$testSeries = [
			'living_dead'                    => 'Living Dead!',
			'kagefumi_merry'                 => 'Kagefumi Merry',
			'yumekui_merry'                  => 'Yumekui Merry',
			'tolove_darkness'                => 'ToLoveる ~Darkness~',
			'yumekui_merry_4koma_anthology_' => 'Yumekui Merry 4-koma Anthology ',
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
