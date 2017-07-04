<?php declare(strict_types=1); defined('BASEPATH') OR exit('No direct script access allowed');

class DemonicScans extends Base_Site_Model {
	public $titleFormat   = '/^[a-z0-9_-]+$/';
	public $chapterFormat = '/^en\/[0-9]+(?:\/[0-9]+(?:\/[0-9]+(?:\/[0-9]+)?)?)?$/';

	public function getFullTitleURL(string $title_url) : string {
		return "http://www.demonicscans.com/FoOlSlide/series/{$title_url}";
	}

	public function getChapterData(string $title_url, string $chapter) : array {
		//LANG/VOLUME/CHAPTER/CHAPTER_EXTRA(/page/)
		$chapter_parts = explode('/', $chapter);
		return [
			'url'    => "http://www.demonicscans.com/FoOlSlide/read/{$title_url}/{$chapter}/",
			'number' => ($chapter_parts[1] !== '0' ? "v{$chapter_parts[1]}/" : '') . "c{$chapter_parts[2]}" . (isset($chapter_parts[3]) ? ".{$chapter_parts[3]}" : '')/*)*/
		];
	}

	public function getTitleData(string $title_url, bool $firstGet = FALSE) : ?array {
		$fullURL = $this->getFullTitleURL($title_url);
		return $this->parseFoolSlide($fullURL, $title_url);
	}
}
