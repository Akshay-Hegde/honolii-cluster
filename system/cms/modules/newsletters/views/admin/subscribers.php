<section class="title">
	<h4><?php echo lang('newsletters.subscribers'); ?></h4>
	<h4 style="float: right;"><?php echo sprintf(lang('newsletters.subscriber_count'), $subscribers); ?></h4>
</section>

<section class="item">
	<?php echo form_open('admin/newsletters/subscribers/subscribe', 'class="form_inputs"'); ?>
		<ul>
			<li class="<?php echo alternator('even', ''); ?>">
				<h4><?php echo lang('newsletters.admin_subscribe'); ?></h4>
			</li>
			<li>
				<label for="email"><?php echo lang('newsletters.email_label');?>:</label>
				<div class="input">
					<?php echo form_input('email', set_value('email')); ?>
				</div>
			</li>
		</ul>
		<div class="buttons">
			<?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'cancel') )); ?>
		</div>
	<?php echo form_close(); ?>


	<?php echo form_open('admin/newsletters/subscribers/unsubscribe', 'class="form_inputs"'); ?>
		<ul>
			<li class="<?php echo alternator('even', ''); ?>">
				<h4><?php echo lang('newsletters.admin_unsubscribe'); ?></h4>
			</li>

			<li class="<?php echo alternator('even', ''); ?>">
				<label for="email"><?php echo lang('newsletters.email_label');?></label>
				<div class="input">
					<?php echo form_input('email', set_value('email')); ?>
				</div>
			</li>
		</ul>
		<div class="buttons">
			<?php $this->load->view('admin/partials/buttons', array('buttons' => array('delete', 'cancel') )); ?>
		</div>
	<?php echo form_close(); ?>
</section>