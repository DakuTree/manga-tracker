<?php

class Site_Model_test extends TestCase {
	private $Sites_Model;

	public function setUp() {
		$this->resetInstance();

		$this->Sites_Model = new Sites_Model();
	}

	//TODO: Each test should check a randomized series each time instead of using the same series.

	public function test_MangaFox() {
		$this->_testSiteSuccess('MangaFox', 'tsugumomo', 'Tsugumomo');
	}
	public function test_MangaFox_fail() {
		$this->_testSiteFailure('MangaFox', 'Bad Status Code (302)');
	}

	public function test_MangaHere() {
		$this->_testSiteSuccess('MangaHere', 'tsugumomo', 'Tsugumomo');
	}
	public function test_MangaHere_fail() {
		$this->_testSiteFailure('MangaHere', 'Failure string matched');
	}

	public function test_Batoto() {
		$this->skipTravis('Missing required cookies.');

		$this->_testSiteSuccess('Batoto', '17709:--:English', 'Kumo desu ga, nani ka?');
	}
	public function test_Batoto_fail() {
		$this->_testSiteFailure('Batoto', 'Bad Status Code (404)', '00000:--:bad_lang');
	}

	public function test_DynastyScans() {
		$this->_testSiteSuccess('DynastyScans', 'qualia_the_purple:--:0', 'Qualia the Purple');
	}
	public function test_DynastyScans_fail() {
		$this->markTestNotImplemented();
	}

	public function test_MangaPanda() {
		$this->_testSiteSuccess('MangaPanda', 'relife', 'ReLIFE');
	}
	public function test_MangaPanda_fail() {
		$this->_testSiteFailure('MangaPanda', 'Bad Status Code (404)');
	}

	public function test_MangaStream() {
		$this->skipTravis('Travis\'s PHP Curl ver. doesn\'t seem to play nice with SSL.');

		$this->_testSiteSuccess('MangaStream', 'okitegami', 'The Memorandum of Kyoko Okitegami');
	}
	public function test_MangaStream_fail() {
		$this->skipTravis('Travis\'s PHP Curl ver. doesn\'t seem to play nice with SSL.');

		$this->_testSiteFailure('MangaStream', 'Bad Status Code (302)');
	}

	public function test_WebToons() {
		$this->_testSiteSuccess('WebToons', '93:--:en:--:girls-of-the-wilds:--:action', 'Girls of the Wild\'s');
	}
	public function test_WebToons_fail() {
		$this->markTestNotImplemented();
	}

	public function test_KissManga() {
		$this->markTestSkipped('KissManga is not supported for the time being');

		//$this->skipTravis('Missing required cookies.');
		//
		//$result = $this->Sites_Model->{'KissManga'}->getTitleData('Tsugumomo');
		//
		//$this->assertInternalType('array', $result);
		//$this->assertArrayHasKey('title', $result);
		//$this->assertArrayHasKey('latest_chapter', $result);
		//$this->assertArrayHasKey('last_updated', $result);
		//
		//$this->assertEquals('Tsugumomo', $result['title']);
		//$this->assertRegExp('/^.*?:--:[0-9]+$/', $result['latest_chapter']);
		//$this->assertRegExp('/^[0-9]+-[0-9]+-[0-9]+ [0-9]+:[0-9]+:[0-9]+$/', $result['last_updated']);
	}
	public function test_KissManga_fail() {
		$this->markTestNotImplemented();
	}


	public function test_GameOfScanlation() {
		$this->skipTravis('Travis\'s PHP Curl ver. doesn\'t seem to play nice with SSL.');

		$this->_testSiteSuccess('GameOfScanlation', 'legendary-moonlight-sculptor.99', 'Legendary Moonlight Sculptor');
	}
	public function test_GameOfScanlation_fail() {
		$this->markTestNotImplemented();
	}

	public function test_MangaCow() {
		$this->_testSiteSuccess('MangaCow', 'The_scholars_reincarnation', 'The Scholar\'s Reincarnation');
	}
	public function test_MangaCow_fail() {
		$this->markTestNotImplemented();
	}

	public function test_KireiCake() {
		$this->_testSiteSuccess('KireiCake', 'helck', 'helck');
	}
	public function test_KireiCake_fail() {
		$this->_testSiteFailure('KireiCake', 'Bad Status Code (404)');
	}

	public function test_SeaOtterScans() {
		$this->_testSiteSuccess('SeaOtterScans', 'marry_me', 'Marry Me!');
	}
	public function test_SeaOtterScans_fail() {
		$this->_testSiteFailure('SeaOtterScans', 'Bad Status Code (404)');
	}

	public function test_HelveticaScans() {
		$this->_testSiteSuccess('HelveticaScans', 'mousou-telepathy', 'Mousou Telepathy');
	}
	public function test_HelveticaScans_fail() {
		$this->_testSiteFailure('HelveticaScans', 'Bad Status Code (404)');
	}

	public function test_SenseScans() {
		$this->_testSiteSuccess('SenseScans', 'to_you_the_immortal', 'To You, The Immortal');
	}
	public function test_SenseScans_fail() {
		$this->_testSiteFailure('SenseScans', 'Bad Status Code (404)');
	}

	private function _testSiteSuccess(string $siteName, string $title_url, string $expectedTitle) {
		$result = $this->Sites_Model->{$siteName}->getTitleData($title_url);

		$this->assertInternalType('array', $result);
		$this->assertArrayHasKey('title', $result);
		$this->assertArrayHasKey('latest_chapter', $result);
		$this->assertArrayHasKey('last_updated', $result);

		$this->assertEquals($expectedTitle, $result['title']);
		$this->assertRegExp($this->Sites_Model->{$siteName}->chapterFormat, $result['latest_chapter']);
		$this->assertRegExp('/^[0-9]+-[0-9]+-[0-9]+ [0-9]+:[0-9]+:[0-9]+$/', $result['last_updated']);

	}
	private function _testSiteFailure(string $siteName, string $errorMessage, string $title_url = 'i_am_a_bad_url') {
		MonkeyPatch::patchFunction('log_message', NULL, $siteName); //Stop logging stuff...
		$result = $this->Sites_Model->{$siteName}->getTitleData($title_url);

		$this->assertNull($result);
		MonkeyPatch::verifyInvokedOnce('log_message', ['error', "{$siteName} : {$title_url} | {$errorMessage}"]);
	}
}
