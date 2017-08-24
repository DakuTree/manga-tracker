<?php

class MangaPanda_test extends SiteTestCase {
	public function test_success() {
		$testSeries = [
			'relife'                  => 'ReLIFE',
			'kimi-ni-todoke'          => 'Kimi Ni Todoke',
			'watashi-ni-xx-shinasai'  => 'Watashi ni xx Shinasai!',
			'the-gamer'               => 'The Gamer',
			'shokugeki-no-soma'       => 'Shokugeki no Soma'
		];
		$this->_testSiteSuccessRandom($testSeries);
	}
	public function test_failure() {
		$this->_testSiteFailure('Bad Status Code (404)');
	}
}
