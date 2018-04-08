<?php declare(strict_types=1); defined('BASEPATH') OR exit('No direct script access allowed');

class MangaDex extends Base_Site_Model {
	/* Update lang via: $(temp1).find('li img').map(function(i,e) { return $(e).attr('title').replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&'); }).toArray().join('|'); */
	public $titleFormat   = '/^[0-9]+:--:(Arabic|Bengali|Bulgarian|Catalan|Chinese \(Simp\)|Chinese \(Trad\)|Czech|Danish|Dutch|English|Filipino|Finnish|French|German|Greek|Hungarian|Indonesian|Italian|Japanese|Korean|Malaysian|Mongolian|Persian|Polish|Portuguese \(Br\)|Portuguese \(Pt\)|Romanian|Russian|Serbo\-Croatian|Spanish \(Es\)|Spanish \(LATAM\)|Swedish|Thai|Turkish|Vietnamese)$/';
	public $chapterFormat = '/^[0-9]+:--:(?:(?:v[0-9]+\/)?c[0-9\.v]+|[0-9a-zA-Z \.]+)$/';

	public $customType    = 2;

	public $canHaveNoChapters = TRUE;

	public $cookieString  = 'mangadex_h_toggle=1';

	public $siteRateLimit = 500; //MangaDex limit is 600 in 60s, but to avoid going over by mistake, we go a bit lower.

	public function getFullTitleURL(string $title_url) : string {
		$title_parts = explode(':--:', $title_url);
		return "https://mangadex.org/manga/{$title_parts[0]}";
	}

	public function getChapterData(string $title_url, string $chapter) : array {
		$chapter_parts = explode(':--:', $chapter);
		return [
			'url'    => "https://mangadex.org/chapter/{$chapter_parts[0]}",
			'number' => $chapter_parts[1]
		];
	}

	public function getTitleData(string $title_url, bool $firstGet = FALSE) : ?array {
		$titleData = [];

		$failureMatched = FALSE;

		//FIXME: There also seems to be RSS Feeds but they don't appear to work at the moment.
		$fullURL = $this->getFullTitleURL($title_url);

		$content = $this->get_content($fullURL, $this->cookieString);

		$title_parts = explode(':--:', $title_url);
		$data = $this->parseTitleDataDOM(
			$content,
			$title_url,
			'//title',
			"//div[@class='edit tab-content']/div/table/tbody/tr[.//*[@alt='{$title_parts[1]}']][1]",
			'td[7]',
			'td[2]/a',
			function($data) use (&$failureMatched) {
				$failed = strpos($data, 'Warning:</strong> Manga #') !== FALSE;
				if($failed) $failureMatched = TRUE;
				return $failed;
			},
			function($data, $xpath, &$returnData) {
				if(strpos($data, 'Notice:</strong> No chapters') !== FALSE) {
					// No chapters exist at all.
				} else if(strpos($data, 'Notice:</strong> There are no chapters in your selected language(s).') !== FALSE) {
					// No chapters exist at all.
				} else {
					$nodes_row = $xpath->query("//div[@class='edit tab-content']/div/table/tbody/tr[.//*[@alt]][1]");
					if($nodes_row->length === 1) {
						// Chapters exist, but not in the language we're looking for.
					} else {
						// No chapters exist, failure string wasn't matched and xpath failed?
						// Mostly likely an HTML change.
						$returnData = FALSE;
					}
				}
			},
			function($xpath, &$returnData) {
				$nodes_mal = $xpath->query('//th[contains(text(), "Links:")]/following-sibling::td[1]/a[contains(@href,"myanimelist.net")]');
				if($nodes_mal->length === 1) {
					$returnData['nodes_mal'] = $nodes_mal->item(0);
				}
			}
		);
		if($data) {
			$titleData['title'] = preg_replace('/\(Manga\)( -)? MangaDex.*?$/','', trim($data['nodes_title']->textContent));

			if(isset($data['nodes_latest']) && isset($data['nodes_chapter'])) {
				$chapterID     = explode('/', (string) $data['nodes_chapter']->getAttribute('href'))[2];
				$chapterNumber = preg_replace('/v\//', '', preg_replace('/^(?:Vol(?:ume|\.) ([0-9\.]+)?.*?)?Ch(?:apter|\.) ([0-9\.v]+)[\s\S]*$/', 'v$1/c$2', trim((string) $data['nodes_chapter']->textContent)));

				$titleData['latest_chapter'] = $chapterID . ':--:' . $chapterNumber;

				$titleData['last_updated'] =  date("Y-m-d H:i:s", strtotime((string) $data['nodes_latest']->getAttribute('title')));

				if(isset($data['nodes_mal'])) {
					$mal_id = explode('/', $data['nodes_mal']->getAttribute('href'))[4];
					if($mal_id !== "0") {
						$titleData['mal_id'] = explode('/', $data['nodes_mal']->getAttribute('href'))[4];
					}
				}
			}
		}

		return (!empty($titleData) ? $titleData : (!$failureMatched ? NULL : []));
	}

	public function doCustomUpdate() {
		$titleDataList = [];

		$lastChapterID   = (int) ($this->cache->get("mangadex_lastchapterid") ?: 0);
		$latestChapterID = 0;

		$page = 1;
		$getNextPage = TRUE;
		while($getNextPage) {
			if($page >= 5) break;

			$updateURL = "https://mangadex.org/0/{$page}"; //All Languages
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

							$title_url_arr = explode('/', $title->getAttribute('href'));
							$titleID = $title_url_arr[2];

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
										if($latestChapterID === 0) $latestChapterID = $chapterID;
										$chapterNumber = preg_replace('/v\//', '', preg_replace('/^(?:Vol(?:ume|\.) ([0-9\.]+)?.*?)?Ch(?:apter|\.) ([0-9\.v]+)[\s\S]*$/', 'v$1/c$2', trim((string) $chapter->textContent)));

										$titleData['latest_chapter'] = $chapterID . ':--:' . $chapterNumber;

										$dateString = trim($nodes_latest->item(0)->getAttribute('datetime'));
										$titleData['last_updated'] = date("Y-m-d H:i:s", strtotime($dateString));

										if((int) $chapterID < $lastChapterID) {
											$getNextPage = FALSE;
										}
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

			if($lastChapterID === 0) break;
			$page++;
		}

		$this->cache->save("mangadex_lastchapterid", $latestChapterID,  31536000 /* 1 year, or until we renew it */);

		return $titleDataList;
	}
}
