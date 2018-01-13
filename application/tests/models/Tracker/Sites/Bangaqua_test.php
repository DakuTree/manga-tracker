<?php

/**
 * @coversDefaultClass Bangaqua
 * @group FoolSlide
 */
class Bangaqua_test extends SiteTestCase {
	public function test_success() {
		$testSeries = [
			'zashikiwarashi_ga_dete_iku_sodesu'     => 'Zashikiwarashi ga dete iku Sodesu',
			'shisei_gokumon'                        => 'Shisei Gokumon',
			'doujinshi_hetalia_axis_power'          => '[Doujinshi] Hetalia Axis Power',
			'doujinshi_yoru_to_asa'                 => '[Doujinshi] Yoru to Asa',
			'doujinshi_magi_the_labyrinth_of_magic' => '[Doujinshi] Magi: The Labyrinth of Magic',
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
