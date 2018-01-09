<?php declare(strict_types=1); defined('BASEPATH') OR exit('No direct script access allowed');

class LOLScans extends Base_Site_Model {
	public $titleFormat   = '/^[A-Za-z0-9\-_\!]+$/';
	public $chapterFormat = '/^.*?$/';

	public function getFullTitleURL(string $title_url) : string {
		return "https://forums.lolscans.com/book/browseChapters.php?p={$title_url}&t=manga&pF=projectFolderName";
	}

	public function getChapterData(string $title_url, string $chapter) : array {
		return [
			'url'    => "https://forums.lolscans.com/book/page2.php?c={$chapter}&p={$title_url}&t=manga&pF=projectFolderName",
			'number' => urldecode($chapter)
		];
	}

	public function getTitleData(string $title_url, bool $firstGet = FALSE) : ?array {
		$titleData = [];

		$fullURL = $this->getFullTitleURL($title_url);
		$content = $this->get_content($fullURL);

		$data = $this->parseTitleDataDOM(
			$content,
			$title_url,
			"//body/a[1]",
			"//body/a[last()]",
			"//html", //FIXME: EGScans doesn't have a proper title page so we can't grab chapter time.
			"",
			"This project doesn't exist. Quitting."
		);
		if($data) {
			$title = explode(" - ", $data['nodes_title']->textContent, 2);
			$titleData['title'] = html_entity_decode($title[0]);

			parse_str(substr((string) $data['nodes_chapter']->getAttribute('href'), 10), $output);
			$titleData['latest_chapter'] = $output['c'];
			$titleData['last_updated'] = date("Y-m-d H:i:s", now());
		}

		return (!empty($titleData) ? $titleData : NULL);
	}
}
