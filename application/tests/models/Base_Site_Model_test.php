<?php

class Mock_Base_Site_Model extends Base_Site_Model {

	public function getFullTitleURL(string $title_url) : string {}

	public function getChapterData(string $title_url, string $chapter) : array {}

	public function getTitleData(string $title_url, bool $firstGet = FALSE) {}
}

class Base_Site_Model_test extends TestCase {
	/** @var Base_Site_Model **/
	private $Base_Site_Model;
	private $titleFormat;

	public function setUp() {
		$this->resetInstance();

		$this->Base_Site_Model = new Mock_Base_Site_Model();
	}

	public function test_isValidTitleURL_pass() {
		$this->Base_Site_Model->titleFormat = '/^(This[0-9]+is\.a\-test)$/';
		$this->assertTrue($this->Base_Site_Model->isValidTitleURL('This123is.a-test'));
	}
	public function test_isValidTitleURL_fail() {
		//Bad title URL
		$this->Base_Site_Model->titleFormat = '/^(This[0-9]+is\.a\-test)$/';
		$this->assertFalse($this->Base_Site_Model->isValidTitleURL('This123isNOT.a-test'));
	}
	public function test_isValidChapter_pass() {
		$this->Base_Site_Model->chapterFormat = '/^(This[0-9]+is\.a\-test)$/';
		$this->assertTrue($this->Base_Site_Model->isValidChapter('This123is.a-test'));
	}
	public function test_isValidChapter_fail() {
		//Bad title URL
		$this->Base_Site_Model->chapterFormat = '/^(This[0-9]+is\.a\-test)$/';
		$this->assertFalse($this->Base_Site_Model->isValidChapter('This123isNOT.a-test'));
	}
	public function test_doCustomCheckCompare_pass_1() {
		//Chapter has updated
		$this->assertTrue($this->Base_Site_Model->doCustomCheckCompare(['v10', 'c100'], ['v10', 'c101']));
	}
	public function test_doCustomCheckCompare_pass_2() {
		//Volume has updated.
		$this->assertTrue($this->Base_Site_Model->doCustomCheckCompare(['v10', 'c100'], ['v11', 'c100']));
	}
	public function test_doCustomCheckCompare_pass_3() {
		//New volume has been marked as TBD, but chapter is higher.
		$this->assertTrue($this->Base_Site_Model->doCustomCheckCompare(['v11', 'c100'], ['vTBD', 'c101']));
	}
	public function test_doCustomCheckCompare_pass_4() {
		//Volume is missing, but chapter is updated
		$this->assertTrue($this->Base_Site_Model->doCustomCheckCompare(['v3', 'c15'], ['c16']));
	}
	public function test_doCustomCheckCompare_pass_5() {
		//New Volume is marked as TBD, but chapter is updated
		$this->assertTrue($this->Base_Site_Model->doCustomCheckCompare(['v7', 'c83'], ['vTBD', 'c85']));
	}
	public function test_doCustomCheckCompare_pass_6() {
		//Old Volume is marked as TBD, but chapter is the same
		$this->assertTrue($this->Base_Site_Model->doCustomCheckCompare(['vTBD', 'c568'], ['v52', 'c568']));
	}
	public function test_doCustomCheckCompare_pass_7() {
		//Site uses weird, non-incrementing chapter format across volumes
		$this->assertTrue($this->Base_Site_Model->doCustomCheckCompare(['v1', 'c5'], ['v2', 'c1']));
	}
	public function test_doCustomCheckCompare_fail_1() {
		//Chapter is lower (usually due to chapter being deleted)
		$this->assertFalse($this->Base_Site_Model->doCustomCheckCompare(['v10', 'c100'], ['v10', 'c99']));
	}
	public function test_doCustomCheckCompare_fail_2() {
		//Volume is lower (usually due to assumed volume numbers being corrected)
		$this->assertFalse($this->Base_Site_Model->doCustomCheckCompare(['v10', 'c100'], ['v9', 'c100']));
	}
	public function test_doCustomCheckCompare_fail_3() {
		//New volume has been marked as TBD, but chapter is lower
		$this->assertFalse($this->Base_Site_Model->doCustomCheckCompare(['v16', 'c110'], ['vTBD', 'c108']));
	}
	public function test_doCustomCheckCompare_fail_4() {
		//New volume is higher, but chapter is lower
		$this->assertFalse($this->Base_Site_Model->doCustomCheckCompare(['v7', 'c32'], ['v8', 'c31']));
	}
	public function test_doCustomCheckCompare_fail_5() {
		//Volume is added, but chapter is lower
		$this->assertFalse($this->Base_Site_Model->doCustomCheckCompare(['c028'], ['v1', 'c15']));
	}
}
