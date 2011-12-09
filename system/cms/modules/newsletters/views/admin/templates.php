<section class="title">
	<h4><?php echo lang('newsletters.template_manager'); ?></h4>
</section>

<section class="item">
<?php echo form_open('admin/newsletters/templates', 'class="crud"'); ?>

	<ul>
		<li class="<?php echo alternator('', 'even'); ?>">
			<h4><?php echo lang('newsletters.template_select_edit'); ?></h4>
		</li>
				
		<li class="<?php echo alternator('', 'even'); ?>">
			<div class="template-delete">
				<label for="template_list"><?php echo lang('newsletters.template_select');?></label>
				<br />
				<?php echo form_dropdown('template_list', $template_list, '', 'class = "select_edit edit"'); ?>
				<?php echo image('loading.gif', 'newsletters', array('id' => 'template-loading', 'alt' => 'loading template')); ?>
				<?php $this->load->view('admin/partials/buttons', array('buttons' => array('delete') )); ?>
			</div>
		</li>
		
		<hr>
		
		<li class="<?php echo alternator('', 'even'); ?>">
			<h4 id="new_label"><?php echo lang('newsletters.template_new'); ?></h4>
			<h4 id="edit_label" style="display: none;"><?php echo lang('newsletters.template_edit'); ?></h4>
		</li>
		
		<?php echo form_hidden('id', 0); ?>
		<li class="<?php echo alternator('', 'even'); ?>">
			<label for="title"><?php echo lang('newsletters.template_title');?></label>
			<br ?>
			<?php echo form_input(array('id' => 'title', 'name' => 'title', 'value' => set_value('title'))); ?>
			<span class="required-icon tooltip"><?php echo lang('required_label');?></span>
		</li>
		
		<hr>

		<li class="<?php echo alternator('', 'even'); ?>">
			<label for="body"><?php echo lang('newsletters.template_body');?></label>
			<br />
			<?php echo form_textarea(array('id'=>'body', 'name'=>'body', 'value' => set_value('body'), 'rows' => 40, 'class'=>'wysiwyg-advanced')); ?>
		</li>
	</ul>
	<div class="buttons float-left">
		<?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'cancel') )); ?>
	</div>
<?php echo form_close(); ?>
</section>