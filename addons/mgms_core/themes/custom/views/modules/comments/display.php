<?php if ($comments): ?>
    <?php foreach ($comments as $item): ?>
        
        <div class="comment">
            <div class="image">
                <?= gravatar($item->email, 60); ?>
            </div>
            <div class="details">
                <div class="name">
                    <p>
                    <?= $item->user_name ?>
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

<p><?= lang('comments:no_comments'); ?></p>

<?php endif; ?>