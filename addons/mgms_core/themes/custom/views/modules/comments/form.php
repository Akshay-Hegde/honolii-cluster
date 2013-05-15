<?php echo form_open("comments/create/{$module}", 'id="create-comment"') ?>

    <noscript><?php echo form_input('d0ntf1llth1s1n', '', 'style="display:none"') ?></noscript>
    <h3 class="col-headline"><?= lang('comments:your_comment'); ?></h3>

    <?php echo form_hidden('entry', $entry_hash) ?>

    <?php if ( ! is_logged_in()): ?>
    
    <div class="form_name">
        <label for="name"><?= lang('comments:name_label'); ?><span class="required">*</span>:</label>
        <?= form_input(array('name'=>'name','id'=>'name','class'=>'text','value'=>$comment['name'],'placeholder'=>lang('comments:name_label'))); ?>
    </div>
    <div class="form_email">
        <label for="email"><?= lang('global:email'); ?><span class="required">*</span>:</label>
        <?= form_input(array('name'=>'email','id'=>'email','class'=>'text','value'=>$comment['email'],'placeholder'=>lang('global:email'))); ?>
    </div>
    <div class="form_url">
        <label for="website"><?= lang('comments:website_label'); ?>:</label>
        <?= form_input(array('name'=>'website','id'=>'website','class'=>'text','value'=>$comment['website'],'placeholder'=>lang('comments:website_label'))); ?>
    </div>

    <?php endif ?>
    
    <div class="form_textarea">
        <label for="message"><?= lang('comments:message_label'); ?>:</label>
        <?= form_textarea(array('name'=>'comment','id'=>'message','class'=>'text','value'=>$comment['comment'],'placeholder'=>'Your comment...')); ?>
    </div>
    <div class="form_submit">
        <?= form_submit(array('class'=>'button','name'=>'submit'), lang('comments:send_label')); ?>
    </div>
    
<?php echo form_close() ?>