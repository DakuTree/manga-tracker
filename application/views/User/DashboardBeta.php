<?php if($notice = $this->User->getLatestNotice()) { ?>
	<div id="update-notice" class="alert alert-info" role="alert">
		<a href="#" class="close" data-dismiss="alert">&times;</a>
		<strong>Update (<?=$notice['date']?>)</strong>
		<?=$notice['text']?>
	</div>
<?php } ?>

<div id="inactive-container" class="alert alert-warning" hidden role="alert">
	<p>
		Some of the series on your list have been marked as inactive and have not been updated due to either: no active users or having trouble updating.<br>
		These <i>should</i> be updated during the next update (Within the next 4 hours or so).
	</p>
	<p>If this continues to appear after 4 hours, please submit an issue report.</p>
	<!-- TODO: We should link to the bug-report with some pre-filled info if possible -->

	<p><button id="inactive-display" type="button" class="btn btn-info">Display inactive series</button></p>
	<div id="inactive-list-container" hidden>
		<p>Inactive series:</p>
		<ul></ul>
	</div>
</div>

<div id="list-nav">
	<div class="list-header">
		<a href="<?=base_url('help')?>">Need help?</a>
	</div>

	<nav id="list-nav-category">
		<ul class="nav navbar-nav flex-row pull-left">
			<li class="active"><a href="#" data-list="reading">Reading</a></li>
			<li><a href="#" data-list="on-hold">On-Hold</a></li>
			<li><a href="#" data-list="plan-to-read">Plan to Read</a></li>
		</ul>
		<div class="pull-right">
			<div id="update-timer-container">
				Next update in: <a id="update-timer" href="<?=base_url('update_status')?>"><?=$this->Tracker->admin->getNextUpdateTime()?></a>
				<i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="left" title="Each series is updated at different times, but only once every 14 hours.<br>This is to avoid bombarding the sites with 100s of requests all at once.<br>MangaFox, Batoto and FoolSlide sites are exceptions to this and are updated hourly as they use a different (and more efficent) update method."></i>
			</div>
		</div>
	</nav>

	<div id="tracker-table-links" class="nav-row">
		<div class="pull-left">
			<div id="mass-action">
				<b>Modify selected: </b>
				<select>
					<option value="n/a">---</option>
					<option value="delete">Delete</option>
					<option value="tag">Tag</option>
				</select>
			</div>

			<div id="move-container">
				<label for="move-input" class="control-label">Move to:</label>
				<select id="move-input">
					<option>---</option>
					<option value="reading">Reading</option>
					<option value="on-hold">On-Hold</option>
					<option value="plan-to-read">Plan to Read</option>
				</select>
			</div>

			<div id="search-container">
				<div class="btn-group" style="vertical-align: baseline">
					<input type="text" class="form-control" aria-label="Search" title="Search. This checks both titles and tags." placeholder="Search" id="search" name="search" data-column="1" required>
					<span class="clear-text-input glyphicon glyphicon-remove-circle" data-clear-id="search"></span>
				</div>
			</div>
		</div>
		<div class="pull-right">
			<a href="#" id="toggle-nav-options"><i class="fa fa-cog"></i></a>
		</div>
	</div>

	<div id="nav-options" class="nav-row">
		<div class="pull-left">
			<?=form_label('List sort: ', 'list_sort_type')?>
			<?=form_dropdown('list_sort_type', ['n/a' => '----------'] + $list_sort_type, $list_sort_type_selected, ['class' => 'list_sort'])?>
			<?=form_dropdown('list_sort_order', $list_sort_order, $list_sort_order_selected, ['class' => 'list_sort'])?>
		</div>
		<div class="pull-right">
			<!-- NOTE: We would use the download attr here, but it can cause issues if the user logs out -->
			<a href="<?=base_url('export_list')?>">Export List</a>
		</div>
	</div>
</div>

<div id="list-container"></div>

<script>
	//noinspection JSAnnotator
	const use_live_countdown_timer = <?=$use_live_countdown_timer?>;
	//noinspection JSAnnotator
	const mal_sync = "<?=$mal_sync?>";

	const list_sort_type  = "<?=$list_sort_type_selected?>";
	const list_sort_order = "<?=$list_sort_order_selected?>";


	//noinspection JSAnnotator
	const site_aliases = <?=$siteAliases?>;
</script>
