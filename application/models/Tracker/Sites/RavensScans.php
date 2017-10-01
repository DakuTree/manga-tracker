<?php declare(strict_types=1); defined('BASEPATH') OR exit('No direct script access allowed');

//Ravens Scans is a bit of an irregular in that it uses a combination of a FoolSlide fork (https://github.com/dvaJi/FoOlSlide) and a standalone front-end (https://github.com/dvaJi/ReaderFront).
class RavensScans extends Base_FoolSlide_Site_Model {
	public $baseURL = 'http://ravens-scans.com';

	public function getFullTitleURL(string $title_url) : string {
		return "{$this->baseURL}/multi/comic/{$title_url}";
	}

	public function getJSONTitleURL(string $title_url) : string {
		return "{$this->baseURL}/lector/api/v1/comic?stub={$title_url}";
	}
	public function getJSONUpdateURL() : string {
		return "{$this->baseURL}/lector/api/reader/chapters/orderby/desc_created/format/json";
	}
}
