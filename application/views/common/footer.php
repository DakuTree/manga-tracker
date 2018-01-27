			<div class="push"></div>
		</div>
	</div>
</div>

<!--------------------------------------------------------------------------------------------------------------------->

<?php if($show_header) { ?>
<footer id="site-footer">
	<div class="container">
		<div class="pull-left">
			<p class="text-muted" id="footer-left">
				<span class="footer-copyright">©2018 trackr.moe</span>
				<span class="footer-debug"><?="Page rendered in <strong>{elapsed_time}</strong> seconds and used {memory_usage} of RAM. CodeIgniter Version <strong>".CI_VERSION."</strong>."?></span>
			</p>
		</div>
		<div class="pull-right">
			<ul class="list-inline">
				<li><a href="https://github.com/DakuTree/manga-tracker"><i class="fa fa-github-alt" aria-hidden="true"></i> Github</a></li>
				<li><a href="https://twitter.com/trackr_dev"><i class="fa fa-twitter" aria-hidden="true"></i> Twitter</a></li>
				<li><a href="<?=base_url('report_issue')?>">Report an Issue</a></li>
				<li><a href="<?=base_url('stats')?>">Site Stats</a></li>
				<li><a href="https://github.com/DakuTree/manga-tracker/wiki/Changelog">Changelog</a></li>
				<li><a href="<?=base_url('about/terms')?>">Terms</a></li>
				<li><a href="<?=base_url('about')?>">About</a></li>
			</ul>
		</div>
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

	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script><script>$.fn.modal || document.write('<script src="<?=asset_url()?>vendor/js/bootstrap.min.js"><\/script>')</script>
	<script>
		$(document).ready(function() {
			let check = $('#fb-check');
			if(check.css('width') !== '1px' && check.css('height') !== '1px') {
				$("head").prepend('<link rel="stylesheet" href="<?=asset_url()?>vendor/css/bootstrap.min.css">');
			}
		});
	</script>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.29.4/js/jquery.tablesorter.combined.min.js"></script>
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
