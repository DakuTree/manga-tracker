<?php declare(strict_types=1); defined('BASEPATH') or exit('No direct script access allowed');

//This entire function is stupid, but I liked my formatted code, and I'd rather not use HTML Tidy.
function form_dropdown_indented(array $options, int $indentCount = 0) : string {
	$dropdown = call_user_func_array('form_dropdown', $options); //FIXME: This seems...bad?

	$dropdown = preg_replace("/^(<[\\/]?select.*)/m",  str_repeat("\t", $indentCount)."$1"  , $dropdown);
	$dropdown = preg_replace("/^(<option.*)/m",       str_repeat("\t", $indentCount+1)."$1", $dropdown);

	return trim($dropdown)."\n";
}
