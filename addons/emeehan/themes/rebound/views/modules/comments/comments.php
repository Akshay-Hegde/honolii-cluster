<div id="comments" class="mod comments">
	<div id="existing-comments" class="hd comments">
		<h4><?= lang('comments.title'); ?></h4>
		<?php if ($comments): ?>
			<?php foreach ($comments as $item): ?>
				
				<div class="comment">
					<div class="image">
						<?= gravatar($item->email, 60); ?>
					</div>
					<div class="details">
						<div class="name">
							<p>
							<?php if ($item->user_id): ?>
								<?= anchor($item->website ? $item->website : 'user/'.$item->user_id, 
									$this->ion_auth->get_user($item->user_id)->display_name, 
									$item->website ? 'rel="external nofollow"' : ''); ?>
							<?php else: ?>
								<?= $item->website ? anchor($item->website, $item->name, 'rel="external nofollow"') : $item->name; ?>
							<?php endif; ?>
							</p>
						</div>
						<div class="date">
							<p><?= format_date($item->created_on); ?></p>
						</div>
						<div class="content">
							<?php if (Settings::get('comment_markdown') AND $item->parsed > ''): ?>
								<?= $item->parsed; ?>
							<?php else: ?>
								<p><?= nl2br($item->comment); ?></p>
							<?php endif; ?>
						</div>
					</div>
				</div><!-- close .comment -->
				
			<?php endforeach; ?>
		<?php else: ?>
		
		<p><?= lang('comments.no_comments'); ?></p>
		
		<?php endif; ?>
	</div>
	<div id="comments-form" class="bd comments-form">
		<?= form_open('comments/create/' . $module . '/' . $id, array('id'=>'create-comment','class'=>'well')); ?>
			<h4><?= lang('comments.your_comment'); ?></h4>
			<?= form_hidden('redirect_to', uri_string()); ?>
			<noscript><?= form_input('d0ntf1llth1s1n', '', 'style="display:none"'); ?></noscript>

			<?php if ( ! $current_user): ?>
			<div class="form_name">
				<label for="name"><?= lang('comments.name_label'); ?>:</label>
				<?= form_input(array('name'=>'name','id'=>'name','class'=>'span8','value'=>$comment['name'],'placeholder'=>lang('comments.name_label'))); ?>
			</div>
			<div class="form_email">
				<label for="email"><?= lang('comments.email_label'); ?>:</label>
				<?= form_input(array('name'=>'email','id'=>'email','class'=>'span8','value'=>$comment['email'],'placeholder'=>lang('comments.email_label'))); ?>
			</div>
			<div class="form_url">
				<label for="website"><?= lang('comments.website_label'); ?>:</label>
				<?= form_input(array('name'=>'website','id'=>'website','class'=>'span8','value'=>$comment['website'],'placeholder'=>lang('comments.website_label'))); ?>
			</div>
			<?php endif; ?>
			
			<div class="form_textarea">
				<label for="message"><?= lang('comments.message_label'); ?>:</label><br />
				<?= form_textarea(array('name'=>'comment','id'=>'message','class'=>'span10','value'=>$comment['comment'],'placeholder'=>'Your comment...')); ?>
			</div>
			<div class="form_submit">
				<?= form_submit(array('class'=>'btn','name'=>'submit'), lang('comments.send_label')); ?>
			</div>
		<?= form_close(); ?>
	</div>
	<div class="fd"></div>
</div>