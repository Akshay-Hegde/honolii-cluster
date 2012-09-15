<div id="comments" class="comments-layout">
	<div id="existing-comments" class="comments">
		<h4><?= lang('comments.title'); ?></h4>
		<?php if ($comments): ?>
		<?php foreach ($comments as $item): ?>
		<div class="mod comment">
			<div class="hd">
				<div class="user-avatar">
					<?= gravatar($item->email, 40); ?>
				</div>
				<div class="user-meta">
					<h4 class="name">
						<?php if ($item->user_id): ?>
							<?= anchor($item->website ? $item->website : 'user/'.$item->user_id, 
												$this->ion_auth->get_user($item->user_id)->display_name, 
												$item->website ? 'rel="external nofollow"' : ''); ?>
						<?php else: ?>
							<?= $item->website ? anchor($item->website, $item->name, 'rel="external nofollow"') : $item->name; ?>
						<?php endif; ?>
					</h4>
					<p class="date">
						<?= format_date($item->created_on); ?>
					</p>
				</div>
			</div>
			<div class="bd">
				<?php if (Settings::get('comment_markdown') AND $item->parsed > ''): ?>
					<?= $item->parsed; ?>
				<?php else: ?>
					<p><?= nl2br($item->comment); ?></p>
				<?php endif; ?>
			</div>
			<div class="fd"></div>
		</div>
		<?php endforeach; ?>
		<?php else: ?>
		<p><?= lang('comments.no_comments'); ?></p>
		<?php endif; ?>
	</div>
	
	<?php
	// Form values and attributes
	$form_data = array();
	// Contact Information
	$form_data['name'] = array('name'=>'name','id'=>'form_name','value'=>$comment['name'],'class'=>'input-text','maxlength'=>'40');
	$form_data['email'] = array('name'=>'email','id'=>'form_email','value'=>$comment['email'],'class'=>'input-text','maxlength'=>'40');
	$form_data['url'] = array('name'=>'website','id'=>'form_url','value'=>$comment['website'],'class'=>'input-text','maxlength'=>'40');
	$form_data['message'] = array('name'=>'comment','id'=>'form_message','value'=>$comment['comment'],'rows'=>'5');
	?>
	
	<?= form_open('comments/create/' . $module . '/' . $id, 'id="create-comment" class="nice"'); ?>
		<?= form_fieldset('Post a comment') ?>
			<?= form_hidden('redirect_to', uri_string()); ?>
			<noscript><?= form_input('d0ntf1llth1s1n', '', 'style="display:none"'); ?></noscript>
			<?php if ( ! $current_user): ?>
			<div class="row">
				<div class="columns six">
					<?= form_label('<span class="required-field">*</span>Your Name',$form_data['name']['id']); ?>
					<?= form_input($form_data['name']); ?>
				</div>
				<div class="columns six">
					<?= form_label('<span class="required-field">*</span>Email Address',$form_data['email']['id']); ?>
					<?= form_input($form_data['email']); ?>
				</div>
			</div>
			<div class="row">
				<div class="columns ten">
					<?= form_label('Website',$form_data['url']['id']); ?>
					<?= form_input($form_data['url']); ?>
				</div>
			</div>
			<?php endif; ?>
			<div class="row">
				<div class="columns twelve">
					<?= form_label('<span class="required-field">*</span>Message',$form_data['message']['id']); ?>
					<?= form_textarea($form_data['message']); ?>
				</div>
			</div>
			<div class="form_submit">
				<?= form_submit('submit', lang('comments.send_label'), 'class="nice radius large blue button"'); ?>
			</div>
		<?= form_fieldset_close() ?>
	<?= form_close(); ?>
</div>