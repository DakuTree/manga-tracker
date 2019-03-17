<?php declare(strict_types=1); defined('BASEPATH') OR exit('No direct script access allowed');

class MangaDex extends Base_Site_Model {
	/* Update lang via: $(temp1).find('li img').map(function(i,e) { return $(e).attr('title').replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&'); }).toArray().join('|'); */
	public $titleFormat   = '/^[0-9]+:--:(Arabic|Bengali|Bulgarian|Catalan|Chinese \(Simp\)|Chinese \(Trad\)|Czech|Danish|Dutch|English|Filipino|Finnish|French|German|Greek|Hungarian|Indonesian|Italian|Japanese|Korean|Malaysian|Mongolian|Persian|Polish|Portuguese \(Br\)|Portuguese \(Pt\)|Romanian|Russian|Serbo\-Croatian|Spanish \(Es\)|Spanish \(LATAM\)|Swedish|Thai|Turkish|Vietnamese)$/';
	public $chapterFormat = '/^[0-9]+:--:(?:(?:v[0-9\.]+\/)?c[0-9\.v\-]+|[0-9a-zA-Z \.\'’,]+)$/';
	public $pageSeparator = '/';

	public $customType    = 2;

	public $canHaveNoChapters = TRUE;

	public $cookieString  = 'mangadex_h_toggle=1';

	public $siteRateLimit = 450; //MangaDex limit 600 in 600s (10m). To avoid possible issues, we stick a bit lower than that,

	private $langCodes = ['Arabic'=>'sa','Bengali'=>'bd','Bulgarian'=>'bg','Catalan'=>'ct','Chinese (Simp)'=>'cn','Chinese (Trad)'=>'hk','Czech'=>'cz','Danish'=>'dk','Dutch'=>'nl','English'=>'gb','Filipino'=>'ph','Finnish'=>'fi','French'=>'fr','German'=>'de','Greek'=>'gr','Hungarian'=>'hu','Indonesian'=>'id','Italian'=>'it','Japanese'=>'jp','Korean'=>'kr','Malay'=>'my','Mongolian'=>'mn','Persian'=>'ir','Polish'=>'pl','Portuguese (Br)'=>'br','Portuguese (Pt)'=>'pt','Romanian'=>'ro','Russian'=>'ru','Serbo-Croatian'=>'rs','Spanish (Es)'=>'es','Spanish (LATAM)'=>'mx','Swedish'=>'se','Thai'=>'th','Turkish'=>'tr','Ukrainian'=>'ua','Vietnamese'=>'vn'];

	public function getFullTitleURL(string $title_url) : string {
		$title_parts = explode(':--:', $title_url);
		return "https://mangadex.org/manga/{$title_parts[0]}";
	}

	public function getChapterData(string $title_url, string $chapter) : array {
		$chapter_parts = explode(':--:', $chapter);
		return [
			'url'    => "https://mangadex.org/chapter/{$chapter_parts[0]}",
			'number' => str_replace('cOneshot', 'Oneshot', $chapter_parts[1])
		];
	}

	public function getTitleData(string $title_url, bool $firstGet = FALSE) : ?array {
		$titleData = [];

		$titleParts = explode(':--:', $title_url);
		if($content = $this->get_content("https://mangadex.org/api/manga/{$titleParts[0]}")) {
			//TODO: isValidContent? Status code check and whatnot.
			if($json = json_decode($content['body'], TRUE)) {
				//JSON appears to be valid.

				switch($json['status']) {
					case 'OK':
						// API query appears to have been successful.
						$titleData['title'] = trim($json['manga']['title']);
						if(array_key_exists('chapter',$json)) {
							$filteredChapters = array_filter($json['chapter'], function ($v) use ($titleParts) {
								return $v['lang_code'] === $this->langCodes[$titleParts[1]];
							});

							// MangaDex allows groups to upload in advance. Make sure we avoid grabbing these chapters.
							$unixTimestamp = time();
							$filteredChapters = array_filter($filteredChapters, function ($v) use ($titleParts, $unixTimestamp) {
								return $unixTimestamp > $v['timestamp'];
							});

							uasort($filteredChapters, function ($a, $b) {
								//CHECK: Should we account for volume here, and/or other non-numeric data?
								return (float) $b['chapter'] <=> (float) $a['chapter'];
							});
							if(!empty($filteredChapters)) {
								$latestChapter = reset($filteredChapters);
								$chapterID     = key($filteredChapters);

								$chapterNumberVolume  = (!empty($latestChapter['volume']) ? "v{$latestChapter['volume']}" : '');
								$chapterNumberChapter = (!empty($latestChapter['chapter']) ? "c{$latestChapter['chapter']}" : '');
								$chapterNumber        = trim($chapterNumberVolume . (!empty($chapterNumberVolume) && !empty($chapterNumberChapter) ? '/' : '') . $chapterNumberChapter);
								if(empty($chapterNumber)) {
									$chapterNumber = 'Oneshot';
								}
								$titleData['latest_chapter'] = $chapterID . ':--:' . $chapterNumber;

								$titleData['last_updated'] = date('Y-m-d H:i:s', $latestChapter['timestamp']);

								//FIXME: MAL ID (and all other link info) does not exist in the API, even though it does on the actual page.
								//       I've went and requested it in the beta thread - http://beta.mangadex.org/thread/16819/6/#post_109993
								//if(isset($data['nodes_mal'])) {
								//	$mal_id = explode('/', $data['nodes_mal']->getAttribute('href'))[4];
								//	if($mal_id !== "0") {
								//		$titleData['mal_id'] = explode('/', $data['nodes_mal']->getAttribute('href'))[4];
								//	}
								//}
							}
						}
						break;

					case 'Manga ID does not exist.':
						// Series has been deleted for some reason, disable it.
						$titleData['status'] = 255;
						break;

					default:
						log_message('error', 'MangaDex threw an unknown status: '.$json['status']);
						break;
				}
			}
		}

		return (!empty($titleData) ? $titleData : NULL);
	}

	public function doCustomUpdate() {
		$titleDataList = [];

		$lastChapterID   = (int) ($this->cache->get('mangadex_lastchapterid') ?: 0);
		$latestChapterID = 0;

		$page = 1;
		$getNextPage = TRUE;
		while($getNextPage) {
			if($page >= 5) break;
			if($page > 1 && empty($titleDataList)) {
				log_message('error', "{$this->site}/Custom | Custom update list failed to parse?");
			}

			//TODO: We should have a user account for R18 options
			$updateURL = "https://mangadex.org/updates/{$page}"; //All Languages
			if(($content = $this->get_content($updateURL, $this->cookieString)) && $content['status_code'] === 200) {
				$data = $content['body'];

				$dom = new DOMDocument();
				libxml_use_internal_errors(TRUE);
				$dom->loadHTML($data);
				libxml_use_internal_errors(FALSE);

				$xpath      = new DOMXPath($dom);
				$nodes_rows = $xpath->query("//div[@role='main']/div/div[@class='table-responsive']/table/tbody/tr[.//td[@rowspan]]");
				if($nodes_rows->length > 0) {
					$i = 0;
					foreach($nodes_rows as $row) {
						$i++;
						$titleData = [];

						$nodes_title         = $xpath->query('td[3]/span/a', $row);
						$nodes_rows_chapters = $xpath->query("following-sibling::tr[.//td[@title] and count(preceding-sibling::tr[.//td[@rowspan]])=$i]", $row);

						if($nodes_title->length === 1 && $nodes_rows_chapters->length >= 1) {
							$title = $nodes_title->item(0);

							$title_url_arr = explode('/', $title->getAttribute('href'));
							$titleID = $title_url_arr[2];

							foreach($nodes_rows_chapters as $rowC) {
								$nodes_lang     = $xpath->query('td[3]/span', $rowC);
								$nodes_chapter  = $xpath->query('td[2]/a', $rowC);
								$nodes_latest   = $xpath->query('td[7]/time', $rowC);

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
										$titleData['last_updated'] = date('Y-m-d H:i:s', strtotime($dateString));

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

		$this->cache->save('MangaDex_lastchapterid', $latestChapterID, 31536000 /* 1 year, or until we renew it */);

		return $titleDataList;
	}
}
