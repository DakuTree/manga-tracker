<?php

/**
 * @coversDefaultClass ReadManhua
 * @group              myMangaReaderCMS
 */
class ReadManhua_test extends SiteTestCase {
	public function test_success() {
		$testSeries = [
			'holy-ancestor'     => 'Holy Ancestor',
			'chaotic-sword-god' => 'Chaotic Sword God',
			'lord-xue-ying'     => 'Lord Xue Ying'
		];
		$this->_testSiteSuccessRandom($testSeries);
	}
	public function test_failure() {
		$this->_testSiteFailure('Bad Status Code (500)');
	}

	public function test_custom() {
		$this->_testSiteCustom();
	}
}
