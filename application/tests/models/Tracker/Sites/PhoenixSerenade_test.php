<?php

/**
 * @coversDefaultClass PhoenixSerenade
 * @group FoolSlide
 */
class PhoenixSerenade_test extends SiteTestCase {
	public function test_success() {
		$testSeries = [
			'majo_to_hatsukoi'              => 'Majo to Hatsukoi',
			'sakura_taisen_kanadegumi'      => 'Sakura Taisen Kanadegumi',
			'akb0048_episode_0'             => 'AKB0048 Episode 0',
			'boukyaku_no_shirushi_to_hime'  => 'Boukyaku no Shirushi to Hime',
			'kimi_no_koto_nado_zettai_ni_2' => 'Kimi no Koto nado Zettai ni',
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
