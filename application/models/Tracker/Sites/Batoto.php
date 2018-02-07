<?php declare(strict_types=1); defined('BASEPATH') OR exit('No direct script access allowed');

class Batoto extends Base_Site_Model {
	//Batoto is a bit tricky to track. Unlike MangaFox and MangaHere, it doesn't store anything in the title_url, which means we have to get the data via other methods.
	//One problem we have though, is the tracker must support multiple sites, so this means we need to do some weird things to track Batoto.
	//title_url is stored like: "ID:--:LANGUAGE"
	//chapter_urls are stored like "CHAPTER_ID:--:CHAPTER_NUMBER"

	public $titleFormat   = '/^[0-9]+:--:(?:English|Spanish|French|German|Portuguese|Turkish|Indonesian|Greek|Filipino|Italian|Polish|Thai|Malay|Hungarian|Romanian|Arabic|Hebrew|Russian|Vietnamese|Dutch)$/';
	//FIXME: We're not validating the chapter name since we don't know what all the possible valid characters can be
	//       Preferably we'd just use /^[0-9a-z]+:--:(v[0-9]+\/)?c[0-9]+(\.[0-9]+)?$/
	public $chapterFormat = '/^[0-9a-z]+:--:.+$/';
	public $customType    = 1;
	public $hasCloudFlare = TRUE;

	public function getFullTitleURL(string $title_url) : string {
		//FIXME: This does not point to the language specific title page. Should ask if it is possible to set LANG as arg?
		//NOTE: This points to a generic URL which will redirect according to the ID.
		//      It's possible the title of a series can change, essentially making it possible for us to have multiple versions of the same title. This stops that.
		$title_parts = explode(':--:', $title_url);
		return "https://vatoto.com/comic/_/comics/-r{$title_parts[0]}";
	}

	public function getChapterData(string $title_url, string $chapter) : array {
		//$title_url isn't used here.

		$chapter_parts = explode(':--:', $chapter);
		return [
			'url'    => "https://vatoto.com/reader#" . $chapter_parts[0],
			'number' => $chapter_parts[1]
		];
	}

	public function getTitleData(string $title_url, bool $firstGet = FALSE) : ?array {
		$titleData = [];

		$title_parts = explode(':--:', $title_url);
		$fullURL     = $this->getFullTitleURL($title_url);
		$lang        = $title_parts[1]; //TODO: Validate title_lang from array?


		//Bato.to is annoying and locks stuff behind auth. See: https://github.com/DakuTree/manga-tracker/issues/14#issuecomment-233830855
		$cookies = [
			"lang_option={$lang}",
			"member_id={$this->config->item('batoto_cookie_member_id')}",
			"pass_hash={$this->config->item('batoto_cookie_pass_hash')}"
		];
		$content = $this->get_content($fullURL, implode("; ", $cookies), "", TRUE);

		$data = $this->parseTitleDataDOM(
			$content,
			$title_url,
			"//h1[@class='ipsType_pagetitle']",
			"//table[contains(@class, 'chapters_list')]/tbody/tr[2]",
			"td[last()]",
			"td/a[contains(@href,'reader#')]",
			">Register now<"
		);
		if($data) {
			$titleData['title'] = html_entity_decode(trim($data['nodes_title']->textContent));

			preg_match('/^(?:Vol\.(?<volume>\S+) )?(?:Ch.(?<chapter>[^\s:]+)(?:\s?-\s?(?<extra>[0-9]+))?):?.*/', trim($data['nodes_chapter']->nodeValue), $text);
			$chapter_url = $data['nodes_chapter']->getAttribute('href');
			$titleData['latest_chapter'] = substr($chapter_url, strpos($chapter_url, "reader#") + 7) . ':--:' . ((!empty($text['volume']) ? 'v'.$text['volume'].'/' : '') . 'c'.$text['chapter'] . (!empty($text['extra']) ? '-'.$text['extra'] : ''));

			$dateString = $data['nodes_latest']->nodeValue;
			if($dateString == 'An hour ago') {
				$dateString = '1 hour ago';
			}
			$titleData['last_updated'] = date("Y-m-d H:i:s", strtotime(preg_replace('/ (-|\[A\]).*$/', '', $dateString)));

			if($firstGet && $lang == 'English') {
				//FIXME: English is forced due for now. See #78.
				$titleData = array_merge($titleData, $this->doCustomFollow($content['body'], ['id' => $title_parts[0], 'lang' => $lang]));
			}
		}

		return (!empty($titleData) ? $titleData : NULL);
	}

	public function cleanTitleDataDOM(string $data) : string {
		$data = preg_replace('/^[\s\S]+<!-- ::: CONTENT ::: -->/', '<!-- ::: CONTENT ::: -->', $data);
		$data = preg_replace('/<!-- end mainContent -->[\s\S]+$/', '<!-- end mainContent -->', $data);
		$data = preg_replace('/<div id=\'commentsStart\' class=\'ipsBox\'>[\s\S]+$/', '</div></div><!-- end mainContent -->', $data);

		return $data;
	}

	//FIXME: This entire thing feels like an awful implementation....BUT IT WORKS FOR NOW.
	public function handleCustomFollow(callable $callback, string $data = "", array $extra = []) {
		preg_match('/ipb\.vars\[\'secure_hash\'\]\s+=\s+\'(?<secure_hash>[0-9a-z]+)\';[\s\S]+ipb\.vars\[\'session_id\'\]\s+=\s+\'(?<session_id>[0-9a-z]+)\';/', $data, $text);

		$params = [
			's'          => $text['session_id'],
			'app'        => 'core',
			'module'     => 'ajax',
			'section'    => 'like',
			'do'         => 'save',
			'secure_key' => $text['secure_hash'],
			'f_app'      => 'ccs',
			'f_area'     => 'ccs_custom_database_3_records',
			'f_relid'    => $extra['id']
		];
		$formData = [
			'like_notify' => '0',
			'like_freq'   => 'immediate',
			'like_anon'   => '0'
		];

		$cookies = [
			"lang_option={$extra['lang']}",
			"member_id={$this->config->item('batoto_cookie_member_id')}",
			"pass_hash={$this->config->item('batoto_cookie_pass_hash')}"
		];
		$content = $this->get_content('https://vatoto.com/forums/index.php?'.http_build_query($params), implode("; ", $cookies), "", TRUE, TRUE, $formData);

		$callback($content, $extra['id'], function($body) {
			return strpos($body, '>Unfollow<') !== FALSE;
		});
	}
	public function doCustomUpdate() {
		$titleDataList = [];

		$cookies = [
			"lang_option=English", //FIXME: English is forced due for now. See #78.
			"member_id={$this->config->item('batoto_cookie_member_id')}",
			"pass_hash={$this->config->item('batoto_cookie_pass_hash')}"
		];
		$content = $this->get_content("https://vatoto.com/myfollows", implode("; ", $cookies), "", TRUE);
		if(!is_array($content)) {
			log_message('error', "{$this->site} /myfollows | Failed to grab URL (See above curl error)");
		} else {
			$headers     = $content['headers'];
			$status_code = $content['status_code'];
			$data        = $content['body'];

			if(!($status_code >= 200 && $status_code < 300)) {
				log_message('error', "{$this->site} /myfollows | Bad Status Code ({$status_code})");
			} else if(empty($data)) {
				log_message('error', "{$this->site} /myfollows | Data is empty? (Status code: {$status_code})");
			} else {
				$data = preg_replace('/^[\s\S]+<!-- ::: CONTENT ::: -->/', '<!-- ::: CONTENT ::: -->', $data);
				$data = preg_replace('/<!-- end mainContent -->[\s\S]+$/', '<!-- end mainContent -->', $data);

				$dom = new DOMDocument();
				libxml_use_internal_errors(TRUE);
				$dom->loadHTML($data);
				libxml_use_internal_errors(FALSE);

				$xpath      = new DOMXPath($dom);
				$nodes_rows = $xpath->query("//table[contains(@class, 'chapters_list')]/tbody/tr[position()>1]");
				if($nodes_rows->length > 0) {
					foreach($nodes_rows as $row) {
						$titleData = [];

						$nodes_title   = $xpath->query("td[2]/a[1]", $row);
						$nodes_chapter = $xpath->query("td[2]/a[2]", $row);
						$nodes_lang    = $xpath->query("td[3]/div", $row);
						$nodes_latest  = $xpath->query("td[5]", $row);

						if($nodes_lang->length === 1 && $nodes_lang->item(0)->getAttribute('title') == 'English') {
							if($nodes_title->length === 1 && $nodes_chapter->length === 1 && $nodes_latest->length === 1) {
								$title = $nodes_title->item(0);

								preg_match('/(?<id>[0-9]+)$/', $title->getAttribute('href'), $title_url_arr);
								$title_url = "{$title_url_arr['id']}:--:English"; //FIXME: English is currently forced, see #78

								if(!array_key_exists($title_url, $titleDataList)) {
									$titleData['title'] = trim($title->textContent);

									$chapter = $nodes_chapter->item(0);
									preg_match('/^(?:Vol\.(?<volume>\S+) )?(?:Ch.(?<chapter>[^\s:]+)(?:\s?-\s?(?<extra>[0-9]+))?):?.*/', trim($chapter->nodeValue), $text);
									$chapter_url = $chapter->getAttribute('href');
									$titleData['latest_chapter'] = substr($chapter_url, strpos($chapter_url, "reader#") + 7) . ':--:' . ((!empty($text['volume']) ? 'v'.$text['volume'].'/' : '') . 'c'.$text['chapter'] . (!empty($text['extra']) ? '-'.$text['extra'] : ''));

									$dateString = $nodes_latest->item(0)->nodeValue;
									if($dateString == 'An hour ago') {
										$dateString = '1 hour ago';
									}
									$titleData['last_updated'] = date("Y-m-d H:i:s", strtotime(preg_replace('/ (-|\[A\]).*$/', '', $dateString)));


									$titleDataList[$title_url] = $titleData;
								}
							} else {
								log_message('error', "{$this->site}/Custom | Invalid amount of nodes (TITLE: {$nodes_title->length} | CHAPTER: {$nodes_chapter->length}) | LATEST: {$nodes_latest->length})");
							}
						}
					}
				} else {
					log_message('error', "{$this->site} | Following list is empty?");
				}
			}
		}
		return $titleDataList;
	}
	public function doCustomCheck(?string $oldChapterString, string $newChapterString) : bool {
		$oldChapterSegments = explode('/', $this->getChapterData('', $oldChapterString)['number']);
		$newChapterSegments = explode('/', $this->getChapterData('', $newChapterString)['number']);

		$status = $this->doCustomCheckCompare($oldChapterSegments, $newChapterSegments);

		return $status;
	}
}
