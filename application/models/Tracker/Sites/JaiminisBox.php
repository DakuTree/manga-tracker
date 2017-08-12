<?php declare(strict_types=1); defined('BASEPATH') OR exit('No direct script access allowed');

class JaiminisBox extends Base_FoolSlide_Site_Model {
	public $baseURL = 'https://jaiminisbox.com/reader';

	//NOTE: Jaimini's Box appears to have disabled API support for some reason. Fallback to using the old method.
	public function getTitleData(string $title_url, bool $firstGet = FALSE) : ?array {
		$fullURL = $this->getFullTitleURL($title_url);
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
}
