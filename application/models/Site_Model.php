<?php declare(strict_types=1); defined('BASEPATH') OR exit('No direct script access allowed');

class Site_Model extends CI_Model {
	public function __construct() {
		parent::__construct();

		$this->load->database();
	}

	public function getFullTitleURL(string $title_url) : string {}

	public function getChapterURL(string $title_url, string $chapter) : string {}

	public function getLatestChapter(string $title_url) {}

	protected function get_content($URL){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_ENCODING , "gzip");
		curl_setopt($ch, CURLOPT_URL, $URL);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
}
class Sites_Model extends CI_Model {
	public $MangaFox;
	public $MangaHere;
	public function __construct() {
		parent::__construct();

		$this->MangaFox = new MangaFox();
		$this->MangaHere = new MangaHere();
	}

	public function MangaHere() {
		return new MangaHere();
	}
}

class MangaFox extends Site_Model {
	public function getFullTitleURL(string $title_url) : string {
		return "http://mangafox.me/manga/{$title_url}/";
	}

	public function getChapterURL(string $title_url, string $chapter) : string {
		return "http://mangafox.me/manga/{$title_url}/{$chapter}/";
	}

	public function getLatestChapter(string $title_url) {
		$rssURL = "http://mangafox.me/rss/{$title_url}.xml";

		$data = $this->get_content($rssURL);
		if($data !== 'Can\'t find the manga series.') {
			$xml = simplexml_load_string($data) or die("Error: Cannot create object");

			if(isset($xml->channel->item[0])) {
				$chapterURLSegments = explode('/', ((string) $xml->channel->item[0]->link));
				$latestChapter      = $chapterURLSegments[5] . ($chapterURLSegments[6] ? "/{$chapterURLSegments[6]}" : "");
			}
		} else {
			//TODO: Throw ERRORS;
		}

		return $latestChapter ?? NULL;
	}
}

class MangaHere extends Site_Model {
	public function getFullTitleURL(string $title_url) : string {
		return "http://www.mangahere.co/manga/{$title_url}/";
	}

	public function getChapterURL(string $title, string $chapter) : string {
		return "http://www.mangahere.co/manga/{$title}/{$chapter}/";
	}

	public function getLatestChapter(string $title_url) {
		$rssURL = "http://www.mangahere.co/rss/{$title_url}.xml";

		$data = $this->get_content($rssURL);
		if($data !== 'Can\'t find the manga series.') {
			$xml = simplexml_load_string($data) or die("Error: Cannot create object");

			if(isset($xml->channel->item[0])) {
				$chapterURLSegments = explode('/', ((string) $xml->channel->item[0]->link));
				$latestChapter      = $chapterURLSegments[5] . ($chapterURLSegments[6] ? "/{$chapterURLSegments[6]}" : "");
			}
		} else {
			//TODO: Throw ERRORS;
		}

		return $latestChapter ?? NULL;
	}
}

class Batoto extends Site_Model {
	public function getChapterURL(string $title, string $chapter) : string {
		$chapterInfo = explode("::::", $chapter);
		return "http://bato.to/reader#" . $chapterInfo[0];
	}

	public function getLatestChapter(string $title_url) {

	}
}
