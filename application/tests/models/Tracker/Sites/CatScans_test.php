<?php

/**
 * @coversDefaultClass CatScans
 * @group FoolSlide
 */
class CatScans_test extends SiteTestCase {
	public function test_success() {
		$testSeries = [
			'magika-no-kenshi-to-shoukan-maou'     => 'Magika no Kenshi to Shoukan Maou',
			'hitsugi-no-chaika'                    => 'Hitsugi no Chaika',
			'saijaku-muhai-no-bahamut'             => 'Saijaku Muhai no Bahamut',
			'busou-shoujo-machiavellism'           => 'Busou Shoujo Machiavellism',
			'kono-subarashii-sekai-ni-shukufuku-o' => 'Kono Subarashii Sekai ni Shukufuku o!',
		];
		$this->_testSiteSuccessRandom($testSeries);
	}
	public function test_failure() {
		$this->_testSiteFailure('Bad Status Code (404)');
	}
}
