<?php

/**
 * @coversDefaultClass LOLScans
 */
class LOLScans_test extends SiteTestCase {
	public function test_success() {
		$testSeries = [
			'Densetsu_no_Yuusha_no_Konkatsu' => 'Densetsu no Yuusha no Konkatsu',
			'Marginal_Operation'             => 'Marginal Operation',
			'Tenjin'                         => 'Tenjin',
			'Addicted_to_Curry'              => 'Addicted to Curry',
			'Alice_Royale'                   => 'Alice Royale'
		];
		$this->_testSiteSuccessRandom($testSeries);
	}
	public function test_failure() {
		$this->_testSiteFailure('Failure string matched');
	}
}
