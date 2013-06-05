<?php if ($this->method === 'create'): ?>
<h3><?php echo lang('site:create_site'); ?></h3>
<?php else: ?>
<h3><?php echo sprintf(lang('site:edit_site'), $name); ?></h3>
<?php endif; ?>

<?php echo form_open(uri_string(), 'class="crud"'); ?>

		<ol class="site">
			<?php echo form_hidden('id', $id); ?>
			<?php echo form_hidden('user_id', $user_id); ?>
			
			<h4><?php echo lang('site:site_details'); ?></h4>
			<li class="<?php echo alternator('even', ''); ?>">
				<?php echo form_label(lang('site:descriptive_name'), 'name'); ?>
				<?php echo form_input('name', set_value('name', $name), 'class="required"'); ?>
			</li>

			<li class="<?php echo alternator('even', ''); ?>">
				<?php echo form_label(lang('site:domain'), 'domain'); ?>
				<?php echo form_input('domain', set_value('domain', $domain), 'class="required"'); ?>
			</li>
			
			<li class="<?php echo alternator('even', ''); ?>">
				<?php echo form_label(lang('site:ref'), 'ref'); ?>
				<?php echo form_input('ref', set_value('ref', $ref), 'class="required"'); ?>
			</li>
		</ol>
		
		<ol class="user">
			<h4><?php echo lang('site:first_admin'); ?></h4>
			<li class="<?php echo alternator('', 'even'); ?>">
				<?php echo form_label(lang('user:username'), 'username'); ?>
				<?php echo form_input('username', set_value('username', $username), 'class="required"'); ?>
			</li>
			
			<li class="<?php echo alternator('', 'even'); ?>">
				<?php echo form_label(lang('user:first_name'), 'first_name'); ?>
				<?php echo form_input('first_name', set_value('first_name', $first_name), 'class="required"'); ?>
			</li>
			
			<li class="<?php echo alternator('', 'even'); ?>">
				<?php echo form_label(lang('user:last_name'), 'last_name'); ?>
				<?php echo form_input('last_name', set_value('last_name', $last_name), 'class="required"'); ?>
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