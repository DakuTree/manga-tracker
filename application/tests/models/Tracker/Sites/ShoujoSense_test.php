<?php

/**
 * @coversDefaultClass ShoujoSense
 * @group FoolSlide
 */
class ShoujoSense_test extends SiteTestCase {
	public function test_success() {
		$testSeries = [
			'uso_tsurezure'                         => 'Uso Tsurezure',
			'tetsugaku_letra'                       => 'Tetsugaku Letra',
			'lofi_after_school'                     => 'Lo-Fi After School',
			'cubism_love'                           => 'Cubism Love',
			'harizuki_kagerou_enishi_no_monogatari' => 'Harizuki Kagerou Enishi no Monogatari',
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
