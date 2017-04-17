<?php declare(strict_types=1); defined('BASEPATH') OR exit('No direct script access allowed');

class EGScans extends Base_Site_Model {
	public $titleFormat   = '/^[A-Za-z0-9\-_\!,]+$/';
	public $chapterFormat = '/^Chapter_[0-9]+(?:_extra)?$/';

	public function getFullTitleURL(string $title_url) : string {
		return "http://read.egscans.com/{$title_url}/";
	}

	public function getChapterData(string $title_url, string $chapter) : array {
		return [
			'url'    => "http://read.egscans.com/{$title_url}/{$chapter}",
			'number' => $chapter
		];
	}

	public function getTitleData(string $title_url, bool $firstGet = FALSE) {
		$titleData = [];

		$fullURL = $this->getFullTitleURL($title_url);
		$content = $this->get_content($fullURL);

		$data = $this->parseTitleDataDOM(
			$content,
			$title_url,
			"//select[@name='manga']/option[@selected='selected']",
			"//select[@name='chapter']/option[last()]",
			"//html", //FIXME: EGScans doesn't have a proper title page so we can't grab chapter time.
			"",
			"Select a manga title to get started!"
		);
		if($data) {
			$titleData['title'] = html_entity_decode($data['nodes_title']->textContent);

			$titleData['latest_chapter'] = (string) $data['nodes_chapter']->getAttribute('value');
			$titleData['last_updated'] = date("Y-m-d H:i:s", now());
		}

		return (!empty($titleData) ? $titleData : NULL);
	}
}
