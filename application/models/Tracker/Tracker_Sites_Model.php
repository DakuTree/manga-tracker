<?php declare(strict_types=1); defined('BASEPATH') OR exit('No direct script access allowed');

class Tracker_Sites_Model extends CI_Model {
	public function __construct() {
		parent::__construct();
	}

	public function __get($name) {
		//TODO: Is this a good idea? There wasn't a good consensus on if this is good practice or not..
		//      It's probably a minor speed reduction, but that isn't much of an issue.
		//      An alternate solution would simply have a function which generates a PHP file with code to load each model. Similar to: https://github.com/shish/shimmie2/blob/834bc740a4eeef751f546979e6400fd089db64f8/core/util.inc.php#L1422
		if(!class_exists($name) || !(get_parent_class($name) === 'Base_Site_Model')) {
			return get_instance()->{$name};
		} else {
			$this->loadSite($name);
			return $this->{$name};
		}
	}

	private function loadSite(string $siteName) {
		$this->{$siteName} = new $siteName();
	}
}

abstract class Base_Site_Model extends CI_Model {
	public $site          = '';
	public $titleFormat   = '';
	public $chapterFormat = '';

	public function __construct() {
		parent::__construct();

		$this->load->database();

		$this->site = get_class($this);
	}

	abstract public function getFullTitleURL(string $title_url) : string;

	abstract public function getChapterData(string $title_url, string $chapter) : array;

	//TODO: When ci-phpunit-test supports PHP Parser 3.x, add " : ?array"
	abstract public function getTitleData(string $title_url, bool $firstGet = FALSE);

	final public function isValidTitleURL(string $title_url) : bool {
		$success = (bool) preg_match($this->titleFormat, $title_url);
		if(!$success) log_message('error', "Invalid Title URL ({$this->site}): {$title_url}");
		return $success;
	}
	final public function isValidChapter(string $chapter) : bool {
		$success = (bool) preg_match($this->chapterFormat, $chapter);
		if(!$success) log_message('error', "Invalid Chapter ({$this->site}): {$chapter}");
		return $success;
	}

	final protected function get_content(string $url, string $cookie_string = "", string $cookiejar_path = "", bool $follow_redirect = FALSE, bool $isPost = FALSE, array $postFields = []) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_ENCODING , "gzip");
		//curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_HEADER, 1);

		if($follow_redirect)        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

		if(!empty($cookie_string))  curl_setopt($ch, CURLOPT_COOKIE, $cookie_string);
		if(!empty($cookiejar_path)) curl_setopt($ch, CURLOPT_COOKIEFILE, $cookiejar_path);

		//Some sites check the useragent for stuff, use a pre-defined user-agent to avoid stuff.
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2824.0 Safari/537.36');

		//TODO: Check in a while if this being enabled still causes issues
		//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); //FIXME: This isn't safe, but it allows us to grab SSL URLs

		curl_setopt($ch, CURLOPT_URL, $url);

		if($isPost) {
			curl_setopt($ch,CURLOPT_POST, count($postFields));
			curl_setopt($ch,CURLOPT_POSTFIELDS, http_build_query($postFields));
		}

		$response = curl_exec($ch);
		if($response === FALSE) {
			log_message('error', "curl failed with error: ".curl_errno($ch)." | ".curl_error($ch));
			//FIXME: We don't always account for FALSE return
			return FALSE;
		}

		$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$headers     = http_parse_headers(substr($response, 0, $header_size));
		$body        = substr($response, $header_size);
		curl_close($ch);

		return [
			'headers'     => $headers,
			'status_code' => $status_code,
			'body'        => $body
		];
	}

	/**
	 * @param array  $content
	 * @param string $title_url
	 * @param string $node_title_string
	 * @param string $node_row_string
	 * @param string $node_latest_string
	 * @param string $node_chapter_string
	 * @param string $failure_string
	 *
	 * @return DOMElement[]|false
	 */
	final protected function parseTitleDataDOM(
		$content, string $title_url,
		string $node_title_string, string $node_row_string,
		string $node_latest_string, string $node_chapter_string,
		string $failure_string = "") {

		if(!is_array($content)) {
			log_message('error', "{$this->site} : {$title_url} | Failed to grab URL (See above curl error)");
		} else {
			list('headers' => $headers, 'status_code' => $status_code, 'body' => $data) = $content;

			if(!($status_code >= 200 && $status_code < 300)) {
				log_message('error', "{$this->site} : {$title_url} | Bad Status Code ({$status_code})");
			} else if(empty($data)) {
				log_message('error', "{$this->site} : {$title_url} | Data is empty? (Status code: {$status_code})");
			} else if($failure_string !== "" && strpos($data, $failure_string) !== FALSE) {
				log_message('error', "{$this->site} : {$title_url} | Failure string matched");
			} else {
				$data = $this->cleanTitleDataDOM($data); //This allows us to clean the DOM prior to parsing. It's faster to grab the only part we need THEN parse it.

				$dom = new DOMDocument();
				libxml_use_internal_errors(TRUE);
				$dom->loadHTML('<?xml encoding="utf-8" ?>' . $data);
				libxml_use_internal_errors(FALSE);

				$xpath = new DOMXPath($dom);
				$nodes_title = $xpath->query($node_title_string);
				$nodes_row   = $xpath->query($node_row_string);
				if($nodes_title->length === 1 && $nodes_row->length === 1) {
					$firstRow      = $nodes_row->item(0);
					$nodes_latest  = $xpath->query($node_latest_string,  $firstRow);

					if($node_chapter_string !== '') {
						$nodes_chapter = $xpath->query($node_chapter_string, $firstRow);
					} else {
						$nodes_chapter = $nodes_row;
					}

					if($nodes_latest->length === 1 && $nodes_chapter->length === 1) {
						return [
							'nodes_title'   => $nodes_title->item(0),
							'nodes_latest'  => $nodes_latest->item(0),
							'nodes_chapter' => $nodes_chapter->item(0)
						];
					} else {
						log_message('error', "{$this->site} : {$title_url} | Invalid amount of nodes (LATEST: {$nodes_latest->length} | CHAPTER: {$nodes_chapter->length})");
					}
				} else {
					log_message('error', "{$this->site} : {$title_url} | Invalid amount of nodes (TITLE: {$nodes_title->length} | ROW: {$nodes_row->length})");
				}
			}
		}

		return FALSE;
	}

	public function cleanTitleDataDOM(string $data) : string {
		return $data;
	}

	//This has it's own function due to FoOlSlide being used a lot by fan translation sites, and the code being pretty much the same across all of them.
	final public function parseFoolSlide(string $fullURL, string $title_url) {
		$titleData = [];

		if($content = $this->get_content($fullURL, "", "", FALSE, TRUE, ['adult' => 'true'])) {
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
				$titleData['title'] = trim($data['nodes_title']->textContent);

				$link                        = (string) $data['nodes_chapter']->getAttribute('href');
				$titleData['latest_chapter'] = preg_replace('/.*\/read\/.*?\/(.*?)\/$/', '$1', $link);

				$titleData['last_updated'] = date("Y-m-d H:i:s", strtotime((string) str_replace('.', '', explode(',', $data['nodes_latest']->nodeValue)[1])));
			}
		}

		return (!empty($titleData) ? $titleData : NULL);
	}

	final public function doCustomFollow(string $data = "", array $extra = []) : array {
		$titleData = [];
		$this->handleCustomFollow(function($content, $id, closure $successCallback = NULL) use(&$titleData) {
			if(is_array($content)) {
				if(array_key_exists('status_code', $content)) {
					$statusCode = $content['status_code'];
					if($statusCode === 200) {
						$isCallable = is_callable($successCallback);
						if(($isCallable && $successCallback($content['body'])) || !$isCallable) {
							$titleData['followed'] = 'Y';

							log_message('info', "doCustomFollow succeeded for {$id}");
						} else {
							log_message('error', "doCustomFollow failed (Invalid response?) for {$id}");
						}
					} else {
						log_message('error', "doCustomFollow failed (Invalid status code ({$statusCode})) for {$id}");
					}
				} else {
					log_message('error', "doCustomFollow failed (Missing status code?) for {$id}");
				}
			} else {
				log_message('error', "doCustomFollow failed (Failed request) for {$id}");
			}
		}, $data, $extra);
		return $titleData;
	}
	public function handleCustomFollow(callable $callback, string $data = "", array $extra = []) {}
	public function doCustomUpdate() {}
	public function doCustomCheck(string $oldChapter, string $newChapter) {}
	final public function doCustomCheckCompare(array $oldChapterSegments, array $newChapterSegments) : bool {
		//FIXME: Make this more generic when we have more site support for it. MangaFox and Batoto have similar chapter formats.

		$status = FALSE;

		$newCount = count($newChapterSegments);
		$oldCount = count($oldChapterSegments);
		if($newCount === $oldCount) {
			//Make sure chapter format looks correct.
			//NOTE: We only need to check newCount as we know oldCount is the same count.
			if($newCount === 2) {
				//FIXME: Can we loop this?
				$oldVolume = substr(array_shift($oldChapterSegments), 1);
				$newVolume = substr(array_shift($newChapterSegments), 1);

				//Forcing volume to 999 makes sure it's always considered the latest volume.
				//This obviously can cause issues (I.E: Bato.to sometimes adds odd chapters with TBD which forces them to the top, even if they are older chapters)
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
				//This also hits cases where:
				// > The old chapter had a weird chapter format, so the volume was set as 0.
				// > The new chapter had
				$status = TRUE;
			} elseif($newChapter > $oldChapter) {
				//$newVolume isn't higher, but chapter is.
				$status = TRUE;
			}
		}

		return $status;
	}
}
