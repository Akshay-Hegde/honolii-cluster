<div class="box">
<h2 id="breadcrumbs" class="icon portfolio"><span class="current">Gallery</span></h2>
<hr/>
</div>
<!-- Div containing all galleries -->
<div class="galleries_container clearfix" id="gallery_index">
	<?php if ( ! empty($galleries)): foreach ($galleries as $gallery): if (empty($gallery->parent)): ?>
	<div class="span-8 <?= alternator('', 'last'); ?>">
		<?php if ( ! empty($gallery->filename)): ?>
		<div class="mod gallery-image" style="background-image: url(<?= site_url() . 'files/thumb/' . $gallery->file_id . '/400/500' ?>)">
		<?php else: ?>
		<div class="mod gallery-image">
		<?php endif; ?>
			<a href="<?= 'galleries/' . $gallery->slug; ?>" title="<?= $gallery->title; ?>"></a>
			<div class="label">
			<div class="title"><?= $gallery->title; ?></div>
			
			</div>
		</div>
	</div>
	<?php endif; endforeach; else: ?>
	<p><?= lang('galleries.no_galleries_error'); ?></p>
	<?php endif; ?>
	<hr class="space"/>
</div>