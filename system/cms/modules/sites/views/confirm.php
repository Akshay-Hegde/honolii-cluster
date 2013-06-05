<?php echo form_open(uri_string(), 'class="crud"'); ?>

	<div class="box width-50">
		<h3 class="align-center padding-top">
			<?php echo sprintf(lang('site:really_delete'), $name); ?>
			<?php echo form_hidden('id', $id); ?>
		</h3>
		
		<div class="buttons align-center padding-top">
			<?php $this->load->view('admin/partials/buttons', array('buttons' => array('delete') )); ?>
		</div>
	</div>

<?php echo form_close(); ?>