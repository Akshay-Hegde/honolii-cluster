<h3 class="col-headline"><?= lang('user_login_header') ?></h3>

<div class="success">
	<?= $this->lang->line('user_activated_message'); ?>
</div>

<?php
	// Form values and attributes
	$form_data = array();
	// Contact Information
	$form_data['email'] = array('name'=>'email','id'=>'form_email','value'=>NULL,'class'=>'text required','maxlength'=>'100');
	$form_data['password'] = array('name'=>'password','id'=>'form_password','value'=>NULL,'class'=>'text required','maxlength'=>'40');
?>

<?= form_open('users/login', array('id'=>'login')); ?>

	<?= form_label(lang('user_email'),$form_data['email']['id']); ?>
	<?= form_input($form_data['email']);?>

	<?= form_label(lang('user_password'),$form_data['password']['id']); ?>
	<?= form_password($form_data['password']);?>
	<hr class="space"/>
	<?= form_submit('btnLogin', lang('user_login_btn'), 'class="button"') ?>

<?= form_close(); ?>