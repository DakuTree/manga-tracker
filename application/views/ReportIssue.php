<h1>Report an Issue</h1>
<?=form_open(base_url('report_issue'))?>
	<div class="form-group">
		<?=form_label('Description (*): ', 'issue_description')?>
		<?=form_textarea('issue_description', '', ['class' => 'form-control', 'rows' => '3'])?>
	</div>

	<div class="form-group">
		<?=form_label('URL: ', 'issue_url')?>
		<?=form_input('issue_url', '', ['class' => 'form-control'])?>
	</div>

	<?=validation_errors()?><?=($issue_submitted ? "Issue successfully submitted" : "")?>
	<button type="submit" class="btn btn-primary">Submit</button> | Alternatively, post an issue on our <?=anchor('https://github.com/DakuTree/manga-tracker/issues/new', 'Github page')?>.
<?=form_close()?>
