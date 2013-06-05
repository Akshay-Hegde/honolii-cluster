<?php if ($comments): ?>
    <?php foreach ($comments as $item): ?>
        <div class="comment">
            <div class="image">
                <?= gravatar($item->user_email, 60) ?>
            </div>
            <div class="details">
                <div class="name">
                    <p>
                        <?php if ($item->user_id): ?>
                            <?= anchor($item->user_website ? $item->user_website : 'user/'.$item->user_id, 
                                $this->ion_auth->get_user($item->user_id)->display_name, 
                                $item->user_website ? 'rel="external nofollow"' : ''); ?>
                        <?php else: ?>
                            <?= $item->user_website ? anchor($item->user_website, $item->user_name, 'rel="external nofollow"') : $item->user_name; ?>
                        <?php endif; ?>
                    </p>
                </div>
                <div class="date">
                    <p><?php echo format_date($item->created_on) ?></p>
                </div>
                <div class="content">
                    <?php if (Settings::get('comment_markdown') and $item->parsed): ?>
                        <?php echo $item->parsed ?>
                    <?php else: ?>
                        <p><?php echo nl2br($item->comment) ?></p>
                    <?php endif ?>
                </div>
            </div>
        </div><!-- close .comment -->
    <?php endforeach ?>
<?php else: ?>
    <p><?php echo lang('comments:no_comments') ?></p>
<?php endif ?>