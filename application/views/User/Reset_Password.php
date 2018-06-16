<div class="row justify-content-center">
	<div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
		<form action="<?=base_url("user/reset_password/{$reset_code}")?>" method="post" accept-charset="utf-8" role="form" autocomplete="off">
			<input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>">

			<h2>Reset Password</h2>

			<hr class="colorgraph">

			<div class="form-group">
				Please enter your new password.
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

			<hr class="colorgraph">

			<div class="row">
				<div class="col-xs-12 col-md-12">
					<?=form_submit($form_submit);?>
				</div>
			</div>
		</form>
	</div>
</div>
