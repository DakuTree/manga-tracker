<div id="userscript-options">
	<div id="userscript-check" class="alert alert-danger" role="alert">
		Userscript is not enabled!
	</div>

	<div id="api-key-div">
		API Key: <strong><span id="api-key">not set</span></strong> | <a id="generate-api-key" href="#" onclick="return false">Generate new API key</a>
	</div>
	<br/>
	<form id="userscript-form">
		<?=form_fieldset('Tracker Options', array('disabled' => TRUE))?>
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

