<?php

/**
 * @coversDefaultClass Lolitannia
 * @group FoolSlide
 */
class Lolitannia_test extends SiteTestCase {
	public function test_success() {
		$testSeries = [
			'lolicon_saga'                 => 'Lolicon Saga',
			'lolicon_phoenix__shinshokkan' => 'Lolicon Phoenix - Shinshokkan  ',
			'seitokai_no_ichizon'          => 'Seitokai no Ichizon',
			'chitose_get_you'              => 'Chitose Get You!!',
			'oneshots'                     => 'Oneshots',
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
