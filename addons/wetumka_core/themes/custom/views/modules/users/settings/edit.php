<h2><?= lang('user_settings_edit') ?></h2>
<div id="user_edit_settings">
<?php if (validation_errors()): ?>
	<div class="notice">
		<?= validation_errors();?>
	</div>
<?php endif; ?>
<?= form_open('edit-settings', array('id'=>'user_edit_settings'));?>
	<?= form_fieldset(lang('user_details_section')) ?>
		<div class="clearfix append-bottom">
			<div class="span-4"><?= form_label(lang('user_first_name').':', 'settings_first_name'); ?></div>
			<?= form_input('settings_first_name', $user_settings->first_name, 'class="span-9 text" maxlength="80"'); ?>
		</div>
		<div class="clearfix append-bottom">
			<div class="span-4"><?= form_label(lang('user_last_name').':', 'settings_last_name'); ?></div>
			<?= form_input('settings_last_name', $user_settings->last_name, 'class="span-9 text" maxlength="80"'); ?>
		</div>
		<?php /*?><div class="clearfix append-bottom">
			<div class="span-4"><?= form_label(lang('user_email').':', 'settings_email'); ?></div>
			<?= form_input('settings_email', $user_settings->email, 'class="span-9 text" maxlength="80"'); ?>
		</div>
		<div class="clearfix append-bottom">
			<div class="span-4"><?= form_label(lang('user_confirm_email').':', 'settings_confirm_email'); ?></div>
			<?= form_input('settings_confirm_email', null, 'class="span-9 text" maxlength="80"'); ?>
		</div><?php */?>


	<?= form_fieldset_close() ?>
	<?= form_fieldset(lang('user_password_section')) ?>
		<div class="clearfix append-bottom">
			<div class="span-4"><?= form_label(lang('user_password').':', 'settings_password'); ?></div>
			<?= form_password('settings_password', null, 'class="span-9 text" maxlength="80"'); ?>
		</div>
		<div class="clearfix append-bottom">
			<div class="span-4"><?= form_label(lang('user_confirm_password').':', 'settings_confirm_password'); ?></div>
			<?= form_password('settings_confirm_password', null, 'class="span-9 text" maxlength="80"'); ?>
		</div>
	<?= form_fieldset_close() ?>
	<?= form_fieldset(lang('user_other_settings_section')) ?>
		<div class="clearfix append-bottom">
			<div class="span-4"><?= form_label(lang('user_lang').':', 'settings_lang'); ?></div>
			<?= form_dropdown('settings_lang', $languages, $user_settings->lang, 'class="span-5"'); ?>
		</div>
	<?= form_fieldset_close() ?>
	<?= form_submit('', lang('user_settings_btn'), 'class="cta"'); ?>
 <?= form_close(); ?>
 </div>