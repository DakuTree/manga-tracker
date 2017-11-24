<?php

/**
 * @coversDefaultClass HatigarmScans
 * @group FoolSlide
 */
class HatigarmScans_test extends SiteTestCase {
	public function test_success() {
		$testSeries = [
			'joujuu-senjin-mushibugyo'                                                  => 'Joujuu Senjin!! Mushibugyo',
			'hardcore-leveling-warrior'                                                 => 'Hardcore Leveling Warrior',
			'tensei-shitara-slime-datta-ken-the-ways-of-strolling-in-the-demon-country' => 'Tensei Shitara Slime Datta Ken: The Ways of Strolling in the Demon Country',
			'life-and-death-the-song-of-the-night'                                      => 'Life and Death - The Song of the Night',
			'tales-of-demons-and-gods-portugues'                                        => 'Tales of Demons and Gods [português]',

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
