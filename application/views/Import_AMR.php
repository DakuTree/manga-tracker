<h3>AMR Import Helper</h3>
<p>
	This is a WIP tool to help import series from <abbr title="All Mangas Reader">AMR</abbr>. Due to the differences between AMR and trackr.moe, we can't just handle it all on the backend, we still need the user to manually track it themselves.<br>
	Upload your exported AMR backup below and it will output a formatted list with links of everything in the backup.
</p>

<input type="file" name="amr_import" id="amr_import" class="form-control-" accept=".json,.txt">

<div id="amr_output">
	<div id="amr_good"></div>
	<div id="amr_bad"></div>
</div>
