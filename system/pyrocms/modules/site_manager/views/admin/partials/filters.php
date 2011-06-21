<div class="filter">
<?php echo form_open('admin/site_manager/users/filter'); ?>
<?php echo form_hidden('f_module', $module_details['slug']); ?>
<ul>  
	<li>
            <?php echo lang('site.active', 'f_active'); ?>
            <?php echo form_dropdown('f_active', array(0 => lang('select.all'), 1 => lang('dialog.yes'), 2 => lang('dialog.no') )); ?>
        </li>
	<li>
            <?php echo lang('site.select_site', 'f_group'); ?>
            <?php echo form_dropdown('f_group', array(0 => lang('select.all')) + $form_data); ?>
        </li>
	<li><?php echo form_input('f_keywords'); ?></li>
	<li><?php echo anchor(current_url(), lang('buttons.cancel'), 'class="cancel"'); ?></li>
</ul>
<?php echo form_close(); ?>
<br class="clear-both">
</div>
