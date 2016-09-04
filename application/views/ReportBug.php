<h1>Report a Bug</h1>
<?=form_open(base_url('report_bug'))?>

	<div class="form-group">
		<?=form_label('Description (*): ', 'bug_description')?>
		<?=form_textarea('bug_description', '', ['class' => 'form-control', 'rows' => '3'])?>
	</div>

	<div class="form-group">
		<?=form_label('URL: ', 'bug_url')?>
		<?=form_input('bug_url', '', ['class' => 'form-control'])?>
	</div>

	<?=validation_errors()?><?=($bug_submitted ? "Bug successfully submitted" : "")?>
	<button type="submit" class="btn btn-primary">Submit</button>
<?=form_close()?>
