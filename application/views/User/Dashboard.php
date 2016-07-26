<div>
	<nav id="category-nav">
		<ul class="nav navbar-nav">
			<?php $first = key($trackerData); ?>
			<?php foreach($trackerData as $trackerDataTypeKey => $trackerDataType) { ?>
			<li <?=($first == $trackerDataTypeKey ? 'class="active"' : '')?>>
				<a href="#" data-list="<?=$trackerDataTypeKey?>"><?=$trackerDataType['name']?></a>
			</li>
			<?php } ?>
		</ul>
	</nav>

	<div id="tracker-table-links">
		<div class="pull-left">
			<a href="#" type="submit" id="delete_selected">
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
			<!-- FIXME: We would use the download attr here, but it can cause issues if the user logs out -->
			<a href="./export_list">Export List</a> |
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
		<tr data-id="<?=$row['id']?>">
			<td>
				<span class="hidden"><?=$row['new_chapter_exists']?> - <?=$row['title_data']['title']?></span>
				<input type="checkbox" name="check">
			</td>
			<td>
				<img src="<?=img_url()."time_icons/".get_time_icon($row['title_data']['last_updated'])?>" title="<?=$row['title_data']['last_updated']?>"/>
				<img src="<?=img_url()."site_icons/{$row['site_data']['site']}.ico"?>" />
				<a href="<?=$row['full_title_url']?>" rel="nofollow"><?=$row['title_data']['title']?></a>

				<?php if($row['has_tags']) { ?>
				<small class="more-info pull-right text-muted">Less info</small>
				<div class="tags" style="display: block">
				<?php } else { ?>
				<small class="more-info pull-right text-muted">More info</small>
				<div class="tags">
				<?php } ?>
					<small>
						<a href="#" class="edit-tags">Edit</a>
						|
						Tags: <em class="text-lowercase tag-list"><?=($row['has_tags'] ? $row['tag_list'] : "none")?></em>
						<div class="input-group hidden tag-edit">
							<input type="text" class="form-control" placeholder="tag1,tag2,tag3" maxlength="255" value="<?=$row['tag_list']?>">
							<span class="input-group-btn">
								<button class="btn btn-default" type="button">Save</button>
							</span>
						</div><!-- /input-group -->
					</small>
				</div>
			</td>
			<td>
				<a class="chp-release current" href="<?=$row['generated_current_data']['url']?>" rel="nofollow"><?=$row['generated_current_data']['number']?></a>
			</td>
			<td>
				<a class="chp-release latest" href="<?=$row['generated_latest_data']['url']?>" rel="nofollow"><?=$row['generated_latest_data']['number']?></a>
			</td>
			<td>
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
