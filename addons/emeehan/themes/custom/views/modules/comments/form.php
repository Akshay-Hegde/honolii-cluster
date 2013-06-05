<?php echo form_open("comments/create/{$module}", array('id'=>'create-comment','class'=>'well')) ?>

    <noscript><?php echo form_input('d0ntf1llth1s1n', '', 'style="display:none"') ?></noscript>

    <h4><?php echo lang('comments:your_comment') ?></h4>

    <?php echo form_hidden('entry', $entry_hash) ?>
    <?php if ( ! is_logged_in()): ?>

    <div class="form_name">
        <label for="name"><?php echo lang('comments:name_label') ?><span class="required">*</span>:</label>
        <?= form_input(array('name'=>'name','id'=>'name','class'=>'span8','value'=>$comment['name'],'placeholder'=>lang('comments:name_label'))); ?>
    </div>

    <div class="form_email">
        <label for="email"><?php echo lang('global:email') ?><span class="required">*</span>:</label>
         <?= form_input(array('name'=>'email','id'=>'email','class'=>'span8','value'=>$comment['email'],'placeholder'=>lang('global:email'))); ?>
    </div>

    <div class="form_url">
        <label for="website"><?php echo lang('comments:website_label') ?>:</label>
        <?= form_input(array('name'=>'website','id'=>'website','class'=>'span8','value'=>$comment['website'],'placeholder'=>lang('comments:website_label'))); ?>
    </div>

    <?php endif ?>

    <div class="form_textarea">
        <label for="comment"><?php echo lang('comments:message_label') ?><span class="required">*</span>:</label><br />
        <?= form_textarea(array('name'=>'comment','id'=>'message','class'=>'span10','value'=>$comment['comment'],'placeholder'=>'Your comment...')); ?>
    </div>
    <div class="form_submit">
        <?= form_submit(array('class'=>'btn','name'=>'submit'), lang('comments:send_label')); ?>
    </div>

<?php echo form_close() ?>