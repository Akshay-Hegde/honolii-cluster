<a class="youtube" href="https://www.youtube.com/channel/<?= $username ?>" title="<?= $videoFeed->author; ?>">YouTube Channel</a>
<ul class="youtube-feed-list">
    <?php foreach($videoFeed->entry as $video): ?>
    <li>
        <iframe width="<?= $width ?>" height="<?= $height ?>" src="http://www.youtube.com/embed/<?= $video ?>?rel=<?= $suggested ?>&html5=<?= $html5 ?>" frameborder="0" allowfullscreen></iframe>
    </li>
    <?php endforeach; ?>
</ul>