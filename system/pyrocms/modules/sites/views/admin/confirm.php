<?php echo form_open(uri_string(), 'class="crud"'); ?>

	<ol>
		<li><strong><?php echo sprintf(lang('site.really_delete'), $name); ?></strong></li>
		<?php echo form_hidden('id', $id); ?>
	</ol>
	
	<div class="buttons align-left padding-top">
		<?php $this->load->view('admin/partials/buttons', array('buttons' => array('delete') )); ?>
	</div>

<?php echo form_close(); ?>