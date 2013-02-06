<a class="instagram" href="http://instagram.com/<?= $username ?>" title="<?= $username ?>">Instagram</a>
<ul class="instagram-feed-list">
    <?php foreach($instagramFeed->entry as $image): ?>
    <li>
        <?= img((string) $image->children('media',true)->thumbnail->attributes()->url) ?>
    </li>
    <?php endforeach; ?>
</ul>