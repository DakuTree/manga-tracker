<table class="table table-striped table-bordered">
	<tbody>
		<tr>
			<!-- TODO: We should have a delete button here -->
			<th class="col-md-2">Favourited at</th>
			<th class="col-md-8">Title</th>
			<th class="col-md-2">Chapter</th>
		</tr>
		<?php foreach($favouriteData as $row) { ?>
		<tr>
			<td><?=$row['updated_at']?></td>

			<td>
				<i class="sprite-site sprite-<?=$row['site_sprite']?>" title="<?=$row['site']?>"></i>
				<a href="<?=$row['title_url']?>"><?=$row['title']?></a>
			</td>

			<td><?=$row['chapter']?></td>
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
		<li class="page-item <?=($currentPage == $page ? 'active' : '')?>"><a class="page-link" href="<?=base_url("user/favourites/{$page}")?>"><?=$page?></a></li>
		<?php } ?>
		<li class="page-item <?=($currentPage == $totalPages ? 'disabled' : '')?>">
			<a class="page-link" href="#" aria-label="Next">
				<span aria-hidden="true">&raquo;</span>
				<span class="sr-only">Next</span>
			</a>
		</li>
	</ul>
</nav>
