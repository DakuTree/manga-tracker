<?php

/**
 * @coversDefaultClass MangaDex
 */
class MangaCow_test extends SiteTestCase {
	public function test_success() {
		$testSeries = [
			'164' => 'Gisou Honey Trap',
			'205' => 'Witch Hat Atelier',
			'204' => 'Kumika no Mikaku',
			'200' => 'Ki Ni Naru Mori-san',
			'171' => 'Rough Sketch Senpai'
		];
		$this->_testSiteSuccessRandom($testSeries);
	}
	public function test_failure() {
		$this->_testSiteFailure('Failure string matched');
	}
}
