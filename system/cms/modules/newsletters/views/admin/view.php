<div class="box">
	
	<h3><?php echo $newsletter->title; ?></h3>
	
	<div class="box-container">
		<p>
			<em><?php echo lang('newsletters.created');?>: <?php echo format_date($newsletter->created_on); ?></em>	
			<?php if($newsletter->sent_on): ?>
				<br />
				<em><?php echo lang('newsletters.sent');?>: <?php echo format_date($newsletter->sent_on); ?></em>
			<?php endif; ?>
		</p>
		
		<hr />
		
		<?php echo html_entity_decode(stripslashes($newsletter->body)); ?>
	</div>
</div>