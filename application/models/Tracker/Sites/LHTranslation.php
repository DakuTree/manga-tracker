<?php declare(strict_types=1); defined('BASEPATH') OR exit('No direct script access allowed');

class LHTranslation extends Base_Site_Model {
	public $titleFormat   = '/^[a-zA-Z0-9_\-.]+$/';
	public $chapterFormat = '/^[0-9\.]+$/';

	public function getFullTitleURL(string $title_url) : string {
		return "http://lhtranslation.com/manga-{$title_url}.html";
	}

	public function getChapterData(string $title_url, string $chapter) : array {
		return [
			'url'    => "http://lhtranslation.com/read-{$title_url}-chapter-{$chapter}.html",
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
			"//title",
			"//div[@id='tab-chapper']/div/ul/table/tbody/tr[1]",
			"td[2]/i/time",
			"td[1]/a[1]",
			"Donate for Us" //bad titles simply get redirected to front page
		);
		if($data) {
			$titleData['title'] = str_replace(' - LHtranslation', '', trim($data['nodes_title']->textContent));

			$titleData['latest_chapter'] = preg_replace('/^read-(?:.*?)chapter-(.*?)\.html$/', '$1', (string) $data['nodes_chapter']->getAttribute('href'));

			$titleData['last_updated'] =  date("Y-m-d H:i:s", strtotime((string) $data['nodes_latest']->textContent));
		}

		return (!empty($titleData) ? $titleData : NULL);
	}
}
