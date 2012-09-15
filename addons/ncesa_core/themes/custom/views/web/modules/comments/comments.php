<div class="comments" id="comments_container">
	<h2><?php echo lang('comments.title'); ?></h2>
<?php if ($comments): ?>
	<ul id="comment_list">
	<?php foreach ($comments as $item): ?>
		<li class="comment mod <?php echo alternator('', 'even'); ?>">
			<div class="hd">
				<?php echo gravatar($item->email, 80); ?>
				<?php if ($item->user_id): ?>
					<p class="comment_member"><strong><?php echo anchor('user/' . $item->user_id, $this->ion_auth->get_user($item->user_id)->display_name); ?></strong></p>
				<?php else: ?>
					<p class="comment_member"><strong><?php echo anchor($item->website, $item->name); ?></strong></p>
				<?php endif; ?>
				<p class="comment_date"><?php echo format_date($item->created_on); ?></p>
			</div>
			<div class="bd">
				<p class="comment_body"><?php echo nl2br($item->comment); ?></p>
			</div>
			<div class="fd"></div>
		</li>
	<?php endforeach; ?>
	</ul>
<?php else: ?>
	<p><?php echo lang('comments.no_comments'); ?></p>
<?php endif; ?>
</div>
<div id="comments_form_container">
	<h2><?php echo lang('comments.your_comment'); ?></h2>
	<hr class="space" />
	
	<?php echo form_open('comments/create/' . $module . '/' . $id); ?>
		<?php echo form_hidden('redirect_to', $this->uri->uri_string()); ?>
		<noscript><?php echo form_input('d0ntf1llth1s1n', '', 'style="display:none"'); ?></noscript>
	
	<?php if ( ! $this->user): ?>
		<div class="clearfix">
			<div class="span-4"><div class="label-wrapper"><?= form_label(lang('comments.name_label').':', 'name'); ?></div></div>         
			<div class="span-9 last"><div class="input-wrapper"><?= form_input('name', $comment['name'], 'class="text required" maxlength="40"'); ?></div></div>
			<div class="prepend-4 span-9 input-help required">Required</div>
		</div>
		<div class="clearfix">
			<div class="span-4"><div class="label-wrapper"><?= form_label(lang('comments.email_label').':', 'email'); ?></div></div>         
			<div class="span-9 last"><div class="input-wrapper"><?= form_input('email', $comment['email'], 'class="text required" maxlength="40"'); ?></div></div>
			<div class="prepend-4 span-9 input-help required">Required</div>
		</div>
		<div class="clearfix">
			<div class="span-4"><div class="label-wrapper"><?= form_label(lang('comments.website_label').':', 'website'); ?></div></div>         
			<div class="span-9 last"><div class="input-wrapper"><?= form_input('website', $comment['website'], 'class="text required" maxlength="40"'); ?></div></div>
			<div class="prepend-4 span-9 input-help required">Required</div>
		</div>
	<?php endif; ?>
		<div class="append-bottom clearfix">
			<div class="span-4"><?= form_label(lang('comments.message_label').':', 'message'); ?></div>
			<div class="span-10 last">
				<div class="textarea-wrapper"><?= form_textarea('comment', $comment['comment'], 'id="message" class="span-10 required"'); ?></div>
				<div class="span-10 input-help required">Required</div>
			</div>
		</div>
		<div class="span-14 form-submit">
			<?= form_submit('btnSubmit', lang('comments.send_label'), 'class="submit button"') ?>
		</div>
		<div class="clear"></div>
	<?php echo form_close(); ?>
</div>