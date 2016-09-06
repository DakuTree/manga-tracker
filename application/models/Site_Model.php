<?php declare(strict_types=1); defined('BASEPATH') OR exit('No direct script access allowed');

class Site_Model extends CI_Model {
	public function __construct() {
		parent::__construct();

		$this->load->database();
	}

	public function getFullTitleURL(string $title_url) : string {}

	public function getChapterData(string $title_url, string $chapter) : array {}

	public function getTitleData(string $title_url) {}

	public function validTitleURL(string $title_url) : bool {}
	public function isValidChapter(string $chapter): bool {}

	protected function get_content(string $url, string $cookie_string = "", string $cookiejar_path = ""){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_ENCODING , "gzip");

		if(!empty($cookie_string))  curl_setopt($ch, CURLOPT_COOKIE, $cookie_string);
		if(!empty($cookiejar_path)) curl_setopt($ch, CURLOPT_COOKIEFILE, $cookiejar_path);

		//Some sites check the useragent for stuff, use a pre-defined user-agent to avoid stuff.
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2824.0 Safari/537.36');

		curl_setopt($ch, CURLOPT_URL, $url);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
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

	public function __construct() {
		parent::__construct();

		$this->MangaFox     = new MangaFox();
		$this->MangaHere    = new MangaHere();
		$this->Batoto       = new Batoto();
		$this->DynastyScans = new DynastyScans();
		$this->MangaPanda   = new MangaPanda();
		$this->MangaStream  = new MangaStream();
		$this->WebToons     = new WebToons();
		$this->KissManga    = new KissManga();
		$this->KireiCake    = new KireiCake();
	}
}

class MangaFox extends Site_Model {
	public function getFullTitleURL(string $title_url) : string {
		return "http://mangafox.me/manga/{$title_url}/";
	}

	public function isValidTitleURL(string $title_url) : bool {
		$success = (bool) preg_match('/^[a-z0-9_]+$/', $title_url);
		if(!$success) log_message('error', "Invalid Title URL (MangaFox): {$title_url}");
		return $success;
	}
	public function isValidChapter(string $chapter) : bool {
		$success = (bool) preg_match('/^(?:v[0-9]+\/)?c[0-9]+(?:\.[0-9]+)?$/', $chapter);
		if(!$success) log_message('error', 'Invalid Chapter (MangaFox): '.$chapter);
		return $success;
	}

	public function getChapterData(string $title_url, string $chapter) : array {
		return [
			'url'    => "http://mangafox.me/manga/{$title_url}/{$chapter}/",
			'number' => $chapter
		];
	}

	public function getTitleData(string $title_url) {
		$titleData = [];

		$fullURL = "http://mangafox.me/manga/{$title_url}";

		$data = $this->get_content($fullURL);
		if($data !== 'Can\'t find the manga series.') {
			//$data = preg_replace('/^[\S\s]*(<body id="body">[\S\s]*<\/body>)[\S\s]*$/', '$1', $data);

			$dom = new DOMDocument();
			libxml_use_internal_errors(true);
			$dom->loadHTML($data);
			libxml_use_internal_errors(false);

			$xpath = new DOMXPath($dom);

			$nodes_title = $xpath->query("//meta[@property='og:title']");
			$nodes_row   = $xpath->query("//body/div[@id='page']/div[@class='left']/div[@id='chapters']/ul[1]/li[1]");
			if($nodes_title->length === 1 & $nodes_row->length === 1) {
				//This seems to be be the only viable way to grab the title...
				$titleData['title'] = substr($nodes_title[0]->getAttribute('content'), 0, -6);

				$nodes_latest  = $xpath->query("div/span[@class='date']", $nodes_row[0]);
				$nodes_chapter = $xpath->query("div/h3/a", $nodes_row[0]);

				$link = preg_replace('/^(.*\/)(?:[0-9]+\.html)?$/', '$1', (string) $nodes_chapter[0]->getAttribute('href'));
				$chapterURLSegments = explode('/', $link);
				$titleData['latest_chapter'] = $chapterURLSegments[5] . (isset($chapterURLSegments[6]) && !empty($chapterURLSegments[6]) ? "/{$chapterURLSegments[6]}" : "");
				$titleData['last_updated'] =  date("Y-m-d H:i:s", strtotime((string) $nodes_latest[0]->nodeValue));
			}
		} else {
			//TODO: Throw ERRORS;
		}

		return (!empty($titleData) ? $titleData : NULL);
	}
}

class MangaHere extends Site_Model {
	public function getFullTitleURL(string $title_url) : string {
		return "http://www.mangahere.co/manga/{$title_url}/";
	}

	public function isValidTitleURL(string $title_url) : bool {
		$success = (bool) preg_match('/^[a-z0-9_]+$/', $title_url);
		if(!$success) log_message('error', "Invalid Title URL (MangaFox): {$title_url}");
		return $success;
	}
	public function isValidChapter(string $chapter) : bool {
		$success = (bool) preg_match('/^(?:v[0-9]+\/)?c[0-9]+(?:\.[0-9]+)?$/', $chapter);
		if(!$success) log_message('error', 'Invalid Chapter (MangaFox): '.$chapter);
		return $success;
	}

	public function getChapterData(string $title, string $chapter) : array {
		return [
			'url'    => "http://www.mangahere.co/manga/{$title}/{$chapter}/",
			'number' => $chapter
		];
	}

	public function getTitleData(string $title_url) {
		$titleData = [];

		$fullURL = "http://www.mangahere.co/manga/{$title_url}/";

		$data = $this->get_content($fullURL);
		if($data !== 'Can\'t find the manga series.') {
			//$data = preg_replace('/^[\S\s]*(<body id="body">[\S\s]*<\/body>)[\S\s]*$/', '$1', $data);

			$dom = new DOMDocument();
			libxml_use_internal_errors(true);
			$dom->loadHTML($data);
			libxml_use_internal_errors(false);

			$xpath = new DOMXPath($dom);
			$nodes_title = $xpath->query("//meta[@property='og:title']");
			$nodes_row   = $xpath->query("//body/section/article/div/div[@class='manga_detail']/div[@class='detail_list']/ul[1]/li[1]");
			if($nodes_title->length === 1 & $nodes_row->length === 1) {
				//This seems to be be the only viable way to grab the title...
				$titleData['title'] = $nodes_title[0]->getAttribute('content');

				$nodes_latest  = $xpath->query("span[@class='right']", $nodes_row[0]);
				$nodes_chapter = $xpath->query("span[@class='left']/a", $nodes_row[0]);

				$link = preg_replace('/^(.*\/)(?:[0-9]+\.html)?$/', '$1', (string) $nodes_chapter[0]->getAttribute('href'));
				$chapterURLSegments = explode('/', $link);
				$titleData['latest_chapter'] = $chapterURLSegments[5] . (isset($chapterURLSegments[6]) && !empty($chapterURLSegments[6]) ? "/{$chapterURLSegments[6]}" : "");
				$titleData['last_updated'] =  date("Y-m-d H:i:s", strtotime((string) $nodes_latest[0]->nodeValue));
			}
		} else {
			//TODO: Throw ERRORS;
		}
		return (!empty($titleData) ? $titleData : NULL);
	}
}

class Batoto extends Site_Model {
	//Batoto is a bit tricky to track. Unlike MangaFox and MangaHere, it doesn't store anything in the title_url, which means we have to get the data via other methods.
	//One problem we have though, is the tracker must support multiple sites, so this means we need to do some weird things to track Batoto.
	//title_url is stored like: "TITLE_URL:--:LANGUAGE"
	//chapter_urls are stored like "CHAPTER_ID:--:CHAPTER_NUMBER"

	public function getFullTitleURL(string $title_string) : string {
		//FIXME: This does not point to the language specific title page. Should ask if it is possible to set LANG as arg?
		$title_parts = explode(':--:', $title_string);
		return "http://bato.to/comic/_/comics/".$title_parts[0];
	}

	public function isValidTitleURL(string $title_url) : bool {
		$success = (bool) preg_match('/^[a-z0-9%-]+:--:(?:English|Spanish|French|German|Portuguese|Turkish|Indonesian|Greek|Filipino|Italian|Polish|Thai|Malay|Hungarian|Romanian|Arabic|Hebrew|Russian|Vietnamese|Dutch)$/', $title_url);
		if(!$success) log_message('error', "Invalid Title URL (Batoto): {$title_url}");
		return $success;
	}
	public function isValidChapter(string $chapter) : bool {
		//FIXME: We're not validating the chapter name since we don't know what all the possible valid characters can be
		//       Preferably we'd just use /^[0-9a-z]+:--:(v[0-9]+\/)?c[0-9]+(\.[0-9]+)?$/

		$success = (bool) preg_match('/^[0-9a-z]+:--:.+$/', $chapter);
		if(!$success) log_message('error', 'Invalid Chapter (Batoto): '.$chapter);
		return $success;
	}

	public function getChapterData(string $title_string, string $chapter) : array {
		//$title_string isn't used here.

		$chapter_parts = explode(':--:', $chapter);
		return [
			'url'    => "http://bato.to/reader#" . $chapter_parts[0],
			'number' => $chapter_parts[1]
		];
	}

	public function getTitleData(string $title_string) {
		$title_parts = explode(':--:', $title_string);
		$title_url   = $this->getFullTitleURL($title_string);
		$title_lang  = $title_parts[1];
		//TODO: Validate title_lang from array?

		$titleData = [];

		//Bato.to is annoying and locks stuff behind auth. See: https://github.com/DakuTree/manga-tracker/issues/14#issuecomment-233830855
		$cookies = [
			"lang_option={$title_lang}",
		    "member_id=" . $this->config->item('batoto_cookie_member_id'),
		    "pass_hash=" . $this->config->item('batoto_cookie_pass_hash')
		];
		$data = $this->get_content($title_url, implode("; ", $cookies));
		if(!$data) {
			log_message('error', "Batoto: Couldn't successfully grab URL ({$title_url})");
			return NULL;
		}

		$data = preg_replace('/^[\s\S]+<!-- ::: CONTENT ::: -->/', '<!-- ::: CONTENT ::: -->', $data);
		$data = preg_replace('/<!-- end mainContent -->[\s\S]+$/', '<!-- end mainContent -->', $data);
		$data = preg_replace('/<div id=\'commentsStart\' class=\'ipsBox\'>[\s\S]+$/', '</div></div><!-- end mainContent -->', $data);
		if(strpos($data, '>Register now<') === FALSE) {
			//Auth was successful
			$dom = new DOMDocument();
			libxml_use_internal_errors(true);
			$dom->loadHTML($data);
			libxml_use_internal_errors(false);

			$xpath = new DOMXPath($dom);
			$nodes = $xpath->query("(//div/div)[last()]/table/tbody/tr[2]");
			if($nodes->length === 1) {
				$node = $nodes[0];

				$nodes_chapter = $xpath->query('td/a[contains(@href,\'reader\')]', $node);
				$nodes_updated = $xpath->query('td[last()]', $node);
				if($nodes_chapter->length === 1 && $nodes_updated->length === 1) {
					$chapter_element = $nodes_chapter->item(0);
					$updated_element = $nodes_updated->item(0);

					preg_match('/^(?:Vol\.(?<volume>\S+) )?(?:Ch.(?<chapter>[^\s:]+)):?.*/', trim($chapter_element->nodeValue), $text);

					$titleData['title']          = trim($xpath->query('//h1[@class="ipsType_pagetitle"]')->item(0)->nodeValue);
					$titleData['latest_chapter'] = substr($chapter_element->getAttribute('href'), 22) . ':--:' . ((!empty($text['volume']) ? 'v'.$text['volume'].'/' : '') . 'c'.$text['chapter']);
					$titleData['last_updated']   = date("Y-m-d H:i:s", strtotime(preg_replace('/ (-|\[A\]).*$/', '', $updated_element->nodeValue)));
				} else {
					//FIXME: SOMETHING WENT WRONG
				}
			} else {
				//FIXME: SOMETHING WENT WRONG
			}
		} else {
			//Auth was not successful. Alert the admin / Try to login.
			//FIXME: SOMETHING WENT WRONG
			//print implode(";\n", $cookies)."\n";
		}
		return (!empty($titleData) ? $titleData : NULL);
	}
}

class DynastyScans extends Site_Model {
	//FIXME: This has some major issues. SEE: https://github.com/DakuTree/manga-tracker/issues/58
	public function getFullTitleURL(string $title_string) : string {
		$title_parts = explode(':--:', $title_string);
		$url_type = ($title_parts[1] == '0' ? 'series' : 'chapters');

		return 'http://dynasty-scans.com/'.$url_type.'/'.$title_parts[0];
	}

	public function isValidTitleURL(string $title_url) : bool {
		$success = (bool) preg_match('/^[a-z0-9_]+:--:(?:0|1)$/', $title_url);
		if(!$success) log_message('error', "Invalid Title URL (DynastyScans): {$title_url}");
		return $success;
	}
	public function isValidChapter(string $chapter) : bool {
		$success = (bool) preg_match('/^[0-9a-z_]+$/', $chapter);
		if(!$success) log_message('error', 'Invalid Chapter (DynastyScans): '.$chapter);
		return $success;
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
			$data = $this->get_content('http://dynasty-scans.com/series/'.$title_url);

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
			$data = $this->get_content('http://dynasty-scans.com/chapters/'.$title_url);

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
	public function getFullTitleURL(string $title_url) : string {
		return "http://www.mangapanda.com/{$title_url}";
	}

	public function getChapterData(string $title_url, string $chapter) : array {
		return [
			'url'    => "http://www.mangapanda.com/{$title_url}/{$chapter}/",
			'number' => 'c'.$chapter
		];
	}

	public function isValidTitleURL(string $title_url) : bool {
		$success = (bool) preg_match('/^[a-z0-9-]+$/', $title_url);
		if(!$success) log_message('error', "Invalid Title URL (MangaPanda): {$title_url}");
		return $success;
	}
	public function isValidChapter(string $chapter) : bool {
		$success = (bool) preg_match('/^[0-9]+$/', $chapter);
		if(!$success) log_message('error', 'Invalid Chapter (MangaPanda): '.$chapter);
		return $success;
	}

	public function getTitleData(string $title_url) {
		$titleData = [];

		$fullURL = "http://www.mangapanda.com/{$title_url}";

		$data = $this->get_content($fullURL);
		if($data !== 'Can\'t find the manga series.') {
			//$data = preg_replace('/^[\S\s]*(<body id="body">[\S\s]*<\/body>)[\S\s]*$/', '$1', $data);

			$dom = new DOMDocument();
			libxml_use_internal_errors(true);
			$dom->loadHTML($data);
			libxml_use_internal_errors(false);

			$xpath = new DOMXPath($dom);

			$nodes_title = $xpath->query("//h2[@class='aname']");
			$nodes_row   = $xpath->query("(//table[@id='listing']/tr)[last()]");
			if($nodes_title->length === 1 & $nodes_row->length === 1) {
				//This seems to be be the only viable way to grab the title...
				$titleData['title'] = $nodes_title[0]->nodeValue;

				$nodes_latest  = $xpath->query("td[2]", $nodes_row[0]);
				$nodes_chapter = $xpath->query("td[1]/a", $nodes_row[0]);

				$titleData['latest_chapter'] = preg_replace('/^.*\/([0-9]+)$/', '$1', (string) $nodes_chapter[0]->getAttribute('href'));
				$titleData['last_updated'] =  date("Y-m-d H:i:s", strtotime((string) $nodes_latest[0]->nodeValue));
			}
		} else {
			//TODO: Throw ERRORS;
		}

		return (!empty($titleData) ? $titleData : NULL);
	}
}

class MangaStream extends Site_Model {
	public function getFullTitleURL(string $title_url) : string {
		return "https://mangastream.com/manga/{$title_url}/";
	}

	public function isValidTitleURL(string $title_url) : bool {
		$success = (bool) preg_match('/^[a-z0-9_]+$/', $title_url);
		if(!$success) log_message('error', "Invalid Title URL (MangaStream): {$title_url}");
		return $success;
	}
	public function isValidChapter(string $chapter) : bool {
		$success = (bool) preg_match('/^(.*?)\/[0-9]+$/', $chapter);
		if(!$success) log_message('error', 'Invalid Chapter (MangaStream): '.$chapter);
		return $success;
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

		$data = $this->get_content($fullURL);
		if($data !== 'Can\'t find the manga series.') {
			//$data = preg_replace('/^[\S\s]*(<body id="body">[\S\s]*<\/body>)[\S\s]*$/', '$1', $data);

			$dom = new DOMDocument();
			libxml_use_internal_errors(true);
			$dom->loadHTML($data);
			libxml_use_internal_errors(false);

			$xpath = new DOMXPath($dom);

			$nodes_title = $xpath->query("//div[contains(@class, 'content')]/div[1]/h1");
			$nodes_row   = $xpath->query("//div[contains(@class, 'content')]/div[1]/table/tr[2]"); //Missing tbody here..
			if($nodes_title->length === 1 & $nodes_row->length === 1) {
				$titleData['title'] = $nodes_title[0]->nodeValue;

				$nodes_latest  = $xpath->query("td[2]", $nodes_row[0]);
				$nodes_chapter = $xpath->query("td[1]/a", $nodes_row[0]);

				$titleData['latest_chapter'] = preg_replace('/^.*\/(.*?\/[0-9]+)\/[0-9]+$/', '$1', (string) $nodes_chapter[0]->getAttribute('href'));
				$titleData['last_updated'] =  date("Y-m-d H:i:s", strtotime((string) $nodes_latest[0]->nodeValue));
			}
		} else {
			//TODO: Throw ERRORS;
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

	public function getFullTitleURL(string $title_url) : string {
		$title_parts = explode(':--:', $title_url);
		return "http://www.webtoons.com/{$title_parts[1]}/{$title_parts[3]}/{$title_parts[2]}/list?title_no={$title_parts[0]}/";
	}

	public function isValidTitleURL(string $title_url) : bool {
		$success = (bool) preg_match('/^[0-9]+:--:(?:en|zh-hant|zh-hans|th|id):--:[a-z0-9-]+:--:(?:drama|fantasy|comedy|action|slice-of-life|romance|superhero|thriller|sports|sci-fi)$/', $title_url);
		if(!$success) log_message('error', "Invalid Title URL (WebToons): {$title_url}");
		return $success;
	}
	public function isValidChapter(string $chapter) : bool {
		$success = (bool) preg_match('/^[0-9]+:--:.*$/', $chapter);
		if(!$success) log_message('error', 'Invalid Chapter (WebToons): '.$chapter);
		return $success;
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

		$data = $this->get_content($fullURL);
		if($data !== 'Can\'t find the manga series.') {
			$xml = simplexml_load_string($data) or die("Error: Cannot create object");
			if(isset($xml->channel->item[0])) {
				$titleData['title'] = trim((string) $xml->channel->title);

				$chapterURLSegments = explode('/', ((string) $xml->channel->item[0]->link));
				$titleData['latest_chapter'] = preg_replace('/^.*?([0-9]+)$/', '$1', $chapterURLSegments[7]) . ':--:' . $chapterURLSegments[6];
				$titleData['last_updated'] =  date("Y-m-d H:i:s", strtotime((string) $xml->channel->item[0]->pubDate));
			}
		} else {
			//TODO: Throw ERRORS;
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

	public function getFullTitleURL(string $title_url) : string {
		return "http://kissmanga.com/Manga/{$title_url}";
	}

	public function isValidTitleURL(string $title_url) : bool {
		$success = (bool) preg_match('/^[A-Za-z0-9-]+/', $title_url);
		if(!$success) log_message('error', "Invalid Title URL (KissManga): {$title_url}");
		return $success;
	}
	public function isValidChapter(string $chapter) : bool {
		$success = (bool) preg_match('/^.*?:--:[0-9]+$/', $chapter);
		if(!$success) log_message('error', 'Invalid Chapter (KissManga): '.$chapter);
		return $success;
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
			$data = $this->get_content($fullURL, '', $cookiejar_path);
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
				if($nodes_title->length === 1 & $nodes_row->length === 1) {
					$titleData['title'] = $nodes_title[0]->textContent;

					$nodes_latest  = $xpath->query("td[2]", $nodes_row[0]);
					$nodes_chapter = $xpath->query("td[1]/a", $nodes_row[0]);

					$link = (string) $nodes_chapter[0]->getAttribute('href');
					$chapterURLSegments = explode('/', preg_replace('/\?.*$/', '', $link));
					$titleData['latest_chapter'] = $chapterURLSegments[3] . ':--:' . preg_replace('/.*?([0-9]+)$/', '$1', $link);
					$titleData['last_updated'] =  date("Y-m-d H:i:s", strtotime((string) $nodes_latest[0]->textContent));
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

class KireiCake extends Site_Model {
	public function getFullTitleURL(string $title_url) : string {
		return "http://reader.kireicake.com/series/{$title_url}";
	}

	public function isValidTitleURL(string $title_url) : bool {
		$success = (bool) preg_match('/^[a-z0-9_]+/', $title_url);
		if(!$success) log_message('error', "Invalid Title URL (KireiCake): {$title_url}");
		return $success;
	}
	public function isValidChapter(string $chapter) : bool {
		$success = (bool) preg_match('/^en\/[0-9]+(?:\/[0-9]+(?:\/[0-9]+(?:\/[0-9]+)?)?)?$/', $chapter);
		if(!$success) log_message('error', 'Invalid Chapter (KireiCake): '.$chapter);
		return $success;
	}

	public function getChapterData(string $title_url, string $chapter) : array {
		//LANG/VOLUME/CHAPTER/CHAPTER_EXTRA(/page/)
		$chapter_parts = explode('/', $chapter);
		return [
			'url'    => "http://reader.kireicake.com/read/{$title_url}/{$chapter}",
			'number' => ($chapter_parts[1] !== '0' ? "v{$chapter_parts[1]}/" : '') . "c{$chapter_parts[2]}" . (isset($chapter_parts[3]) ? ".{$chapter_parts[3]}" : '')/*)*/
		];
	}

	public function getTitleData(string $title_url) {
		$titleData = [];

		$fullURL = $this->getFullTitleURL($title_url);
		$data = $this->get_content($fullURL);
		if(strpos($data, '404 Page Not Found') === FALSE) {
			//FIXME: For whatever reason, we can't grab the entire div without simplexml shouting at us
			$data = preg_replace('/^[\S\s]*(<article>[\S\s]*)<\/article>[\S\s]*$/', '$1', $data);

			$dom = new DOMDocument();
			libxml_use_internal_errors(true);
			$dom->loadHTML($data);
			libxml_use_internal_errors(false);

			$xpath = new DOMXPath($dom);

			$nodes_title = $xpath->query("//div[@class='large comic']/h1[@class='title']");
			$nodes_row   = $xpath->query("//div[@class='list']/div[@class='element'][1]");
			if($nodes_title->length === 1 & $nodes_row->length === 1) {
				$titleData['title'] = trim($nodes_title[0]->textContent);


				$nodes_latest  = $xpath->query("div[@class='meta_r']", $nodes_row[0]);
				$nodes_chapter = $xpath->query("div[@class='title']/a", $nodes_row[0]);

				$link = (string) $nodes_chapter[0]->getAttribute('href');
				$titleData['latest_chapter'] = preg_replace('/.*\/read\/.*?\/(.*?)\/$/', '$1', $link);
				$titleData['last_updated'] =  date("Y-m-d H:i:s", strtotime((string) str_replace('.', '', explode(',', $nodes_latest[0]->textContent)[1])));
			}
		} else {
			//TODO: Throw ERRORS;
		}

		return (!empty($titleData) ? $titleData : NULL);
	}
}
