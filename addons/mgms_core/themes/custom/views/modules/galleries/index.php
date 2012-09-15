<h3 class="col-headline"><?= lang('galleries.galleries_label'); ?></h3>

<div class="gallery-container clearfix" id="gallery_index">
	<?php if ( ! empty($galleries)): foreach ($galleries as $gallery): if (empty($gallery->parent)): ?>
	<div class="mod gallery clearfix <?= alternator('clear-row',null); ?>">
		<div class="hd">
			<?php if ( ! empty($gallery->filename)): ?>
			<a href="<?= site_url('galleries/'.$gallery->slug); ?>">
				<?= img(array('src' => site_url('files/thumb/'.$gallery->file_id.'/150/150'), 'alt' => $gallery->title)); ?>
			</a>
			<?php endif; ?>
		</div>
		<div class="bd">
			<h3><?= anchor('galleries/' . $gallery->slug, $gallery->title); ?></h3>
			<?= ( ! empty($gallery->description)) ? $gallery->description : lang('galleries.no_gallery_description'); ?>
			<?= anchor('galleries/' . $gallery->slug, 'View gallery &raquo;', 'class="cta"'); ?>
		</div>
	</div>
	<?php endif; endforeach; else: ?>
		
	<p><?= lang('galleries.no_galleries_error'); ?></p>
	
	<?php endif; ?>
</div>