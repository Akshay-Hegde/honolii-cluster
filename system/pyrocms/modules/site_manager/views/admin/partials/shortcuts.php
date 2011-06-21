<nav id="shortcuts">
	<h6><?php echo lang('cp_shortcuts_title'); ?></h6>
	
	<ul>
		<li><?php echo anchor('site-manager'	, lang('site.existing_sites')); ?></li>
		<li><?php echo anchor('site-manager/create'	, lang('site.create_site'), 'class="add"'); ?></li>
		<li><?php echo anchor('site-manager/users', lang('site.super_admins')); ?>
		<li><?php echo anchor('site-manager/users/add', lang('site.add_super_admin'), 'class="add"'); ?>
	</ul>
	<br class="clear-both" />
</nav>
