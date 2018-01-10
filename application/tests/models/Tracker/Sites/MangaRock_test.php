<?php

/**
 * @coversDefaultClass MangaRock
 */
class MangaRock_test extends SiteTestCase {
	public function test_success() {
		$testSeries = [
			'299234' => 'Boku no Hero Academia',
			'47804'  => 'Douluo Dalu I',
			'172761' => 'Nanatsu no Taizai',
			'358279' => 'One Punch-Man',
			'240647' => 'Tales of Demons and Gods'
		];
		$this->_testSiteSuccessRandom($testSeries);
	}
	public function test_failure() {
		$this->_testSiteFailure('Failure string matched');
	}
}
