<?php declare(strict_types=1); defined('BASEPATH') OR exit('No direct script access allowed');

class JaiminisBox extends Base_FoolSlide_Site_Model {
	public $baseURL = 'https://jaiminisbox.com/reader';

	public $customType    = 2;

	//NOTE: Jaimini's Box appears to have disabled API support for some reason. Fallback to using the old method.
	public function getTitleData(string $title_url, bool $firstGet = FALSE) : ?array {
		$fullURL = $this->getFullTitleURL($title_url);
		$titleData = [];
		if($content = $this->get_content($fullURL, '', '', FALSE, TRUE, ['adult' => 'true'])) {
			$content['body'] = preg_replace('/^[\S\s]*(<article[\S\s]*)<\/article>[\S\s]*$/', '$1', $content['body']);
			$data = $this->parseTitleDataDOM(
				$content,
				$title_url,
				"//div[@class='large comic']/h1[@class='title']",
				"(//div[@class='list']/div[@class='group']/div[@class='title' and text() = 'Chapters']/following-sibling::div[@class='element'][1] | //div[@class='list']/div[@class='element'][1] | //div[@class='list']/div[@class='group'][1]/div[@class='element'][1])[1]",
				"div[@class='meta_r']",
				"div[@class='title']/a"
			);
			if($data) {
				$titleData['title']          = trim($data['nodes_title']->textContent);
				$link                        = (string) $data['nodes_chapter']->getAttribute('href');
				$titleData['latest_chapter'] = preg_replace('/.*\/read\/.*?\/(.*?)\/$/', '$1', $link);
				$titleData['last_updated']   = date('Y-m-d H:i:s', strtotime((string) str_replace('.', '', explode(',', $data['nodes_latest']->nodeValue)[1])));
			}
		}
		return (!empty($titleData) ? $titleData : NULL);
	}

	public function doCustomUpdate() {
		$titleDataList = [];

		$updateURL = 'https://jaiminisbox.com/reader/';
		if(($content = $this->get_content($updateURL)) && $content['status_code'] === 200) {
			$data = $content['body'];

			$dom = new DOMDocument();
			libxml_use_internal_errors(TRUE);
			$dom->loadHTML($data);
			libxml_use_internal_errors(FALSE);

			$xpath      = new DOMXPath($dom);
			$nodes_rows = $xpath->query("//div[@class='group']");
			if($nodes_rows->length > 0) {
				foreach($nodes_rows as $row) {
					$titleData = [];

					$nodes_title   = $xpath->query("div[@class='title']/a", $row);
					$nodes_chapter = $xpath->query("div[@class='element'][1]/div[@class='title']/a", $row);
					$nodes_latest  = $xpath->query("div[@class='element'][1]/div[@class='meta_r']/text()[last()]", $row);

					if($nodes_title->length === 1 && $nodes_chapter->length === 1 && $nodes_latest->length === 1) {
						$title   = $nodes_title->item(0);
						$chapter = $nodes_chapter->item(0);

						preg_match('/(?<url>[^\/]+(?=\/$|$))/', $title->getAttribute('href'), $title_url_arr);
						$title_url = $title_url_arr['url'];

						if(!array_key_exists($title_url, $titleDataList)) {
							$titleData['title'] = trim($title->getAttribute('title'));

							$chapter_parts = explode('/', $chapter->getAttribute('href'));
							$titleData['latest_chapter'] = trim(implode('/', array_slice($chapter_parts, 6)), '/');

							$dateString = trim(str_replace(',', '', str_replace('.', '-', $nodes_latest->item(0)->textContent)));
							switch($dateString) {
								case 'Today':
									$dateString = date('Y-m-d', now());
									break;

								case 'Yesterday':
									$dateString = date('Y-m-d', strtotime('-1 days'));
									break;

								default:
									//Do nothing
									break;
							}
							$titleData['last_updated'] = date('Y-m-d H:i:s', strtotime($dateString));

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
