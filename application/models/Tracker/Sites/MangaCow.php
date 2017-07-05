<?php declare(strict_types=1); defined('BASEPATH') OR exit('No direct script access allowed');

class MangaCow extends Base_Site_Model {
	public $titleFormat   = '/^[a-zA-Z0-9_-]+$/';
	public $chapterFormat = '/^[0-9]+$/';

	public function getFullTitleURL(string $title_url) : string {
		return "http://mngcow.co/{$title_url}/";
	}

	public function getChapterData(string $title_url, string $chapter) : array {
		return [
			'url'    => $this->getFullTitleURL($title_url).$chapter.'/',
			'number' => "c{$chapter}"
		];
	}

	public function getTitleData(string $title_url, bool $firstGet = FALSE) : ?array {
		$titleData = [];

		$fullURL = $this->getFullTitleURL($title_url);

		$content = $this->get_content($fullURL);

		$data = $this->parseTitleDataDOM(
			$content,
			$title_url,
			"//h4",
			"//ul[contains(@class, 'mng_chp')]/li[1]/a[1]",
			"b[@class='dte']",
			"",
			"404 Page Not Found"
		);
		if($data) {
			$titleData['title'] = trim($data['nodes_title']->textContent);

			$titleData['latest_chapter'] = preg_replace('/^.*\/([0-9]+)\/$/', '$1', (string) $data['nodes_chapter']->getAttribute('href'));

			$titleData['last_updated'] =  date("Y-m-d H:i:s", strtotime((string) substr($data['nodes_latest']->getAttribute('title'), 13)));
		}

		return (!empty($titleData) ? $titleData : NULL);
	}
}
