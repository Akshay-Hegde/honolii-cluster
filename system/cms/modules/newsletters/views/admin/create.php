<section class="title">
	<h4><?php echo lang('newsletters.add_title'); ?></h4>
</section>

<section class="item">
<?php echo form_open('admin/newsletters/create', 'class="form_inputs"'); ?>
	
	<ul>
		<li class="<?php echo alternator('', 'even'); ?>">
			<label for="template_list"><?php echo lang('newsletters.template_select');?></label>
			<div class="input">
				<?php echo form_dropdown('template_list', $template_list, '', 'class = "select_edit"'); ?>
				<?php echo Asset::img('module::loading.gif', 'loading template', array('id' => 'template-loading')); ?>
			</div>
		</li>
		
		<li class="<?php echo alternator('', 'even'); ?>">
			<label for="title"><?php echo lang('newsletters.title_label');?></label>
			<div class="input">
				<input type="text" id="title" name="title" maxlength="100" value="<?php echo $newsletter->title; ?>" class="text" />
				<span class="required-icon tooltip"><?php echo lang('required_label');?></span>
			</div>
		</li>
		
		<li class="<?php echo alternator('', 'even'); ?>">
			<label for="newsletter_opens"><?php echo lang('newsletters.title_newsletter_opens');?></label>
			<?php echo form_checkbox('read_receipts', '1', TRUE); ?>
		</li>
		
		<li>
			<label><?php echo lang('newsletters.title_newsletter_urls');?></label>
			<a class="url-add button" href="#"><?php echo lang('global:add'); ?></a>
		</li>
		
		<?php if($newsletter->tracked_urls > ''): ?>
			<?php foreach($newsletter->tracked_urls AS $url => $hash): ?>
				<li class="<?php echo alternator('', 'even'); ?>">
					<?php echo lang('newsletters.target').': '; echo form_input('tracked_urls[target][]', $url, 'class="width-25 target" style="margin-right: 20px;"'); ?>
					<?php echo lang('newsletters.url').': '; echo form_input('tracked_urls[hash][]', $hash, 'class="width-25 url"'); ?>
					
					<a class="url-remove button" href="#"><?php echo lang('global:remove'); ?></a>
				</li>
			<?php endforeach; ?>
		<?php endif; ?>
		
		<li style="display:none;" >
			<?php echo lang('newsletters.target').': '; echo form_input('tracked_urls[target][]', set_value('tracked_urls[target][]'), 'class="width-25 target" style="margin-right: 20px;"'); ?>
			<?php echo lang('newsletters.url').': '; echo form_input('tracked_urls[hash][]', set_value('tracked_urls[hash][]'), 'class="width-25 url"'); ?>
			
			<a class="url-remove button" href="#"><?php echo lang('global:remove'); ?></a>
		</li>
		
		<li class="<?php echo alternator('', 'even'); ?>">
			<label for="body"><?php echo lang('newsletters.body');?></label>
			<br style="clear:both" />
			<?php echo form_textarea(array('id'=>'body', 'name'=>'body', 'value' => set_value('body', stripslashes($newsletter->body)), 'rows' => 40, 'class'=>'wysiwyg-advanced')); ?>
		</li>
	</ul>
	<div class="buttons float-left">
		<?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'cancel') )); ?>
	</div>
<?php echo form_close(); ?>
</section>