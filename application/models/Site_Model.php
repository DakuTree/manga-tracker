<?php declare(strict_types=1); defined('BASEPATH') OR exit('No direct script access allowed');

class Site_Model extends CI_Model {
	public function __construct() {
		parent::__construct();

		$this->load->database();
	}

	public function getFullTitleURL(string $title_url) : string {}

	public function getChapterData(string $title_url, string $chapter) : array {}

	public function getTitleData(string $title_url) {}

	protected function get_content(string $url, string $cookie_string = ""){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_ENCODING , "gzip");
		if(!empty($cookie_string)) curl_setopt($ch, CURLOPT_COOKIE, $cookie_string);
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

	public function __construct() {
		parent::__construct();

		$this->MangaFox     = new MangaFox();
		$this->MangaHere    = new MangaHere();
		$this->Batoto       = new Batoto();
		$this->DynastyScans = new DynastyScans();
		$this->MangaPanda   = new MangaPanda();
		$this->MangaStream  = new MangaStream();
	}
}

class MangaFox extends Site_Model {
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
		return $title_parts[0];
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
		$title_url   = $title_parts[0];
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
					$titleData['last_updated']   = date("Y-m-d H:i:s", strtotime(preg_replace('/ -.*$/', '', $updated_element->nodeValue)));
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
			print $nodes_row->length;
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
