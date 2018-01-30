<?php declare(strict_types=1); defined('BASEPATH') OR exit('No direct script access allowed');

class PsychoPlay extends Base_Site_Model {
	public $titleFormat   = '/^[a-zA-Z0-9-]+$/';
	public $chapterFormat = '/^[0-9\.]+$/';

	public $customType    = 2;

	public function getFullTitleURL(string $title_url) : string {
		return "https://psychoplay.co/series/{$title_url}";
	}
	public function getChapterData(string $title_url, string $chapter) : array {
		return [
			'url'    => "https://psychoplay.co/read/{$title_url}/{$chapter}",
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
			"//div[@id='activity']/descendant::div[@class='media'][1]/descendant::div[@class='media-body']/h2/text()",
			"//ul[contains(@class, 'media-list')]/li[@class='media'][1]/a",
			"div[@class='media-body']/span[@class='text-muted']",
			""
		);
		if($data) {
			$titleData['title'] = trim(preg_replace('/ Added on .*$/','', $data['nodes_title']->textContent));
			$titleData['latest_chapter'] = preg_replace('/^.*\/([0-9\.]+)$/', '$1', (string) $data['nodes_chapter']->getAttribute('href'));

			$dateString = preg_replace('/^Added (?:on )?/', '',$data['nodes_latest']->textContent);
			$titleData['last_updated'] =  date("Y-m-d H:i:s", strtotime($dateString));
		}
		return (!empty($titleData) ? $titleData : NULL);
	}


	public function doCustomUpdate() {
		$titleDataList = [];

		$updateURL = "https://psychoplay.co/latest";
		if(($content = $this->get_content($updateURL)) && $content['status_code'] == 200) {
			$data = $content['body'];

			$dom = new DOMDocument();
			libxml_use_internal_errors(TRUE);
			$dom->loadHTML($data);
			libxml_use_internal_errors(FALSE);

			$xpath      = new DOMXPath($dom);
			$nodes_rows = $xpath->query("//div[@class='content-wrapper']/div[@class='row']/div/div");
			if($nodes_rows->length > 0) {
				foreach($nodes_rows as $row) {
					$titleData = [];

					$nodes_title   = $xpath->query("div[@class='caption']/h6/a", $row);
					$nodes_chapter = $xpath->query("div[@class='panel-footer no-padding']/a", $row);
					$nodes_latest  = $xpath->query("div[@class='caption']/text()", $row);

					if($nodes_title->length === 1 && $nodes_chapter->length === 1 && $nodes_latest->length === 1) {
						$title = $nodes_title->item(0);

						preg_match('/(?<url>[^\/]+(?=\/$|$))/', $title->getAttribute('href'), $title_url_arr);
						$title_url = $title_url_arr['url'];

						if(!array_key_exists($title_url, $titleDataList)) {
							$titleData['title'] = trim($title->textContent);

							$chapter = $nodes_chapter->item(0);
							preg_match('/(?<chapter>[^\/]+(?=\/$|$))/', $chapter->getAttribute('href'), $chapter_arr);
							$titleData['latest_chapter'] = $chapter_arr['chapter'];

							$dateString = trim(str_replace('Added ', '', $nodes_latest->item(0)->textContent));
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
