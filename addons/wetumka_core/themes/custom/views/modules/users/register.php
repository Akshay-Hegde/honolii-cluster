<h2><?php echo lang('user_register_header') ?></h2>

<p>
	<span id="active_step"><?php echo lang('user_register_step1') ?></span> -&gt; 
	<span><?php echo lang('user_register_step2') ?></span>
</p>

<p><?php echo lang('user_register_reasons') ?></p>

<?php if(!empty($error_string)):?>
<div class="notice"><?= $error_string;?></div>
<?php endif;?>  

<?= form_open('register', array('id'=>'register')); ?>
	
	<?php /*?><div class="clearfix append-bottom">
		<div class="span-4"><?= form_label(lang('user_first_name').':', 'first_name'); ?></div>
		<div class="span-10 last"><?= form_input('first_name', $user_data->first_name, 'class="span-9 text" maxlength="40"'); ?></div>
	</div>
	
	<div class="clearfix append-bottom">
		<div class="span-4"><?= form_label(lang('user_last_name').':', 'last_name'); ?></div>
		<div class="span-10 last"><?= form_input('last_name', $user_data->last_name, 'class="span-9 text" maxlength="40"'); ?></div>
	</div><?php */?>
	
	<div class="clearfix append-bottom">
		<div class="span-4"><?= form_label(lang('user_username').':', 'username'); ?></div>
		<div class="span-10 last"><?= form_input('username', $user_data->username, 'class="span-9 text" maxlength="100"'); ?></div>
	</div>
	
	<?php /*?><div class="clearfix append-bottom">
		<div class="span-4"><?= form_label(lang('user_display_name').':', 'display_name'); ?></div>
		<div class="span-10 last"><?= form_input('display_name', $user_data->display_name, 'class="span-9 text" maxlength="100"'); ?></div>
	</div><?php */?>
	
	<div class="clearfix append-bottom">
		<div class="span-4"><?= form_label(lang('user_email').':', 'email'); ?></div>
		<div class="span-10 last"><?= form_input('email', $user_data->email, 'class="span-9 text" maxlength="100"'); ?></div>
	</div>
	
	<?php /*?><div class="clearfix append-bottom">
		<div class="span-4"><?= form_label(lang('user_confirm_email').':', 'confirm_email'); ?></div>
		<div class="span-10 last"><?= form_input('confirm_email', $user_data->confirm_email, 'class="span-9 text" maxlength="100"'); ?></div>
	</div><?php */?>
	
	<div class="clearfix append-bottom">
		<div class="span-4"><?= form_label(lang('user_password').':', 'password'); ?></div>
		<div class="span-10 last"><?= form_password('password',null, 'class="span-9 text" maxlength="100"'); ?></div>
	</div>
	
	<div class="clearfix append-bottom">
		<div class="span-4"><?= form_label(lang('user_confirm_password').':', 'confirm_password'); ?></div>
		<div class="span-10 last"><?= form_password('confirm_password',null, 'class="span-9 text" maxlength="100"'); ?></div>
	</div>
	
	<div class="clearfix append-bottom">
		<?php echo form_submit('btnSubmit', lang('user_register_btn'), 'class="cta"') ?>
	</div>
<?= form_close(); ?>