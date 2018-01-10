<?php

/**
 * @coversDefaultClass ShoujoHearts
 * @group FoolSlide
 */
class ShoujoHearts_test extends SiteTestCase {
	public function test_success() {
		$testSeries = [
			'oboreru-knife'               => 'Oboreru Knife',
			'hana-o-meshimase'            => 'Hana o Meshimase',
			'tea-ceremony-training'       => 'Tea Ceremony Training',
			'hotaru-no-hikari'            => 'Hotaru no Hikari',
			'ousama-ni-sasagu-kusuriyubi' => 'Ousama ni Sasagu Kusuriyubi ',
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
