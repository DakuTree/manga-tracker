<?php declare(strict_types=1); defined('BASEPATH') OR exit('No direct script access allowed');

class MangaHere extends Base_Site_Model {
	public $titleFormat   = '/^[a-z0-9_]+$/';
	public $chapterFormat = '/^(?:v[0-9]+\/)?c[0-9]+(?:\.[0-9]+)?$/';

	public function getFullTitleURL(string $title_url) : string {
		return "http://www.mangahere.cc/manga/{$title_url}/";
	}

	public function getChapterData(string $title, string $chapter) : array {
		return [
			'url'    => "http://www.mangahere.cc/manga/{$title}/{$chapter}/",
			'number' => $chapter
		];
	}

	public function getTitleData(string $title_url, bool $firstGet = FALSE) : ?array {
		$titleData = [];

		$fullURL = $this->getFullTitleURL($title_url);
		$content = $this->get_content($fullURL);

		$data = $this->parseTitleDataDOM(
			$content,
			$title_url,
			"//meta[@property='og:title']/@content",
			"//body/section/article/div/div[@class='manga_detail']/div[@class='detail_list']/ul[1]/li[1]",
			"span[@class='right']",
			"span[@class='left']/a",
			"<div class=\"error_text\">Sorry, the page you have requested can’t be found."
		);
		if($data) {
			$titleData['title'] = $data['nodes_title']->textContent;

			$link = preg_replace('/^(.*\/)(?:[0-9]+\.html)?$/', '$1', (string) $data['nodes_chapter']->getAttribute('href'));
			$chapterURLSegments = explode('/', $link);
			$titleData['latest_chapter'] = $chapterURLSegments[5] . (isset($chapterURLSegments[6]) && !empty($chapterURLSegments[6]) ? "/{$chapterURLSegments[6]}" : "");
			$titleData['last_updated'] =  date("Y-m-d H:i:s", strtotime((string) $data['nodes_latest']->nodeValue));
		}

		return (!empty($titleData) ? $titleData : NULL);
	}
}
