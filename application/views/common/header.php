<!DOCTYPE html>
<html lang='en'>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<title><?=$title?></title>
	<meta name="description" content="A site for tracking manga across multiple sites.">
	<meta name="author" content="https://github.com/DakuTree/manga-tracker/graphs/contributors" />
	<link type="text/plain" rel="author" href="<?=base_url('humans.txt')?>" />

	<link rel="shortcut icon" href="<?=base_url('favicon.ico')?>">

	<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.30.5/css/theme.bootstrap_4.min.css" crossorigin="anonymous" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.4/cookieconsent.min.css" />

	<?php if(ENVIRONMENT === 'production') { ?>
	<link rel="stylesheet" href="<?=$compiled_css_path()?>">
	<?php } else { ?>
	<link rel="stylesheet/less" href="<?=asset_url()?>less/main.less" type="text/css">
	<script>less = {env: 'development', relativeUrls: true, modifyVars: {themeLocation: 'common\\themes\\<?=$theme?>'}};</script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/less.js/2.7.2/less.min.js"></script>
	<style>
		/* Dynamic icon generation */
		.sprite-site, .sprite-time { background-position: initial !important; }
		<?php foreach(['site', 'time'] as $iconType) {
			$iconFileList = array_map('pathinfo', array_diff(scandir(APPPATH."../public/assets/img/{$iconType}_icons", SCANDIR_SORT_NONE), array('..', '.')));

			foreach($iconFileList as $iconFile) {
				if($iconFile['extension'] === 'png') { ?>
		.sprite-<?=$iconFile['filename']?> {
			background: url(<?=img_url().$iconType?>_icons/<?=$iconFile['basename']?>) !important;
		}
				<?php }
			}
		} ?>
	</style>
	<?php } ?>
</head>

<body>

<?php if($show_header) { ?>
<header id="site-header" class="navbar navbar-expand-sm navbar-light bg-faded">
	<a class="navbar-brand" href="<?=base_url()?>">Manga Tracker<?=(ENVIRONMENT === 'development' ? ' (DEV)' : '')?></a>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
	<div id="navbarNavDropdown" class="navbar-collapse collapse">
		<ul class="navbar-nav ml-auto">
			<?php if(!$this->ion_auth->logged_in()) { ?>
			<li class="nav-item"><a class="nav-link" href="<?=base_url('user/login')?>">Login</a></li>
			<!--<li class="nav-item"><a class="nav-link" href="<?=base_url('user/signup')?>">Register</a></li>-->
			<?php } else { ?>
			<li class="nav-item dropdown p-0">
				<a class="nav-link dropdown-toggle px-1 py-0" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<img src="<?=$this->User->getGravatarURL()?>" class="profile-image" />
					<?=$username?>
				</a>
				<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
					<?php if($this->ion_auth->is_admin()) { ?>
						<a class="dropdown-item" href="<?=base_url('admin_panel')?>">Admin Panel</a>
						<div class="dropdown-divider"></div>
					<?php } ?>
					<a class="dropdown-item" href="<?=base_url('user/favourites')?>">Favourites</a>
					<a class="dropdown-item" href="<?=base_url('user/history')?>">History</a>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item" href="<?=base_url('user/options')?>">Options</a>
					<a class="dropdown-item" href="<?=base_url('user/logout')?>">Logout</a>
				</div>
			</li>
			<?php } ?>
		</ul>
	</div>
</header>
<?php } ?>
<!--------------------------------------------------------------------------------------------------------------------->

<main id="page-wrap" class="m-2">
	<div id="page-holder">
		<div id="page" data-page="<?=$page?>">
			<?php if($notices) { ?>
			<div id="notices">
				<?=$notices?>
			</div>
			<?php } ?>

			<div class="row">
				<div class="col-md-12">
					<div id="update-notice" class="alert alert-warning py-1" role="alert">
						<strong>Notice of Site Closure</strong> : As of 2019/07/02, trackr.moe has shut its doors. More info can be found <a href='https://blog.codeanimu.net/posts/2019/07/trackr-moe-the-future/'>here</a>.
					</div>
				</div>
			</div>
