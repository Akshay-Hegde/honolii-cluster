<ol>
	<li class="even">
		<label>User Name/ID</label>
		<?php echo form_input('username', $options['username']); ?>
	</li>
	<li class="odd">
		<label>Number of items (max:25)</label>
		<?php echo form_input('number', $options['number']); ?>
	</li>
	<li class="even">
		<label>Cache time - minutes</label>
		<?php echo form_input('cache', $options['cache']); ?>
	</li>
</ol>