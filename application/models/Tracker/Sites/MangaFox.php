<?php declare(strict_types=1); defined('BASEPATH') OR exit('No direct script access allowed');

class MangaFox extends Base_Site_Model {
	public $titleFormat   = '/^[a-z0-9_]+$/';
	public $chapterFormat = '/^(?:v[0-9a-zA-Z]+\/)?c[0-9\.]+$/';

	public function getFullTitleURL(string $title_url) : string {
		return "http://mangafox.me/manga/{$title_url}/";
	}

	public function getChapterData(string $title_url, string $chapter) : array {
		return [
			'url'    => "http://mangafox.me/manga/{$title_url}/{$chapter}/1.html",
			'number' => $chapter
		];
	}

	public function getTitleData(string $title_url, bool $firstGet = FALSE) {
		$titleData = [];

		$fullURL = $this->getFullTitleURL($title_url);
		$content = $this->get_content($fullURL);

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

	//FIXME: This entire thing feels like an awful implementation....BUT IT WORKS FOR NOW.
	public function handleCustomFollow(callable $callback, string $data = "", array $extra = []) {
		preg_match('/var sid=(?<id>[0-9]+);/', $data, $matches);

		$formData = [
			'action' => 'add',
			'sid'    => $matches['id']
		];

		$cookies = [
			"mfvb_userid={$this->config->item('mangafox_userid')}",
			"mfvb_password={$this->config->item('mangafox_password')}",
			"bmsort=last_chapter"
		];
		$content = $this->get_content('http://mangafox.me/ajax/bookmark.php', implode("; ", $cookies), "", TRUE, TRUE, $formData);

		$callback($content, $matches['id'], function($body) {
			return $body == 'true';
		});
	}
	public function doCustomUpdate() {
		$titleDataList = [];

		$cookies = [
			"mfvb_userid={$this->config->item('mangafox_userid')}",
			"mfvb_password={$this->config->item('mangafox_password')}",
			"bmsort=last_chapter",
			"bmorder=za"
		];
		$content = $this->get_content('http://mangafox.me/bookmark/?status=currentreading&sort=last_chapter&order=za', implode("; ", $cookies), "", TRUE);

		if(!is_array($content)) {
			log_message('error', "{$this->site} /bookmark | Failed to grab URL (See above curl error)");
		} else {
			$headers     = $content['headers'];
			$status_code = $content['status_code'];
			$data        = $content['body'];

			if(!($status_code >= 200 && $status_code < 300)) {
				log_message('error', "{$this->site} /bookmark | Bad Status Code ({$status_code})");
			} else if(empty($data)) {
				log_message('error', "{$this->site} /bookmark | Data is empty? (Status code: {$status_code})");
			} else {
				$data = preg_replace('/^[\s\S]+<ul id="bmlist">/', '<ul id="bmlist">', $data);
				$data = preg_replace('/<!-- end of bookmark -->[\s\S]+$/', '<!-- end of bookmark -->', $data);

				$dom = new DOMDocument();
				libxml_use_internal_errors(TRUE);
				$dom->loadHTML($data);
				libxml_use_internal_errors(FALSE);

				$xpath      = new DOMXPath($dom);
				$nodes_rows = $xpath->query("//ul[@id='bmlist']/li/div[@class='series_grp' and h2[@class='title']/span[@class='updatedch'] and dl]");
				if($nodes_rows->length > 0) {
					foreach($nodes_rows as $row) {
						$titleData = [];

						$nodes_title   = $xpath->query("h2[@class='title']/a[contains(@class, 'title')]", $row);
						$nodes_chapter = $xpath->query("dl/dt[1]/a[@class='chapter']", $row);
						$nodes_latest  = $xpath->query("dl/dt[1]/em/span[@class='timing']", $row);

						if($nodes_title->length === 1 && $nodes_chapter->length === 1 && $nodes_latest->length === 1) {
							$title = $nodes_title->item(0);

							$titleData['title'] = trim($title->textContent);


							$link = preg_replace('/^(.*\/)(?:[0-9]+\.html)?$/', '$1', (string) $nodes_chapter->item(0)->getAttribute('href'));
							$chapterURLSegments = explode('/', $link);
							$titleData['latest_chapter'] = $chapterURLSegments[5] . (isset($chapterURLSegments[6]) && !empty($chapterURLSegments[6]) ? "/{$chapterURLSegments[6]}" : "");

							$titleData['last_updated'] =  date("Y-m-d H:i:s", strtotime((string) $nodes_latest->item(0)->nodeValue));

							$title_url = explode('/', $title->getAttribute('href'))[4];
							$titleDataList[$title_url] = $titleData;
						} else {
							log_message('error', "{$this->site}/Custom | Invalid amount of nodes (TITLE: {$nodes_title->length} | CHAPTER: {$nodes_chapter->length}) | LATEST: {$nodes_latest->length})");
						}
					}
				} else {
					log_message('error', '{$this->site} | Following list is empty?');
				}
			}
		}
		return $titleDataList;
	}
	public function doCustomCheck(string $oldChapterString, string $newChapterString) {
		$status = FALSE;

		$oldChapterSegments = explode('/', $oldChapterString);
		$newChapterSegments = explode('/', $newChapterString);

		//Although it's rare, it's possible for new chapters to have a different amount of segments to the oldChapter (or vice versa).
		//Since this can cause errors, we just throw a fail.
		$count = count($newChapterSegments);
		if($count === count($oldChapterSegments)) {
			if($count === 2) {
				//FIXME: This feels like a mess.
				$oldVolume = substr(array_shift($oldChapterSegments), 1);
				$newVolume = substr(array_shift($newChapterSegments), 1);

				if(in_array($oldVolume, ['TBD', 'TBA', 'NA', 'LMT'])) $oldVolume = 999;
				if(in_array($newVolume, ['TBD', 'TBA', 'NA', 'LMT'])) $newVolume = 999;

				$oldVolume = floatval($oldVolume);
				$newVolume = floatval($newVolume);
			} else {
				$oldVolume = 0;
				$newVolume = 0;
			}
			$oldChapter = floatval(substr(array_shift($oldChapterSegments), 1));
			$newChapter = floatval(substr(array_shift($newChapterSegments), 1));

			if($newVolume > $oldVolume) {
				//$newVolume is higher, no need to check chapter.
				$status = TRUE;
			} elseif($newChapter > $oldChapter) {
				//$newVolume isn't higher, but chapter is.
				$status = TRUE;
			}
		}

		return $status;
	}
}
