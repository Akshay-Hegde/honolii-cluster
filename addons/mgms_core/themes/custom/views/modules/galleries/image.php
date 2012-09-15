<h3 class="col-headline"><?= $gallery_image->name; ?></h3>
<div class="back-btn"><a class="cta" href="/{{ url:segments segment="1" }}/{{ url:segments segment="2" }}">Back</a></div>
<div class="gallery-container" id="gallery_image">
	<div class="gallery-image clearfix">
		<div class="hd">
			<img src="<?= site_url('files/thumb/'.$gallery_image->file_id .'/800'); ?>" alt="<?= $gallery_image->name; ?>" />
		</div>
		<?php if ( ! empty($gallery_image->description) ):?>
		<div class="bd">
			<p><?= $gallery_image->description; ?></p>
		</div>
		<?php endif; ?>
	</div>
</div>
<?php if ($gallery->enable_comments == 1): ?>
	<?= display_comments($gallery_image->id, 'gallery-image'); ?>
<?php endif; ?>