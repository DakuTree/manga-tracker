<!DOCTYPE html>
<html lang='en'>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content='width=device-width, initial-scale=1' />

	<title>Manga Tracker - <?=$title?></title>
	<meta name="description" content="A site for tracking manga across multiple sites.">
	<meta name="author" content="https://github.com/DakuTree/manga-tracker/graphs/contributors" />
	<link type="text/plain" rel="author" href="<?=base_url('humans.txt')?>" />

	<link rel="shortcut icon" href="<?=base_url('favicon.ico')?>">

	<link rel="stylesheet" href="<?=asset_url()?>vendor/css/normalize.css">
	<link rel="stylesheet" href="<?=asset_url()?>vendor/css/boilerplate.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous"><link rel="stylesheet" href="<?=asset_url()?>vendor/css/bootstrap-theme.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous"><link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.26.6/css/theme.bootstrap.min.css">
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.4/cookieconsent.min.css" />

	<link rel="stylesheet" href="<?=$complied_css_path?>">

	<!--[if lt IE 9]>
		<script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
		<script>window.html5 || document.write('<script src="<?=asset_url()?>vendor/js/html5shiv.js"><\/script>')</script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<script>window.respond || document.write('<script src="<?=asset_url()?>vendor/js/respond.min.js"><\/script>')</script>
	<![endif]-->
</head>

<body>

<header id="site-header">
	<!-- Fixed navbar -->
	<nav class="navbar navbar-default navbar-fixed-top">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="<?=base_url()?>">Manga Tracker</a>
			</div>
			<div id="navbar" class="navbar-collapse collapse">
				<ul class="nav navbar-nav navbar-right" id="header-nav-user">
					<?php if(!$this->User->logged_in()) { ?>
					<li><a href="<?=base_url('user/login')?>">Login</a></li>
					<li><a href="<?=base_url('user/signup')?>">Register</a></li>
					<?php } else { ?>
					<li class="dropdown">
						<img src="<?=$this->User->get_gravatar_url()?>" class="profile-image" />

						<a href="<?=base_url('/')?>">
							<?=$username?>
							<span class="caret"></span>
						</a>

						<ul class="dropdown-menu">
							<li><a href="<?=base_url('user/favourites')?>">Favourites</a></li>
							<li class="divider"></li>
							<li><a href="<?=base_url('user/history')?>">History</a></li>
							<li class="divider"></li>
							<li><a href="<?=base_url('user/options')?>">Options</a></li>
							<li class="divider"></li>
							<li><a href="<?=base_url('user/logout')?>">Logout</a></li>
						</ul>
					</li>
					<?php } ?>
				</ul>
			</div><!--/.nav-collapse -->
		</div>
	</nav>
</header>
<!--------------------------------------------------------------------------------------------------------------------->

<div id="page-wrap">
	<div id="page-holder">
		<div id="page" data-page="<?=$page?>">
