<?php declare(strict_types=1); defined('BASEPATH') OR exit('No direct script access allowed');

class WebToons extends Base_Site_Model {
	/* Webtoons.com has a very weird and pointless URL format.
	   TITLE URL:   /#LANG#/#GENRE#/#TITLE#/list?title_no=#TITLEID#
	   RSS URL:     /#LANG#/#GENRE#/#TITLE#/rss?title_no=#TITLEID#
	   CHAPTER URL: /#LANG#/#GENRE#/#TITLE#/#CHAPTER#/viewer?title_no=#TITLEID#&episode_no=#CHAPTERID#

	   For both the title and chapter URLs, only the TITLEID and CHAPTERID are needed. Everything else can be anything at all (Well, alphanumeric at least).
	   The RSS URL however, requires everything to be exactly correct. I have no idea why this is, but it does mean we need to store all that info too.
	   We <could> not use the RSS url, and just parse via the title url, but rss is much better in the long run as it shouldn't change much.

	   FORMATS:
	   TITLE_URL: ID:--:LANG:--:TITLE:--:GENRE
	   CHAPTER:   ID:--:CHAPTER_N
	*/
	//private $validLang = ['en', 'zh-hant', 'zh-hans', 'th', 'id'];

	public $titleFormat   = '/^[0-9]+:--:(?:en|zh-hant|zh-hans|th|id):--:[a-z0-9-]+:--:(?:drama|fantasy|comedy|action|slice-of-life|romance|superhero|thriller|sports|sci-fi|sf|challenge)$/';
	public $chapterFormat = '/^[0-9]+:--:.*$/';

	public function getFullTitleURL(string $title_url) : string {
		$title_parts = explode(':--:', $title_url);
		return "http://www.webtoons.com/{$title_parts[1]}/{$title_parts[3]}/{$title_parts[2]}/list?title_no={$title_parts[0]}";
	}

	public function getChapterData(string $title_url, string $chapter) : array {
		$title_parts   = explode(':--:', $title_url);
		$chapter_parts = explode(':--:', $chapter);

		return [
			'url'    => "http://www.webtoons.com/{$title_parts[1]}/{$title_parts[3]}/{$title_parts[2]}/{$chapter_parts[1]}/viewer?title_no={$title_parts[0]}&episode_no={$chapter_parts[0]}",
			'number' => $chapter_parts[1] //TODO: Possibly replace certain formats in here? Since webtoons doesn't have a standard chapter format
		];
	}

	public function getTitleData(string $title_url, bool $firstGet = FALSE) : ?array {
		$titleData = [];

		//FIXME: We don't use parseTitleDOM here due to using rss. Should probably have an alternate method for XML parsing.

		//NOTE: getTitleData uses a different FullTitleURL due to it grabbing the rss ver. instead.
		$title_parts = explode(':--:', $title_url);
		$fullURL = "http://www.webtoons.com/{$title_parts[1]}/{$title_parts[3]}/{$title_parts[2]}/rss?title_no={$title_parts[0]}";

		$content = $this->get_content($fullURL);
		$data = $content['body'];
		if($data !== 'Can\'t find the manga series.') { //FIXME: We should check for he proper error here.
			$xml = simplexml_load_string($data);
			if($xml) {
				if(isset($xml->{'channel'}->item[0])) {
					$titleData['title'] = trim((string) $xml->{'channel'}->title);

					$chapterURLSegments = explode('/', ((string) $xml->{'channel'}->item[0]->link));
					$titleData['latest_chapter'] = preg_replace('/^.*?([0-9]+)$/', '$1', $chapterURLSegments[7]) . ':--:' . $chapterURLSegments[6];
					$titleData['last_updated'] =  date("Y-m-d H:i:s", strtotime((string) $xml->{'channel'}->item[0]->pubDate));

					if($firstGet) {
						$titleData = array_merge($titleData, $this->doCustomFollow($content['body'], ['id' => $title_parts[0]]));
					}
				}
			} else {
				log_message('error', "URL isn't valid XML/RSS? (WebToons): {$title_url}");
			}
		} else {
			log_message('error', "Series missing? (WebToons): {$title_url}");
			return NULL;
		}

		return (!empty($titleData) ? $titleData : NULL);
	}

	public function handleCustomFollow(callable $callback, string $data = "", array $extra = []) {
		$formData = [
			'titleNo'       => $extra['id'],
			'currentStatus' => 'false',
			'promotionName' => ''
		];

		$cookies = [
			"NEO_SES={$this->config->item('webtoons_cookie')}"
		];
		$content = $this->get_content('http://www.webtoons.com/setFavorite?'.http_build_query($formData), implode("; ", $cookies), "", TRUE);

		$callback($content, $extra['id'], function($body) {
			return strpos($body, '"favorite":true') !== FALSE;
		});
	}
	public function doCustomUpdate() {
		/*$titleDataList = [];

		$cookies = [
			"NEO_SES={$this->config->item('webtoons_cookie')}"
		];
		$content = $this->get_content('http://www.webtoons.com/favorite', implode("; ", $cookies), "", TRUE);

		if(!is_array($content)) {
			log_message('error', "{$this->site} /favorite | Failed to grab URL (See above curl error)");
		} else {
			$headers     = $content['headers'];
			$status_code = $content['status_code'];
			$data        = $content['body'];

			if(!($status_code >= 200 && $status_code < 300)) {
				log_message('error', "{$this->site} /favorite | Bad Status Code ({$status_code})");
			} else if(empty($data)) {
				log_message('error', "{$this->site} /favorite | Data is empty? (Status code: {$status_code})");
			} else {
				$data = preg_replace('/^[\s\S]+<\!-- container -->/', '<!-- container -->', $data);
				$data = preg_replace('/<\!-- \/\/container -->[\s\S]+$/', '<!-- //container -->', $data);

				$dom = new DOMDocument();
				libxml_use_internal_errors(TRUE);
				$dom->loadHTML($data);
				libxml_use_internal_errors(FALSE);

				$xpath      = new DOMXPath($dom);
				$nodes_rows = $xpath->query("//ul[@id='_webtoonList']/li/a");
				if($nodes_rows->length > 0) {
					foreach($nodes_rows as $row) {
						$titleData = [];

						$nodes_title   = $xpath->query("span[@class='update']", $row);
						$nodes_chapter = $xpath->query("dl/dt[1]/a[@class='chapter']", $row);
						$nodes_latest  = $xpath->query("span[@class='update']", $row);

						print $nodes_latest->length;
						if($nodes_title->length === 1 && $nodes_chapter->length === 1 && $nodes_latest->length === 1) {
					//		$title = $nodes_title->item(0);
					//
					//		$titleData['title'] = trim($title->textContent);
					//
					//
					//		$link = preg_replace('/^(.*\/)(?:[0-9]+\.html)?$/', '$1', (string) $nodes_chapter->item(0)->getAttribute('href'));
					//		$chapterURLSegments = explode('/', $link);
					//		$titleData['latest_chapter'] = $chapterURLSegments[5] . (isset($chapterURLSegments[6]) && !empty($chapterURLSegments[6]) ? "/{$chapterURLSegments[6]}" : "");
					//
					//		$titleData['last_updated'] =  date("Y-m-d H:i:s", strtotime((string) $nodes_latest->item(0)->nodeValue));
					//
					//		$title_url = explode('/', $title->getAttribute('href'))[4];
					//		$titleDataList[$title_url] = $titleData;
						} else {
					//		log_message('error', "{$this->site}/Custom | Invalid amount of nodes (TITLE: {$nodes_title->length} | CHAPTER: {$nodes_chapter->length}) | LATEST: {$nodes_latest->length})");
						}
					}
				} else {
					log_message('error', "{$this->site} | Following list is empty?");
				}
			}
		}
		return $titleDataList;*/
	}
	public function doCustomCheck(string $oldChapterString, string $newChapterString) {}
}
