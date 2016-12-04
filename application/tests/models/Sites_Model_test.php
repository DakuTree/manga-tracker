<?php

class Site_Model_test extends TestCase {
	private $Sites_Model;

	public function setUp() {
		$this->resetInstance();

		$this->Sites_Model = new Sites_Model();
	}

	//TODO: Each test should check a randomized series each time instead of using the same series.

	public function test_MangaFox() {
		$result = $this->Sites_Model->{'MangaFox'}->getTitleData('tsugumomo');

		$this->assertInternalType('array', $result);
		$this->assertArrayHasKey('title', $result);
		$this->assertArrayHasKey('latest_chapter', $result);
		$this->assertArrayHasKey('last_updated', $result);

		$this->assertEquals('Tsugumomo', $result['title']);
		$this->assertRegExp('/^[c|v][0-9\.]+(?:\/c[0-9\.]+)?$/', $result['latest_chapter']);
		$this->assertRegExp('/^[0-9]+-[0-9]+-[0-9]+ [0-9]+:[0-9]+:[0-9]+$/', $result['last_updated']);
	}
	public function test_MangaFox_fail() {
		MonkeyPatch::patchFunction('log_message', NULL, 'MangaFox'); //Stop logging stuff...
		$result = $this->Sites_Model->{'MangaFox'}->getTitleData('i_am_a_bad_url');

		$this->assertNull($result);
		MonkeyPatch::verifyInvokedOnce('log_message', ['error', 'MangaFox : i_am_a_bad_url | Bad Status Code (302)']);
	}

	public function test_MangaHere() {
		$result = $this->Sites_Model->{'MangaHere'}->getTitleData('tsugumomo');

		$this->assertInternalType('array', $result);
		$this->assertArrayHasKey('title', $result);
		$this->assertArrayHasKey('latest_chapter', $result);
		$this->assertArrayHasKey('last_updated', $result);

		$this->assertEquals('Tsugumomo', $result['title']);
		$this->assertRegExp('/^[c|v][0-9\.]+(?:\/c[0-9\.]+)?$/', $result['latest_chapter']);
		$this->assertRegExp('/^[0-9]+-[0-9]+-[0-9]+ [0-9]+:[0-9]+:[0-9]+$/', $result['last_updated']);
	}

	public function test_Batoto() {
		$this->skipTravis('Missing required cookies.');

		$result = $this->Sites_Model->{'Batoto'}->getTitleData('17709:--:English');

		$this->assertInternalType('array', $result);
		$this->assertArrayHasKey('title', $result);
		$this->assertArrayHasKey('latest_chapter', $result);
		$this->assertArrayHasKey('last_updated', $result);

		$this->assertEquals('Kumo desu ga, nani ka?', $result['title']);
		$this->assertRegExp('/^[a-zA-Z0-9]+:--:[c|v][0-9\.\-]+(?:\/c[0-9\.\-]+)?$/', $result['latest_chapter']);
		$this->assertRegExp('/^[0-9]+-[0-9]+-[0-9]+ [0-9]+:[0-9]+:[0-9]+$/', $result['last_updated']);
	}

	public function test_DynastyScans() {
		$result = $this->Sites_Model->{'DynastyScans'}->getTitleData('qualia_the_purple:--:0');

		$this->assertInternalType('array', $result);
		$this->assertArrayHasKey('title', $result);
		$this->assertArrayHasKey('latest_chapter', $result);
		$this->assertArrayHasKey('last_updated', $result);

		$this->assertEquals('Qualia the Purple', $result['title']);
		$this->assertRegExp('/^ch(?:apters)?[0-9]+(?:_[0-9]+)?$/', $result['latest_chapter']);
		$this->assertRegExp('/^[0-9]+-[0-9]+-[0-9]+ [0-9]+:[0-9]+:[0-9]+$/', $result['last_updated']);
	}

	public function test_MangaPanda() {
		$result = $this->Sites_Model->{'MangaPanda'}->getTitleData('relife');

		$this->assertInternalType('array', $result);
		$this->assertArrayHasKey('title', $result);
		$this->assertArrayHasKey('latest_chapter', $result);
		$this->assertArrayHasKey('last_updated', $result);

		$this->assertEquals('ReLIFE', $result['title']);
		$this->assertRegExp('/^[0-9]+$/', $result['latest_chapter']);
		$this->assertRegExp('/^[0-9]+-[0-9]+-[0-9]+ [0-9]+:[0-9]+:[0-9]+$/', $result['last_updated']);
	}

	public function test_MangaStream() {
		$this->skipTravis('Travis\'s PHP Curl ver. doesn\'t seem to play nice with SSL.');

		$result = $this->Sites_Model->{'MangaStream'}->getTitleData('okitegami');

		$this->assertInternalType('array', $result);
		$this->assertArrayHasKey('title', $result);
		$this->assertArrayHasKey('latest_chapter', $result);
		$this->assertArrayHasKey('last_updated', $result);

		$this->assertEquals('The Memorandum of Kyoko Okitegami', $result['title']);
		$this->assertRegExp('/^.*?\/[0-9]+$/', $result['latest_chapter']);
		$this->assertRegExp('/^[0-9]+-[0-9]+-[0-9]+ [0-9]+:[0-9]+:[0-9]+$/', $result['last_updated']);
	}

	public function test_WebToons() {
		$result = $this->Sites_Model->{'WebToons'}->getTitleData('93:--:en:--:girls-of-the-wilds:--:action');

		$this->assertInternalType('array', $result);
		$this->assertArrayHasKey('title', $result);
		$this->assertArrayHasKey('latest_chapter', $result);
		$this->assertArrayHasKey('last_updated', $result);

		$this->assertEquals('Girls of the Wild\'s', $result['title']);
		$this->assertRegExp('/^.*?$/', $result['latest_chapter']);
		$this->assertRegExp('/^[0-9]+-[0-9]+-[0-9]+ [0-9]+:[0-9]+:[0-9]+$/', $result['last_updated']);
	}

	public function test_KissManga() {
		$this->markTestSkipped('KM is not supported for the time being');

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

	public function test_KireiCake() {
		$result = $this->Sites_Model->{'KireiCake'}->getTitleData('helck');

		$this->assertInternalType('array', $result);
		$this->assertArrayHasKey('title', $result);
		$this->assertArrayHasKey('latest_chapter', $result);
		$this->assertArrayHasKey('last_updated', $result);

		$this->assertEquals('helck', $result['title']);
		$this->assertRegExp('/^[a-z]+\/[0-9]+\/[0-9]+(?:\/[0-9]+)?$/', $result['latest_chapter']);
		$this->assertRegExp('/^[0-9]+-[0-9]+-[0-9]+ [0-9]+:[0-9]+:[0-9]+$/', $result['last_updated']);
	}

	public function test_GameOfScanlation() {
		$this->skipTravis('Travis\'s PHP Curl ver. doesn\'t seem to play nice with SSL.');

		$result = $this->Sites_Model->{'GameOfScanlation'}->getTitleData('legendary-moonlight-sculptor.99');

		$this->assertInternalType('array', $result);
		$this->assertArrayHasKey('title', $result);
		$this->assertArrayHasKey('latest_chapter', $result);
		$this->assertArrayHasKey('last_updated', $result);

		$this->assertEquals('Legendary Moonlight Sculptor', $result['title']);
		$this->assertRegExp('/^[a-z0-9\.-]+$/', $result['latest_chapter']);
		$this->assertRegExp('/^[0-9]+-[0-9]+-[0-9]+ [0-9]+:[0-9]+:[0-9]+$/', $result['last_updated']);
	}

	public function test_MangaCow() {
		$result = $this->Sites_Model->{'MangaCow'}->getTitleData('The_scholars_reincarnation');

		$this->assertInternalType('array', $result);
		$this->assertArrayHasKey('title', $result);
		$this->assertArrayHasKey('latest_chapter', $result);
		$this->assertArrayHasKey('last_updated', $result);

		$this->assertEquals('The Scholar\'s Reincarnation', $result['title']);
		$this->assertRegExp('/^[0-9]+$/', $result['latest_chapter']);
		$this->assertRegExp('/^[0-9]+-[0-9]+-[0-9]+ [0-9]+:[0-9]+:[0-9]+$/', $result['last_updated']);
	}

	public function test_SeaOtterScans() {
		$result = $this->Sites_Model->{'SeaOtterScans'}->getTitleData('marry_me');

		$this->assertInternalType('array', $result);
		$this->assertArrayHasKey('title', $result);
		$this->assertArrayHasKey('latest_chapter', $result);
		$this->assertArrayHasKey('last_updated', $result);

		$this->assertEquals('Marry Me!', $result['title']);
		$this->assertRegExp('/^[a-z]+\/[0-9]+\/[0-9]+(?:\/[0-9]+)?$/', $result['latest_chapter']);
		$this->assertRegExp('/^[0-9]+-[0-9]+-[0-9]+ [0-9]+:[0-9]+:[0-9]+$/', $result['last_updated']);
	}

	public function test_HelveticaScans() {
		$result = $this->Sites_Model->{'HelveticaScans'}->getTitleData('mousou-telepathy');

		$this->assertInternalType('array', $result);
		$this->assertArrayHasKey('title', $result);
		$this->assertArrayHasKey('latest_chapter', $result);
		$this->assertArrayHasKey('last_updated', $result);

		$this->assertEquals('Mousou Telepathy', $result['title']);
		$this->assertRegExp('/^[a-z]+\/[0-9]+\/[0-9]+(?:\/[0-9]+)?$/', $result['latest_chapter']);
		$this->assertRegExp('/^[0-9]+-[0-9]+-[0-9]+ [0-9]+:[0-9]+:[0-9]+$/', $result['last_updated']);
	}

	public function test_SenseScans() {
		$result = $this->Sites_Model->{'SenseScans'}->getTitleData('to_you_the_immortal');

		$this->assertInternalType('array', $result);
		$this->assertArrayHasKey('title', $result);
		$this->assertArrayHasKey('latest_chapter', $result);
		$this->assertArrayHasKey('last_updated', $result);

		$this->assertEquals('To You, The Immortal', $result['title']);
		$this->assertRegExp('/^[a-z]+\/[0-9]+\/[0-9]+(?:\/[0-9]+)?$/', $result['latest_chapter']);
		$this->assertRegExp('/^[0-9]+-[0-9]+-[0-9]+ [0-9]+:[0-9]+:[0-9]+$/', $result['last_updated']);
	}
}
