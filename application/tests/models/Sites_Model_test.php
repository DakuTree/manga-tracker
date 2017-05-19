<?php

/**
 * @group SiteTests
 */
class Site_Model_test extends TestCase {
	private $Sites_Model;

	public function setUp() {
		$this->resetInstance();

		$this->Sites_Model = new Tracker_Sites_Model();
	}

	//TODO: Each test should check a randomized series each time instead of using the same series.

	public function test_MangaFox() {
		$testSeries = [
			'tsugumomo'              => 'Tsugumomo',
			'inari_konkon_koi_iroha' => 'Inari, Konkon, Koi Iroha',
			'futari_no_renai_shoka'  => 'Futari no Renai Shoka',
			'boku_girl'              => 'Boku Girl',
			'sakuranbo_syndrome'     => 'Sakuranbo Syndrome'
		];
		$randSeries = array_rand($testSeries);

		$this->_testSiteSuccess('MangaFox', $randSeries, $testSeries[$randSeries]);
	}
	public function test_MangaFox_fail() {
		$this->_testSiteFailure('MangaFox', 'Bad Status Code (302)');
	}

	public function test_MangaHere() {
		$testSeries = [
			'tsugumomo'              => 'Tsugumomo',
			'inari_konkon_koi_iroha' => 'Inari, Konkon, Koi Iroha',
			'futari_no_renai_shoka'  => 'Futari no Renai Shoka',
			'boku_girl'              => 'Boku Girl',
			'sakuranbo_syndrome'     => 'Sakuranbo Syndrome'
		];
		$randSeries = array_rand($testSeries);

		$this->_testSiteSuccess('MangaHere', $randSeries, $testSeries[$randSeries]);
	}
	public function test_MangaHere_fail() {
		$this->_testSiteFailure('MangaHere', 'Failure string matched');
	}

	public function test_Batoto() {
		$this->skipTravis('Missing required cookies.');

		$testSeries = [
			'17709:--:English' => 'Kumo desu ga, nani ka?',
			'718:--:English'   => 'AKB49 - Renai Kinshi Jourei',
			'3996:--:English'  => 'Akatsuki no Yona',
			'12619:--:English' => 'Ojojojo',
			'10271:--:English' => 'Ballroom e Youkoso'
		];
		$randSeries = array_rand($testSeries);

		$this->_testSiteSuccess('Batoto', $randSeries, $testSeries[$randSeries]);
	}
	public function test_Batoto_fail() {
		$this->_testSiteFailure('Batoto', 'Bad Status Code (404)', '00000:--:bad_lang');
	}

	public function test_DynastyScans_1() { //Test Series
		$this->skipTravis('Travis\'s PHP Curl ver. doesn\'t seem to play nice with SSL.');

		$testSeries = [
			'qualia_the_purple:--:0'       => 'Qualia the Purple',
			'a_kiss_and_a_white_lily:--:0' => 'A Kiss And A White Lily',
			'smiling_broadly:--:0'         => 'Smiling Broadly'
		];
		$randSeries = array_rand($testSeries);

		$this->_testSiteSuccess('DynastyScans', $randSeries, $testSeries[$randSeries]);
	}
	public function test_DynastyScans_2() { //Test Oneshot
		$this->skipTravis('Travis\'s PHP Curl ver. doesn\'t seem to play nice with SSL.');

		$testSeries = [
			'afterschool_girl:--:1'     => 'Afterschool Girl',
			'moon_and_sunflower:--:1'   => 'Moon and Sunflower',
			'a_secret_on_the_lips:--:1' => 'A Secret on the Lips'
		];
		$randSeries = array_rand($testSeries);

		$this->_testSiteSuccess('DynastyScans', $randSeries, $testSeries[$randSeries]);
	}
	public function test_DynastyScans_fail() {
		$this->markTestNotImplemented();
	}

	public function test_MangaPanda() {
		$testSeries = [
			'relife'                  => 'ReLIFE',
			'kimi-ni-todoke'          => 'Kimi Ni Todoke',
			'watashi-ni-xx-shinasai'  => 'Watashi ni xx Shinasai!',
			'the-gamer'               => 'The Gamer',
			'shokugeki-no-soma'       => 'Shokugeki no Soma'
		];
		$randSeries = array_rand($testSeries);

		$this->_testSiteSuccess('MangaPanda', $randSeries, $testSeries[$randSeries]);
	}
	public function test_MangaPanda_fail() {
		$this->_testSiteFailure('MangaPanda', 'Bad Status Code (404)');
	}

	public function test_MangaStream() {
		$this->skipTravis('Travis\'s PHP Curl ver. doesn\'t seem to play nice with SSL.');

		$testSeries = [
			'okitegami'  => 'The Memorandum of Kyoko Okitegami',
			'fairy_tail' => 'Fairy Tail',
			'one_piece'  => 'One Piece',
			'wt'         => 'World Trigger',
			'yona'       => 'Akatsuki no Yona'
		];
		$randSeries = array_rand($testSeries);

		$this->_testSiteSuccess('MangaStream', $randSeries, $testSeries[$randSeries]);
	}
	public function test_MangaStream_fail() {
		$this->skipTravis('Travis\'s PHP Curl ver. doesn\'t seem to play nice with SSL.');

		$this->_testSiteFailure('MangaStream', 'Bad Status Code (302)');
	}

	public function test_WebToons() {
		$testSeries = [
			'93:--:en:--:girls-of-the-wilds:--:action' => 'Girls of the Wild\'s',
			'700:--:en:--:nano-list:--:action'         => 'Nano List',
			'88:--:en:--:the-gamer:--:fantasy'         => 'The Gamer',
			'666:--:en:--:super-secret:--:romance'     => 'Super Secret',
			'87:--:en:--:noblesse:--:fantasy'          => 'Noblesse'
		];
		$randSeries = array_rand($testSeries);

		$this->_testSiteSuccess('WebToons', $randSeries, $testSeries[$randSeries]);
	}
	public function test_WebToons_fail() {
		$this->markTestSkipped('WebToons doesn\'t support parseTitleDataDOM yet, which makes failure testing not work'); //FIXME: See note
		//$this->_testSiteFailure('WebToons', 'Bad Status Code (404)', '0:--:en:--:-:--:-');
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

		$testSeries = [
			'legendary-moonlight-sculptor.99' => 'Legendary Moonlight Sculptor',
			'skill-of-lure'                   => 'Skill of Lure',
			'here-comes-the-fiancee.105'      => 'Here comes the Fiancee!',
			'balls-friend'                    => 'Balls Friend',
			'pride-complex'                   => 'Pride Complex'
		];
		$randSeries = array_rand($testSeries);

		$this->_testSiteSuccess('GameOfScanlation', $randSeries, $testSeries[$randSeries]);
	}
	public function test_GameOfScanlation_fail() {
		$this->skipTravis('Travis\'s PHP Curl ver. doesn\'t seem to play nice with SSL.');

		$this->_testSiteFailure('GameOfScanlation', 'Bad Status Code (404)');
	}

	public function test_MangaCow() {
		$testSeries = [
			'The_scholars_reincarnation'       => 'The Scholar\'s Reincarnation',
			'the_legendary_moonlight_sculptor' => 'The Legendary Moonlight Sculptor',
			'action_idols'                     => 'Action Idols: Age of Young Dragons',
			'the-great-conqueror'              => 'The Great Conqueror',
			'wizardly_tower'                   => 'Wizardly Tower'
		];
		$randSeries = array_rand($testSeries);

		$this->_testSiteSuccess('MangaCow', $randSeries, $testSeries[$randSeries]);
	}
	public function test_MangaCow_fail() {
		$this->_testSiteFailure('MangaCow', 'Bad Status Code (404)');
	}

	public function test_EGScans() {
		$testSeries = [
			'Yongbi_the_Invincible'          => 'Yongbi the Invincible',
			'Fate_Stay_Night_-_Heavens_Feel' => 'Fate Stay Night - Heavens Feel',
			'Cherry_Boy,_That_Girl'          => 'Cherry Boy, That Girl',
			'Piano_no_Mori'                  => 'Piano no Mori',
			'Jin'                            => 'Jin'
		];
		$randSeries = array_rand($testSeries);

		$this->_testSiteSuccess('EGScans', $randSeries, $testSeries[$randSeries]);
	}
	public function test_EGScans_fail() {
		$this->_testSiteFailure('EGScans', 'Failure string matched');
	}

	public function test_KireiCake() {
		$testSeries = [
			'helck'                                         => 'helck',
			'the_sister_of_the_woods_with_a_thousand_young' => 'The Sister of the Woods with a Thousand Young',
			'worlds_end_harem'                              => 'World\'s End Harem',
			'arachnid'                                      => 'Arachnid',
			'sweet_dreams_in_the_demon_castle'              => 'Sweet Dreams in the Demon Castle'
		];
		$randSeries = array_rand($testSeries);

		$this->_testSiteSuccess('KireiCake', $randSeries, $testSeries[$randSeries]);
	}
	public function test_KireiCake_fail() {
		$this->_testSiteFailure('KireiCake', 'Bad Status Code (404)');
	}

	public function test_SeaOtterScans() {
		$testSeries = [
			'marry_me'                                                 => 'Marry Me!',
			'chuusotsu_worker_kara_hajimeru_koukou_seikatsu_roudousha' => 'Chuusotsu Worker kara Hajimeru Koukou Seikatsu Roudousha',
			'boku_to_rune_to_aoarashi'                                 => 'Boku to rune to Aoarashi',
			'kuhime'                                                   => 'Kuhime',
			'taishau_wotome_otogibanashi'                              => 'Taishau Wotome Otogibanashi'
		];
		$randSeries = array_rand($testSeries);

		$this->_testSiteSuccess('SeaOtterScans', $randSeries, $testSeries[$randSeries]);
	}
	public function test_SeaOtterScans_fail() {
		$this->_testSiteFailure('SeaOtterScans', 'Bad Status Code (404)');
	}

	public function test_HelveticaScans() {
		$testSeries = [
			'mousou-telepathy'                    => 'Mousou Telepathy',
			'grand-blue'                          => 'Grand Blue',
			'kumika-no-mikaku'                    => 'Kumika no Mikaku',
			'mousou-telepathy-twitter-extras-art' => 'Mousou Telepathy: Twitter Extras & Art',
			'kings-viking'                        => 'Kings\' Viking'
		];
		$randSeries = array_rand($testSeries);

		$this->_testSiteSuccess('HelveticaScans', $randSeries, $testSeries[$randSeries]);
	}
	public function test_HelveticaScans_fail() {
		$this->_testSiteFailure('HelveticaScans', 'Bad Status Code (404)');
	}

	public function test_SenseScans() {
		$testSeries = [
			'to_you_the_immortal'                => 'To You, The Immortal',
			'magi__labyrinth_of_magic'           => 'Magi - Labyrinth of Magic',
			'youkai_apaato_no_yuuga_na_nichijou' => 'Youkai Apaato no Yuuga na Nichijou',
			'kino_no_tabi'                       => 'Kino no Tabi',
			'shoukoku_no_altair'                 => 'Shoukoku no Altair'

		];
		$randSeries = array_rand($testSeries);

		$this->_testSiteSuccess('SenseScans', $randSeries, $testSeries[$randSeries]);
	}
	public function test_SenseScans_fail() {
		$this->_testSiteFailure('SenseScans', 'Bad Status Code (404)');
	}

	public function test_JaiminisBox() {
		$this->skipTravis('Travis\'s PHP Curl ver. doesn\'t seem to play nice with SSL.');

		$testSeries = [
			'black_clover'          => 'Black Clover',
			'one-piece-2'           => 'One Piece',
			'hungry-marie'          => 'Hungry Marie',
			're-monster'            => 're: monster',
			'boku-no-hero-academia' => 'Boku no Hero Academia'
		];
		$randSeries = array_rand($testSeries);

		$this->_testSiteSuccess('JaiminisBox', $randSeries, $testSeries[$randSeries]);
	}
	public function test_JaiminisBox_fail() {
		$this->skipTravis('Travis\'s PHP Curl ver. doesn\'t seem to play nice with SSL.');

		$this->_testSiteFailure('JaiminisBox', 'Bad Status Code (404)');
	}

	public function test_DokiFansubs() {
		$this->skipTravis('Travis\'s PHP Curl ver. doesn\'t seem to play nice with SSL.');

		$testSeries = [
			'sui_youbi'                                            => 'Sui Youbi',
			'henjyo__hen_na_jyoshi_kousei_amaguri_senko'           => 'Henjyo – Hen na Jyoshi Kousei Amaguri Senko',
			'kabe_ni_marycom'                                      => 'Kabe ni Mary.com',
			'goblin_is_very_strong'                                => 'Goblin Is Very Strong',
			'hitoribocchi_no_oo_seikatsu'                          => 'Hitoribocchi no OO Seikatsu'
		];
		$randSeries = array_rand($testSeries);

		$this->_testSiteSuccess('DokiFansubs', $randSeries, $testSeries[$randSeries]);
	}
	public function test_DokiFansubs_fail() {
		$this->skipTravis('Travis\'s PHP Curl ver. doesn\'t seem to play nice with SSL.');

		$this->_testSiteFailure('DokiFansubs', 'Bad Status Code (404)');
	}

	public function test_DemonicScans() {
		$this->markTestSkipped('DemonicScans no longer exists.');

		//$this->_testSiteSuccess('DemonicScans', 'shen_yin_wang_zuo', 'Shen Yin Wang Zuo');
	}
	public function test_DemonicScans_fail() {
		$this->markTestSkipped('DemonicScans no longer exists.');

		//$this->_testSiteFailure('DemonicScans', 'Bad Status Code (404)');
	}

	public function test_DeathTollScans() {
		$testSeries = [
			'destroy_and_revolution'           => 'Destroy and Revolution',
			'ouroboros'                        => 'Ouroboros',
			'sprite'                           => 'Sprite',
			'suicide_island'                   => 'Suicide Island',
			'god_you_bastard_i_wanna_kill_you' => 'God, you bastard, I wanna kill you!'
		];
		$randSeries = array_rand($testSeries);

		$this->_testSiteSuccess('DeathTollScans', $randSeries, $testSeries[$randSeries]);
	}
	public function test_DeathTollScans_fail() {
		$this->_testSiteFailure('DeathTollScans', 'Bad Status Code (404)');
	}

	private function _testSiteSuccess(string $siteName, string $title_url, string $expectedTitle) {
		$result = $this->Sites_Model->{$siteName}->getTitleData($title_url);

		$this->assertInternalType('array', $result, "Title URL ({$title_url})");
		$this->assertArrayHasKey('title', $result, "Title URL ({$title_url})");
		$this->assertArrayHasKey('latest_chapter', $result, "Title URL ({$title_url})");
		$this->assertArrayHasKey('last_updated', $result, "Title URL ({$title_url})");

		$this->assertEquals($expectedTitle, $result['title'], "Title URL ({$title_url})");
		$this->assertRegExp($this->Sites_Model->{$siteName}->chapterFormat, $result['latest_chapter'], "Title URL ({$title_url})");
		$this->assertRegExp('/^[0-9]+-[0-9]+-[0-9]+ [0-9]+:[0-9]+:[0-9]+$/', $result['last_updated'], "Title URL ({$title_url})");

	}
	private function _testSiteFailure(string $siteName, string $errorMessage, string $title_url = 'i_am_a_bad_url') {
		$this->markTestSkipped('MonkeyPatching slows down our tests a ton so we\'ve disabled it for now (which has also disables tests which use it).');

		MonkeyPatch::patchFunction('log_message', NULL, $siteName); //Stop logging stuff...
		$result = $this->Sites_Model->{$siteName}->getTitleData($title_url);

		$this->assertNull($result, "Title URL ({$title_url}");
		MonkeyPatch::verifyInvokedOnce('log_message', ['error', "{$siteName} : {$title_url} | {$errorMessage}"]);
	}
}
