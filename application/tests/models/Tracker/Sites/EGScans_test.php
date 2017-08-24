<?php

class EGScans_test extends SiteTestCase {
	public function test_success() {
		$testSeries = [
			'Yongbi_the_Invincible'          => 'Yongbi the Invincible',
			'Fate_Stay_Night_-_Heavens_Feel' => 'Fate Stay Night - Heavens Feel',
			'Cherry_Boy,_That_Girl'          => 'Cherry Boy, That Girl',
			'Piano_no_Mori'                  => 'Piano no Mori',
			'Jin'                            => 'Jin'
		];
		$this->_testSiteSuccessRandom($testSeries);
	}
	public function test_failure() {
		$this->_testSiteFailure('Failure string matched');
	}
}
