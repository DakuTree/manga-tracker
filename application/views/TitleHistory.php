<?php if(count($historyData) === 0) { ?>
<div class="alert alert-warning">
	This shows as empty as there has been no updates since 2016-09-19 (when title history was implemented).
</div>
<?php } ?>

<table class="table table-striped table-bordered tablesorter">
	<thead>
		<tr>
			<th class="col-md-2">Updated at</th>
			<th class="col-md-5">New Chapter</th>
			<th class="col-md-5">New Chapter (Without parsing)</th>
		</tr>
	</thead>

	<tbody>
		<?php foreach($historyData as $row) { ?>
		<tr>
			<td><?=$row['updated_at']?></td>

			<td><?=$row['new_chapter']?></td>

			<td><?=$row['new_chapter_full']?></td>
		</tr>
		<?php } ?>
	</tbody>
</table>

<nav aria-label="Page navigation" style="text-align:center">
	<ul class="pagination">
		<li class="page-item <?=($currentPage == 1 ? 'disabled' : '')?>">
			<a class="page-link" href="<?=base_url("user/history/".($currentPage - 1))?>" aria-label="Previous">
				<span aria-hidden="true">&laquo;</span>
				<span class="sr-only">Previous</span>
			</a>
		</li>

		<?php foreach(range(1, $totalPages) as $page) { ?>
		<li class="page-item <?=($currentPage == $page ? 'active' : '')?>"><a class="page-link" href="<?=base_url("user/history/{$page}")?>"><?=$page?></a></li>
		<?php } ?>

		<li class="page-item <?=(($currentPage == 1 || $currentPage == $totalPages) ? 'disabled' : '')?>">
			<a class="page-link" href="<?=base_url("user/history/".($currentPage + 1))?>" aria-label="Next">
				<span aria-hidden="true">&raquo;</span>
				<span class="sr-only">Next</span>
			</a>
		</li>
	</ul>
</nav>


<script src="<?=asset_url()?>vendor/js/jquery.simplePagination.js" defer></script>
<script>
	const currentPagination = parseInt("<?=$currentPage?>");
	const totalPagination   = parseInt("<?=$totalPages?>");
	const titleID           = parseInt("<?=$titleID?>");
</script>
