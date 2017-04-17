<?php declare(strict_types=1); defined('BASEPATH') OR exit('No direct script access allowed');

class MangaStream extends Base_Site_Model {
	public $titleFormat   = '/^[a-z0-9_]+$/';
	public $chapterFormat = '/^(.*?)\/[0-9]+$/';

	public function getFullTitleURL(string $title_url) : string {
		return "https://readms.net/manga/{$title_url}/";
	}

	public function getChapterData(string $title_url, string $chapter) : array {
		return [
			'url'    => "https://readms.net/r/{$title_url}/{$chapter}",
			'number' => 'c'.explode('/', $chapter)[0]
		];
	}

	public function getTitleData(string $title_url, bool $firstGet = FALSE) {
		$titleData = [];

		$fullURL = $this->getFullTitleURL($title_url);
		$content = $this->get_content($fullURL);

		$data = $this->parseTitleDataDOM(
			$content,
			$title_url,
			"//div[contains(@class, 'content')]/div[1]/h1",
			"//div[contains(@class, 'content')]/div[1]/table/tr[2]",
			"td[2]",
			"td[1]/a",
			"<h1>Page Not Found</h1>"
		);
		if($data) {
			$titleData['title'] = $data['nodes_title']->textContent;

			$titleData['latest_chapter'] = preg_replace('/^.*\/(.*?\/[0-9]+)\/[0-9]+$/', '$1', (string) $data['nodes_chapter']->getAttribute('href'));

			$titleData['last_updated'] =  date("Y-m-d H:i:s", strtotime((string) $data['nodes_latest']->nodeValue));
		}

		return (!empty($titleData) ? $titleData : NULL);
	}
}
