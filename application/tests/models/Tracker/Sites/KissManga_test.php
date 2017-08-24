<?php

/**
 * @coversDefaultClass KissManga
 */
class KissManga_test extends SiteTestCase {
	public function test_success() {
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
	public function test_failure() {
		$this->markTestNotImplemented();
	}
}
