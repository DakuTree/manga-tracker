<?php declare(strict_types=1); defined('BASEPATH') OR exit('No direct script access allowed');

class MangaDex extends Base_Site_Model {
	public $titleFormat   = '/^[0-9]+$/';
	public $chapterFormat = '/^[0-9]+:--:(?:v[0-9]+\/)?c[0-9\.v]+$/';

	public function getFullTitleURL(string $title_url) : string {
		return "https://mangadex.com/manga/{$title_url}";
	}

	public function getChapterData(string $title_url, string $chapter) : array {
		$chapter_parts = explode(':--:', $chapter);
		return [
			'url'    => "https://mangadex.com/chapter/${$chapter_parts[0]}",
			'number' => $chapter_parts[1]
		];
	}

	public function getTitleData(string $title_url, bool $firstGet = FALSE) : ?array {
		$titleData = [];

		//FIXME: There also seems to be RSS Feeds but they don't appear to work at the moment.
		$fullURL = $this->getFullTitleURL($title_url);

		$content = $this->get_content($fullURL);

		$data = $this->parseTitleDataDOM(
			$content,
			$title_url,
			"//h3[contains(@class, 'panel-title')]/text()",
			"//div[contains(@id, 'torrents')]/div/table/tbody/tr[.//*[@alt='English']][1]", //FIXME: This forces English for now.
			"td[7]",
			"td[1]/a",
			"Warning: Manga #"
		);
		if($data) {
			$titleData['title'] = preg_replace('/\(Batoto .*?$/','', trim($data['nodes_title']->textContent));

			$chapterID     = explode('/', (string) $data['nodes_chapter']->getAttribute('href'))[2];
			$chapterNumber = preg_replace('/v\//', '', preg_replace('/^(?:Vol(?:ume|\.) ([0-9\.]+)?.*?)?Ch(?:apter|\.) ([0-9\.v]+)[\s\S]*$/', 'v$1/c$2', trim((string) $data['nodes_chapter']->textContent)));

			$titleData['latest_chapter'] = $chapterID . ':--:' . $chapterNumber;

			$titleData['last_updated'] =  date("Y-m-d H:i:s", strtotime((string) $data['nodes_latest']->getAttribute('title')));
		}

		return (!empty($titleData) ? $titleData : NULL);
	}

	//TODO: Custom updater support
}
