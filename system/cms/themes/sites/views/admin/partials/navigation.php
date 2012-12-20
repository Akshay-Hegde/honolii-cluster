<nav id="primary">
	<ul>
			<li>
				<?php echo anchor('sites', lang('site:existing_sites'),
								  ( ! $this->uri->segment(2) OR
								   $this->uri->segment(2) == 'addons' OR
								   $this->uri->segment(2) == 'edit') ? 'class="current"' : '');?>
			</li>
			<li>
				<?php echo anchor('sites/create', lang('site:create_site'),
								  ($this->uri->segment(2) == 'create') ? 'class="current"' : '');?>
			</li>
			<li>
				<?php echo anchor('sites/users', lang('site:super_admins'),
								  ($this->uri->segment(2) == 'users' AND
								   ! $this->uri->segment(3) OR
								   $this->uri->segment(3) == 'edit') ? 'class="current"' : '');?>
			</li>
			<li>
				<?php echo anchor('sites/users/add', lang('site:add_super_admin'),
								  ($this->uri->segment(3) == 'add') ? 'class="current last"' : 'class="last"');?>
			</li>
			<li>
				<?php echo anchor('sites/logout', lang('cp:logout_label')); ?>
			</li>
			<li>
				<?php echo anchor('sites/settings', lang('site:settings'), 'class="modal"'); ?>
			</li>
	</ul>
</nav>
