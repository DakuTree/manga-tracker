<?php declare(strict_types=1);

if(!extension_loaded('gd')) { die('GD ext is required to run this!'); }

chdir(__DIR__ . '/../'); //Just to make things easier, change dir to project root.
const ASSET_FOLDER = 'public/assets';
const ICON_FOLDER  = ASSET_FOLDER.'/img/site_icons';

/** @noinspection AutoloadingIssuesInspection */
class Spritesheet {
	private $fileList;
	private $less;

	public function __construct() {
		$this->fileList = $this->getFileList();
		$this->less     = '';
	}

	public function generate() : void {
		$this->generateSpritesheet();
		$this->modifySiteLESS();
	}

	private function generateSpritesheet() : void {
		$width = (count($this->fileList) * (16 + /* padding */ 2)) - 2;

		$sheetImage = imagecreatetruecolor($width, 16);
		imagealphablending($sheetImage, FALSE);
		imagesavealpha($sheetImage, TRUE);

		imagefill($sheetImage,0,0,0x7fff0000);

		$x = 0;
		foreach ($this->fileList as $filename) {
			$siteImage = imagecreatefrompng(ICON_FOLDER. "/{$filename}");
			imagealphablending($siteImage, TRUE);

			$dst_x = ((16 + 2) * $x);
			imagecopyresampled($sheetImage, $siteImage, $dst_x, 0, 0, 0, 16, 16, 16, 16);

			$this->generateLESS($filename, $dst_x);
			$x++;
		}

		imagepng($sheetImage, ASSET_FOLDER . '/img/sites.png');
		say('Updated spritesheet!');
	}
	private function generateLESS(string $filename, int $dst_x) : void {
		$parts = pathinfo($filename);

		$this->less .= "\n".
			"	&.sprite-{$parts['filename']} {\n".
			"		.stitches-sprite(-{$dst_x}px);\n".
			"	}\n";
	}
	private function modifySiteLESS() : void {
		$newSiteLESS = trim($this->less);

		$icons_file = ASSET_FOLDER.'/less/modules/icons.less';
		$oldLESS = file_get_contents($icons_file);
		if(preg_match('/\.sprite-site.*\@cache-version: (\d+);/s', $oldLESS, $cvMatches)) {
			$cacheVersion = ((int) $cvMatches[1]) + 1;

			$newLESS = preg_replace('/\.sprite-site.*/s', '',$oldLESS);
			$newLESS .= ''.
				".sprite-site {\n".
				"	.sprite();\n".
				"	@cache-version: {$cacheVersion};\n".
				"	background: url('../../img/sites.@{cache-version}.png') no-repeat;\n\n".
				"	{$newSiteLESS}\n".
				"}\n";

			file_put_contents($icons_file, $newLESS);
			say('Updated LESS!');
		} else {
			die("Can't find cache-version?");
		}
	}

	private function getFileList() : array {
		return array_diff(scandir(ICON_FOLDER, SCANDIR_SORT_NONE), array('..', '.'));
	}
}

/** @noinspection PhpFunctionNamingConventionInspection */
function say(string $text = '') { print "{$text}\n"; }

$Spritesheet = new Spritesheet();
$Spritesheet->generate();
