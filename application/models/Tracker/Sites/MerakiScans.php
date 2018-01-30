<?php declare(strict_types=1); defined('BASEPATH') OR exit('No direct script access allowed');

class MerakiScans extends Base_Site_Model {
	public $titleFormat   = '/^[a-zA-Z0-9_-]+$/';
	public $chapterFormat = '/^[0-9\.]+$/';

	public $customType    = 2;

	public function getFullTitleURL(string $title_url) : string {
		return "http://merakiscans.com/{$title_url}/";
	}

	public function getChapterData(string $title_url, string $chapter) : array {
		return [
			'url'    => $this->getFullTitleURL($title_url).$chapter.'/',
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
			"//h1",
			"//ul[contains(@class, 'mng_chp')]/li[1]/a[1]",
			"b[@class='dte']",
			"",
			"404 Page Not Found"
		);
		if($data) {
			$titleData['title'] = trim($data['nodes_title']->textContent);

			$titleData['latest_chapter'] = preg_replace('/^.*\/([0-9\.]+)\/$/', '$1', (string) $data['nodes_chapter']->getAttribute('href'));

			$titleData['last_updated'] =  date("Y-m-d H:i:s", strtotime((string) substr($data['nodes_latest']->getAttribute('title'), 13)));
		}

		return (!empty($titleData) ? $titleData : NULL);
	}

	public function doCustomUpdate() {
		$titleDataList = [];

		$updateURL = "http://merakiscans.com/";
		if(($content = $this->get_content($updateURL)) && $content['status_code'] == 200) {
			$data = $content['body'];

			$dom = new DOMDocument();
			libxml_use_internal_errors(TRUE);
			$dom->loadHTML($data);
			libxml_use_internal_errors(FALSE);

			$xpath      = new DOMXPath($dom);
			$nodes_rows = $xpath->query("//div[@class='wpm_pag mng_lts_chp grp']/div[@class='row']/div[@class='det']");
			if($nodes_rows->length > 0) {
				foreach($nodes_rows as $row) {
					$titleData = [];

					$nodes_title   = $xpath->query("a[1]", $row);
					$nodes_chapter = $xpath->query("ul/li[1]/a", $row);
					$nodes_latest  = $xpath->query("ul/li[1]/a/b[@class='dte']", $row);

					if($nodes_title->length === 1 && $nodes_chapter->length === 1 && $nodes_latest->length === 1) {
						$title   = $nodes_title->item(0);
						$chapter = $nodes_chapter->item(0);

						preg_match('/(?<url>[^\/]+(?=\/$|$))/', $title->getAttribute('href'), $title_url_arr);
						$title_url = $title_url_arr['url'];

						if(!array_key_exists($title_url, $titleDataList)) {
							$titleData['title'] = trim($title->getAttribute('title'));

							preg_match('/(?<chapter>[^\/]+(?=\/$|$))/', $chapter->getAttribute('href'), $chapter_arr);
							$titleData['latest_chapter'] = $chapter_arr['chapter'];

							$dateString = trim(str_replace('Published on ', '', $nodes_latest->item(0)->textContent));
							switch($dateString) {
								case 'Today':
									$dateString = date("Y-m-d", now());
									break;

								case 'Yesterday':
									$dateString = date("Y-m-d", strtotime("-1 days"));
									break;

								default:
									//Do nothing
									break;
							}
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
