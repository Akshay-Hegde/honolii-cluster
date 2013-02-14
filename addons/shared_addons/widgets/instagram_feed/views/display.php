<?php /* if($username != NULL): ?>
     <a class="instagram" href="http://instagram.com/<?= $username ?>" title="<?= $username ?>">Instagram</a>
<?php endif; */ ?>

<ul class="instagram-feed-list">
    <?php foreach($instagramFeed->entry as $image):
        $img_prop = array(
            'src' => (string) $image->children('media',true)->thumbnail->attributes()->url,
            'data-src' => (string) $image->link,
            'alt' => (string) $image->title
        );
    ?>
    <li>
        <?= img($img_prop); ?>
    </li>
    <?php endforeach; ?>
</ul>
