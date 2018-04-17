<?php declare(strict_types=1); defined('BASEPATH') OR exit('No direct script access allowed');

class MangaFox extends Base_Site_Model {
	public $titleFormat   = '/^[a-z0-9_]+$/';
	public $chapterFormat = '/^(?:v[0-9a-zA-Z]+\/)?c[0-9\.]+$/';
	public $customType    = 2;

	public function getFullTitleURL(string $title_url) : string {
		return "http://fanfox.net/manga/{$title_url}/";
	}

	public function getChapterData(string $title_url, string $chapter) : array {
		return [
			'url'    => "http://fanfox.net/manga/{$title_url}/{$chapter}/1.html",
			'number' => $chapter
		];
	}

	public function getTitleData(string $title_url, bool $firstGet = FALSE) : ?array {
		$titleData = [];

		$fullURL = $this->getFullTitleURL($title_url);
		$content = $this->get_content($fullURL);

		$content['body'] = preg_replace('/\/manga\/\<\!DOCTYPE html\>[\s\S]*class="downloadimage"/', "/manga/{$title_url}\" class=\"downloadimage\" ", $content['body']);
		$data = $this->parseTitleDataDOM(
			$content,
			$title_url,
			"//title",
			"//body/div[@id='page']/div[@class='left']/div[@id='chapters']/ul[1]/li[1]",
			"div/span[@class='date']",
			"div/h3/a"
		);
		if($data) {
			$titleData['title'] = html_entity_decode(explode(' Manga - Read ', $data['nodes_title']->textContent)[0]);

			$link = preg_replace('/^(.*\/)(?:[0-9]+\.html)?$/', '$1', (string) $data['nodes_chapter']->getAttribute('href'));
			$chapterURLSegments = explode('/', $link);
			$titleData['latest_chapter'] = $chapterURLSegments[5] . (isset($chapterURLSegments[6]) && !empty($chapterURLSegments[6]) ? "/{$chapterURLSegments[6]}" : "");
			$titleData['last_updated'] =  date("Y-m-d H:i:s", strtotime((string) $data['nodes_latest']->nodeValue));

			if($firstGet) {
				$titleData = array_merge($titleData, $this->doCustomFollow($content['body']));
			}
		}

		return (!empty($titleData) ? $titleData : NULL);
	}

	public function doCustomUpdate() {
		$titleDataList = [];

		$updateURL = 'http://fanfox.net/releases/';
		if(($content = $this->get_content($updateURL)) && $content['status_code'] == 200) {
			$data = $content['body'];

			$dom = new DOMDocument();
			libxml_use_internal_errors(TRUE);
			$dom->loadHTML($data);
			libxml_use_internal_errors(FALSE);

			$xpath      = new DOMXPath($dom);
			$nodes_rows = $xpath->query("//ul[@id='updates']/li/div");
			if($nodes_rows->length > 0) {
				foreach($nodes_rows as $row) {
					$titleData = [];

					$nodes_title   = $xpath->query("h3[@class='title']/a", $row);
					$nodes_chapter = $xpath->query("dl/dt[1]/span/a", $row);
					$nodes_latest  = $xpath->query("dl/dt[1]/em", $row);

					if($nodes_title->length === 1 && $nodes_chapter->length === 1 && $nodes_latest->length === 1) {
						$title   = $nodes_title->item(0);
						$chapter = $nodes_chapter->item(0);

						preg_match('/(?<url>[^\/]+(?=\/$|$))/', $title->getAttribute('href'), $title_url_arr);
						$title_url = $title_url_arr['url'];

						if(!array_key_exists($title_url, $titleDataList)) {
							$titleData['title'] = trim($title->textContent);

							$chapterURLSegments = explode('/', str_replace('/1.html', '', $chapter->getAttribute('href')));
							$titleData['latest_chapter'] = $chapterURLSegments[5] . (isset($chapterURLSegments[6]) && !empty($chapterURLSegments[6]) ? "/{$chapterURLSegments[6]}" : "");

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
	public function doCustomCheck(?string $oldChapterString, string $newChapterString) : bool {
		$oldChapterSegments = explode('/', $oldChapterString);
		$newChapterSegments = explode('/', $newChapterString);

		$status = $this->doCustomCheckCompare($oldChapterSegments, $newChapterSegments);

		return $status;
	}
}
