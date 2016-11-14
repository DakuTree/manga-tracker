<!--<div class="alert alert-info" role="alert">-->
<!--	<strong>Update (2016/11/14):</strong> Added support for Sea Otter Scans reader (<a href="http://reader.seaotterscans.com">http://reader.seaotterscans.com</a>)-->
<!--</div>-->

<?php if($has_inactive) { ?>
<div class="alert alert-warning" role="alert">
	Some of the series in your list have been marked as inactive and not updated due to having no active users tracking it.<br>
	These will be updated during the next update.
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
				Next update in: <span id="update-timer"><?=$this->Tracker->getNextUpdateTime()?></span>
				<i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="Each series is updated at different times, but only once every 14 hours.<br>This is to avoid bombarding the sites with 100s of requests all at once."></i>
			</div>
		</div>
	</nav>

	<div id="tracker-table-links">
		<div class="pull-left">
			<a href="#" id="delete_selected">
				<i class="fa fa-trash-o" aria-hidden="true"></i> Delete Selected
			</a>

			<div id="move-container">
				<label for="move-input" class="control-label">Move to:</label>
				<select id="move-input">
					<option>---</option>
					<?php foreach($trackerData as $trackerDataTypeKey => $trackerDataType) { ?>
					<option value="<?=$trackerDataTypeKey?>"><?=$trackerDataType['name']?></option>
					<?php } ?>
				</select>
			</div>
		</div>
		<div class="pull-right">
			<span id="import-status"></span>
			<!-- NOTE: We would use the download attr here, but it can cause issues if the user logs out -->
			<a href="<?=base_url('export_list')?>">Export List</a> |
			<div>
				<div>
					<label for="file_import"><span>Import List</span></label>
					<input type="file" name="file_import" id="file_import" class="form-control" accept=".json">
				</div>
			</div>
		</div>
	</div>
</div>

<?php foreach($trackerData as $trackerDataTypeKey => $trackerDataType) { ?>
<table class="tablesorter tablesorter-bootstrap table-striped tracker-table" data-list="<?=$trackerDataTypeKey?>" style="<?=($trackerDataTypeKey !== 'reading' ? 'display:none' : '')?>">
	<thead>
		<tr>
			<th class="header read headerSortDown"></th>
			<th class="header read"><div class="tablesorter-header-inner">Series<?=($trackerDataType['unread_count'] > 0 ? ' ('.$trackerDataType['unread_count'].' unread)' : '')?></div></th>
			<th class="header read"><div class="tablesorter-header-inner">My Status</div></th>
			<th class="header read"><div class="tablesorter-header-inner">Latest Release</div></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($trackerDataType['manga'] as $row) { ?>
		<tr data-id="<?=$row['id']?>" <?=($row['site_data']['status'] == 'disabled' ? 'class="bg-danger"' : '')?>>
			<td>
				<span class="hidden"><?=$row['new_chapter_exists']?> - <?=htmlentities($row['title_data']['title'])?></span>
				<input type="checkbox" name="check">
			</td>
			<td>
				<i class="sprite-time <?=get_time_class($row['title_data']['last_updated'])?>" title="<?=$row['title_data']['last_updated']?>"></i>
				<i class="sprite-site sprite-<?=str_replace('.', '-', $row['site_data']['site'])?>" title="<?=$row['site_data']['site']?>"></i>
				<a href="<?=$row['full_title_url']?>" rel="nofollow"><?=htmlentities($row['title_data']['title'])?></a>

				<?php if($row['has_tags']) { ?>
				<small class="more-info pull-right text-muted">Less info</small>
				<div class="tags" style="display: block">
				<?php } else { ?>
				<small class="more-info pull-right text-muted">More info</small>
				<div class="tags">
				<?php } ?>
					<small>
						<a href="<?=base_url("history/{$row['title_data']['id']}")?>">History</a>
						|
						<a href="#" class="edit-tags">Edit</a>
						|
						Tags: <em class="text-lowercase tag-list"><?=($row['has_tags'] ? $row['tag_list'] : "none")?></em>
						<div class="input-group hidden tag-edit">
							<input type="text" class="form-control" placeholder="tag1,tag2,tag3" maxlength="255" pattern='[a-z0-9-_,]{0,255}' value="<?=$row['tag_list']?>">
							<span class="input-group-btn">
								<button class="btn btn-default" type="button">Save</button>
							</span>
						</div><!-- /input-group -->
					</small>
				</div>
			</td>
			<td>
				<a class="chp-release current" href="<?=$row['generated_current_data']['url']?>" rel="nofollow"><?=htmlentities($row['generated_current_data']['number'])?></a>
			</td>
			<td>
				<a class="chp-release latest" href="<?=$row['generated_latest_data']['url']?>" rel="nofollow" data-chapter="<?=$row['title_data']['latest_chapter']?>"><?=htmlentities($row['generated_latest_data']['number'])?></a>
			</td>
			<td>
				<!--<i class="fa fa-exclamation-triangle" aria-hidden="true"></i>-->
				<?=($row['site_data']['status'] == 'disabled' ? '<i class="fa fa-exclamation-triangle" aria-hidden="true" style="color: red" title="This is not being tracked as tracking ('.$row['site_data']['site'].') is disabled"></i>' : '')?>
				<?php if($row['new_chapter_exists'] == '0') { ?>
				<span class="update-read" title="I've read the latest chapter!">
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
	var use_live_countdown_timer = <?=$use_live_countdown_timer?>;
</script>
