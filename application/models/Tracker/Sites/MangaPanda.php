<?php declare(strict_types=1); defined('BASEPATH') OR exit('No direct script access allowed');

class MangaPanda extends Base_Site_Model {
	//NOTE: MangaPanda has manga pages under the root URL, so we need to filter out pages we know that aren't manga.
	public $titleFormat   = '/^(?!(?:latest|search|popular|random|alphabetical|privacy)$)([a-z0-9-]+)$/';
	public $chapterFormat = '/^[0-9]+$/';

	public function getFullTitleURL(string $title_url) : string {
		return "http://www.mangapanda.com/{$title_url}";
	}

	public function getChapterData(string $title_url, string $chapter) : array {
		return [
			'url'    => "http://www.mangapanda.com/{$title_url}/{$chapter}/",
			'number' => 'c'.$chapter
		];
	}

	public function getTitleData(string $title_url, bool $firstGet = FALSE) : ?array {
		$titleData = [];

		$fullURL = $this->getFullTitleURL($title_url);
		$content = $this->get_content($fullURL);

		$data = $this->parseTitleDataDOM(
			$content,
			$title_url,
			"//h2[@class='aname']",
			"(//table[@id='listing']/tr)[last()]",
			"td[2]",
			"td[1]/a"
		);
		if($data) {
			$titleData['title'] = $data['nodes_title']->textContent;

			$titleData['latest_chapter'] = preg_replace('/^.*\/([0-9]+)$/', '$1', (string) $data['nodes_chapter']->getAttribute('href'));

			$titleData['last_updated'] =  date("Y-m-d H:i:s", strtotime((string) $data['nodes_latest']->nodeValue));
		}

		return (!empty($titleData) ? $titleData : NULL);
	}
}
