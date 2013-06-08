<div class="filter">
<?php echo form_open('sites/users/filter'); ?>
<?php echo form_hidden('f_module', $this->module_details['slug']); ?>
<ul>  
	<li>
            <?php echo lang('site:select_group', 'f_group'); ?>
            <?php echo form_dropdown('f_group', array(0 => lang('site:super_admins')) + $form_data); ?>
        </li>
	<li>
		<?php echo lang('site:keywords', 'f_keywords'); ?>
		<?php echo form_input('f_keywords'); ?>
	</li>
	
	<li><?php echo anchor(current_url(), lang('buttons:cancel'), 'class="cancel"'); ?></li>
</ul>
<?php echo form_close(); ?>
<br class="clear-both">
</div>
