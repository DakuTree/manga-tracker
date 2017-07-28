<?php declare(strict_types=1); defined('BASEPATH') OR exit('No direct script access allowed');

class FallenAngelsScans extends Base_Site_Model {
	public $titleFormat   = '/^[a-zA-Z0-9_-]+$/';
	public $chapterFormat = '/^[0-9\.]+$/';

	public function getFullTitleURL(string $title_url) : string {
		return "https://manga.fascans.com/manga/{$title_url}";
	}

	public function getChapterData(string $title_url, string $chapter) : array {
		return [
			'url'    => $this->getFullTitleURL($title_url).'/'.$chapter,
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
			"(//h2[@class='widget-title'])[1]",
			"//ul[contains(@class, 'chapters')]/li[not(contains(@class, 'btn'))][1]",
			"div[contains(@class, 'action')]/div[@class='date-chapter-title-rtl']",
			"h5/a[1]",
			"Whoops, looks like something went wrong."
		);
		if($data) {
			$titleData['title'] = trim($data['nodes_title']->textContent);

			$titleData['latest_chapter'] = preg_replace('/^.*\/([0-9\.]+)$/', '$1', (string) $data['nodes_chapter']->getAttribute('href'));

			$dateString = $data['nodes_latest']->nodeValue;
			$titleData['last_updated'] = date("Y-m-d H:i:s", strtotime(preg_replace('/ (-|\[A\]).*$/', '', $dateString)));
		}

		return (!empty($titleData) ? $titleData : NULL);
	}
}
