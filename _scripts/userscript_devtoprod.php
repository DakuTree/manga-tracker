<?php

(PHP_SAPI !== 'cli' || isset($_SERVER['HTTP_USER_AGENT'])) && die('CLI only.');

chdir(dirname(__FILE__, 2)); //Just to make things easier, change dir to project root.

$userscriptDev = file_get_contents('./public/userscripts/manga-tracker.dev.user.js');

// Replace where needed.
$userscriptDev = str_replace('http://manga-tracker.localhost:20180/userscripts', 'https://trackr.moe/userscripts', $userscriptDev);

// Push to prod file.
file_put_contents('./public/userscripts/manga-tracker.user.js', $userscriptDev);
