<?php declare(strict_types=1); defined('BASEPATH') OR exit('No direct script access allowed');

class MangaRock extends Base_Site_Model {
	public $titleFormat   = '/^[0-9]+$/';
	public $chapterFormat = '/^[0-9]+:--:(?:v[0-9]+)?c[0-9\.]+$/';

	public function getFullTitleURL(string $title_url) : string {
		return "https://mangarock.com/manga/mrs-serie-{$title_url}/";
	}

	public function getChapterData(string $title_url, string $chapter) : array {
		$chapter_parts = explode(':--:', $chapter);
		return [
			'url'    => "https://mangarock.com/manga/mrs-serie-{$title_url}/chapter/mrs-chapter-{$chapter_parts[0]}",
			'number' =>$chapter_parts[1]
		];
	}

	public function getTitleData(string $title_url, bool $firstGet = FALSE) : ?array {
		$titleData = [];

		$fullURL = "https://api.mangarockhd.com/query/web400/info?oid=mrs-serie-{$title_url}";
		$content = $this->get_content($fullURL);

		if(!is_array($content)) {
			log_message('error', "{$this->site} : {$title_url} | Failed to grab URL (See above curl error)");
		} else {
			list('headers' => $headers, 'status_code' => $status_code, 'body' => $data) = $content;

			if(!($status_code >= 200 && $status_code < 300)) {
				log_message('error', "{$this->site} : {$title_url} | Bad Status Code ({$status_code})");
			} else if(empty($data)) {
				log_message('error', "{$this->site} : {$title_url} | Data is empty? (Status code: {$status_code})");
			} else {
				$json = json_decode($content['body'], TRUE);
				if($json['data'] !== 'Unknown serie') {
					$titleData['title'] = $json['data']['name'];

					$latestChapter = end($json['data']['chapters']);

					preg_match('/^(?:Vol\.(?<volume>\S+) )?(?:Chapter (?<chapter>[^\s:]+)(?:\s?-\s?(?<extra>[0-9]+))?):?.*/', $latestChapter['name'], $text);
					$titleData['latest_chapter'] = $latestChapter['cid'].':--:'.((!empty($text['volume']) ? 'v'.$text['volume'].'/' : '') . 'c'.$text['chapter'] . (!empty($text['extra']) ? '-'.$text['extra'] : ''));;
					$titleData['last_updated'] = gmdate("Y-m-d H:i:s", $latestChapter['updatedAt']);
				} else {
					log_message('error', "{$this->site} : {$title_url} | Failure string matched");
				}
			}
		}

		return (!empty($titleData) ? $titleData : NULL);
	}
}
