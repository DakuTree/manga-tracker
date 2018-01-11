<?php

/**
 * @coversDefaultClass LHTranslation
 */
class LHTranslation_test extends SiteTestCase {
	public function test_success() {
		$testSeries = [
			'tondemo-skill-de-isekai-hourou-meshi'                        => 'Tondemo Skill de Isekai Hourou Meshi',
			'isekai-goumon-hime'                                          => 'Isekai Goumon Hime',
			'magi-craft-meister'                                          => 'Magi Craft Meister',
			'isekai-shihai-no-skill-taker-zero-kara-hajimeru-dorei-harem' => 'Isekai Shihai No Skill Taker: Zero Kara Hajimeru Dorei Harem',
			'grancrest-senki'                                             => 'Grancrest Senki'
		];
		$this->_testSiteSuccessRandom($testSeries);
	}
	public function test_failure() {
		$this->_testSiteFailure('Bad Status Code (302)');
	}
}
