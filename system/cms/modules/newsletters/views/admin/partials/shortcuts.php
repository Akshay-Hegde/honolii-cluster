<nav id="shortcuts">
	<h6><?php echo lang('cp_shortcuts_title'); ?></h6>
	<ul>
		<li><?php echo anchor('admin/newsletters/create', lang('newsletters.add_title'), 'class="add"') ?></li>
		<li><?php echo anchor('admin/newsletters', lang('newsletters.list_title')); ?></li>
		<li><?php echo anchor('admin/newsletters/templates', lang('newsletters.template_manager')); ?></li>
		<li><?php echo anchor('admin/newsletters/export', lang('newsletters.export_title') . '<strong> &nbsp; - &nbsp; ' . $subscribers . '</strong>'); ?></li>
		<li><?php echo anchor('admin/newsletters/unsubscribe', lang('newsletters.unsubscribe')); ?></li>
	</ul>
	<br class="clear-both" />
</nav>