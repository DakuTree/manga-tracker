<?php declare(strict_types=1); defined('BASEPATH') OR exit('No direct script access allowed');

class LOLScans extends Base_Site_Model {
	public $titleFormat   = '/^[A-Za-z0-9\-_\!]+$/';
	public $chapterFormat = '/^.*?$/';

	public $customType    = 2;

	public function getFullTitleURL(string $title_url) : string {
		return "https://forums.lolscans.com/book/browseChapters.php?p={$title_url}&t=webcomic&pF=projectFolderName";
	}

	public function getChapterData(string $title_url, string $chapter) : array {
		return [
			'url'    => "https://forums.lolscans.com/book/page2.php?c={$chapter}&p={$title_url}&t=webcomic&pF=projectFolderName",
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

	public function doCustomUpdate() {
		$titleDataList = [];

		$updateURL = "https://lolscans.com/feed/";
		if(($content = $this->get_content($updateURL)) && $content['status_code'] == 200) {
			$data = $content['body'];

			$dom = new DOMDocument();
			libxml_use_internal_errors(TRUE);
			$dom->loadHTML('<?xml encoding="utf-8" ?>' . $data);
			libxml_use_internal_errors(FALSE);

			$xpath      = new DOMXPath($dom);
			$nodes_rows = $xpath->query("//rss/channel/item");
			if($nodes_rows->length > 0) {
				foreach($nodes_rows as $row) {
					$titleData = [];

					$nodes_title   = $xpath->query("title", $row);
					$nodes_chapter = $xpath->query("encoded", $row);
					$nodes_latest  = $xpath->query("pubdate", $row);

					if($nodes_title->length === 1 && $nodes_chapter->length === 1 && $nodes_latest->length === 1) {
						$title   = $nodes_title->item(0);
						$chapter = $nodes_chapter->item(0);

						preg_match('/(?:https:\/\/forums\.lolscans\.com\/book\/page2\.php\?([^"]+))/', $dom->saveHTML($chapter), $chapter_matches);
						if(count($chapter_matches) == 2) {
							parse_str(htmlspecialchars_decode($chapter_matches[1]), $url_args);
							$title_url = $url_args['p'];

							if(!array_key_exists($title_url, $titleDataList)) {
								$title = trim($title->textContent);
								$titleData['title'] = substr($title, 0, stripos($title, ' Chapter') ?: stripos($title, ' Ch.') ?: strpos($title, ' ch'));

								$titleData['latest_chapter'] = $url_args['c'];

								$dateString = trim($nodes_latest->item(0)->textContent);
								$titleData['last_updated'] = date("Y-m-d H:i:s", strtotime($dateString));

								$titleDataList[$title_url] = $titleData;
							}
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
