<section id="col-main" class="span24">
	<h2 id="page_title" class="category-title gallery-title"><?= $gallery->title; ?></h2>
	<?= $gallery->description; ?>
	
	<?php if ($gallery_images): ?>
	<div class="row">
		<?php foreach ( $gallery_images as $image): ?>
		<?= alternator('','','','','</div><div class="row">'); /* row grouping */ ?>
		<div class="span6">
			<div class="mod block-gallery">
				<a href="<?= site_url('galleries/'.$gallery->slug.'/'.$image->id); ?>" class="gallery-image" rel="gallery-image" data-src="<?= site_url('files/large/'.$image->file_id); ?>" title="<?= $image->name; ?>">
					<?= img(array('src' => site_url('files/thumb/'.$image->file_id.'/230/230/fit'), 'alt' => $image->name)); ?>
				</a>
			</div>
		</div>
		<?php endforeach; ?>
	</div>
	<?php endif; ?>



<?php if ( ! empty($sub_galleries) ): ?>
<h2><?php echo lang('galleries.sub-galleries_label'); ?></h2>
<!-- Show all sub-galleries -->
<div class="sub_galleries_container">
	<?php foreach ($sub_galleries as $sub_gallery): ?>
	<div class="gallery clearfix">
		<!-- Heading -->
		<div class="gallery_heading">
			<?php if ( ! empty($sub_gallery->filename)) : ?>
			<a href="<?php echo site_url('galleries/'.$sub_gallery->slug); ?>">
				<?php echo img(array('src' => site_url('files/thumb/'.$sub_gallery->file_id), 'alt' => $sub_gallery->title)); ?>
			</a>
			<?php endif; ?>
			<h3><?php echo anchor('galleries/' . $sub_gallery->slug, $sub_gallery->title); ?></h3>
		</div>
		<!-- And the body -->
		<div class="gallery_body">
			<p>
				<?php echo ( ! empty($sub_gallery->description)) ? $sub_gallery->description : lang('galleries.no_gallery_description'); ?>
			</p>
		</div>
	</div>
	<?php endforeach; ?>
</div>
<?php endif; ?>
<br style="clear: both;" />

<?php if ($gallery->enable_comments == 1): ?>
	<?php echo display_comments($gallery->id);?>
<?php endif; ?>
</section>