<?php declare(strict_types=1); defined('BASEPATH') OR exit('No direct script access allowed');

abstract class Site_Model extends CI_Model {
	public $site          = '';
	public $titleFormat   = '';
	public $chapterFormat = '';

	public function __construct() {
		parent::__construct();

		$this->load->database();
	}

	abstract public function getFullTitleURL(string $title_url) : string;

	abstract public function getChapterData(string $title_url, string $chapter) : array;

	//TODO: When ci-phpunit-test supports PHP Parser 3.x, add " : ?array"
	abstract public function getTitleData(string $title_url);

	public function isValidTitleURL(string $title_url) : bool {
		$success = (bool) preg_match($this->titleFormat, $title_url);
		if(!$success) log_message('error', "Invalid Title URL ({$this->site}): {$title_url}");
		return $success;
	}
	public function isValidChapter(string $chapter) : bool {
		$success = (bool) preg_match($this->chapterFormat, $chapter);
		if(!$success) log_message('error', "Invalid Chapter ({$this->site}): {$chapter}");
		return $success;
	}

	final protected function get_content(string $url, string $cookie_string = "", string $cookiejar_path = "", bool $follow_redirect = FALSE) {
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
		$response = curl_exec($ch);
		if($response === FALSE) {
			log_message('error', "curl failed with error: ".curl_errno($ch)." | ".curl_error($ch));
			//FIXME: We don't always account for FALSE return
			return FALSE;
		}

		$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$header      = http_parse_headers(substr($response, 0, $header_size));
		$body        = substr($response, $header_size);
		curl_close($ch);

		return [
			'headers'     => $header,
			'status_code' => $status_code,
			'body'        => $body
		];
	}

	/**
	 * @param array  $content
	 * @param string $site
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
		$content, string $site, string $title_url,
		string $node_title_string, string $node_row_string,
		string $node_latest_string, string $node_chapter_string,
		string $failure_string = "") {
		//list('headers' => $headers, 'status_code' => $status_code, 'body' => $data) = $content; //TODO: PHP 7.1
		$headers     = $content['headers'];
		$status_code = $content['status_code'];
		$data        = $content['body'];

		if(!is_array($content)) {
			log_message('error', "{$site} : {$title_url} | Failed to grab URL (See above curl error)");
		} else if(!($status_code >= 200 && $status_code < 300)) {
			log_message('error', "{$site} : {$title_url} | Bad Status Code ({$status_code})");
		} else if(empty($data)) {
			log_message('error', "{$site} : {$title_url} | Data is empty? (Status code: {$status_code})");
		} else if($failure_string !== "" && strpos($data, $failure_string) !== FALSE) {
			log_message('error', "{$site} : {$title_url} | Failure string matched");
		} else {
			$data = $this->cleanTitleDataDOM($data); //This allows us to clean the DOM prior to parsing. It's faster to grab the only part we need THEN parse it.

			$dom = new DOMDocument();
			libxml_use_internal_errors(TRUE);
			$dom->loadHTML($data);
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
					log_message('error', "{$site} : {$title_url} | Invalid amount of nodes (LATEST: {$nodes_latest->length} | CHAPTER: {$nodes_chapter->length})");
				}
			} else {
				log_message('error', "{$site} : {$title_url} | Invalid amount of nodes (TITLE: {$nodes_title->length} | ROW: {$nodes_row->length})");
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

		$content         = $this->get_content($fullURL);
		$content['body'] = preg_replace('/^[\S\s]*(<article[\S\s]*)<\/article>[\S\s]*$/', '$1', $content['body']);

		$data = $this->parseTitleDataDOM(
			$content,
			$this->site,
			$title_url,
			"//div[@class='large comic']/h1[@class='title']",
			"(//div[@class='list']/div[@class='group']/div[@class='title' and text() = 'Chapters']/following-sibling::div[@class='element'][1] | //div[@class='list']/div[@class='element'][1] | //div[@class='list']/div[@class='group'][1]/div[@class='element'][1])[1]",
			"div[@class='meta_r']",
			"div[@class='title']/a"
		);
		if($data) {
			$titleData['title'] = trim($data['nodes_title']->textContent);

			$link = (string) $data['nodes_chapter']->getAttribute('href');
			$titleData['latest_chapter'] = preg_replace('/.*\/read\/.*?\/(.*?)\/$/', '$1', $link);

			$titleData['last_updated']   = date("Y-m-d H:i:s", strtotime((string) str_replace('.', '', explode(',', $data['nodes_latest']->nodeValue)[1])));
		}

		return (!empty($titleData) ? $titleData : NULL);
	}
}
class Sites_Model extends CI_Model {
	public $MangaFox;
	public $MangaHere;
	public $Batoto;
	public $DynastyScans;
	public $MangaPanda;
	public $MangaStream;
	public $WebToons;
	public $KissManga;
	public $KireiCake;
	public $GameOfScanlation;
	public $MangaCow;
	public $SeaOtterScans;
	public $HelveticaScans;
	public $SenseScans;

	public function __construct() {
		parent::__construct();

		$this->MangaFox         = new MangaFox();
		$this->MangaHere        = new MangaHere();
		$this->Batoto           = new Batoto();
		$this->DynastyScans     = new DynastyScans();
		$this->MangaPanda       = new MangaPanda();
		$this->MangaStream      = new MangaStream();
		$this->WebToons         = new WebToons();
		$this->KissManga        = new KissManga();
		$this->KireiCake        = new KireiCake();
		$this->GameOfScanlation = new GameOfScanlation();
		$this->MangaCow         = new MangaCow();
		$this->SeaOtterScans    = new SeaOtterScans();
		$this->HelveticaScans   = new HelveticaScans();
		$this->SenseScans       = new SenseScans();
	}
}

class MangaFox extends Site_Model {
	public $site          = 'MangaFox';
	public $titleFormat   = '/^[a-z0-9_]+$/';
	public $chapterFormat = '/^(?:v[0-9a-zA-Z]+\/)?c[0-9\.]+$/';

	public function getFullTitleURL(string $title_url) : string {
		return "http://mangafox.me/manga/{$title_url}/";
	}

	public function getChapterData(string $title_url, string $chapter) : array {
		return [
			'url'    => "http://mangafox.me/manga/{$title_url}/{$chapter}/",
			'number' => $chapter
		];
	}

	public function getTitleData(string $title_url) {
		$titleData = [];

		$fullURL = $this->getFullTitleURL($title_url);
		$content = $this->get_content($fullURL);

		$data = $this->parseTitleDataDOM(
			$content,
			'MangaFox',
			$title_url,
			"//meta[@property='og:title']/@content",
			"//body/div[@id='page']/div[@class='left']/div[@id='chapters']/ul[1]/li[1]",
			"div/span[@class='date']",
			"div/h3/a"
		);
		if($data) {
			$titleData['title'] = html_entity_decode(substr($data['nodes_title']->textContent, 0, -6));

			$link = preg_replace('/^(.*\/)(?:[0-9]+\.html)?$/', '$1', (string) $data['nodes_chapter']->getAttribute('href'));
			$chapterURLSegments = explode('/', $link);
			$titleData['latest_chapter'] = $chapterURLSegments[5] . (isset($chapterURLSegments[6]) && !empty($chapterURLSegments[6]) ? "/{$chapterURLSegments[6]}" : "");
			$titleData['last_updated'] =  date("Y-m-d H:i:s", strtotime((string) $data['nodes_latest']->nodeValue));
		}

		return (!empty($titleData) ? $titleData : NULL);
	}
}

class MangaHere extends Site_Model {
	public $site          = 'MangaHere';
	public $titleFormat   = '/^[a-z0-9_]+$/';
	public $chapterFormat = '/^(?:v[0-9]+\/)?c[0-9]+(?:\.[0-9]+)?$/';

	public function getFullTitleURL(string $title_url) : string {
		return "http://www.mangahere.co/manga/{$title_url}/";
	}

	public function getChapterData(string $title, string $chapter) : array {
		return [
			'url'    => "http://www.mangahere.co/manga/{$title}/{$chapter}/",
			'number' => $chapter
		];
	}

	public function getTitleData(string $title_url) {
		$titleData = [];

		$fullURL = $this->getFullTitleURL($title_url);
		$content = $this->get_content($fullURL);

		$data = $this->parseTitleDataDOM(
			$content,
			'MangaHere',
			$title_url,
			"//meta[@property='og:title']/@content",
			"//body/section/article/div/div[@class='manga_detail']/div[@class='detail_list']/ul[1]/li[1]",
			"span[@class='right']",
			"span[@class='left']/a",
			"<div class=\"error_text\">Sorry, the page you have requested can’t be found."
		);
		if($data) {
			$titleData['title'] = $data['nodes_title']->textContent;

			$link = preg_replace('/^(.*\/)(?:[0-9]+\.html)?$/', '$1', (string) $data['nodes_chapter']->getAttribute('href'));
			$chapterURLSegments = explode('/', $link);
			$titleData['latest_chapter'] = $chapterURLSegments[5] . (isset($chapterURLSegments[6]) && !empty($chapterURLSegments[6]) ? "/{$chapterURLSegments[6]}" : "");
			$titleData['last_updated'] =  date("Y-m-d H:i:s", strtotime((string) $data['nodes_latest']->nodeValue));
		}

		return (!empty($titleData) ? $titleData : NULL);
	}
}

class Batoto extends Site_Model {
	//Batoto is a bit tricky to track. Unlike MangaFox and MangaHere, it doesn't store anything in the title_url, which means we have to get the data via other methods.
	//One problem we have though, is the tracker must support multiple sites, so this means we need to do some weird things to track Batoto.
	//title_url is stored like: "TITLE_URL:--:LANGUAGE"
	//chapter_urls are stored like "CHAPTER_ID:--:CHAPTER_NUMBER"

	public $site          = 'Batoto';
	public $titleFormat   = '/^[0-9]+:--:(?:English|Spanish|French|German|Portuguese|Turkish|Indonesian|Greek|Filipino|Italian|Polish|Thai|Malay|Hungarian|Romanian|Arabic|Hebrew|Russian|Vietnamese|Dutch)$/';
	//FIXME: We're not validating the chapter name since we don't know what all the possible valid characters can be
	//       Preferably we'd just use /^[0-9a-z]+:--:(v[0-9]+\/)?c[0-9]+(\.[0-9]+)?$/
	public $chapterFormat = '/^[0-9a-z]+:--:.+$/';

	public function getFullTitleURL(string $title_string) : string {
		//FIXME: This does not point to the language specific title page. Should ask if it is possible to set LANG as arg?
		//FIXME: This points to a generic URL which will redirect according to the ID. Preferably we'd try and get the exact URL from the title, but we can't pass it here.
		$title_parts = explode(':--:', $title_string);
		return "http://bato.to/comic/_/comics/-r".$title_parts[0];
	}

	public function getChapterData(string $title_string, string $chapter) : array {
		//$title_string isn't used here.

		$chapter_parts = explode(':--:', $chapter);
		return [
			'url'    => "http://bato.to/reader#" . $chapter_parts[0],
			'number' => $chapter_parts[1]
		];
	}

	public function getTitleData(string $title_url) {
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
			'Batoto',
			$title_url,
			"//h1[@class='ipsType_pagetitle']",
			"//table[contains(@class, 'chapters_list')]/tbody/tr[2]",
			"td[last()]",
			"td/a[contains(@href,'reader')]",
			">Register now<"
		);
		if($data) {
			$titleData['title'] = html_entity_decode(trim($data['nodes_title']->textContent));

			///^(?:Vol\.(?<volume>\S+) )?(?:Ch.(?<chapter>[^\s:]+)(?:\s?-\s?(?<extra>[0-9]+))?):?.*/
			preg_match('/^(?:Vol\.(?<volume>\S+) )?(?:Ch.(?<chapter>[^\s:]+)(?:\s?-\s?(?<extra>[0-9]+))?):?.*/', trim($data['nodes_chapter']->nodeValue), $text);
			$titleData['latest_chapter'] = substr($data['nodes_chapter']->getAttribute('href'), 22) . ':--:' . ((!empty($text['volume']) ? 'v'.$text['volume'].'/' : '') . 'c'.$text['chapter'] . (!empty($text['extra']) ? '-'.$text['extra'] : ''));

			$dateString = $data['nodes_latest']->nodeValue;
			if($dateString == 'An hour ago') {
				$dateString = '1 hour ago';
			}
			$titleData['last_updated']   = date("Y-m-d H:i:s", strtotime(preg_replace('/ (-|\[A\]).*$/', '', $dateString)));
		}

		return (!empty($titleData) ? $titleData : NULL);
	}

	public function cleanTitleDataDOM(string $data) : string {
		$data = preg_replace('/^[\s\S]+<!-- ::: CONTENT ::: -->/', '<!-- ::: CONTENT ::: -->', $data);
		$data = preg_replace('/<!-- end mainContent -->[\s\S]+$/', '<!-- end mainContent -->', $data);
		$data = preg_replace('/<div id=\'commentsStart\' class=\'ipsBox\'>[\s\S]+$/', '</div></div><!-- end mainContent -->', $data);

		return $data;
	}
}

class DynastyScans extends Site_Model {
	//FIXME: This has some major issues. SEE: https://github.com/DakuTree/manga-tracker/issues/58

	public $site          = 'DynastyScans';
	public $titleFormat   = '/^[a-z0-9_]+:--:(?:0|1)$/';
	public $chapterFormat = '/^[0-9a-z_]+$/';

	public function getFullTitleURL(string $title_string) : string {
		$title_parts = explode(':--:', $title_string);
		$url_type = ($title_parts[1] == '0' ? 'series' : 'chapters');

		return 'http://dynasty-scans.com/'.$url_type.'/'.$title_parts[0];
	}

	public function getChapterData(string $title_string, string $chapter) : array {
		$title_parts = explode(':--:', $title_string);
		/* Known chapter url formats (# is numbers):
		       chapters_#A_#B - Ch#A-#B
		       ch_#A          - Ch#A
		       ch_#A_#B       - Ch#A.#B
		       <NOTHING>      - Oneshot (This is passed as "oneshot")
		*/

		$chapterData = [
			'url'    => 'http://dynasty-scans.com/chapters/' . $title_parts[0].'_'.$chapter,
			'number' => ''
		];

		if($chapter == 'oneshot') {
			$chapterData['number'] = 'oneshot';
		} else {
			$chapter = preg_replace("/^([a-zA-Z]+)/", '$1_', $chapter);
			$chapterSegments = explode('_', $chapter);
			switch($chapterSegments[0]) {
				case 'ch':
					$chapterData['number'] = 'c'.$chapterSegments[1].(isset($chapterSegments[2]) && !empty($chapterSegments[2]) ? '.'.$chapterSegments[2] : '');
					break;

				case 'chapters':
					//This is barely ever used, but I have seen it.
					$chapterData['number'] = 'c'.$chapterSegments[1].'-'.$chapterSegments[2];
					break;

				default:
					//TODO: FALLBACK, ALERT ADMIN?
					$chapterData['number'] = $chapter;
					break;
			}
		}
		return $chapterData;
	}

	public function getTitleData(string $title_string) {
		$title_parts = explode(':--:', $title_string);
		$title_url   = $title_parts[0];

		$titleData = [];
		//FIXME: Using regex here is probably a terrible idea, but we're doing it anyway....
		//FIXME (ASAP): All the regex here should be checked to see if it even returns something, and we should probably error if possible.
		if($title_parts[1] == '0') {
			$content = $this->get_content('http://dynasty-scans.com/series/'.$title_url);
			$data = $content['body'];

			preg_match('/<b>.*<\/b>/', $data, $matchesT);
			preg_match('/\/doujins\/[^"]+">(.+)?(?=<\/a>)<\/a>/', $data, $matchesD);
			$titleData['title'] = (!empty($matchesD) ? (substr($matchesD[1], 0, -7) !== 'Original' ? substr($matchesD[1], 0, -7).' - ' : '') : '') . substr($matchesT[0], 3, -4);

			$data = preg_replace('/^[\S\s]*(<dl class=\'chapter-list\'>[\S\s]*<\/dl>)[\S\s]*$/', '$1', $data);
			preg_match_all('/<dd>[\s\S]+?(?=<\/dd>)<\/dd>/', $data, $matches);
			$latest_chapter_html = array_pop($matches[0]);

			preg_match('/\/chapters\/([^"]+)/', $latest_chapter_html, $matches);
			$titleData['latest_chapter'] = substr($matches[1], strlen($title_url)+1);
			//FIXME: THIS IS A TEMP FIX, SEE https://github.com/DakuTree/manga-tracker/issues/58
			if(!$titleData['latest_chapter']) {
				log_message('error', 'DynastyScans::getTitleData cannot parse title properly as it contains oneshot. || URL: '.$title_url);
				return NULL;
			}

			preg_match('/<small>released (.*)<\/small>/', $latest_chapter_html, $matches);
			$titleData['last_updated']   = date("Y-m-d H:i:s", strtotime(str_replace('\'', '', $matches[1])));
		} elseif($title_parts[1] == '1') {
			$content = $this->get_content('http://dynasty-scans.com/chapters/'.$title_url);
			$data = $content['body'];

			preg_match('/<b>.*<\/b>/', $data, $matchesT);
			preg_match('/\/doujins\/[^"]+">(.+)?(?=<\/a>)<\/a>/', $data, $matchesD);
			$titleData['title'] = (!empty($matchesD) ? ($matchesD[1] !== 'Original' ? $matchesD[1].' - ' : '') : '') . substr($matchesT[0], 3, -4);

			$titleData['latest_chapter'] = 'oneshot'; //This will never change

			preg_match('/<i class="icon-calendar"><\/i> (.*)<\/span>/', $data, $matches);
			$titleData['last_updated']   = date("Y-m-d H:i:s", strtotime($matches[1]));

			//Oneshots are special, and really shouldn't need to be re-tracked
			//FIXME: We need to have a specific "no-track" complete param.
			$titleData['complete'] = 'Y';
		} else {
			//FIXME: WTF?
		}
		return (!empty($titleData) ? $titleData : NULL);
	}
}

class MangaPanda extends Site_Model {
	public $site          = 'MangaPanda';
	//NOTE: MangaPanda has manga pages under the root URL, so we need to filter out pages we know that aren't manga.
	public $titleFormat   = '/^(?!(?:latest|search|popular|random|alphabetical|privacy)$)([a-z0-9-]+)$/';
	public $chapterFormat = '/^[0-9]+$/';

	public function getFullTitleURL(string $title_url) : string {
		return "http://www.mangapanda.com/{$title_url}";
	}

	public function getChapterData(string $title_url, string $chapter) : array {
		return [
			'url'    => "http://www.mangapanda.com/{$title_url}/{$chapter}/",
			'number' => 'c'.$chapter
		];
	}

	public function getTitleData(string $title_url) {
		$titleData = [];

		$fullURL = $this->getFullTitleURL($title_url);
		$content = $this->get_content($fullURL);

		$data = $this->parseTitleDataDOM(
			$content,
			'MangaPanda',
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
}

class MangaStream extends Site_Model {
	public $site          = 'MangaPanda';
	public $titleFormat   = '/^[a-z0-9_]+$/';
	public $chapterFormat = '/^(.*?)\/[0-9]+$/';

	public function getFullTitleURL(string $title_url) : string {
		return "https://mangastream.com/manga/{$title_url}/";
	}

	public function getChapterData(string $title_url, string $chapter) : array {
		return [
			'url'    => "https://mangastream.com/r/{$title_url}/{$chapter}",
			'number' => 'c'.explode('/', $chapter)[0]
		];
	}

	public function getTitleData(string $title_url) {
		$titleData = [];

		$fullURL = $this->getFullTitleURL($title_url);
		$content = $this->get_content($fullURL);

		$data = $this->parseTitleDataDOM(
			$content,
			'MangaStream',
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
}

class WebToons extends Site_Model {
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

	public $site          = 'WebToons';
	public $titleFormat   = '/^[0-9]+:--:(?:en|zh-hant|zh-hans|th|id):--:[a-z0-9-]+:--:(?:drama|fantasy|comedy|action|slice-of-life|romance|superhero|thriller|sports|sci-fi)$/';
	public $chapterFormat = '/^[0-9]+:--:.*$/';

	public function getFullTitleURL(string $title_url) : string {
		$title_parts = explode(':--:', $title_url);
		return "http://www.webtoons.com/{$title_parts[1]}/{$title_parts[3]}/{$title_parts[2]}/list?title_no={$title_parts[0]}/";
	}

	public function getChapterData(string $title_url, string $chapter) : array {
		$title_parts   = explode(':--:', $title_url);
		$chapter_parts = explode(':--:', $chapter);

		return [
			'url'    => "http://www.webtoons.com/{$title_parts[1]}/{$title_parts[3]}/{$title_parts[2]}/{$chapter_parts[1]}/viewer?title_no={$title_parts[0]}&episode_no={$chapter_parts[0]}",
			'number' => $chapter_parts[1] //TODO: Possibly replace certain formats in here? Since webtoons doesn't have a standard chapter format
		];
	}

	public function getTitleData(string $title_url) {
		$titleData = [];

		$title_parts = explode(':--:', $title_url);
		$fullURL = "http://www.webtoons.com/{$title_parts[1]}/{$title_parts[3]}/{$title_parts[2]}/rss?title_no={$title_parts[0]}";

		$content = $this->get_content($fullURL);
		$data = $content['body'];
		if($data !== 'Can\'t find the manga series.') { //FIXME: We should check for he proper error here.
			$xml = simplexml_load_string($data) or die("Error: Cannot create object");
			if(isset($xml->{'channel'}->item[0])) {
				$titleData['title'] = trim((string) $xml->{'channel'}->title);

				$chapterURLSegments = explode('/', ((string) $xml->{'channel'}->item[0]->link));
				$titleData['latest_chapter'] = preg_replace('/^.*?([0-9]+)$/', '$1', $chapterURLSegments[7]) . ':--:' . $chapterURLSegments[6];
				$titleData['last_updated'] =  date("Y-m-d H:i:s", strtotime((string) $xml->{'channel'}->item[0]->pubDate));
			}
		} else {
			log_message('error', "Series missing? (WebToons): {$title_url}");
			return NULL;
		}

		return (!empty($titleData) ? $titleData : NULL);
	}
}

class KissManga extends Site_Model {
	/* This site is a massive pain in the ass. The only reason I'm supporting it is it's one of the few aggregator sites which actually support more risqué manga.
	   The main problem with this site is it has some form of bot protection. To view any part of the site normally, you need a cookie set by the bot protection.

	   To generate this cookie, we need three variables. Two are static, but the other is generated by randomly generated JS on the page.
	   The randomly generated JS is the troublesome part. We can't easily parse this with PHP. Both V8JS & SpiderMonkey refuse to build properly for me, so that rules that out.
	   The other option is using regex, but that is a rabbit hole I don't want to touch with a ten-foot pole.

	   To make the entire site work, I've built a python script to handle grabbing this cookie. This is grabbed & updated at the same time the manga are updated. The script saves the cookiejar which the PHP later reads.
	   The cookie has a length of 1 year, but I don't think it actually lasts that long, so we update every 6hours instead.
	   I should probably also mention that the cookie generated also uses your user-agent, so if it changes the cookie will break.
	*/

	public $site          = 'KissManga';
	public $titleFormat   = '/^[A-Za-z0-9-]+$/';
	public $chapterFormat = '/^.*?:--:[0-9]+$/';

	public function getFullTitleURL(string $title_url) : string {
		return "http://kissmanga.com/Manga/{$title_url}";
	}

	public function getChapterData(string $title_url, string $chapter) : array {
		$chapter_parts = explode(':--:', $chapter);

		return [
			'url'    => "http://kissmanga.com/Manga/{$title_url}/{$chapter_parts[0]}?id={$chapter_parts[1]}",
			//FIXME: KM has an extremely inconsistant chapter format which makes it difficult to parse.
			'number' => /*preg_replace('/--.*?$/', '', */$chapter_parts[0]/*)*/
		];
	}

	public function getTitleData(string $title_url) {
		$titleData = [];

		//Check if cookiejar is a day old (so we can know if something went wrong)
		$cookiejar_path = str_replace("public/", "_scripts/cookiejar", FCPATH);
		$cookie_last_updated = filemtime($cookiejar_path);
		if($cookie_last_updated && ((time() - 86400) < $cookie_last_updated)) {

			$fullURL = $this->getFullTitleURL($title_url);

			$content = $this->get_content($fullURL, '', $cookiejar_path);
			$data = $content['body'];
			if(strpos($data, 'containerRoot') !== FALSE) {
				//FIXME: For whatever reason, we can't grab the entire div without simplexml shouting at us
				$data = preg_replace('/^[\S\s]*(<div id="leftside">[\S\s]*)<div id="rightside">[\S\s]*$/', '$1', $data);

				$dom = new DOMDocument();
				libxml_use_internal_errors(true);
				$dom->loadHTML($data);
				libxml_use_internal_errors(false);

				$xpath = new DOMXPath($dom);

				$nodes_title = $xpath->query("//a[@class='bigChar']");
				$nodes_row   = $xpath->query("//table[@class='listing']/tr[3]");
				if($nodes_title->length === 1 && $nodes_row->length === 1) {
					$titleData['title'] = $nodes_title->item(0)->textContent;

					$firstRow      = $nodes_row->item(0);
					$nodes_latest  = $xpath->query("td[2]",   $firstRow);
					$nodes_chapter = $xpath->query("td[1]/a", $firstRow);

					$link = (string) $nodes_chapter->item(0)->getAttribute('href');
					$chapterURLSegments = explode('/', preg_replace('/\?.*$/', '', $link));
					$titleData['latest_chapter'] = $chapterURLSegments[3] . ':--:' . preg_replace('/.*?([0-9]+)$/', '$1', $link);
					$titleData['last_updated'] =  date("Y-m-d H:i:s", strtotime((string) $nodes_latest->item(0)->textContent));
				}
			} else {
				//TODO: Throw ERRORS;
			}
		} else {
			//Do nothing, wait until next update.
			//TODO: NAG ADMIN??
		}

		return (!empty($titleData) ? $titleData : NULL);
	}
}

class GameOfScanlation extends Site_Model {
	public $site          = 'GameOfScanlation';
	public $titleFormat   = '/^[a-z0-9-]+$/';
	public $chapterFormat = '/^[a-z0-9\.-]+$/';

	public function getFullTitleURL(string $title_url) : string {
		return "https://gameofscanlation.moe/forums/{$title_url}/";
	}

	public function getChapterData(string $title_url, string $chapter) : array {
		return [
			'url'    => "https://gameofscanlation.moe/projects/".preg_replace("/\\.[0-9]+$/", "", $title_url).'/'.$chapter.'/',
			'number' => preg_replace("/chapter-/", "c", preg_replace("/\\.[0-9]+$/", "", $chapter))
		];
	}

	public function getTitleData(string $title_url) {
		$titleData = [];

		$fullURL = $this->getFullTitleURL($title_url);

		$content = $this->get_content($fullURL);
		$data = $content['body'];
		if(strpos($data, '404 Page Not Found') === FALSE) {
			//$data = preg_replace('/^[\S\s]*(<ol[\S\s]*)<\/ol>[\S\s]*$/', '$1', $data);

			$dom = new DOMDocument();
			libxml_use_internal_errors(true);
			$dom->loadHTML($data);
			libxml_use_internal_errors(false);

			$xpath = new DOMXPath($dom);

			$nodes_title = $xpath->query("//meta[@property='og:title']");
			$nodes_row   = $xpath->query("//ol[@class='discussionListItems']/li[1]/div[@class='home_list']/ul/li/div[@class='list_press_text']");
			if($nodes_title->length === 1 && $nodes_row->length === 1) {
				$titleData['title'] = html_entity_decode($nodes_title->item(0)->getAttribute('content'));

				$firstRow      = $nodes_row->item(0);
				$nodes_latest  = $xpath->query("p[@class='author']/span|p[@class='author']/abbr", $firstRow);
				$nodes_chapter = $xpath->query("p[@class='text_work']/a",                         $firstRow);

				$link = (string) $nodes_chapter->item(0)->getAttribute('href');
				$titleData['latest_chapter'] = preg_replace('/^projects\/.*?\/(.*?)\/$/', '$1', $link);
				$titleData['last_updated'] =  date("Y-m-d H:i:s", (int) $nodes_latest->item(0)->getAttribute('data-time'));
			} else {
				log_message('error', "GameOfScanlation: Unable to find nodes.");
				return NULL;
			}
		} else {
			//TODO: Throw ERRORS;
		}

		return (!empty($titleData) ? $titleData : NULL);
	}
}

class MangaCow extends Site_Model {
	public $site          = 'MangaCow';
	public $titleFormat   = '/^[a-zA-Z0-9_]+$/';
	public $chapterFormat = '/^[0-9]+$/';

	public function getFullTitleURL(string $title_url) : string {
		return "http://mngcow.co/{$title_url}/";
	}

	public function getChapterData(string $title_url, string $chapter) : array {
		return [
			'url'    => $this->getFullTitleURL($title_url).$chapter.'/',
			'number' => "c{$chapter}"
		];
	}

	public function getTitleData(string $title_url) {
		$titleData = [];

		$fullURL = $this->getFullTitleURL($title_url);

		$content = $this->get_content($fullURL);

		$data = $this->parseTitleDataDOM(
			$content,
			'MangaCow',
			$title_url,
			"//h4",
			"//ul[contains(@class, 'mng_chp')]/li[1]/a[1]",
			"b[@class='dte']",
			"",
			"404 Page Not Found"
		);
		if($data) {
			$titleData['title'] = trim($data['nodes_title']->textContent);

			$titleData['latest_chapter'] = preg_replace('/^.*\/([0-9]+)\/$/', '$1', (string) $data['nodes_chapter']->getAttribute('href'));

			$titleData['last_updated'] =  date("Y-m-d H:i:s", strtotime((string) substr($data['nodes_latest']->getAttribute('title'), 13)));
		}

		return (!empty($titleData) ? $titleData : NULL);
	}
}

class KireiCake extends Site_Model {
	public $site          = 'KireiCake';
	public $titleFormat   = '/^[a-z0-9_-]+$/';
	public $chapterFormat = '/^en\/[0-9]+(?:\/[0-9]+(?:\/[0-9]+(?:\/[0-9]+)?)?)?$/';

	public function getFullTitleURL(string $title_url) : string {
		return "https://reader.kireicake.com/series/{$title_url}";
	}

	public function getChapterData(string $title_url, string $chapter) : array {
		//LANG/VOLUME/CHAPTER/CHAPTER_EXTRA(/page/)
		$chapter_parts = explode('/', $chapter);
		return [
			'url'    => "https://reader.kireicake.com/read/{$title_url}/{$chapter}/",
			'number' => ($chapter_parts[1] !== '0' ? "v{$chapter_parts[1]}/" : '') . "c{$chapter_parts[2]}" . (isset($chapter_parts[3]) ? ".{$chapter_parts[3]}" : '')/*)*/
		];
	}

	public function getTitleData(string $title_url) {
		$fullURL = $this->getFullTitleURL($title_url);
		return $this->parseFoolSlide($fullURL, $title_url);
	}
}

class SeaOtterScans extends Site_Model {
	public $site          = 'SeaOtterScans';
	public $titleFormat   = '/^[a-z0-9_-]+$/';
	public $chapterFormat = '/^en\/[0-9]+(?:\/[0-9]+(?:\/[0-9]+(?:\/[0-9]+)?)?)?$/';

	public function getFullTitleURL(string $title_url) : string {
		return "https://reader.seaotterscans.com/series/{$title_url}";
	}

	public function getChapterData(string $title_url, string $chapter) : array {
		//LANG/VOLUME/CHAPTER/CHAPTER_EXTRA(/page/)
		$chapter_parts = explode('/', $chapter);
		return [
			'url'    => "https://reader.seaotterscans.com/read/{$title_url}/{$chapter}/",
			'number' => ($chapter_parts[1] !== '0' ? "v{$chapter_parts[1]}/" : '') . "c{$chapter_parts[2]}" . (isset($chapter_parts[3]) ? ".{$chapter_parts[3]}" : '')/*)*/
		];
	}

	public function getTitleData(string $title_url) {
		$fullURL = $this->getFullTitleURL($title_url);
		return $this->parseFoolSlide($fullURL, $title_url);
	}
}

class HelveticaScans extends Site_Model {
	public $site          = 'HelveticaScans';
	public $titleFormat   = '/^[a-z0-9_-]+$/';
	public $chapterFormat = '/^en\/[0-9]+(?:\/[0-9]+(?:\/[0-9]+(?:\/[0-9]+)?)?)?$/';

	public function getFullTitleURL(string $title_url) : string {
		return "http://helveticascans.com/reader/series/{$title_url}";
	}

	public function getChapterData(string $title_url, string $chapter) : array {
		//LANG/VOLUME/CHAPTER/CHAPTER_EXTRA(/page/)
		$chapter_parts = explode('/', $chapter);
		return [
			'url'    => "http://helveticascans.com/reader/read/{$title_url}/{$chapter}/",
			'number' => ($chapter_parts[1] !== '0' ? "v{$chapter_parts[1]}/" : '') . "c{$chapter_parts[2]}" . (isset($chapter_parts[3]) ? ".{$chapter_parts[3]}" : '')/*)*/
		];
	}

	public function getTitleData(string $title_url) {
		$fullURL = $this->getFullTitleURL($title_url);
		return $this->parseFoolSlide($fullURL, $title_url);
	}
}

class SenseScans extends Site_Model {
	public $site          = 'SenseScans';
	public $titleFormat   = '/^[a-z0-9_-]+$/';
	public $chapterFormat = '/^en\/[0-9]+(?:\/[0-9]+(?:\/[0-9]+(?:\/[0-9]+)?)?)?$/';

	public function getFullTitleURL(string $title_url) : string {
		return "http://reader.sensescans.com/series/{$title_url}";
	}

	public function getChapterData(string $title_url, string $chapter) : array {
		//LANG/VOLUME/CHAPTER/CHAPTER_EXTRA(/page/)
		$chapter_parts = explode('/', $chapter);
		return [
			'url'    => "http://reader.sensescans.com/read/{$title_url}/{$chapter}/",
			'number' => ($chapter_parts[1] !== '0' ? "v{$chapter_parts[1]}/" : '') . "c{$chapter_parts[2]}" . (isset($chapter_parts[3]) ? ".{$chapter_parts[3]}" : '')/*)*/
		];
	}

	public function getTitleData(string $title_url) {
		$fullURL = $this->getFullTitleURL($title_url);
		return $this->parseFoolSlide($fullURL, $title_url);
	}
}
