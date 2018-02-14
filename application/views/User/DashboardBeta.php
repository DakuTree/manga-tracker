<div id="inactive-container" class="alert alert-warning" hidden role="alert">
	<p>
		Some of the series on your list have been marked as inactive and have not been updated due to either: no active users or having trouble updating.<br>
		These <i>should</i> be updated during the next update (Within the next 4 hours or so).
	</p>
	<p>If this continues to appear after 4 hours, please submit a bug report.</p>
	<!-- TODO: We should link to the bug-report with some pre-filled info if possible -->

	<p><button id="inactive-display" type="button" class="btn btn-info">Display inactive series</button></p>
	<div id="inactive-list-container" hidden>
		<p>Inactive series:</p>
		<ul></ul>
	</div>
</div>

<div id="list-container">
	<header>
		<div class="pull-right"><a href="<?=base_url('help')?>">Need help?</a></div>

		<nav id="list-nav-category" class="clear">
			<ul class="nav navbar-nav">
				<li class="active">
					<a href="#" data-list="reading">Reading</a>
				</li>
				<li>
					<a href="#" data-list="on-hold">On-Hold</a>
				</li>
				<li>
					<a href="#" data-list="plan-to-read">Plan to Read</a>
				</li>
			</ul>
			<div class="pull-right">
				<div id="update-timer-container">
					Next update in: <a id="update-timer" href="<?=base_url('update_status')?>"><?=$this->Tracker->admin->getNextUpdateTime()?></a>
					<i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="left" title="Each series is updated at different times, but only once every 14 hours.<br>This is to avoid bombarding the sites with 100s of requests all at once.<br>Sites that use FoolSlide, as well as various popular aggregators updated hourly as they use a different (and more efficient) update method."></i>
				</div>
			</div>
		</nav>

		<div id="tracker-table-links" class="nav-row"></div>
	</header>
</div>

<script>
	// noinspection JSAnnotator
	const site_aliases = <?=$siteAliases?>;
</script>
