<h3 class="col-headline"><?= lang('user_login_header') ?></h3>

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

		<?= form_label(lang('user_email'),$form_data['email']['id']); ?>
		<?= form_input($form_data['email']);?>

		<?= form_label(lang('user_password'),$form_data['pass']['id']); ?>
		<?= form_password($form_data['pass']);?>
		<label>
			<?= form_checkbox('remember', '1', FALSE); ?> <?= lang('user_remember'); ?>
		</label>
		<div class="reset-pass"><?= anchor('users/reset_pass', lang('user_reset_password_link'));?></div>
		<hr class="space"/>
		<?= form_submit('btnLogin', lang('user_login_btn'), 'class="button"') ?>
		<div class="register"><?= anchor('register', lang('user_register_btn'));?></div>
		
<?= form_close(); ?>