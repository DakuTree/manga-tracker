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
	<meta name="robots" content="noindex">

	<link rel="stylesheet" href="<?=asset_url()?>vendor/css/normalize.css">
	<link rel="stylesheet" href="<?=asset_url()?>vendor/css/boilerplate.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
	<link rel="stylesheet" href="<?=asset_url()?>vendor/css/bootstrap-theme.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.26.6/css/theme.bootstrap.min.css">

	<link rel="stylesheet" href="<?=asset_url()?>css/main.css">

	<!--[if lt IE 9]>
		<script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
		<script>window.html5 || document.write('<script src="<?=asset_url()?>vendor/js/html5shiv.js"><\/script>')</script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>

<body>

<header id="site-header">
	<?php if(ENVIRONMENT == 'development') { ?>
	<div class="sticky-alert" style="line-height: 40px;">
		This is a development server.
		<br />
		Next update in: <?=$update_time?><!--<i class="fa fa-question-circle" aria-hidden="true"></i>-->
	</div>
	<?php } else if(ENVIRONMENT == 'testing') { ?>
	<div class="sticky-alert">
		This is a live test server.<br/>
		It is wiped + auto-updated on every successful build.
	</div>
	<?php } ?>

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
					<!--TODO: This should be generated depending on if user is logged in or not-->
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
