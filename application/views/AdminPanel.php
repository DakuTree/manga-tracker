<?=validation_errors()?>

<div>
	<h3>Admin Panel</h3>

	<!-- FIXME: Really don't like the design of this (mainly due to our bootstrap theme), but it's only an admin panel so who cares... -->

	<div>
		<label>Run updater: </label>
		<a href="<?=base_url("admin_panel/update/normal")?>"><button type="button" class="btn btn-lg btn-default">Normal</button></a>
		<a href="<?=base_url("admin_panel/update/custom")?>"><button type="button" class="btn btn-lg btn-default">Custom</button></a>
		<a href="<?=base_url("admin_panel/update/titles")?>"><button type="button" class="btn btn-lg btn-default">Titles</button></a>

		<span id="update-status"></span>
	</div>

	<br />

	<div>
		<h4>Series marked as complete</h4>
		<pre><?=$id_sql?></pre>
		<?=$this->table->generate($complete_list)?>
	</div>
	<!--<div>-->
	<!--	<form class="form-inline">-->
	<!--		<label>Move users from ID to ID: </label>-->
	<!--		<div class="form-group">-->
	<!--			<input type="number" class="form-control" placeholder="Original ID" />-->
	<!--			<input type="number" class="form-control" placeholder="New ID" />-->
	<!--		</div>-->
	<!--		<button type="submit" class="btn btn-default">Submit</button>-->
	<!--	</form>-->
	<!--</div>-->
</div>
