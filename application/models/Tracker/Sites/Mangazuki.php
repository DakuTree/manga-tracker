<?php declare(strict_types=1); defined('BASEPATH') OR exit('No direct script access allowed');

class Mangazuki extends Base_Site_Model {
	public $titleFormat   = '/^[a-zA-Z0-9-]+$/';
	public $chapterFormat = '/^[0-9\.]+$/';

	public function getFullTitleURL(string $title_url) : string {
		return "https://mangazuki.co/series/{$title_url}";
	}

	public function getChapterData(string $title_url, string $chapter) : array {
		return [
			'url'    => "https://mangazuki.co/read/{$title_url}/{$chapter}",
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
			"//div[@id='activity']/descendant::div[@class='media'][1]/descendant::div[@class='media-body']",
			"//ul[contains(@class, 'media-list')]/li[@class='media'][1]/a",
			"div[@class='media-body']/span[@class='text-muted']",
			""
		);
		if($data) {
			$titleData['title'] = trim(preg_replace('/ Added on .*$/','', $data['nodes_title']->textContent));

			$titleData['latest_chapter'] = preg_replace('/^.*\/([0-9\.]+)$/', '$1', (string) $data['nodes_chapter']->getAttribute('href'));

			$dateString = str_replace('Added ', '',$data['nodes_latest']->textContent);
			$titleData['last_updated'] =  date("Y-m-d H:i:s", strtotime($dateString));
		}

		return (!empty($titleData) ? $titleData : NULL);
	}
}
