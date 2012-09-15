<h2><?= lang('user_login_header') ?></h2>

<?php if (validation_errors()): ?>
<div class="notice"><?= validation_errors();?></div>
<?php endif; ?>

<?= form_open('users/login', array('id'=>'login')); ?>
	<div class="clearfix append-bottom">
		<div class="span-4"><?= form_label(lang('user_email').':', 'email'); ?></div>         
		<div class="span-10 last"><?= form_input('email', null, 'class="span-9 text" maxlength="120"'); ?></div>
	</div>
	<div class="clearfix">
		<div class="span-4"><?= form_label(lang('user_password').':', 'password'); ?></div>         
		<div class="span-10 last"><?= form_password('password', null, 'class="span-9 text" maxlength="20"'); ?></div>
		<div class="prepend-4 span-9 field-help"><?= anchor('users/reset_pass', lang('user_reset_password_link'));?></div>
	</div>
	<div class="clearfix append-bottom">         
		<div class="span-10 prepend-4 last"><?php echo form_checkbox('remember', '1', FALSE); ?><?php echo lang('user_remember')?></div>
	</div>
	<div class="prepend-4">
	<?= form_submit('btnLogin', lang('user_login_btn'), 'class="cta"'); ?>
	</div>
<?= form_close(); ?>