<div id="export-options">
	<div class="dropdown pull-right">
		<button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
			Export
			<span class="caret"></span>
		</button>
		<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
			<li><a href="<?=base_url('user/favourites/export/json')?>">Export as JSON</a></li>
			<li><a href="<?=base_url('user/favourites/export/csv')?>">Export as CSV</a></li>
		</ul>
	</div>
	<div class="clearfix"></div>
</div>

<table class="table table-striped table-bordered tablesorter">
	<thead>
		<tr>
			<!-- TODO: We should have a delete button here -->
			<th>Favourited at</th>
			<th class="w-50">Title</th>
			<th>Chapter / Page</th>
		<tr>
	</thead>
	<tbody>
		<?php foreach($favouriteData as $row) { ?>
		<tr>
			<td><?=$row['updated_at']?></td>

			<td>
				<i class="sprite-site sprite-<?=$row['site_sprite']?>" title="<?=$row['site']?>"></i>
				<a href="<?=$row['title_url']?>"><?=$row['title']?></a>
			</td>

			<td><?=$row['chapter_url']?></td>
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
