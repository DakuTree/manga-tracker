<?php declare(strict_types=1); defined('BASEPATH') OR exit('No direct script access allowed');

class ReadMangaToday extends Base_Site_Model {
	public $titleFormat   = '/^[a-zA-Z0-9_-]+$/';
	public $chapterFormat = '/^[0-9\.]+$/';

	public $customType    = 2;

	public function getFullTitleURL(string $title_url) : string {
		return "https://www.readmng.com/{$title_url}";
	}

	public function getChapterData(string $title_url, string $chapter) : array {
		//http://www.readmanga.today/saiki-kusuo-no-psi-nan/128
		return [
			'url'    => $this->getFullTitleURL($title_url).'/'.$chapter,
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
			"//body/div[@class='content']/div/div/div/div/div/div/div/div/h1",
			"//ul[contains(@class, 'chp_lst')]/li[1]/a[1]",
			"span[@class='dte']",
			'',
			function($data) {
				return strpos($data, '404 Page Not Found') !== FALSE;
			}
		);
		if($data) {
			$titleData['title'] = trim($data['nodes_title']->textContent);

			$titleData['latest_chapter'] = preg_replace('/^.*\/([0-9\.]+)$/', '$1', (string) $data['nodes_chapter']->getAttribute('href'));

			$dateString = $data['nodes_latest']->nodeValue;
			$titleData['last_updated'] =  date("Y-m-d H:i:s", strtotime(preg_replace('/ (-|\[A\]).*$/', '', $dateString)));
		}

		return (!empty($titleData) ? $titleData : NULL);
	}

	public function doCustomUpdate() {
		$titleDataList = [];

		$updateURL = "https://www.readmng.com/latest-releases";
		if(($content = $this->get_content($updateURL)) && $content['status_code'] == 200) {
			$data = $content['body'];

			$data = preg_replace('/^[\s\S]+<div class="content-list col-md-12 hot-manga">/', '<div class="content-list col-md-12 hot-manga">', $data);
			$data = preg_replace('/<\!--col-md-12-->[\s\S]+$/', '<!--col-md-12-->', $data);

			$dom = new DOMDocument();
			libxml_use_internal_errors(TRUE);
			$dom->loadHTML($data);
			libxml_use_internal_errors(FALSE);

			$xpath      = new DOMXPath($dom);
			$nodes_rows = $xpath->query("//div[@class='manga_updates']/dl[.//dd[1]/a]");
			if($nodes_rows->length > 0) {
				foreach($nodes_rows as $row) {
					$titleData = [];

					$nodes_title   = $xpath->query("dt/a", $row);
					$nodes_chapter = $xpath->query("dd[1]/a", $row);
					$nodes_latest  = $xpath->query("dt/span[@class='time']", $row);

					if($nodes_title->length === 1 && $nodes_chapter->length === 1 && $nodes_latest->length === 1) {
						$title = $nodes_title->item(0);

						preg_match('/(?<url>[^\/]+(?=\/$|$))/', $title->getAttribute('href'), $title_url_arr);
						$title_url = $title_url_arr['url'];

						if(!array_key_exists($title_url, $titleDataList)) {
							$titleData['title'] = trim($title->textContent);

							$chapter = $nodes_chapter->item(0);
							preg_match('/(?<chapter>[^\/]+(?=\/$|$))/', $chapter->getAttribute('href'), $chapter_arr);
							$titleData['latest_chapter'] = $chapter_arr['chapter'];

							$dateString = trim($nodes_latest->item(0)->textContent);
							$titleData['last_updated'] = date("Y-m-d H:i:s", strtotime(str_replace('/', '-', $dateString)));

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
