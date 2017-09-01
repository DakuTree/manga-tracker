<?php declare(strict_types=1);

if(!extension_loaded('gd')) die('GD ext is required to run this!');

chdir(dirname(__FILE__).'/../'); //Just to make things easier, change dir to project root.

class FoolSlideGenerator {
	private $baseURL;
	private $className;

	public function __construct() {
		if(isset($_SERVER['argv']) && count($_SERVER['argv']) === 3){
			$this->baseURL   = rtrim($_SERVER['argv'][1], '/');
			$this->className = $_SERVER['argv'][2];

			if(!$this->testURL()) { die('URL returning non-200 status code.'); }
		} else {
			die('Args not valid?');
		}
	}

	public function generate() : void {
		$this->generateIcon();

		$this->generateModel();
		$this->generateTest();

		$this->updateUserscript();

		$this->updateDocs();
	}

	public function generateIcon() : void {
		$parse = parse_url($this->baseURL);

		if($icon = file_get_contents('https://www.google.com/s2/favicons?domain='.$parse['scheme'].'://'.$parse['host'])) {
			file_put_contents('./public/assets/img/site_icons/'.str_replace('.', '-', $parse['host']).'.png', $icon);

			system('php _scripts/generate_spritesheet.php'); //This is bad?
		} else {
			die("No favicon found?");
		}
	}

	public function generateModel() : void {
		$baseFile = file_get_contents('./application/models/Tracker/Sites/HelveticaScans.php');

		//Replace class name
		$baseFile = str_replace('class HelveticaScans', "class {$this->className}", $baseFile);

		//Replace baseURL
		$baseFile = str_replace('http://helveticascans.com/r', $this->baseURL, $baseFile);

		file_put_contents("./application/models/Tracker/Sites/{$this->className}.php", $baseFile);
	}
	public function generateTest() : void {
		$baseFile = file_get_contents('./application/tests/models/Tracker/Sites/HelveticaScans_test.php');

		//Replace class names
		$baseFile = str_replace('class HelveticaScans', "class {$this->className}", $baseFile);
		$baseFile = str_replace('coversDefaultClass HelveticaScans', "coversDefaultClass {$this->className}", $baseFile);

		//Replace tests
		$titleList  = $this->getTitles();
		$lengths = array_map('strlen', array_keys($titleList));
		$max_length = max($lengths) + 2;
		$testString = '';
		foreach($titleList as $stub => $name) {
			$stub = str_replace('\'', '\\\'', $stub);
			$name = str_replace('\'', '\\\'', $name);

			$testString .= "\t\t\t".str_pad("'{$stub}'", $max_length)." => '{$name}',\r\n";
		}
		$testString = rtrim($testString, ",\n");

		$baseFile = preg_replace('/\$testSeries.*\]/s', "\$testSeries = [\r\n{$testString}\r\n\t\t]", $baseFile);

		file_put_contents("./application/tests/models/Tracker/Sites/{$this->className}_test.php", $baseFile);
	}

	public function updateUserscript() : void {
		$baseFile = file_get_contents('./public/userscripts/manga-tracker.user.js');

		$parse = parse_url($this->baseURL);
		if(strpos($baseFile, $parse['host']) !== false) die("Domain already exists in userscript?");

		preg_match('/\@updated      ([0-9\-]+)\r.*?\@version      ([0-9\.]+)/s', $baseFile, $matches);

		//Add @include
		$include = '// @include      /^'.str_replace('https', 'https?', preg_replace('/([\/\.])/', '\\\\$1', $this->baseURL)).'\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/';
		$baseFile = str_replace('// @updated', "{$include}\r\n// @updated", $baseFile);

		//Update @updated
		$currentDate = date("Y-m-d", time());
		$baseFile = str_replace("@updated      {$matches[1]}","@updated      {$currentDate}", $baseFile);

		//Update @version
		$currentVersion = explode('.', $matches[2]);
		$newVersion = "{$currentVersion[0]}.{$currentVersion[1]}.". (((int) $currentVersion[2]) + 1);
		$baseFile = str_replace("@version      {$matches[2]}","@version      {$newVersion}", $baseFile);

		//Add site
		$siteData = <<<EOT
	/**
	 * {$this->className} (FoolSlide)
	 * @type {SiteObject}
	 */
	'{$parse['host']}' : extendSite({
		preInit : function(callback) {
			this.setupFoolSlide();
			callback();
		}
	}),

	//Tracking site
EOT;
		$baseFile = str_replace('	//Tracking site', $siteData, $baseFile);

		file_put_contents('./public/userscripts/manga-tracker.user.js', $baseFile);

		//Update .meta.js
		$baseFileMeta = file_get_contents('./public/userscripts/manga-tracker.meta.js');
		$baseFileMeta = str_replace("// @version      {$matches[2]}", "// @version      {$newVersion}", $baseFileMeta);
		file_put_contents('./public/userscripts/manga-tracker.meta.js', $baseFileMeta);
	}

	public function updateDocs() : void {
		$readmeFile = file_get_contents('./README.md');
		file_put_contents('./README.md', preg_replace('/(\s*)$/', "$1* {$this->className}$1", $readmeFile, 1));

		$helpFile = file_get_contents('./application/views/Help.php');
		file_put_contents('./application/views/Help.php', preg_replace('/(\s*<\/ul>)/', "\r\n\t\t<li>{$this->className}</li>$1", $helpFile));

		$changelogFile = file_get_contents('./public/CHANGELOG.md');
		$date = date("Y-m-d", time());
		if(strpos($changelogFile, $date) !== FALSE) {
			//Log already exists for current date.
			if(strpos($changelogFile, "[$date]\r\n### Added") !== FALSE) {
				$changelogFile = str_replace("[$date]\r\n### Added", "[$date]\r\n### Added\r\n- Added support for support for {$this->className}.", $changelogFile);
			} else {
				$changelogFile = str_replace("[$date]\r\n", "[$date]\r\n### Added\r\n- Added support for {$this->className}.\"", $changelogFile);
			}
		} else {
			//Log doesn't exist.
			$log = <<<EOT
## [{$date}]
### Added
- Support for {$this->className}.
EOT;
			$changelogFile = str_replace('All notable changes to this project will be documented in this file.', "All notable changes to this project will be documented in this file.\r\n\r\n{$log}", $changelogFile);
		}

		file_put_contents('./public/CHANGELOG.md', $changelogFile);
	}

	private function testURL() : bool {
		//https://stackoverflow.com/a/2280413/1168377

		$ch = curl_init("{$this->baseURL}/api/reader/chapters/orderby/desc_created/format/json");

		curl_setopt($ch, CURLOPT_NOBODY, true);
		curl_exec($ch);
		$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		return $status_code === 200;
	}
	private function getTitles() : array {
		$titleArr = [];

		$jsonURL = "{$this->baseURL}/api/reader/comics/format/json";
		if($content = file_get_contents($jsonURL)) {
			$json = json_decode($content, TRUE);
			shuffle($json['comics']);
			$comics = array_slice($json['comics'], 0, 5, true);

			foreach($comics as $comic) {
				$titleArr[$comic['stub']] = $comic['name'];
			}
		}

		if(empty($titleArr)) die("API isn't returning any titles?");
		return $titleArr;
	}
}
function say(string $text = "") { print "{$text}\n"; }

$Generator = new FoolSlideGenerator();
$Generator->generate();
