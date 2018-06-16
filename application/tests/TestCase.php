<?php

class TestCase extends CIPHPUnitTestCase {
	//private static $migrate = false;

	//public static function setUpBeforeClass() {
	//	parent::setUpBeforeClass();
	//
	//	// Run migrations once
	//	if (!self::$migrate) {
	//		$CI =& get_instance();
	//		$CI->load->database();
	//		$CI->load->library('migration');
	//		if ($CI->migration->current() === false) {
	//			throw new RuntimeException($CI->migration->error_string());
	//		}
	//
	//		self::$migrate = true;
	//	}
	//}
	//public static function tearDownAfterClass() {
	//	parent::tearDownAfterClass();
	//
	//	$CI =& get_instance();
	//	$CI->load->database();
	//	$CI->load->library('migration');
	//	$CI->migration->version(0);
	//}

	public function getMock_ion_auth_logged_in() {
		$ion_auth = $this->getMockBuilder('Ion_auth')
		                 ->disableOriginalConstructor()
		                 ->getMock();

		$ion_auth->expects($this->at(0))
		         ->method('logged_in')
		         ->willReturn(TRUE);
		$ion_auth->expects($this->at(1))
		         ->method('logged_in')
		         ->willReturn(TRUE);

		$ion_auth->expects($this->any())
		         ->method($this->anything())
		         ->will($this->returnSelf());
		
		return $ion_auth;
	}

	public function getMock_CI_DB_result(array $methods) {
		$db_result = $this->getMockBuilder('CI_DB_result')
		                  ->disableOriginalConstructor()
		                  ->getMock();

		foreach ($methods as $method => $return) {
			$db_result->method($method)->willReturn($return);
		}
		return $db_result;
	}

	public function getMock_CI_DB($return) {
		$db = $this->getMockBuilder('CI_DB')
		           ->disableOriginalConstructor()
		           ->setMethods(array('select', 'from', 'where', 'get'))
		           ->getMock();

		$db->expects($this->at(3))
		   ->method('get')
		   ->willReturn($return);

		$db->expects($this->any())
		   ->method($this->anything())
		   ->will($this->returnSelf());

		return $db;
	}

	//extra functions
	public function markTestNotImplemented() : void {
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	public function skipTravis(string $reason = NULL) : void {
		if(getenv('TRAVIS')) {
			$this->markTestSkipped('This test doesn\'t play nice with Travis'.($reason ? "\nReason: $reason" : ''));
		}
	}
	public function skipTravisSSL() : void {
		$this->skipTravis('Travis\'s PHP Curl ver. doesn\'t seem to play nice with SSL.');
	}
}

class SiteTestCase extends TestCase {
	private $Sites_Model;
	private $siteName;

	public function setUp() {
		$this->resetInstance();

		$this->Sites_Model = new Tracker_Sites_Model();
		$this->siteName = str_replace('_test', '', get_class($this));
	}

	protected function _testSiteSuccess(string $title_url, string $expectedTitle) {
		$result = $this->Sites_Model->{$this->siteName}->getTitleData($title_url);

		//FIXME: We should _try_ and test response code here, specificially against error 537 which is cloudflare "site is down" error
		$this->assertInternalType('array', $result, "Title URL ({$title_url})");
		$this->assertArrayHasKey('title', $result, "Title URL ({$title_url})");
		$this->assertArrayHasKey('latest_chapter', $result, "Title URL ({$title_url})");
		$this->assertArrayHasKey('last_updated', $result, "Title URL ({$title_url})");

		$this->assertEquals($expectedTitle, $result['title'], "Title URL ({$title_url})");
		$this->assertRegExp($this->Sites_Model->{$this->siteName}->chapterFormat, $result['latest_chapter'], "Title URL ({$title_url})");
		$this->assertRegExp('/^[0-9]+-[0-9]+-[0-9]+ [0-9]+:[0-9]+:[0-9]+$/', $result['last_updated'], "Title URL ({$title_url})");

	}
	protected function _testSiteSuccessRandom(array $testTitles) {
		$title_url = array_rand($testTitles);
		$expectedTitle = $testTitles[$title_url];

		$result = $this->Sites_Model->{$this->siteName}->getTitleData($title_url);

		//FIXME: We should _try_ and test response code here, specificially against error 537 which is cloudflare "site is down" error
		$this->assertInternalType('array', $result, "Title URL ({$title_url})");
		$this->assertArrayHasKey('title', $result, "Title URL ({$title_url})");
		$this->assertArrayHasKey('latest_chapter', $result, "Title URL ({$title_url})");
		$this->assertArrayHasKey('last_updated', $result, "Title URL ({$title_url})");

		$this->assertEquals($expectedTitle, $result['title'], "Title URL ({$title_url})");
		$this->assertRegExp($this->Sites_Model->{$this->siteName}->chapterFormat, $result['latest_chapter'], "Title URL ({$title_url})");
		$this->assertRegExp('/^[0-9]+-[0-9]+-[0-9]+ [0-9]+:[0-9]+:[0-9]+$/', $result['last_updated'], "Title URL ({$title_url})");

	}
	protected function _testSiteFailure(string $errorMessage, string $title_url = 'i_am_a_bad_url') {
		$this->markTestSkipped('MonkeyPatching slows down our tests a ton so we\'ve disabled it for now (which also disables tests which use it).');

		MonkeyPatch::patchFunction('log_message', NULL, $this->siteName); //Stop logging stuff...
		$result = $this->Sites_Model->{$this->siteName}->getTitleData($title_url);

		$this->assertNull($result, "Title URL ({$title_url}");
		MonkeyPatch::verifyInvokedOnce('log_message', ['error', "{$this->siteName} : {$title_url} | {$errorMessage}"]);
	}

	protected function _testSiteCustom() {
		$this->assertNotEmpty($this->Sites_Model->{$this->siteName}->doCustomUpdate());
	}
}
