<div class="user-form">
	<?php if ($this->method === 'add'): ?>
	<h3><?php echo lang('site:create_admin'); ?></h3>
	<?php else: ?>
	<h3><?php echo sprintf(lang('site:edit_admin'), $username); ?></h3>
	<?php endif; ?>
	
	<?php echo form_open(uri_string(), 'class="crud add-user"'); ?>
	
			<ol>
				<?php echo form_hidden('id', $id); ?>
	
				<li class="<?php echo alternator('', 'even'); ?>">
					<?php echo form_label(lang('user:username'), 'username'); ?>
					<?php echo form_input('username', set_value('username', $username), 'class="required"'); ?>
				</li>
				
				<li class="<?php echo alternator('', 'even'); ?>">
					<?php echo form_label(lang('global:email'), 'email'); ?>
					<?php echo form_input('email', set_value('email', $email), 'class="required"'); ?>
				</li>
				
				<li class="<?php echo alternator('', 'even'); ?>">
					<?php echo form_label(lang('global:password'), 'password'); ?>
					<?php echo form_password('password', set_value('password', $password), 'class="required"'); ?>
				</li>
			</ol>
	
		<div class="buttons align-right padding-top">
			<?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'cancel') )); ?>
		</div>
	
	<?php echo form_close(); ?>
</div>