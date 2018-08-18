<?php

(PHP_SAPI !== 'cli' || isset($_SERVER['HTTP_USER_AGENT'])) && die('CLI only.');

chdir(dirname(__FILE__, 2)); //Just to make things easier, change dir to project root.

$userscriptProd = file_get_contents('./public/userscripts/manga-tracker.user.js');

// Replace where needed.
$userscriptProd = str_replace('https://trackr.moe/userscripts','http://manga-tracker.localhost:20180/userscripts', $userscriptProd);

// Push to dev file.
file_put_contents('./public/userscripts/manga-tracker.dev.user.js', $userscriptProd);
