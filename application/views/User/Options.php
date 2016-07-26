<?=validation_errors()?>


<div id="userscript-check" class="alert alert-danger" role="alert">
	Userscript is not enabled!
</div>
<div id="api-key-div">
	API Key: <strong><span id="api-key">not set</span></strong> | <a id="generate-api-key" href="#" onclick="return false">Generate new API key</a>
</div>

<div class="row" style="margin-top: 4px;">
	<div class="col-sm-6">
		<div id="options-userscript">
			<form id="userscript-form">
				<?=form_fieldset('Userscript Options', array('disabled' => TRUE))?>
				<div class="fieldset-container">
					<div class="form-group">
						<label for="auto_track">
							Auto track series on page load <input id="auto_track" name="auto_track" type="checkbox">
						</label>
					</div>
				</div>
				<?=form_fieldset_close()?>

				<?=form_submit(...array(NULL, 'Save Settings', array('class' => 'btn btn-success', 'onclick' => 'alert(\'Userscript must be enabled to save settings.\'); return false;')))?>
				<span id="form-feedback"></span>
			</form>
		</div>
	</div>
	<div class="col-sm-6">
		<div id="options-site">
			<h3>Site Options</h3>
			<form method="POST">
				<div class="form-group">
					<div class="input-group">
				<span class="input-group-addon">
					<?=form_checkbox('category_custom_1', 'enabled', $category_custom_1, ['data-has-series' => $category_custom_1_has_series, 'style' => 'vertical-align: bottom;'])?>
					Custom Category 1:
				</span>
						<input type="text" name="category_custom_1_text" id="category_custom_1_text" class="form-control" style="width: auto" maxlength="16" value="<?=$category_custom_1_text?>">
					</div><!-- /input-group -->
				</div>
				<div class="form-group">
					<div class="input-group">
				<span class="input-group-addon">
					<?=form_checkbox('category_custom 2', 'enabled', $category_custom_2, ['data-has-series' => $category_custom_2_has_series, 'style' => 'vertical-align: bottom;'])?>
					Custom Category 2:
				</span>
						<input type="text" name="category_custom_2_text" id="category_custom_2_text" class="form-control" style="width: auto" maxlength="16" value="<?=$category_custom_2_text?>">
					</div><!-- /input-group -->
				</div>
				<div class="form-group">
					<div class="input-group">
				<span class="input-group-addon">
					<?=form_checkbox('category_custom_3', 'enabled', $category_custom_3, ['data-has-series' => $category_custom_3_has_series, 'style' => 'vertical-align: bottom;'])?>
					Custom Category 3:
				</span>
						<input type="text" name="category_custom_3_text" id="category_custom_3_text" class="form-control" style="width: auto" maxlength="16" value="<?=$category_custom_3_text?>">
					</div><!-- /input-group -->
				</div>
				<?=form_submit(...array(NULL, 'Save Settings', array('class' => 'btn btn-success')))?>
			</form>
		</div>
	</div>
</div>



