<?php declare(strict_types=1); defined('BASEPATH') OR exit('No direct script access allowed');

class EGScans extends Base_Site_Model {
	public $titleFormat   = '/^[A-Za-z0-9\-_\!,]+$/';
	public $chapterFormat = '/^Chapter_[0-9]+(?:_[E|e]xtra)?$/';

	public $customType    = 2;

	public function getFullTitleURL(string $title_url) : string {
		return "http://read.egscans.com/{$title_url}/";
	}

	public function getChapterData(string $title_url, string $chapter) : array {
		return [
			'url'    => "http://read.egscans.com/{$title_url}/{$chapter}",
			'number' => $chapter
		];
	}

	public function getTitleData(string $title_url, bool $firstGet = FALSE) : ?array {
		$titleData = [];

		$fullURL = $this->getFullTitleURL($title_url);
		$content = $this->get_content($fullURL);

		$data = $this->parseTitleDataDOM(
			$content,
			$title_url,
			"//select[@name='manga']/option[@selected='selected']",
			"//select[@name='chapter']/option[last()]",
			"//html", //FIXME: EGScans doesn't have a proper title page so we can't grab chapter time.
			"",
			"Select a manga title to get started!"
		);
		if($data) {
			$titleData['title'] = html_entity_decode($data['nodes_title']->textContent);

			$titleData['latest_chapter'] = (string) $data['nodes_chapter']->getAttribute('value');
			$titleData['last_updated'] = date("Y-m-d H:i:s", now());
		}

		return (!empty($titleData) ? $titleData : NULL);
	}

	public function doCustomUpdate() {
		$titleDataList = [];

		$updateURL = "http://feeds.feedburner.com/EasyGoingScans";
		if(($content = $this->get_content($updateURL)) && $content['status_code'] == 200) {
			$data = $content['body'];

			$dom = new DOMDocument();
			libxml_use_internal_errors(TRUE);
			$dom->loadHTML($data);
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

						preg_match('/(?:http:\/\/read\.egscans\.com\/([^"]+))/', $dom->saveHTML($chapter), $chapter_matches);
						if(count($chapter_matches) == 2) {
							$url_args = explode('/', $chapter_matches[1]);
							$title_url = $url_args[0];

							if(!array_key_exists($title_url, $titleDataList)) {
								$titleData['title'] = preg_replace('/ (v[0-9]+ )?(Ch? ?)?[0-9]+$/i', '', trim($title->textContent));

								$titleData['latest_chapter'] = preg_replace('/&amp;.*?$/', '', $url_args[1]);

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
