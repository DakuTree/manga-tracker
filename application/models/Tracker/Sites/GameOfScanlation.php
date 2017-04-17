<?php declare(strict_types=1); defined('BASEPATH') OR exit('No direct script access allowed');

class GameOfScanlation extends Base_Site_Model {
	public $titleFormat   = '/^[a-z0-9\.-]+$/';
	public $chapterFormat = '/^[a-z0-9\.-]+$/';

	public function getFullTitleURL(string $title_url) : string {
		/* NOTE: GoS is a bit weird in that it has two separate title URL formats. One uses /projects/ and the other uses /fourms/.
		         The bad thing is these are interchangeable, despite them showing the exact same listing page.
		         Thankfully the title_url of manga which use /forums/ seem to be appended with ".%ID%" which means we can easily check them. */

		if (strpos($title_url, '.') !== FALSE) {
			$format = "https://gameofscanlation.moe/forums/{$title_url}/";
		} else {
			$format = "https://gameofscanlation.moe/projects/{$title_url}/";
		}
		return $format;
	}

	public function getChapterData(string $title_url, string $chapter) : array {
		return [
			'url'    => "https://gameofscanlation.moe/projects/".preg_replace("/\\.[0-9]+$/", "", $title_url).'/'.$chapter.'/',
			'number' => preg_replace("/chapter-/", "c", preg_replace("/\\.[0-9]+$/", "", $chapter))
		];
	}

	public function getTitleData(string $title_url, bool $firstGet = FALSE) {
		$titleData = [];

		$fullURL = $this->getFullTitleURL($title_url);

		$content = $this->get_content($fullURL);

		$data = $this->parseTitleDataDOM(
			$content,
			$title_url,
			"//meta[@property='og:title']",
			"//ol[@class='discussionListItems']/li[1]/div[@class='home_list']/ul/li/div[@class='list_press_text']",
			"p[@class='author']/span|p[@class='author']/abbr",
			"p[@class='text_work']/a"
		);
		if($data) {
			$titleData['title'] = trim(html_entity_decode($data['nodes_title']->getAttribute('content')));

			$titleData['latest_chapter'] = preg_replace('/^projects\/.*?\/(.*?)\/$/', '$1', (string) $data['nodes_chapter']->getAttribute('href'));

			$titleData['last_updated'] =  date("Y-m-d H:i:s",(int) $data['nodes_latest']->getAttribute('title'));
		}

		return (!empty($titleData) ? $titleData : NULL);
	}
}
