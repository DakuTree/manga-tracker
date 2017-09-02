<?php

/**
 * @coversDefaultClass PureMashiroScans
 * @group FoolSlide
 */
class PureMashiroScans_test extends SiteTestCase {
	public function test_success() {
		$testSeries = [
			'sao-alternative-ggo'                  => 'Sword Art Online Alternative - Gun Gale Online [ENG]',
			'nanoha-yougashiten-no-ii-shigoto-eng' => 'Nanoha Yougashiten no Ii Shigoto [ENG]',
			'hanebado-eng'                         => 'Hanebado! [ENG]',
			'ookumo-chan-flashback'                => 'Ookumo-chan Flashback',
			'100-man'                              => '100-man no Inochi no Ue ni Ore wa Tatteiru [ENG]',
		];
		$this->_testSiteSuccessRandom($testSeries);
	}
	public function test_failure() {
		$this->_testSiteFailure('Bad Status Code (404)');
	}
}
