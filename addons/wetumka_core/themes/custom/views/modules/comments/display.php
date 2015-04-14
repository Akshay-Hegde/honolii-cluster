<?php if ($comments): ?>
	<ul class="media-list">
	<?php foreach ($comments as $item): ?>  	
		<li class="media comment" id="comment-<?php echo $item->created_on . '-' . $item->id; ?>">
			<div class="pull-left">
				<img src="<?php echo gravatar($item->user_email, 60, NULL, TRUE) ?>" class="img-circle" />
			</div>
			<div class="media-body">
				<div class="media-heading">
					<strong class="large"><?php echo $item->user_name ?></strong><wbr/>
					<span class="nobreak"><span class="glyphicon glyphicon-time"></span><a class="small" href="<?php echo base_url($item->uri) . '#comment-' . $item->created_on . '-' . $item->id;?>"><?php echo format_date($item->created_on, 'F n, Y g:i a') ?></a></span>
				</div>
				<?php if (Settings::get('comment_markdown') and $item->parsed): ?>
					<?php echo $item->parsed ?>
				<?php else: ?>
					<p><?php echo nl2br($item->comment) ?></p>
				<?php endif ?>
			</div>
		</li>
	<?php endforeach ?>
	</ul>
<?php else: ?>
	<p><?php echo lang('comments:no_comments') ?></p>
<?php endif ?>