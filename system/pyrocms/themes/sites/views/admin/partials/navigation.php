<nav id="main-nav">
	<ul>
			<li><?php echo anchor('sites', lang('site.existing_sites'));?></li>
			<li><?php echo anchor('sites/create', lang('site.create_site'));?></li>
			<li><?php echo anchor('sites/users', lang('site.super_admins'));?></li>
			<li><?php echo anchor('sites/users/add', lang('site.add_super_admin'), 'class="last"');?></li>
	</ul>
</nav>
