<div class="row">
	<div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
		<form action="<?=base_url('user/signup')?>" method="post" accept-charset="utf-8" role="form" autocomplete="off">
			<input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>">

			<h2>Please Sign Up <small>It's free and always will be.</small></h2>

			<hr class="colorgraph">

			<div class="form-group">
				Before we start the signup, we need to verify your email.
			</div>

			<div class="form-group">
				<?=form_input($form_email)?>
			</div>

			<div id="notices"><?=print_r($notices, TRUE)?></div>

			<hr class="colorgraph">

			<div class="row">
				<?=form_submit($form_submit);?>
			</div>
		</form>
	</div>
</div>
