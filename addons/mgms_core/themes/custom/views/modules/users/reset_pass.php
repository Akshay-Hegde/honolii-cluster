<h3 class="col-headline"><?php echo lang('user_reset_password_title');?></h3>

<?php if(!empty($error_string)):?>
	<div class="error"><?= $error_string;?></div>
<?php endif;?>

<?php if(!empty($success_string)): ?>
	<div class="success"><?= $success_string; ?></div>
<?php else: ?>

<?php
	// Form values and attributes
	$form_data = array();
	// Contact Information
	$form_data['user'] = array('name'=>'user_name','id'=>'form_user','value'=>set_value('email'),'class'=>'text required','maxlength'=>'40');
	$form_data['email'] = array('name'=>'email','id'=>'form_email','value'=>set_value('user_name'),'class'=>'text required email','maxlength'=>'100');

?>

	<?= form_open('users/reset_pass', array('id'=>'reset-pass')); ?>
	
	<h4><?= lang('user_reset_instructions'); ?></h4>
	<?= form_label(lang('user_email'),$form_data['email']['id']); ?>
	<?= form_input($form_data['email']);?>

	<?= form_label(lang('user_username'),$form_data['user']['id']); ?>
	<?= form_input($form_data['user']);?>
	<hr class="space"/>
	<?= form_submit('btnSubmit', lang('user_reset_pass_btn'), 'class="button"') ?>
	

	<?= form_close(); ?>
	
<?php endif; ?>