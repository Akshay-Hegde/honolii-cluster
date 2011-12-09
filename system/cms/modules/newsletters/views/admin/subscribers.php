<section class="title">
	<h4><?php echo lang('newsletters.subscribers'); ?></h4>
	<h4 style="float: right;"><?php echo sprintf(lang('newsletters.subscriber_count'), $subscribers); ?></h4>
</section>

<section class="item">
	<?php echo form_open('admin/newsletters/subscribers/subscribe'); ?>
		<ul>
			<li class="<?php echo alternator('even', ''); ?>">
				<h4><?php echo lang('newsletters.subscribe'); ?></h4>
			</li>
			<li>
				<label for="email"><?php echo lang('newsletters.email_label');?>:</label>
				<br />
				<?php echo form_input('email', set_value('email')); ?>
			</li>
			
			<hr>
		</ul>
		<div class="buttons float-left">
			<?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'cancel') )); ?>
		</div>
	<?php echo form_close(); ?>


	<?php echo form_open('admin/newsletters/subscribers/unsubscribe', 'class="crud"'); ?>
		<ul>
			<li class="<?php echo alternator('even', ''); ?>">
				<h4><?php echo lang('newsletters.unsubscribe'); ?></h4>
			</li>

			<li class="<?php echo alternator('even', ''); ?>">
				<label for="email"><?php echo lang('newsletters.email_label');?></label>
				<br />
				<?php echo form_input('email', set_value('email')); ?>
			</li>
			
			<hr>
		</ul>
		<div class="buttons float-left">
			<?php $this->load->view('admin/partials/buttons', array('buttons' => array('delete', 'cancel') )); ?>
		</div>
	<?php echo form_close(); ?>
</section>