	<style>
		.tracker-table tr td:nth-of-type(2), .tracker-table tr th:nth-of-type(2) {
			padding-left: 10px;
		}
		#page-wrap, body {
			margin-top: 0 !important;
		}
		.tracker-table {
			margin-top: 29px !important;
		}
		#page[data-page="dashboard"] #list-nav.fixed-header {
			top: 0px !important;
		}
		#page-wrap #page-holder #page {
			margin-bottom: 0 !important;
		}
	</style>
	<div id="list-nav">
		<nav id="category-nav">
			<ul class="nav navbar-nav flex-row pull-left">
				<?php $first = key($trackerData); ?>
				<?php foreach($trackerData as $trackerDataTypeKey => $trackerDataType) { ?>
					<li <?=($first == $trackerDataTypeKey ? 'class="active"' : '')?>>
						<a href="#" data-list="<?=$trackerDataTypeKey?>"><?=$trackerDataType['name']?></a>
					</li>
				<?php } ?>
			</ul>
		</nav>
	</div>

<?php foreach($trackerData as $trackerDataTypeKey => $trackerDataType) { ?>
	<table class="tablesorter tablesorter-bootstrap table-striped tracker-table" data-list="<?=$trackerDataTypeKey?>" style="<?=($trackerDataTypeKey !== 'reading' ? 'display:none' : '')?>">
		<thead>
		<tr>
			<th class="header read headerSortDown" hidden></th>
			<th class="header read"><div class="tablesorter-header-inner">Series<?=($trackerDataType['unread_count'] > 0 ? ' ('.$trackerDataType['unread_count'].' unread)' : '')?></div></th>
			<th class="header read"><div class="tablesorter-header-inner">My Status</div></th>
			<th class="header read"><div class="tablesorter-header-inner">Latest Release</div></th>
		</tr>
		</thead>
		<tbody>
		<?php foreach($trackerDataType['manga'] as $row) { ?>
			<tr data-id="<?=$row['id']?>" <?=($row['site_data']['status'] == 'disabled' ? 'class="bg-danger"' : '')?>>
				<td hidden>
					<span hidden><?=$row['new_chapter_exists']?> - <?=htmlentities($row['title_data']['title'])?></span>
				</td>
				<td>
					<i class="sprite-time <?=get_time_class($row['title_data']['last_updated'])?>" title="<?=$row['title_data']['last_updated']?>"></i>
					<i class="sprite-site sprite-<?=str_replace('.', '-', $row['site_data']['site'])?>" title="<?=$row['site_data']['site']?>"></i>
					<?=$row['mal_icon']?>

					<a href="<?=$row['full_title_url']?>" rel="nofollow"><?=htmlentities($row['title_data']['title'])?></a>

					<?php if($row['has_tags']) { ?>
					<small class="more-info pull-right text-muted">Less info</small>
					<div class="tags" style="display: block">
						<?php } else { ?>
						<small class="more-info pull-right text-muted">More info</small>
						<div class="tags">
							<?php } ?>
							<small>
								Tags: <em class="text-lowercase tag-list"><?=($row['has_tags'] ? $row['tag_list'] : "none")?></em>
							</small>
						</div>
				</td>
				<td>
					<?=htmlentities($row['generated_current_data']['number'])?>
					<?php if(!is_null($row['title_data']['ignore_chapter'])) { ?><span class='hidden-chapter' title='The latest chapter was marked as ignored.'><?=$row['generated_ignore_data']['number']?></span><?php } ?>
				</td>
				<td>
					<?=htmlentities($row['generated_latest_data']['number'])?>
				</td>
			</tr>
		<?php } ?>
		</tbody>
	</table>
<?php } ?>
<script>
	const list_sort_type  = "unread";
	const list_sort_order = "asc";
</script>
