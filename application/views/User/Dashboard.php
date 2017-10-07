<?php if($notice = $this->User->getLatestNotice()) { ?>
<div id="update-notice" class="alert alert-info" role="alert">
	<a href="#" class="close" data-dismiss="alert">&times;</a>
	<strong>Update (<?=$notice['date']?>):</strong> <?=$notice['text']?>
</div>
<?php } ?>

<?php if($has_inactive) { ?>
<div id="inactive-series" class="alert alert-warning" role="alert">
	Some of the series on your list have been marked as inactive and have not been updated due to either: no active users or having trouble updating.<br>
	These will be updated during the next update (Within next 4hrs~).<br>
	<br>
	If this continues to appear, please submit a bug report.

	<ul>
	<?php foreach($inactive_titles as $url => $title) { ?>
		<li><a href="<?=$url?>"><?=$title?></a></li>
	<?php } ?>
	</ul>
</div>
<?php } ?>

<div id="list-nav">
	<div class="list-header">
		<a href="<?=base_url('help')?>">Need help?</a>
	</div>

	<nav id="category-nav">
		<ul class="nav navbar-nav">
			<?php $first = key($trackerData); ?>
			<?php foreach($trackerData as $trackerDataTypeKey => $trackerDataType) { ?>
			<li <?=($first == $trackerDataTypeKey ? 'class="active"' : '')?>>
				<a href="#" data-list="<?=$trackerDataTypeKey?>"><?=$trackerDataType['name']?></a>
			</li>
			<?php } ?>
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
					<?php foreach($trackerData as $trackerDataTypeKey => $trackerDataType) { ?>
					<option value="<?=$trackerDataTypeKey?>"><?=$trackerDataType['name']?></option>
					<?php } ?>
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

<?php foreach($trackerData as $trackerDataTypeKey => $trackerDataType) { ?>
<table class="tablesorter-bootstrap tracker-table" data-list="<?=$trackerDataTypeKey?>" style="<?=($trackerDataTypeKey !== 'reading' ? 'display:none' : '')?>" data-unread="<?=$trackerDataType['unread_count']?>">
	<thead>
		<tr>
			<th class="header read"></th>
			<th class="header read">
				<div class="tablesorter-header-inner">
					Series<?=($trackerDataType['unread_count'] > 0 ? ' ('.$trackerDataType['unread_count'].' unread)' : '')?>
				</div>
			</th>
			<th class="header read">
				<div class="tablesorter-header-inner">
					My Status
				</div></th>
			<th class="header read">
				<div class="tablesorter-header-inner">
					Latest Release
				</div></th>
			<th data-sorter="false">
				<i class="fa fa-spinner fa-spin"></i>
			</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($trackerDataType['manga'] as $row) {
			$trInfo = '';
			if($row['site_data']['status'] == 'disabled') {
				$trInfo = 'class="bg-danger"';
			} else if($row['title_data']['status'] === 255) {
				$trInfo = 'class="bg-danger" title="This title is no longer being updated as it has been marked as deleted/ignored."';
			} else if($row['title_data']['failed_checks'] >= 5) {
				$trInfo = 'class="bg-danger" title="The last 5+ updates for this title have failed, as such it may not be completely up to date."';
			} else if($row['title_data']['failed_checks'] > 0) {
				$trInfo = 'class="bg-warning" title="The last update for this title failed, as such it may not be completely up to date."';
			}
		?>
		<tr data-id="<?=$row['id']?>" <?=$trInfo?>>
			<td>
				<span class="hidden"><?=$row['new_chapter_exists']?></span>
				<input type="checkbox" name="check">
			</td>
			<td>
				<i class="sprite-time <?=get_time_class($row['title_data']['last_updated'])?>" title="<?=$row['title_data']['last_updated']?>"></i>
				<i class="sprite-site sprite-<?=str_replace('.', '-', $row['site_data']['site'])?>" title="<?=$row['site_data']['site']?>"></i>
				<?=$row['mal_icon']?>

				<a href="<?=$row['full_title_url']?>" rel="nofollow" class="title" data-title="<?=htmlentities($row['title_data']['title_url'])?>"><?=htmlentities($row['title_data']['title'])?></a>

				<?php if($row['has_tags']) { ?>
				<small class="toggle-info pull-right text-muted">Less info</small>
				<div class="more-info has-tags">
				<?php } else { ?>
				<small class="toggle-info pull-right text-muted">More info</small>
				<div class="more-info">
				<?php } ?>
					<small>
						<a href="<?=base_url("history/{$row['title_data']['id']}")?>">History</a>
						|
						<a href="#" class="set-mal-id" data-mal-id="<?=$row['mal_id']?>" data-mal-type="<?=$row['mal_type']?>">Set MAL ID</a> <?php if(!is_null($row['mal_id']) && $row['mal_type'] == 'chapter') { ?><span>(<small><?=($row['mal_id'] !== '0' ? $row['mal_id'] : 'none')?></small>)</span><?php } ?>
						|
						Tags (<a href="#" class="edit-tags small">Edit</a>): <span class="text-lowercase tag-list"><?=($row['has_tags'] ? implode("", array_map(function ($str) { return "<i class='tag'>{$str}</i>"; }, explode(",", $row['tag_list']))) : "none")?></span>
						<div class="input-group hidden tag-edit">
							<input type="text" class="form-control" placeholder="tag1,tag2,tag3" maxlength="255" pattern='[a-z0-9-_,]{0,255}' value="<?=$row['tag_list']?>">
							<span class="input-group-btn">
								<button class="btn btn-default" type="button">Save</button>
							</span>
						</div><!-- /input-group -->
					</small>
				</div>
			</td>
			<td data-updated-at="<?=$row['last_updated']?>">
				<a class="chp-release current" href="<?=$row['generated_current_data']['url']?>" rel="nofollow"><?=htmlentities($row['generated_current_data']['number'])?></a>
				<?php if(!is_null($row['title_data']['ignore_chapter'])) { ?><span class='hidden-chapter' title='The latest chapter was marked as ignored.'><?=$row['generated_ignore_data']['number']?></span><?php } ?>
			</td>
			<td>
				<a class="chp-release latest" href="<?=$row['generated_latest_data']['url']?>" rel="nofollow" data-chapter="<?=$row['title_data']['latest_chapter']?>"><?=htmlentities($row['generated_latest_data']['number'])?></a>
			</td>
			<td>
				<?=($row['site_data']['status'] == 'disabled' ? '<i class="fa fa-exclamation-triangle" aria-hidden="true" style="color: red" title="This is not being tracked as tracking ('.$row['site_data']['site'].') is disabled"></i>' : '')?>

				<?php if($row['new_chapter_exists'] == '0') { ?>
				<span class="list-icon ignore-latest" title="Ignore latest chapter. Useful when latest chapter isn't actually the latest chapter.">
					<i class="fa fa-bell-slash" aria-hidden="true"></i>
				</span>
				<span class="list-icon update-read" title="I've read the latest chapter!">
					<i class="fa fa-refresh" aria-hidden="true"></i>
				</span>
				<!-- TODO: Chapter List? -->
				<?php } ?>
			</td>
		</tr>
		<?php } ?>
	</tbody>
</table>
<?php } ?>
<script>
	//noinspection JSAnnotator
	const use_live_countdown_timer = <?=$use_live_countdown_timer?>;
	//noinspection JSAnnotator
	const mal_sync = "<?=$mal_sync?>";

	const list_sort_type  = "<?=$list_sort_type_selected?>";
	const list_sort_order = "<?=$list_sort_order_selected?>";
</script>
