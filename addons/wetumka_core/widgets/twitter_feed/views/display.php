<ul class="twitter-feed">
	<?php 
	foreach($tweets as $tweet): ?>
	<li>	
		<?= anchor('http://twitter.com/' . $username, '<img class="avatar" alt="twitter avatar" src="' . $tweet->user->profile_image_url .'" />'); ?>
		<p class="text"><?= $tweet->text; ?><span class="arrow"></span></p>
	    <p class="date"><?= format_date($tweet->created_at); ?></p>
	</li>
    <?php endforeach; ?>
</ul>