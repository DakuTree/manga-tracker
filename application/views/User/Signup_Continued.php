<div class="row">
	<div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
		<!-- TODO: Username/password requirements should be listed somewhere, maybe as popup when :focus -->
		<form action="<?=base_url("user/signup/{$verificationCode}")?>" method="post" accept-charset="utf-8" role="form" autocomplete="off">
			<input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>">

			<h2>Please Sign Up <small>It's free and always will be.</small></h2>

			<hr class="colorgraph">

			<div class="form-group">
				<?=form_input($form_username)?>
			</div>

			<div class="form-group">
				<?=form_input($form_email)?>
			</div>

			<div class="row">
				<div class="col-xs-12 col-sm-6 col-md-6">
					<div class="form-group">
						<?=form_input($form_password)?>
					</div>
				</div>

				<div class="col-xs-12 col-sm-6 col-md-6">
					<div class="form-group">
						<?=form_input($form_password_confirm)?>
					</div>
				</div>
			</div>

			<div id="notices"><?=print_r($notices, TRUE)?></div>

			<div class="row">
				<div class="col-xs-4 col-sm-3 col-md-3">
					<span class="button-checkbox">
						<button type="button" class="btn" data-color="info" tabindex="5">I Agree</button>
						<?=form_checkbox($form_terms)?>
					</span>
				</div>

				<div class="col-xs-8 col-sm-9 col-md-9">
					By clicking <strong class="label label-primary">Register</strong>, you agree to the <a href="<?=base_url('about/terms')?>" target="_blank" onclick="window.open(this, null, 'toolbar=0'); return false;">Terms and Conditions</a> set out by this site, including our <a href="<?=base_url('about/terms#cookie-policy')?>" target="_blank" onclick="window.open(this, null, 'toolbar=0'); return false;">Cookie</a> and <a href="<?=base_url('about/terms#privacy-policy')?>" target="_blank" onclick="window.open(this, null, 'toolbar=0'); return false;">Privacy</a> Policy.
				</div>
			</div>

			<hr class="colorgraph">

			<div class="row">
				<div class="col-xs-12 col-md-6">
					<?=form_submit($form_submit);?>
				</div>
				<div class="col-xs-12 col-md-6">
					<a href="<?=base_url('user/login')?>" class="btn btn-success btn-block btn-lg">Sign In</a>
				</div>
			</div>
		</form>
	</div>
</div>
<!-- Modal -->
<div class="modal fade" id="t_and_c_m" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" id="myModalLabel">Terms & Conditions</h4>
			</div>
			<div class="modal-body">
				<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Similique, itaque, modi, aliquam nostrum at sapiente consequuntur natus odio reiciendis perferendis rem nisi tempore possimus ipsa porro delectus quidem dolorem ad.</p>
				<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Similique, itaque, modi, aliquam nostrum at sapiente consequuntur natus odio reiciendis perferendis rem nisi tempore possimus ipsa porro delectus quidem dolorem ad.</p>
				<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Similique, itaque, modi, aliquam nostrum at sapiente consequuntur natus odio reiciendis perferendis rem nisi tempore possimus ipsa porro delectus quidem dolorem ad.</p>
				<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Similique, itaque, modi, aliquam nostrum at sapiente consequuntur natus odio reiciendis perferendis rem nisi tempore possimus ipsa porro delectus quidem dolorem ad.</p>
				<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Similique, itaque, modi, aliquam nostrum at sapiente consequuntur natus odio reiciendis perferendis rem nisi tempore possimus ipsa porro delectus quidem dolorem ad.</p>
				<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Similique, itaque, modi, aliquam nostrum at sapiente consequuntur natus odio reiciendis perferendis rem nisi tempore possimus ipsa porro delectus quidem dolorem ad.</p>
				<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Similique, itaque, modi, aliquam nostrum at sapiente consequuntur natus odio reiciendis perferendis rem nisi tempore possimus ipsa porro delectus quidem dolorem ad.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" data-dismiss="modal">I Agree</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
