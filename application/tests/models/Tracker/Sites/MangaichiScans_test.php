<?php

/**
 * @coversDefaultClass MangaichiScans
 * @group FoolSlide
 */
class MangaichiScans_test extends SiteTestCase {
	public function test_success() {
		$testSeries = [
			'himouto_umaruchan'            => 'Himouto! Umaru-chan',
			'isekai_mahou_wa_okureteru'    => 'Isekai Mahou wa Okureteru!',
			'nanamaru_sanbatsu'            => 'Nanamaru Sanbatsu',
			'usotsuki_paradox'             => 'Usotsuki Paradox'
		];
		$this->_testSiteSuccessRandom($testSeries);
	}
	public function test_failure() {
		$this->_testSiteFailure('Bad Status Code (404)');
	}
}
