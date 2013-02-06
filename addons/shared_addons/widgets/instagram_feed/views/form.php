<ol>
	<li class="even">
		<label>Hashtag</label>
		<?php echo form_input('hashtag', $options['hashtag']); ?>
	</li>
	<li class="odd">
        <label>Username</label>
        <?php echo form_input('username', $options['username']); ?>
    </li>
	<li class="even">
		<label>Number of images (max:12)</label>
		<?php echo form_input('number', $options['number']); ?>
	</li>
	<li class="odd">
		<label>Cache time - minutes</label>
		<?php echo form_input('cache', $options['cache']); ?>
	</li>
</ol>