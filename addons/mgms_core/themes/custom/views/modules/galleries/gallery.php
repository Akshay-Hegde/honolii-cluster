<h3 class="col-headline"><?= $gallery->title; ?></h3>
<div class="gallery-container" id="gallery_single">
	<div class="gallery-thumb mod clearfix">
		<div class="hd">
			<p><?= $gallery->description; ?></p>
		</div>
		<ul class="bd">
			<?php if ($gallery_images): ?>
			<?php foreach ( $gallery_images as $image): ?>
			<li class="<?= alternator('clear-row',null,null,null,null); ?>">
				<a href="<?= site_url('galleries/'.$gallery->slug.'/'.$image->id); ?>" class="gallery-image" rel="gallery-image" data-src="<?= site_url('files/large/'.$image->file_id); ?>" title="<?= $image->name; ?>">
					<?= img(array('src' => site_url('files/thumb/'.$image->file_id .'/160'), 'alt' => $image->name)); ?>
				</a>
			</li>
			<?php endforeach; ?>
			<?php endif; ?>
		</ul>
	</div>
</div>
<?php if ( ! empty($sub_galleries) ): ?>
<h4><?= lang('galleries.sub-galleries_label'); ?></h4>
<div class="sub_gallery_container">
	<?php foreach ($sub_galleries as $sub_gallery): ?>
	<div class="gallery-image mod clearfix">
		<div class="hd">
			<?php if ( ! empty($sub_gallery->filename)) : ?>
			<a href="<?= site_url('galleries/'.$sub_gallery->slug); ?>">
				<?= img(array('src' => site_url('files/thumb/'.$sub_gallery->file_id), 'alt' => $sub_gallery->title)); ?>
			</a>
			<?php endif; ?>
			<h3><?= anchor('galleries/' . $sub_gallery->slug, $sub_gallery->title); ?></h3>
		</div>
		<div class="bd">
			<p>
				<?= ( ! empty($sub_gallery->description)) ? $sub_gallery->description : lang('galleries.no_gallery_description'); ?>
			</p>
		</div>
	</div>
	<?php endforeach; ?>
</div>
<?php endif; ?>
<?php if ($gallery->enable_comments == 1): ?>
	<?= display_comments($gallery->id);?>
<?php endif; ?>