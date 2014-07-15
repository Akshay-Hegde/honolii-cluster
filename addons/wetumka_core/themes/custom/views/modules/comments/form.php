<?php echo form_open("comments/create/{$module}", 'id="create-comment"') ?>
	<noscript><?php echo form_input('d0ntf1llth1s1n', '', 'style="display:none"') ?></noscript>
	<h3 class="text-center">Leave a comment</h3>
	<p>Tell us your thoughts and contribute to the conversation. All comments are moderated for spam. Thanks for checking out our blog.</p>
	<?php echo form_hidden('entry', $entry_hash) ?>
	
	<?php if ( ! is_logged_in()): ?>
	<div class="row">
		<div class="col-sm-6">
			<div class="form-group">
				<label for="name"><?php echo lang('comments:name_label') ?><span class="required">*</span></label>
				<input class="form-control" type="text" name="name" id="name" maxlength="40" value="<?php echo $comment['name'] ?>" required />
			</div>
		</div>
		<div class="col-sm-6">
			<div class="form-group">
				<label for="email"><?php echo lang('global:email') ?><span class="required">*</span></label>
				<input class="form-control" type="email" name="email" maxlength="40" value="<?php echo $comment['email'] ?>" required />
			</div>
		</div>
	</div>
	
	
	<?php /*
	<div class="form_url">
		<label for="website"><?php echo lang('comments:website_label') ?>:</label>
		<input type="text" name="website" maxlength="40" value="<?php echo $comment['website'] ?>" />
	</div>
     */ ?>

	<?php endif ?>

	<div class="form-group">
		<label for="comment"><?php echo lang('comments:message_label') ?><span class="required">*</span></label>
		<textarea class="form-control" name="comment" id="comment" rows="5" required><?php echo $comment['comment'] ?></textarea>
	</div>

	<?php echo form_submit('submit', 'Submit Comment }', 'class="btn btn-primary"') ?>


<?php echo form_close() ?>