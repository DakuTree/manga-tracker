<?php declare(strict_types=1); defined('BASEPATH') OR exit('No direct script access allowed');

class MangaDex extends Base_Site_Model {
	public $titleFormat   = '/^[0-9]+:--:(English|Polish|Italian|Russian|German|Hungarian|French|Vietnamese|Spanish \(Spain\)|Portuguese \(Brazil\)|Swedish|Turkish|Indonesian|Spanish \(LATAM\)|Catalan)$/';
	public $chapterFormat = '/^[0-9]+:--:(?:v[0-9]+\/)?c[0-9\.v]+$/';

	public $customType    = 2;

	public $canHaveNoChapters = TRUE;

	public $cookieString  = 'mangadex_h_toggle=1';

	public function getFullTitleURL(string $title_url) : string {
		$title_parts = explode(':--:', $title_url);
		return "https://mangadex.com/manga/{$title_parts[0]}";
	}

	public function getChapterData(string $title_url, string $chapter) : array {
		$chapter_parts = explode(':--:', $chapter);
		return [
			'url'    => "https://mangadex.com/chapter/{$chapter_parts[0]}",
			'number' => $chapter_parts[1]
		];
	}

	public function getTitleData(string $title_url, bool $firstGet = FALSE) : ?array {
		$titleData = [];

		//FIXME: There also seems to be RSS Feeds but they don't appear to work at the moment.
		$fullURL = $this->getFullTitleURL($title_url);

		$content = $this->get_content($fullURL, $this->cookieString);

		$title_parts = explode(':--:', $title_url);
		$data = $this->parseTitleDataDOM(
			$content,
			$title_url,
			"//h3[contains(@class, 'panel-title')]/text()[1]",
			"//div[@id='chapters']/div/table/tbody/tr[.//*[@alt='{$title_parts[1]}']][1]", //FIXME: This forces English for now.
			"td[6]",
			"td[1]/a",
			"Warning: Manga #",
			"<strong>Warning:</strong> No chapters."
		);
		if($data) {
			$titleData['title'] = preg_replace('/\(Batoto .*?$/','', trim($data['nodes_title']->textContent));

			if(isset($data['nodes_latest']) && isset($data['nodes_chapter'])) {
				$chapterID     = explode('/', (string) $data['nodes_chapter']->getAttribute('href'))[2];
				$chapterNumber = preg_replace('/v\//', '', preg_replace('/^(?:Vol(?:ume|\.) ([0-9\.]+)?.*?)?Ch(?:apter|\.) ([0-9\.v]+)[\s\S]*$/', 'v$1/c$2', trim((string) $data['nodes_chapter']->textContent)));

				$titleData['latest_chapter'] = $chapterID . ':--:' . $chapterNumber;

				$titleData['last_updated'] =  date("Y-m-d H:i:s", strtotime((string) $data['nodes_latest']->getAttribute('title')));
			}
		}

		return (!empty($titleData) ? $titleData : NULL);
	}

	public function doCustomUpdate() {
		$titleDataList = [];

		$updateURL = "https://mangadex.com/1"; //English only.
		if(($content = $this->get_content($updateURL, $this->cookieString)) && $content['status_code'] == 200) {
			$data = $content['body'];

			$dom = new DOMDocument();
			libxml_use_internal_errors(TRUE);
			$dom->loadHTML($data);
			libxml_use_internal_errors(FALSE);

			$xpath      = new DOMXPath($dom);
			$nodes_rows = $xpath->query("//div[@class='col-sm-9']/div/table/tbody/tr[.//td[@rowspan]]");
			if($nodes_rows->length > 0) {
				foreach($nodes_rows as $row) {
					$titleData = [];

					$nodes_title   = $xpath->query("td[3]/a", $row);
					$nodes_chapter = $xpath->query("following-sibling::tr[1]/td[2]/a", $row);
					$nodes_latest  = $xpath->query("following-sibling::tr[1]/td[5]/time", $row);

					if($nodes_title->length === 1 && $nodes_chapter->length === 1 && $nodes_latest->length === 1) {
						$title = $nodes_title->item(0);

						preg_match('/(?<url>[^\/]+(?=\/$|$))/', $title->getAttribute('href'), $title_url_arr);
						$title_url = $title_url_arr['url'];

						if(!array_key_exists($title_url, $titleDataList)) {
							$titleData['title'] = trim($title->textContent);

							$chapter = $nodes_chapter->item(0);
							$chapterID     = explode('/', (string) $chapter->getAttribute('href'))[2];
							$chapterNumber = preg_replace('/v\//', '', preg_replace('/^(?:Vol(?:ume|\.) ([0-9\.]+)?.*?)?Ch(?:apter|\.) ([0-9\.v]+)[\s\S]*$/', 'v$1/c$2', trim((string) $chapter->textContent)));

							$titleData['latest_chapter'] = $chapterID . ':--:' . $chapterNumber;

							$dateString = trim($nodes_latest->item(0)->getAttribute('datetime'));
							$titleData['last_updated'] = date("Y-m-d H:i:s", strtotime($dateString));

							$titleDataList[$title_url] = $titleData;
						}
					} else {
						log_message('error', "{$this->site}/Custom | Invalid amount of nodes (TITLE: {$nodes_title->length} | CHAPTER: {$nodes_chapter->length}) | LATEST: {$nodes_latest->length})");
					}
				}
			} else {
				log_message('error', "{$this->site} | Following list is empty?");
			}
		} else {
			log_message('error', "{$this->site} - Custom updating failed.");
		}

		return $titleDataList;
	}
}
