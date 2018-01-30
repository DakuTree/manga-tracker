<?php declare(strict_types=1); defined('BASEPATH') OR exit('No direct script access allowed');

class MangaStream extends Base_Site_Model {
	public $titleFormat   = '/^[a-z0-9_]+$/';
	public $chapterFormat = '/^(.*?)\/[0-9]+$/';

	public $customType    = 2;

	public function getFullTitleURL(string $title_url) : string {
		return "https://readms.net/manga/{$title_url}/";
	}

	public function getChapterData(string $title_url, string $chapter) : array {
		return [
			'url'    => "https://readms.net/r/{$title_url}/{$chapter}",
			'number' => 'c'.explode('/', $chapter)[0]
		];
	}

	public function getTitleData(string $title_url, bool $firstGet = FALSE) : ?array {
		$titleData = [];

		$fullURL = $this->getFullTitleURL($title_url);
		$content = $this->get_content($fullURL);

		$data = $this->parseTitleDataDOM(
			$content,
			$title_url,
			"//div[contains(@class, 'content')]/div[1]/h1",
			"//div[contains(@class, 'content')]/div[1]/table/tr[2]",
			"td[2]",
			"td[1]/a",
			"<h1>Page Not Found</h1>"
		);
		if($data) {
			$titleData['title'] = $data['nodes_title']->textContent;

			$titleData['latest_chapter'] = preg_replace('/^.*\/(.*?\/[0-9]+)\/[0-9]+$/', '$1', (string) $data['nodes_chapter']->getAttribute('href'));

			$titleData['last_updated'] =  date("Y-m-d H:i:s", strtotime((string) $data['nodes_latest']->nodeValue));
		}

		return (!empty($titleData) ? $titleData : NULL);
	}

	public function doCustomUpdate() {
		$titleDataList = [];

		$updateURL = "https://readms.net/manga";
		if(($content = $this->get_content($updateURL)) && $content['status_code'] == 200) {
			$data = $content['body'];

			$data = preg_replace('/^[\s\S]+<ul class="new-list">/', '<ul class="new-list">', $data);
			$data = preg_replace('/<\/ul>[\s\S]+$/', '</ul>', $data);

			$dom = new DOMDocument();
			libxml_use_internal_errors(TRUE);
			$dom->loadHTML($data);
			libxml_use_internal_errors(FALSE);

			$xpath      = new DOMXPath($dom);
			$nodes_rows = $xpath->query("//ul[@class='new-list']/li");
			if($nodes_rows->length > 0) {
				foreach($nodes_rows as $row) {
					$titleData = [];

					$nodes_title   = $xpath->query("a/text()", $row);
					$nodes_chapter = $xpath->query("a", $row);
					$nodes_latest  = $xpath->query("a/span[@class='pull-right']", $row);

					if($nodes_title->length === 1 && $nodes_chapter->length === 1 && $nodes_latest->length === 1) {
						$title   = $nodes_title->item(0);
						$chapter = $nodes_chapter->item(0);

						preg_match('/^\/r\/(?<url>[a-z0-9_]+)\/(?<chapter>.*?\/[0-9]+)/', $chapter->getAttribute('href'), $title_url_arr);
						$title_url = $title_url_arr['url'];

						if(!array_key_exists($title_url, $titleDataList)) {
							$titleData['title'] = trim($title->textContent);

							$titleData['latest_chapter'] = $title_url_arr['chapter'];

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
