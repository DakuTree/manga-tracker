			<div class="push"></div>
		</div>
	</div>
</div>

<!--------------------------------------------------------------------------------------------------------------------->

<footer id="site-footer">
	<div class="container">
		<div class="pull-left">
			<p class="text-muted" id="footer-left">
				<span class="footer-copyright">©2016 trackr.moe</span>
				<span class="footer-debug"><?="Page rendered in <strong>{elapsed_time}</strong> seconds and used {memory_usage} of RAM. CodeIgniter Version <strong>".CI_VERSION."</strong>."?></span>
			</p>
		</div>
		<div class="pull-right">
			<ul class="list-inline">
				<li><a href="<?=base_url('report_bug')?>">Report a Bug</a></li>
				<li><a href="<?=base_url('CHANGELOG.md')?>">Changelog</a></li>
				<li><a href="<?=base_url('about')?>">About</a></li>
			</ul>
		</div>
	</div>
</footer>

<!--------------------------------------------------------------------------------------------------------------------->

	<span id="fb-check" class="fa sr-only" style="display: none">&nbsp;</span>
	<!-- JAVASCRIPT BELOW -->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="<?=asset_url()?>vendor/js/jquery-1.12.4.min.js"><\/script>')</script>

	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.15.0/jquery.validate.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.15.0/additional-methods.min.js"></script>
	<script>$().validate || document.write('<script src="<?=asset_url()?>vendor/js/jquery.validate-1.15.0.min.js"><\/script>')</script>
	<script>$().validate || document.write('<script src="<?=asset_url()?>vendor/js/jquery.validate.additional-methods-1.15.0.min.js"><\/script>')</script>

	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
	<script>$.fn.modal || document.write('<script src="<?=asset_url()?>vendor/js/bootstrap.min.js"><\/script>')</script>
	<script>
		$(document).ready(function() {
			var check = $('#fb-check');
			if(check.css('width') !== '1px' && check.css('height') !== '1px') {
				console.log(bodyColor);
				$("head").prepend('<link rel="stylesheet" href="<?=asset_url()?>vendor/css/bootstrap.min.css">');
			}
		});
	</script>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.26.6/js/jquery.tablesorter.combined.min.js"></script>

	<script>
		$(document).ready(function() {
			var check = $('#fb-check');
			if (check.css('fontFamily') != 'FontAwesome') {
				$("head").prepend('<link rel="stylesheet" href="<?=asset_url()?>vendor/css/font-awesome.min.css">');
			}
		});
	</script>

	<!-- Begin Cookie Consent plugin by Silktide - http://silktide.com/cookieconsent -->
	<script type="text/javascript">
		window.cookieconsent_options = {
			"message"   : "This website uses cookies to ensure you get the best experience on our website",
			"dismiss"   : "Got it!",
			"learnMore" : "More info",
			"link"      : "<?=base_url('about/terms#cookie-policy')?>",
			"theme"     : "dark-bottom"
		};
	</script>
	<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/1.0.9/cookieconsent.min.js"></script>
	<!-- End Cookie Consent plugin -->

	<script>
		var base_url = "<?=base_url()?>";
		var page     = "<?=$page?>";
	</script>
	<script src="<?=asset_url()?>js/compiled.min.js"></script>

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
		ga('create', '<?=$analytics_tracking_id?>', 'auto', 't0', {userId: <?=$this->User->id?>}); //TODO: Make sure subdomains are supported/seperate
		ga('send', 'pageview');
	</script>
	<?php } ?>
</body>
<html>
