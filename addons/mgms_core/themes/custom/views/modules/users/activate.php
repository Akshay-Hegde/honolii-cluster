<h3 class="col-headline"><?= lang('user:register_header') ?></h3>

<div class="workflow_steps">
	<span id="active_step"><?= lang('user:register_step1') ?></span> &gt;
	<span><?= lang('user:register_step2') ?></span>
</div>

<?php if(!empty($error_string)): ?>
<div class="error">
	<?= $error_string; ?>
</div>
<?php endif;?>

<?php
	// Form values and attributes
	$form_data = array();
	// Contact Information
	$form_data['email'] = array('name'=>'email','id'=>'form_email','value'=>isset($_user['email']) ? $_user['email'] : '','class'=>'text required','maxlength'=>'100');
	$form_data['activate'] = array('name'=>'activation_code','id'=>'form_activate','value'=>NULL,'class'=>'text required','maxlength'=>'40');
?>


<?= form_open('users/activate', 'id="activate-user"'); ?>
	
	<?= form_label(lang('global:email'),$form_data['email']['id']); ?>
	<?= form_input($form_data['email']);?>

	<?= form_label(lang('user:activation_code'),$form_data['activate']['id']); ?>
	<?= form_input($form_data['activate']);?>
	<hr class="space"/>
	<?= form_submit('btnSubmit', lang('user:activate_btn'), 'class="button"') ?>

<?= form_close(); ?>