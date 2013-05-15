<h3 class="col-headline"><?= lang('user:login_header') ?></h3>

<?php if (validation_errors()): ?>
<div class="error">
	<?= validation_errors();?>
</div>
<?php endif; ?>

<?php
	// Form values and attributes
	$form_data = array();
	// Contact Information
	$form_data['email'] = array('name'=>'email','id'=>'form_email','value'=>$_user->email,'class'=>'text required email','maxlength'=>'100');
	$form_data['pass'] = array('name'=>'password','id'=>'form_pass','value'=>NULL,'class'=>'text required','maxlength'=>'100');
?>

<?= form_open('users/login', array('id'=>'login'), array('redirect_to' => $redirect_to)); ?>

		<?= form_label(lang('global:email'),$form_data['email']['id']); ?>
		<?= form_input($form_data['email']);?>

		<?= form_label(lang('global:password'),$form_data['pass']['id']); ?>
		<?= form_password($form_data['pass']);?>
		<label>
			<?= form_checkbox('remember', '1', FALSE); ?> <?= lang('user:remember'); ?>
		</label>
		<div class="reset-pass"><?= anchor('users/reset_pass', lang('user:reset_password_link'));?></div>
		<hr class="space"/>
		<?= form_submit('btnLogin', lang('user:login_btn'), 'class="button"') ?>
		<hr class="space"/>
		<div class="register"><?= anchor('register', lang('user:register_btn'));?></div>
		
<?= form_close(); ?>