<?php declare(strict_types=1); defined('BASEPATH') OR exit('No direct script access allowed');

class MangaKakalot extends Base_Site_Model {
	public $titleFormat   = '/^[a-zA-Z_\-0-9]+$/';
	public $chapterFormat = '/^[0-9\.]+$/';

	public $customType    = 2;

	public function getFullTitleURL(string $title_url) : string {
		return "http://mangakakalot.com/manga/{$title_url}/";
	}

	public function getChapterData(string $title_url, string $chapter) : array {
		return [
			'url'    => "http://mangakakalot.com/chapter/{$title_url}/chapter_{$chapter}",
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
			"//ul[@class='manga-info-text']/li[1]/h1[1]",
			"//div[@class='chapter-list']/div[1]",
			'span[3]',
			'span[1]/a',
			function($data) {
				return strpos($data, 'Sorry, the page you have requested cannot be found.') !== FALSE;
			}
		);
		if($data) {
			$titleData['title'] = html_entity_decode($data['nodes_title']->textContent);

			preg_match('/[0-9\.]+$/', (string) $data['nodes_chapter']->getAttribute('href'),$chapter);
			$titleData['latest_chapter'] = $chapter[0];

			//FIXME: We can't properly use the time provided by the site as they don't include year :|
			$titleData['last_updated'] = date("Y-m-d H:i:s", now());
		}

		return (!empty($titleData) ? $titleData : NULL);
	}

	public function doCustomUpdate() {
		$titleDataList = [];

		//FIXME: latest list does not work as no timestamp, use  http://mangakakalot.com/
		$updateURL = "http://mangakakalot.com";
		if(($content = $this->get_content($updateURL)) && $content['status_code'] == 200) {
			$data = $content['body'];

			$dom = new DOMDocument();
			libxml_use_internal_errors(TRUE);
			$dom->loadHTML($data);
			libxml_use_internal_errors(FALSE);

			$xpath      = new DOMXPath($dom);
			$nodes_rows = $xpath->query("//div[@class='doreamon']/div[@class='itemupdate first']/ul");
			if($nodes_rows->length > 0) {
				foreach($nodes_rows as $row) {
					$titleData = [];

					$nodes_title   = $xpath->query("li[1]/h3/a[1]", $row);
					$nodes_chapter = $xpath->query("li[2]/span/a", $row);
					$nodes_latest  = $xpath->query("li[2]/i", $row);

					if($nodes_title->length === 1 && $nodes_chapter->length === 1 && $nodes_latest->length === 1) {
						$title = $nodes_title->item(0);

						preg_match('/(?<url>[^\/]+(?=\/$|$))/', $title->getAttribute('href'), $title_url_arr);
						$title_url = $title_url_arr['url'];

						if(!array_key_exists($title_url, $titleDataList)) {
							$titleData['title'] = trim($title->textContent);

							$chapter = $nodes_chapter->item(0);
							preg_match('/(?<chapter>[^\/]+(?=\/$|$))/', $chapter->getAttribute('href'), $chapter_arr);
							$titleData['latest_chapter'] = str_replace('chapter_', '', $chapter_arr['chapter']);

							$dateString = trim($nodes_latest->item(0)->textContent);
							if(strpos($dateString, 'ago') === FALSE) {
								$dateString = date('Y-').$dateString;
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
