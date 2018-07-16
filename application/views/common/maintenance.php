<?php
header('HTTP/1.1 503 Service Temporarily Unavailable');
header('Status: 503 Service Temporarily Unavailable');
header('Retry-After: 300');
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>Maintenance - Manga Tracker</title>
	<meta name="description" content="A site for tracking manga across multiple sites.">
	<meta name="author" content="https://github.com/DakuTree/manga-tracker/graphs/contributors" />

	<style>
		body { text-align: center; padding: 150px; }
		h1 { font-size: 50px; }
		body { font: 20px Helvetica, sans-serif; color: #333; }
		article { display: block; text-align: left; width: 650px; margin: 0 auto; }
		a { color: #dc8100; text-decoration: none; }
		a:hover { color: #333; text-decoration: none; }
	</style>
</head>

<body>
	<!--https://gist.github.com/pitch-gist/2999707-->
	<article>
		<h1>We&rsquo;ll be back soon!</h1>
		<div>
			<p>
				Sorry for the inconvenience but we&rsquo;re performing some maintenance at the moment. If you need to you can always <a href="mailto:admin@trackr.moe">contact us</a>, otherwise we&rsquo;ll be back online shortly!
				<br/>
				You may also be able to find more information on when the maintenance will end on our Twitter at <a href="https://twitter.com/trackr_dev">@trackr_dev</a>.
			</p>

		</div>
	</article>
</body>
</html>
