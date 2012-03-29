<h2><?php echo lang('user_register_header') ?></h2>

<div class="workflow_steps">
	<span id="active_step"><?php echo lang('user_register_step1') ?></span> &gt;
	<span><?php echo lang('user_register_step2') ?></span>
</div>

<?php if(!empty($error_string)): ?>
<div class="error"><?= $error_string; ?></div>
<?php endif;?>

<?= form_open('users/activate', 'id="activate-user"'); ?>
	<div class="clearfix append-bottom">
		<div class="span-4"><?= form_label(lang('user_email').':', 'email'); ?></div>
		<div class="span-10 last"><?= form_input('email',isset($user->email) ? $user->email : '', 'class="span-9 text" maxlength="40"'); ?></div>
	</div>
	<div class="clearfix append-bottom">
		<div class="span-4"><?= form_label(lang('user_activation_code').':', 'activation_code'); ?></div>
		<div class="span-10 last"><?= form_input('activation_code', null, 'class="span-9 text" maxlength="40"'); ?></div>
	</div>
	
	<?= form_submit('btnSubmit', lang('user_activate_btn'), 'class="cta"') ?>

<?= form_close(); ?>