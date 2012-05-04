<h3 class="col-headline"><?= lang('user_register_header') ?></h3>

<p>
	<span id="active_step"><?= lang('user_register_step1') ?></span> -&gt;
	<span><?= lang('user_register_step2') ?></span>
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
	$form_data['fname'] = array('name'=>'first_name','id'=>'form_fname','value'=>$_user->first_name,'class'=>'text required','maxlength'=>'40');
	$form_data['lname'] = array('name'=>'last_name','id'=>'form_lname','value'=>$_user->last_name,'class'=>'text required','maxlength'=>'40');
	$form_data['uname'] = array('name'=>'username','id'=>'form_uname','value'=>$_user->username,'class'=>'text required','maxlength'=>'100');
	$form_data['email'] = array('name'=>'email','id'=>'form_email','value'=>$_user->email,'class'=>'text required email','maxlength'=>'100');
	$form_data['pass'] = array('name'=>'password','id'=>'form_pass','value'=>NULL,'class'=>'text required','maxlength'=>'100');
?>


<?php echo form_open('register', array('id' => 'register')); ?>

		<?= form_label(lang('user_first_name'),$form_data['fname']['id']); ?>
		<?= form_input($form_data['fname']);?>

		<?= form_label(lang('user_last_name'),$form_data['lname']['id']); ?>
		<?= form_input($form_data['lname']);?>
	
	<?php if ( ! Settings::get('auto_username')): ?>
		<?= form_label(lang('user_username'),$form_data['uname']['id']); ?>
		<?= form_input($form_data['uname']);?>
	<?php endif; ?>
	
		<?= form_label(lang('user_email'),$form_data['email']['id']); ?>
		<?= form_input($form_data['email']);?>
		<?= form_input('d0ntf1llth1s1n', ' ', 'class="default-form" style="display:none"'); ?>

		<?= form_label(lang('user_password'),$form_data['pass']['id']); ?>
		<?= form_password($form_data['pass']);?>
		<hr class="space"/>
		<?= form_submit('btnSubmit', lang('user_register_btn'), 'class="button"') ?>

<?php echo form_close(); ?>