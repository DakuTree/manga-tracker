<table class="table table-striped table-bordered">
	<tbody>
		<tr>
			<th class="col-md-2">Updated at</th>
			<th class="col-md-5">Title</th>
			<th class="col-md-5">What changed</th>
		</tr>
		<?php foreach($historyData as $row) { ?>
		<tr>
			<td><?=$row['updated_at']?></td>

			<td>
				<i class="sprite-site sprite-<?=$row['site_sprite']?>" title="<?=$row['site']?>"></i>
				<a href="<?=$row['title_url']?>"><?=$row['title']?></a>
			</td>

			<td><?=$row['status']?></td>
		</tr>
		<?php } ?>
	</tbody>
</table>

<!--PREV | 1 2 3 4 5 | NEXT-->
