<h3 class="col-headline"><?= lang('user:register_header') ?></h3>

<p>
	<span id="active_step"><?= lang('user:register_step1') ?></span> -&gt;
	<span><?= lang('user:register_step2') ?></span>
</p>

<?php if ( ! empty($error_string)):?>
<!-- Woops... -->
<div class="error">
	<?= $error_string;?>
</div>
<?php endif;?>

<?php
	// Form values and attributes
	$form_data = array();
	// Contact Information
	$form_data['uname'] = array('name'=>'username','id'=>'form_uname','value'=>$_user->username,'class'=>'text required','maxlength'=>'100');
	$form_data['email'] = array('name'=>'email','id'=>'form_email','value'=>$_user->email,'class'=>'text required email','maxlength'=>'100');
	$form_data['pass'] = array('name'=>'password','id'=>'form_pass','value'=>$_user->password,'class'=>'text required','maxlength'=>'100');
?>


<?php echo form_open('register', array('id' => 'register')); ?>
    
    <?php foreach($profile_fields as $field) { if($field['required'] and $field['field_slug'] != 'display_name') { ?>
        <?= form_label( (lang($field['field_name'])) ? lang($field['field_name']) : $field['field_name'], $field['field_slug']); ?>
        <?= form_input(array('name'=>$field['field_slug'],'id'=>$field['field_slug'],'value'=>$field['value'],'class'=>'text required','maxlength'=>'100')); ?>
    <?php } } ?>

	<?php if ( ! Settings::get('auto_username')): ?>
	    
		<?= form_label(lang('user:username'),$form_data['uname']['id']); ?>
		<?= form_input($form_data['uname']);?>
		
	<?php endif; ?>
	
		<?= form_label(lang('global:email'),$form_data['email']['id']); ?>
		<?= form_input($form_data['email']);?>
		<?= form_input('d0ntf1llth1s1n', ' ', 'class="default-form" style="display:none"'); ?>

		<?= form_label(lang('global:password'),$form_data['pass']['id']); ?>
		<?= form_password($form_data['pass']);?>
		<hr class="space"/>
		<?= form_submit('btnSubmit', lang('user:register_btn'), 'class="button"') ?>

<?php echo form_close(); ?>