<?=validation_errors()?>

<div class="row" style="margin-top: 4px;">
	<div class="col-sm-6">
		<div id="options-site">
			<h3>Site Options</h3>
			<?=form_open('', ['method' => 'POST'])?>
				<div id="options-custom-categories">
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
				</div>

				<div id="options-default-category">
					<div class="form-group">
						<?=form_label('Default Series Category', 'default_series_category')?>
						<?=form_dropdown('default_series_category', $default_series_category, $default_series_category_selected)?>
					</div>
				</div>

				<div id="options-countdown-timer">
					<div class="form-group">
						<?=form_label('Live Countdown Timer', 'enable_live_countdown_timer')?> <i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="right" title="Live Countdown Timer.<br>Turn off to reducing CPU usage when tab is left open in background."></i>
						<div class="btn-group" data-toggle="buttons">
							<label class="btn btn-primary <?=(isset($enable_live_countdown_timer_enabled['checked']) ? 'active' : '')?>">
								<?=form_radio($enable_live_countdown_timer_enabled)?>
								<span>Enabled</span>
							</label>
							<label class="btn btn-primary <?=(isset($enable_live_countdown_timer_disabled['checked']) ? 'active' : '')?>">
								<?=form_radio($enable_live_countdown_timer_disabled)?>
								<span>Disabled</span>
							</label>
						</div>
					</div>
				</div>

				<div id="options-list-sort">
					<div class="form-group">
						<?=form_label('List Sort Order', 'list_sort')?>
						<div class="btn-group" data-toggle="buttons">
							<?=form_dropdown('list_sort_type', $list_sort_type, $list_sort_type_selected)?>
							<?=form_dropdown('list_sort_order', $list_sort_order, $list_sort_order_selected)?>
						</div>
					</div>
				</div>

				<div id="options-theme">
					<div class="form-group">
						<?=form_label('Site Theme', 'theme')?>
						<div class="btn-group" data-toggle="buttons">
							<?=form_dropdown('theme', $theme, $theme_selected)?>
						</div>
					</div>
				</div>

				<div id="options-public-list">
					<div class="form-group">
						<?=form_label('Enable Public List (<a href="'.base_url("list/{$username}.html").'">HTML</a> | <a href="'.base_url("list/{$username}.json").'">JSON</a>)', 'enable_public_list')?>
						<div class="btn-group" data-toggle="buttons">
							<label class="btn btn-primary <?=(isset($enable_public_list_enabled['checked']) ? 'active' : '')?>">
								<?=form_radio($enable_public_list_enabled)?>
								<span>Enabled</span>
							</label>
							<label class="btn btn-primary <?=(isset($enable_public_list_disabled['checked']) ? 'active' : '')?>">
								<?=form_radio($enable_public_list_disabled)?>
								<span>Disabled</span>
							</label>
						</div>
					</div>
				</div>

				<div id="options-mal-sync">
					<div class="form-group">
						<?=form_label('Enable MAL Sync <i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="This requires users to <i>manually</i> add a \'mal:#id#\' tag for syncing to work for that series.<br>For example, One Piece would have the \'mal:13\' tag."></i>', 'mal_sync')?>
						<div class="btn-group" data-toggle="buttons">
							<label class="btn btn-primary <?=(isset($mal_sync_disabled['checked']) ? 'active' : '')?>">
								<?=form_radio($mal_sync_disabled)?>
								<span>Disabled</span>
							</label>
							<label class="btn btn-primary <?=(isset($mal_sync_csrf['checked']) ? 'active' : '')?>">
								<?=form_radio($mal_sync_csrf)?>
								<span>CSRF <i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="This uses CSRF (Cross Site Request Forgery) to allow us to use MAL's internal API to update.<br>It requires the user to be logged into MAL for it to work properly."></i></span>
							</label>
							<label class="btn btn-primary <?=(isset($mal_sync_api['checked']) ? 'active' : '')?>" disabled>
								<?=form_radio($mal_sync_api)?>
								<span>API <i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="This uses the MAL API to update.<br>This requires us to store your MAL details <i>in your browser</i> in <b>plain text</b>, but it does mean you don't have to be logged in on MAL."></i></span>
							</label>
						</div>
					</div>
				</div>
			
				<?=form_submit(...array(NULL, 'Save Settings', array('class' => 'btn btn-success')))?>
			</form>
		</div>
	</div>

	<div class="col-sm-6">
		<div id="options-userscript">
			<h3>Userscript Options</h3>


			<div id="userscript-check" class="alert alert-danger" role="alert">
				Userscript is not enabled!
			</div>
			<div id="api-key-div">
				API Key: <strong><span id="api-key">not set</span></strong> | <a id="generate-api-key" href="#" onclick="return false">Generate new API key</a>
			</div>

			<?=form_open('', ['method' => 'POST', 'id' => 'userscript-form'])?>
				<div id="options-auto_track">
					<div class="form-group">
						<?=form_checkbox('auto_track')?>
						<?=form_label('Auto track series on page load.', 'auto_track')?>
					</div>
				</div>
				<div id="options-disable_viewer">
					<div class="form-group">
						<?=form_checkbox('disable_viewer')?>
						<?=form_label('Disable single page loader.', 'disable_viewer')?>
					</div>
				</div>

				<?=form_submit(...array(NULL, 'Save Settings', array('class' => 'btn btn-success', 'onclick' => 'alert(\'Userscript must be enabled to save settings.\'); return false;')))?>
				<span id="form-feedback"></span>
			</form>
		</div>
	</div>
</div>



