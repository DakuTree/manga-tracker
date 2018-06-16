<?php declare(strict_types=1); defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . '../vendor/erusev/parsedown/Parsedown.php';
class CustomParsedown extends Parsedown {
	public function __construct() {
		$this->setSafeMode(TRUE);
	}

	protected function inlineLink($excerpt) {
		$link = parent::inlineLink($excerpt);

		if(!isset($link)) {
			return null;
		}

		//Make title attribute the same as link
		$link['element']['attributes']['title'] = $link['element']['attributes']['href'];

		return $link;
	}
}
