<h3 class="col-headline">
	<?php echo ($this->current_user->id !== $_user->id) ? sprintf(lang('user:edit_title'), $_user->first_name.' '.$_user->last_name) : lang('profile_edit') ?>
</h3>
<div>
	<?php if(validation_errors()):?>
	<div class="error-box">
		<?php echo validation_errors();?>
	</div>
	<?php endif;?>

	<?php echo form_open('', array('id'=>'user_edit'));?>

	<fieldset id="user_names">
		<legend>Change Profile Information</legend>
		
		<label for="display_name"><?php echo lang('user:display_name') ?></label>
		<?php echo form_input('display_name', set_value('display_name', $_user->display_name), 'class="text"'); ?>

        <?php foreach($profile_fields as $field) { ?>
            <?= form_label( (lang($field['field_name'])) ? lang($field['field_name']) : $field['field_name'], $field['field_slug']); ?>
            <?= form_input(array('name'=>$field['field_slug'],'id'=>$field['field_slug'],'value'=>$field['value'],'class'=>'text required','maxlength'=>'100')); ?>
        <?php }; ?>
	</fieldset>

	<fieldset id="user_password">
		<legend><?php echo lang('user:password_section') ?></legend>

		<label for="password"><?php echo lang('global:password') ?></label>
		<?php echo form_password('password', '' , 'autocomplete="off" class="text"'); ?>

	</fieldset>
	
	<?php echo form_submit('', lang('profile_save_btn'), 'class="button"'); ?>
	<?php echo form_close(); ?>
</div>