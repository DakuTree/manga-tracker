<?php

chdir(realpath(dirname(__FILE__."../", 2))); //Navigate to root DIR

function setup() {
	vendor_copy();
}

/**********************************************************************************************************************/

function vendor_copy() {
	$json = json_decode(file_get_contents('composer.json'), TRUE)['vendor-copy'];
	array_map('copy', array_keys($json), $json);
}
