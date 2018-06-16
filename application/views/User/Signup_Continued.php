<div class="row justify-content-center">
	<div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
		<form action="<?=base_url("user/signup/{$verificationCode}")?>" method="post" accept-charset="utf-8" role="form" autocomplete="off">
			<input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>">

			<h2>Please Sign Up</h2>

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

			<div class="row">
				<div class="col-xs-4 col-sm-3 col-md-3">
					<span class="button-checkbox">
						<button type="button" class="btn" data-color="info" tabindex="5"> I Agree</button>
						<?=form_checkbox($form_terms)?>
					</span>
				</div>

				<div class="col-xs-8 col-sm-9 col-md-9">
					By clicking <strong class="label label-primary">Register</strong>, you agree to the <a href="<?=base_url('about/terms')?>" target="_blank" onclick="window.open(this, null, 'toolbar=0'); return false;">Terms and Conditions</a> set out by this site, including our <a href="<?=base_url('about/terms#cookie-policy')?>" target="_blank" onclick="window.open(this, null, 'toolbar=0'); return false;">Cookie</a> and <a href="<?=base_url('about/terms#privacy-policy')?>" target="_blank" onclick="window.open(this, null, 'toolbar=0'); return false;">Privacy</a> Policy.
				</div>
			</div>

			<hr class="colorgraph">

			<div class="row">
				<div class="col-xs-12 col-md-12">
					<?=form_submit($form_submit);?>
				</div>
			</div>
		</form>
	</div>
</div>
