			<div class="push"></div>
		</div>
	</div>
</main>

<!--------------------------------------------------------------------------------------------------------------------->

<?php if($show_header) { ?>
<footer id="site-footer" class="navbar navbar-expand-md navbar-light bg-faded">
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdownFooter" aria-controls="navbarNavDropdownFooter" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
	<div id="navbarNavDropdownFooter" class="navbar-collapse collapse">
		<ul class="navbar-nav mr-auto">
			<li id="footer-left" class="nav-item text-muted">
				<span class="footer-copyright">©2018 trackr.moe</span>
				<span class="footer-debug"><?="Page rendered in <strong>{elapsed_time}</strong> seconds and used {memory_usage} of RAM. CodeIgniter Version <strong>".CI_VERSION."</strong>."?></span>
			</li>
		</ul>
		<ul class="navbar-nav">
			<li class="nav-item"><a class="nav-link" href="https://github.com/DakuTree/manga-tracker" target="_blank"><i class="fa fa-github-alt" aria-hidden="true"></i> Github</a></li>
			<li class="nav-item"><a class="nav-link" href="https://twitter.com/trackr_dev" target="_blank"><i class="fa fa-twitter" aria-hidden="true"></i> Twitter</a></li>
			<li class="nav-item"><a class="nav-link" href="<?=base_url('report_issue')?>">Report an Issue</a></li>
			<li class="nav-item"><a class="nav-link" href="<?=base_url('stats')?>">Site Stats</a></li>
			<li class="nav-item"><a class="nav-link" href="https://github.com/DakuTree/manga-tracker/wiki/Changelog" target="_blank">Changelog</a></li>
			<li class="nav-item"><a class="nav-link" href="<?=base_url('about/terms')?>">Terms</a></li>
			<li class="nav-item"><a class="nav-link" href="<?=base_url('about')?>">About</a></li>
		</ul>
	</div>
</footer>
<?php } ?>

<!--------------------------------------------------------------------------------------------------------------------->

	<span id="fb-check" class="fa sr-only cc-grower" style="display: none">&nbsp;</span>
	<!-- JAVASCRIPT BELOW -->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="<?=asset_url()?>vendor/js/jquery-3.3.1.min.js"><\/script>')</script>

	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/additional-methods.min.js"></script>
	<script>$().validate || document.write('<script src="<?=asset_url()?>vendor/js/jquery.validate-1.17.0.min.js"><\/script>')</script>
	<script>$().validate || document.write('<script src="<?=asset_url()?>vendor/js/jquery.validate.additional-methods-1.17.0.min.js"><\/script>')</script>

	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.bundle.min.js" integrity="sha384-feJI7QwhOS+hwpX2zkaeJQjeiwlhOP+SdQDqhgvvo1DsjtiSQByFdThsxO669S2D" crossorigin="anonymous"></script>
	<script>if(typeof($.fn.modal) === 'undefined') {document.write('<script src="<?=asset_url()?>vendor/css/bootstrap.bundle.min.js"><\/script>')}</script>

	<script>
	$(document).ready(function() {
		let check = $('#fb-check');
		if(check.css('width') !== '1px' && check.css('height') !== '1px') {
			$("head").prepend('<link rel="stylesheet" href="<?=asset_url()?>vendor/css/bootstrap.min.css">');
		}
	});
	</script>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.29.5/js/jquery.tablesorter.combined.min.js"></script>
	<script>$.fn.tablesorter  || document.write('<script src="<?=asset_url()?>vendor/js/jquery.tablesorter.combined.min.js"><\/script>')</script>

	<script>
		$(document).ready(function() {
			let check = $('#fb-check');
			if (check.css('fontFamily') !== 'FontAwesome') {
				$("head").prepend('<link rel="stylesheet" href="<?=asset_url()?>vendor/css/font-awesome.min.css">');
			}
		});
	</script>

	<script src="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.4/cookieconsent.min.js"></script>
	<script>window.cookieconsent || document.write('<script src="<?=asset_url()?>vendor/js/cookieconsent.min.js"><\/script>')</script>
	<script>
		$(document).ready(function() {
			let check = $('#fb-check');
			if(check.css('transition') !== 'max-height 1s ease 0s') {
				$("head").prepend('<link rel="stylesheet" href="<?=asset_url()?>vendor/css/cookieconsent.min.css">');
			}
		});
	</script>
	<script>window.cookieconsent.initialise({"palette":{"popup":{"background":"#edeff5","text":"#838391"},"button":{"background":"transparent","text":"#4b81e8","border":"#4b81e8"}}});</script>

	<script>
		const base_url = "<?=base_url()?>";
		const page     = "<?=$page?>";
	</script>
	<?php if(ENVIRONMENT == 'production') { ?>
	<script src="<?=$complied_js_path?>"></script>
	<?php } else { ?>
	<script src="<?=js_url()?>main.js"></script>
	<?php foreach(array_slice(scandir(APPPATH.'../public/assets/js/pages/'), 2) as $filename) { ?>
	<script src="<?=js_url()?>pages/<?=$filename?>"></script>
	<?php } ?>
	<?php } ?>

	<?php if(ENVIRONMENT == 'production') { ?>
	<script>
		(function (b, o, i, l, e, r) {
			b.GoogleAnalyticsObject = l;
			b[l] || (b[l] = function () {(b[l].q = b[l].q || []).push(arguments)});
			b[l].l = +new Date;
			e = o.createElement(i);
			r = o.getElementsByTagName(i)[0];
			e.src = '//www.google-analytics.com/analytics.js';
			r.parentNode.insertBefore(e, r)
		}(window, document, 'script', 'ga'));
		ga('create', '<?=$analytics_tracking_id?>', 'none');
		ga('send', 'pageview');
	</script>
	<?php } ?>
</body>
<html>
