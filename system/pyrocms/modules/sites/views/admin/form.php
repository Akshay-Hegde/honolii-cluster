<?php if ($this->method === 'create'): ?>
<h3><?php echo lang('site.create_site'); ?></h3>
<?php else: ?>
<h3><?php echo sprintf(lang('site.edit_site'), $name); ?></h3>
<?php endif; ?>

<?php echo form_open(uri_string(), 'class="crud"'); ?>

		<ol>
			<?php echo form_hidden('id', $id); ?>
			
			<li class="<?php echo alternator('', 'even'); ?>">
				<?php echo form_label(lang('site.name'), 'name'); ?>
				<?php echo form_input('name', set_value('name', $name), 'class="required"'); ?>
			</li>

			<li class="<?php echo alternator('', 'even'); ?>">
				<?php echo form_label(lang('site.domain'), 'domain'); ?>
				<?php echo form_input('domain', set_value('domain', $domain), 'class="required"'); ?>
			</li>
			
			<li class="<?php echo alternator('', 'even'); ?>">
				<?php echo form_label(lang('site.ref'), 'ref'); ?>
				<?php echo form_input('ref', set_value('ref', $ref), 'class="required"'); ?>
			</li>

		</ol>

	<div class="buttons align-right padding-top">
		<?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'cancel') )); ?>
	</div>

<?php echo form_close(); ?>