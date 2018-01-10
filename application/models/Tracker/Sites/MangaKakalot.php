<?php declare(strict_types=1); defined('BASEPATH') OR exit('No direct script access allowed');

class MangaKakalot extends Base_Site_Model {
	public $titleFormat   = '/^[a-zA-Z_\-0-9]+$/';
	public $chapterFormat = '/^[0-9\.]+$/';

	public function getFullTitleURL(string $title_url) : string {
		return "http://mangakakalot.com/manga/{$title_url}/";
	}

	public function getChapterData(string $title_url, string $chapter) : array {
		return [
			'url'    => "http://mangakakalot.com/chapter/{$title_url}/chapter_{$chapter}",
			'number' => "c${$chapter}"
		];
	}

	public function getTitleData(string $title_url, bool $firstGet = FALSE) : ?array {
		$titleData = [];

		$fullURL = $this->getFullTitleURL($title_url);
		$content = $this->get_content($fullURL);

		$data = $this->parseTitleDataDOM(
			$content,
			$title_url,
			"//ul[@class='manga-info-text']/li[1]/h1[1]",
			"//div[@class='chapter-list']/div[1]",
			"span[3]",
			"span[1]/a",
			"Sorry, the page you have requested cannot be found."
		);
		if($data) {
			$titleData['title'] = html_entity_decode($data['nodes_title']->textContent);

			preg_match('/[0-9\.]+$/', (string) $data['nodes_chapter']->getAttribute('href'),$chapter);
			$titleData['latest_chapter'] = $chapter[0];

			//FIXME: We can't properly use the time provided by the site as they don't include year :|
			$titleData['last_updated'] = date("Y-m-d H:i:s", now());
		}

		return (!empty($titleData) ? $titleData : NULL);
	}
}
