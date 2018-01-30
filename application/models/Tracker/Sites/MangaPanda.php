<?php declare(strict_types=1); defined('BASEPATH') OR exit('No direct script access allowed');

class MangaPanda extends Base_Site_Model {
	//NOTE: MangaPanda has manga pages under the root URL, so we need to filter out pages we know that aren't manga.
	public $titleFormat   = '/^(?!(?:latest|search|popular|random|alphabetical|privacy)$)([a-z0-9-]+)$/';
	public $chapterFormat = '/^[0-9]+$/';

	public $customType    = 2;

	public function getFullTitleURL(string $title_url) : string {
		return "http://www.mangapanda.com/{$title_url}";
	}

	public function getChapterData(string $title_url, string $chapter) : array {
		return [
			'url'    => "http://www.mangapanda.com/{$title_url}/{$chapter}/",
			'number' => 'c'.$chapter
		];
	}

	public function getTitleData(string $title_url, bool $firstGet = FALSE) : ?array {
		$titleData = [];

		$fullURL = $this->getFullTitleURL($title_url);
		$content = $this->get_content($fullURL);

		$data = $this->parseTitleDataDOM(
			$content,
			$title_url,
			"//h2[@class='aname']",
			"(//table[@id='listing']/tr)[last()]",
			"td[2]",
			"td[1]/a"
		);
		if($data) {
			$titleData['title'] = $data['nodes_title']->textContent;

			$titleData['latest_chapter'] = preg_replace('/^.*\/([0-9]+)$/', '$1', (string) $data['nodes_chapter']->getAttribute('href'));

			$titleData['last_updated'] =  date("Y-m-d H:i:s", strtotime((string) $data['nodes_latest']->nodeValue));
		}

		return (!empty($titleData) ? $titleData : NULL);
	}

	public function doCustomUpdate() {
		$titleDataList = [];

		$updateURL = "http://www.mangapanda.com/latest";
		if(($content = $this->get_content($updateURL)) && $content['status_code'] == 200) {
			$data = $content['body'];

			$data = preg_replace('/^[\s\S]+<table class="updates">/', '<table class="updates">', $data);
			$data = preg_replace('/<\/table>[\s\S]+$/', '</table>', $data);

			$dom = new DOMDocument();
			libxml_use_internal_errors(TRUE);
			$dom->loadHTML($data);
			libxml_use_internal_errors(FALSE);

			$xpath      = new DOMXPath($dom);
			$nodes_rows = $xpath->query("//table[@class='updates']/tr"); //No <tbody> for some reason?
			if($nodes_rows->length > 0) {
				foreach($nodes_rows as $row) {
					$titleData = [];

					$nodes_title   = $xpath->query("td[2]/a[1]", $row);
					$nodes_chapter = $xpath->query("td[2]/a[2]", $row);
					$nodes_latest  = $xpath->query("td[3]", $row);

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
