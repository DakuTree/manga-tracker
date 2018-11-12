<?php declare(strict_types=1); defined('BASEPATH') OR exit('No direct script access allowed');

class DokiFansubs extends Base_FoolSlide_Site_Model {
	public $baseURL = 'https://kobato.hologfx.com/reader';
	public $bypassSSL = TRUE; // Certs keep expiring.
}
