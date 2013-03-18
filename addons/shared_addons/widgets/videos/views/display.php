<?php if($videohost === 'youtube'): ?>
<a class="youtube" href="https://www.youtube.com/channel/<?= $username ?>"> YouTube Channel</a>
<?php elseif($videohost === 'vimeo'): ?>
<a class="vimeo" href="https://vimeo.com/<?= $username ?>"> Vimeo Channel</a>
<?php endif; ?>
<ul class="video-feed-list">
    <?php foreach($videoFeed->entry as $video): ?>
    <li>
        <?php if($videohost === 'youtube'): ?>
        <iframe class="youtube" src="http://www.youtube.com/embed/<?= $video ?>?rel=<?= $utsuggested ?>&amp;html5=<?= $uthtml5 ?>"></iframe>
        <?php elseif($videohost === 'vimeo'): ?>
        <iframe class="vimeo" src="http://player.vimeo.com/video/<?= $video ?>?title=<?= $vtitle ?>&amp;byline=<?= $vbyline ?>&amp;portrait=<?= $vportrait ?>"></iframe>
        <?php endif; ?>
    </li>
    <?php endforeach; ?>
</ul>