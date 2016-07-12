<table id="myTable read" class="tablesorter tablesorter-bootstrap">
	<thead>
		<tr>
			<th class="header read headerSortDown"></th>
			<th class="header read">Series (<?=count($trackerData)?>)</th>
			<th class="header read">My Status</th>
			<th class="header read">Latest Release</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($trackerData as $row) { ?>
		<tr data-id="<?=$row['id']?>">
			<td>
				<span class="hidden"><?=$row['new_chapter_exists']?> - <?=$row['title_data']['title']?></span>

				<input type="checkbox" name="check">
			</td>
			<td>
				<img src="<?=img_url()."site_icons/{$row['site_data']['site']}.ico"?>" />
				<a href="<?=$row['full_title_url']?>" rel="nofollow"><?=$row['title_data']['title']?></a>
			</td>
			<td>
				<a class="chp-release current" href="<?=$row['generated_current_url']?>" rel="nofollow"><?=$row['title_data']['current_chapter']?></a>
			</td>
			<td>
				<a class="chp-release latest" href="<?=$row['generated_latest_url']?>" rel="nofollow"><?=$row['title_data']['latest_chapter']?></a>
			</td>
			<td>
				<?php if($row['new_chapter_exists'] == '0') { ?>
				<span class="update-read" title="I've read the latest chapter!">
					<i class="fa fa-refresh" aria-hidden="true"></i>
				</span>
				<!-- TODO: 
				<?php } ?>
			</td>
		</tr>
		<?php } ?>
	</tbody>
</table>
