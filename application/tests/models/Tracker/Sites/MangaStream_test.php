<?php

class MangaStream_test extends SiteTestCase {
	public function test_success() {
		$this->skipTravisSSL();

		$testSeries = [
			'okitegami'  => 'The Memorandum of Kyoko Okitegami',
			'fairy_tail' => 'Fairy Tail',
			'one_piece'  => 'One Piece',
			'wt'         => 'World Trigger',
			'yona'       => 'Akatsuki no Yona'
		];
		$this->_testSiteSuccessRandom($testSeries);
	}
	public function test_failure() {
		$this->skipTravisSSL();

		$this->_testSiteFailure('Bad Status Code (302)');
	}
}
