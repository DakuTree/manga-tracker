<div id="export-options">
	<div class="dropdown pull-right">
		<button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
			Export
			<span class="caret"></span>
		</button>
		<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
			<li><a href="<?=base_url("user/history/export/json")?>">Export as JSON</a></li>
			<li><a href="<?=base_url("user/history/export/csv")?>">Export as CSV</a></li>
		</ul>
	</div>
	<div class="clearfix"></div>
</div>

<table class="table table-striped table-bordered tablesorter">
	<thead>
		<tr>
			<th class="col-md-2">Updated at</th>
			<th class="col-md-5">Title</th>
			<th class="col-md-5">What changed</th>
		</tr>
	</thead>
	<tbody>
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
	const titleID           = 0; //Hack
</script>
