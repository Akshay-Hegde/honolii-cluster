<div class="widget mod-<?= $widget->slug ?>">
	<?php if ($widget->options['show_title']): ?>
		<h4 class="widget-hd mod-<?= $widget->slug ?>-hd"><?= $widget->instance_title; ?></h4>
	<?php endif ?>
	<div class="widget-bd mod-<?= $widget->slug ?>-bd">
	<?= $widget->body; ?>
	</div>
</div>