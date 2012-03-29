<h2><?= lang('user_reset_password_title');?></h2>

<?php if(!empty($error_string)):?>
	<div class="notice"><?= $error_string;?></div>
<?php endif;?>

<?php if(!empty($success_string)): ?>
	<div class="success"><?= $success_string; ?></div>
<?php else: ?>

	<?= form_open('users/reset_pass', array('id'=>'reset-pass')); ?>
	<div class="clearfix append-bottom">
		<div class="span-4"><?= form_label(lang('user_username').':', 'user_name'); ?></div>         
		<div class="span-10 last"><?= form_input('user_name', null, 'class="span-9 text" maxlength="40"'); ?></div>
	</div>
	<div class="clearfix append-bottom">
		<div class="span-4"><?= form_label(lang('user_email').':', 'email'); ?></div>         
		<div class="span-10 last"><?= form_input('email', null, 'class="span-9 text" maxlength="100"'); ?></div>
	</div>
	<div class="prepend-4">
			<?= form_submit('btnSubmit', lang('user_reset_pass_btn'), 'class="cta"') ?>
	</div>
	<?= form_close(); ?>
	
<?php endif; ?>