<div class="widget mod <?= $widget->slug ?>">
	<?php if ($widget->options['show_title']): ?>
		<h4 class="hd"><?= $widget->instance_title; ?></h4>
	<?php endif ?>
	<div class="bd">
	<?= $widget->body; ?>
	</div>
</div>