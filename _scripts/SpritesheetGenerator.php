<?php declare(strict_types=1);

if(!extension_loaded('gd')) { die('GD ext is required to run this!'); }

const ASSET_FOLDER = 'public/assets';

/** @noinspection AutoloadingIssuesInspection */
class Spritesheet {
	private $type;
	private $iconFolder;

	private $updateLESS;

	private $fileList;
	private $less;

	public function __construct(string $type, bool $updateLESS = TRUE) {
		$this->type = $type;
		$this->updateLESS = $updateLESS;

		$this->iconFolder = ASSET_FOLDER."/img/{$type}_icons";

		$this->fileList = array_filter($this->getFileList(), function($str) {
			return substr($str, -3) === 'png';
		});
		$this->less     = '';
	}

	public function generate() : void {
		$this->generateSpritesheet();
		if($this->updateLESS) { $this->modifyIconLESS(); }
	}

	private function generateSpritesheet() : void {
		$width = (count($this->fileList) * (16 + /* padding */ 2)) - 2;

		$sheetImage = imagecreatetruecolor($width, 16);
		imagealphablending($sheetImage, FALSE);
		imagesavealpha($sheetImage, TRUE);

		imagefill($sheetImage,0,0,0x7fff0000);

		$x = 0;
		foreach ($this->fileList as $filename) {
			$iconImage = imagecreatefrompng("{$this->iconFolder}/{$filename}");
			imagealphablending($iconImage, TRUE);

			$dst_x = ((16 + 2) * $x);
			imagecopyresampled($sheetImage, $iconImage, $dst_x, 0, 0, 0, 16, 16, 16, 16);

			$this->generateLESS($filename, $dst_x);
			$x++;
		}

		imagepng($sheetImage, ASSET_FOLDER . "/img/{$this->type}s.png");
		say('Updated spritesheet!');
	}
	private function generateLESS(string $filename, int $dst_x) : void {
		$parts = pathinfo($filename);

		$this->less .= "\n".
			"	&.sprite-{$parts['filename']} {\n".
			"		.stitches-sprite(-{$dst_x}px);\n".
			"	}\n";
	}
	private function modifyIconLESS() : void {
		$newIconLESS = trim($this->less);

		$icons_file = ASSET_FOLDER.'/less/modules/icons.less';
		$oldLESS = file_get_contents($icons_file);
		if(preg_match('/\.sprite-'.$this->type.'.*\@cache-version: (\d+);/s', $oldLESS, $cvMatches)) {
			$cacheVersion = ((int) $cvMatches[1]) + 1;

			$newLESS = preg_replace('/\.sprite-'.$this->type.'.*/s', '',$oldLESS);
			$newLESS .= ''.
				".sprite-{$this->type} {\n".
				"	.sprite();\n".
				"	@cache-version: {$cacheVersion};\n".
				"	background: url('../../img/{$this->type}s.@{cache-version}.png') no-repeat;\n\n".
				"	{$newIconLESS}\n".
				"}\n";

			file_put_contents($icons_file, $newLESS);
			say('Updated LESS!');
		} else {
			die("Can't find cache-version?");
		}
	}

	private function getFileList() : array {
		return array_diff(scandir($this->iconFolder, SCANDIR_SORT_NONE), array('..', '.'));
	}
}

/** @noinspection PhpFunctionNamingConventionInspection */
function say(string $text = '') { print "{$text}\n"; }
