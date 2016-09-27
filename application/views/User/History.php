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

<nav aria-label="Page navigation" style="text-align:center">
	<ul class="pagination">
		<li class="page-item <?=($currentPage == 1 ? 'disabled' : '')?>">
			<a class="page-link" href="#" aria-label="Previous">
				<span aria-hidden="true">&laquo;</span>
				<span class="sr-only">Previous</span>
			</a>
		</li>
		<?php foreach(range(1, $totalPages) as $page) { ?>
		<li class="page-item <?=($currentPage == $page ? 'active' : '')?>"><a class="page-link" href="<?=base_url("user/history/{$page}")?>"><?=$page?></a></li>
		<?php } ?>
		<li class="page-item <?=($currentPage == $totalPages ? 'disabled' : '')?>">
			<a class="page-link" href="#" aria-label="Next">
				<span aria-hidden="true">&raquo;</span>
				<span class="sr-only">Next</span>
			</a>
		</li>
	</ul>
</nav>
