<h3><?php echo lang('comments:your_comment') ?></h3>

<?php echo form_open("comments/create/{$module}", array('id'=>'create-comment','class'=>'well')) ?>
    <noscript><?php echo form_input('d0ntf1llth1s1n', '', 'style="display:none"') ?></noscript>
	<?php echo form_hidden('entry', $entry_hash) ?>
	<ul class="fields">
		<?php if ( ! is_logged_in()): ?>
		<li class="even">
			<label for="name"><?php echo lang('comments:name_label'); ?>:</label>
			<?php echo form_input('name', $comment['name'], 'maxlength="100"'); ?>
		</li>
		<li>
			<label for="email"><?php echo lang('global:email'); ?>:</label>
			<?php echo form_input('email', $comment['email'], 'maxlength="100"'); ?>
		</li>
		<li class="even">
            <label for="website"><?php echo lang('comments:website_label'); ?>:</label>
            <?php echo form_input('website', $comment['website']); ?>
        </li>
		<?php endif; ?>
		<li>
			<label for="body"><?php echo lang('comments:message_label'); ?>:</label><br />
			<?php echo form_textarea(array('name'=>'comment', 'value' => $comment['comment'], 'rows' => 5, 'class'=>'wysiwyg-simple')); ?>
		</li>
	</ul>

	<div class="buttons float-right padding-top">
		<?= form_submit(array('class'=>'nice radius large blue button','name'=>'submit'), lang('comments:send_label')); ?>
	</div>

<?php echo form_close(); ?>