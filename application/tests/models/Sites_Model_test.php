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
		//TODO: Allow this to run locally, but not on Travis.
		$this->markTestIncomplete(
			'This test is temp-disabled as it doesn\'t work on Travis due to the cookie requirement'
		);

		$result = $this->Sites_Model->{'Batoto'}->getTitleData('tsugumomo-r4271:--:English');

		$this->assertInternalType('array', $result);
		$this->assertArrayHasKey('title', $result);
		$this->assertArrayHasKey('latest_chapter', $result);
		$this->assertArrayHasKey('last_updated', $result);

		$this->assertEquals('Tsugumomo', $result['title']);
		$this->assertRegExp('/^[a-zA-Z0-9]+:--:[c|v][0-9\.]+(?:\/c[0-9\.]+)?$/', $result['latest_chapter']);
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
		$result = $this->Sites_Model->{'MangaStream'}->getTitleData('okitegami');

		$this->assertInternalType('array', $result);
		$this->assertArrayHasKey('title', $result);
		$this->assertArrayHasKey('latest_chapter', $result);
		$this->assertArrayHasKey('last_updated', $result);

		$this->assertEquals('The Memorandum of Kyoko Okitegami', $result['title']);
		$this->assertRegExp('/^[0-9]+\/[0-9]+$/', $result['latest_chapter']);
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
		//TODO: Allow this to run locally, but not on Travis.
		$this->markTestIncomplete(
			'This test is temp-disabled as it doesn\'t work on Travis due to the cookie requirement'
		);

		$result = $this->Sites_Model->{'KissManga'}->getTitleData('Tsugumomo');

		$this->assertInternalType('array', $result);
		$this->assertArrayHasKey('title', $result);
		$this->assertArrayHasKey('latest_chapter', $result);
		$this->assertArrayHasKey('last_updated', $result);

		$this->assertEquals('Tsugumomo', $result['title']);
		$this->assertRegExp('/^.*?:--:[0-9]+$/', $result['latest_chapter']);
		$this->assertRegExp('/^[0-9]+-[0-9]+-[0-9]+ [0-9]+:[0-9]+:[0-9]+$/', $result['last_updated']);
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
}
