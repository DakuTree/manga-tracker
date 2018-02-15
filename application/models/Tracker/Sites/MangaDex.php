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
			"//title",
			"//div[@id='chapters']/div/table/tbody/tr[.//*[@alt='{$title_parts[1]}']][1]",
			"td[6]",
			"td[1]/a",
			"Warning: Manga #",
			"<strong>Warning:</strong> No chapters.",
			function($xpath, &$returnData) {
				$nodes_mal = $xpath->query('//th[contains(text(), "Links:")]/following-sibling::td[1]/a[contains(@href,"myanimelist.net")]');
				if($nodes_mal->length === 1) {
					$returnData['nodes_mal'] = $nodes_mal->item(0);
				}
			}
		);
		if($data) {
			$titleData['title'] = preg_replace('/\(Manga\) MangaDex .*?$/','', trim($data['nodes_title']->textContent));

			if(isset($data['nodes_latest']) && isset($data['nodes_chapter'])) {
				$chapterID     = explode('/', (string) $data['nodes_chapter']->getAttribute('href'))[2];
				$chapterNumber = preg_replace('/v\//', '', preg_replace('/^(?:Vol(?:ume|\.) ([0-9\.]+)?.*?)?Ch(?:apter|\.) ([0-9\.v]+)[\s\S]*$/', 'v$1/c$2', trim((string) $data['nodes_chapter']->textContent)));

				$titleData['latest_chapter'] = $chapterID . ':--:' . $chapterNumber;

				$titleData['last_updated'] =  date("Y-m-d H:i:s", strtotime((string) $data['nodes_latest']->getAttribute('title')));

				if(isset($data['nodes_mal'])) {
					$titleData['mal_id'] = explode('/', $data['nodes_mal']->getAttribute('href'))[4];
				}
			}
		}

		return (!empty($titleData) ? $titleData : NULL);
	}

	public function doCustomUpdate() {
		$titleDataList = [];

		$updateURL = "https://mangadex.com/0"; //All Languages
		if(($content = $this->get_content($updateURL, $this->cookieString)) && $content['status_code'] == 200) {
			$data = $content['body'];

			$dom = new DOMDocument();
			libxml_use_internal_errors(TRUE);
			$dom->loadHTML($data);
			libxml_use_internal_errors(FALSE);

			$xpath      = new DOMXPath($dom);
			$nodes_rows = $xpath->query("//div[@class='col-sm-9']/div/table/tbody/tr[.//td[@rowspan]]");
			if($nodes_rows->length > 0) {
				$i = 0;
				foreach($nodes_rows as $row) {
					$i++;
					$titleData = [];

					$nodes_title         = $xpath->query("td[3]/a", $row);
					$nodes_rows_chapters = $xpath->query("following-sibling::tr[.//td[@title] and count(preceding-sibling::tr[.//td[@rowspan]])=$i]", $row);

					if($nodes_title->length === 1 && $nodes_rows_chapters->length >= 1) {
						$title = $nodes_title->item(0);

						preg_match('/(?<url>[^\/]+(?=\/$|$))/', $title->getAttribute('href'), $title_url_arr);
						$titleID = $title_url_arr['url'];

						foreach($nodes_rows_chapters as $rowC) {
							$nodes_lang     = $xpath->query('td[3]/img', $rowC);
							$nodes_chapter  = $xpath->query("td[2]/a", $rowC);
							$nodes_latest   = $xpath->query("td[5]/time", $rowC);

							if($nodes_lang->length === 1 && $nodes_chapter->length === 1 && $nodes_latest->length === 1) {
								$lang = $nodes_lang->item(0)->getAttribute('title');

								$title_url = $titleID . ':--:'. $lang;
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
								log_message('error', "{$this->site}/Custom | Invalid amount of nodes (LANG: {$nodes_lang->length} | CHAPTER: {$nodes_chapter->length} | LATEST: {$nodes_latest->length})");
							}
						}
					} else {
						log_message('error', "{$this->site}/Custom | Invalid amount of nodes (TITLE: {$nodes_title->length} | CHAPTERS: {$nodes_rows_chapters->length})");
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
