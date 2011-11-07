<section class="title">
	<h4><?php echo lang('newsletters.list_title'); ?></h4>
</section>

<section class="item">
	
<?php if (!empty($newsletters)): ?>
	<p class="newsletter-messages">
	  <span class="sending" ><?php echo image('loading.gif', 'newsletters', array('alt' => 'sending')); ?>
							 <?php echo lang('newsletters.sending'); ?>
							 <br />
	  </span>
	</p>

	<table border="0" class="table-list">    
	  <thead>
			<tr>
				<th><?php echo lang('newsletters.subject');?></th>
				<th><?php echo lang('newsletters.created');?></th>
				<th><?php echo lang('newsletters.sent');?></th>
				<th><span><?php echo lang('newsletters.actions');?></span></th>
			</tr>
	  </thead>
		<tbody>
			<?php foreach ($newsletters as $newsletter): ?>
			<tr>
				<td><?php echo $newsletter->title; ?></td>
				<td><?php echo format_date($newsletter->created_on); ?></td>	
				<?php if($newsletter->sent_on > 0): ?>
					<td><?php echo format_date($newsletter->sent_on);?></td>
				<?php else: ?>
					<td><em><?php echo lang('newsletters.not_sent_label');?></em></td>
				<?php endif; ?>	
				<td>
					<?php if($newsletter->sent_on == 0): ?>
					<?php echo anchor('admin/newsletters/edit/' . $newsletter->id, lang('newsletters.edit')); ?>
					| <?php endif; ?>
					<?php echo anchor('admin/newsletters/view/' . $newsletter->id, lang('newsletters.view'), array('class' => 'colorbox')); ?>
					| <?php echo anchor('admin/newsletters/delete/' . $newsletter->id, lang('newsletters.delete'), array('class'=>'confirm')); ?>
					<?php if($newsletter->sent_on == 0 AND $newsletter->send_cron == 0): ?>
					|  <?php if($this->settings->newsletter_cron_enabled == 1): ?>
							  <?php echo anchor('admin/newsletters', lang('newsletters.send_cron'), array('class' => 'newsletter-send', 'title'=>lang('newsletters.confirm'), 'id' => $newsletter->id)); ?>
					  <?php elseif($this->settings->newsletter_email_limit > 0): ?>
							  <?php echo anchor('admin/newsletters', lang('newsletters.send_batch'), array('class' => 'newsletter-send batch', 'title'=>lang('newsletters.confirm'), 'id' => $newsletter->id)); ?>
					  <?php else: ?>
							  <?php echo anchor('admin/newsletters', lang('newsletters.send'), array('class' => 'newsletter-send', 'title'=>lang('newsletters.confirm'), 'id' => $newsletter->id)); ?>
					  <?php endif; ?>
					<?php elseif($newsletter->sent_on == 0 AND $newsletter->send_cron == 1): ?>
					| <?php echo lang('newsletters.pending'); ?>
					<?php else: ?>
					| <?php echo anchor('admin/newsletters/statistics/' . $newsletter->id, lang('newsletters.stats')); ?>
					<?php endif; ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php else: ?>
	<div class="blank-slate">
			  <?php echo image('news.png', 'newsletters', array('alt' => 'news')); ?>
			
		<h4><?php echo lang('newsletters.no_newsletters_error');?></h4>
	</div>
<?php endif;?>

<p><?php $this->load->view('admin/partials/pagination'); ?></p>

</section>