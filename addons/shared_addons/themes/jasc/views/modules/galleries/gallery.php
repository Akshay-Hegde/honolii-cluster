<div class="box">
{{theme:partial name="breadcrumbs"}}
<hr/>
</div>
<!-- Div containing all galleries -->
<div class="galleries_container clearfix" id="gallery_single">
	<div class="box">
		<?php echo $gallery->description; ?>
	</div>
	<div class="mod gallery-thumb clearfix">
		<?php if ($gallery_images): ?>
		<?php foreach ( $gallery_images as $image): ?>
		<div class="span-5">
			<a href="<?= base_url().'uploads/files/' . $image->filename; ?>" style=" background-image:url(<?= site_url() . 'files/thumb/' . $image->file_id . '/250/400' ?>);" class="prettyPhoto" data-src="<?= base_url().'uploads/files/' . $image->filename; ?>" title="<?= $image->description; ?>"></a>
		</div>
		<?= alternator('','', '<br class="clear"/>'); ?>
		<?php endforeach; ?>
		<?php endif; ?>
	</div>
	
</div>
<?php if ( ! empty($sub_galleries) ): ?>
<h2><?php echo lang('galleries.sub-galleries_label'); ?></h2>
<!-- Show all sub-galleries -->
<div class="sub_galleries_container">
	<?php foreach ($sub_galleries as $sub_gallery): ?>
	<div class="gallery clearfix">
		<!-- Heading -->
		<div class="gallery_heading">
			<?php if ( ! empty($sub_gallery->filename)) : ?>
			<a href="<?php echo site_url() . 'galleries/' . $sub_gallery->slug; ?>">
				<?php echo img(array('src' => site_url() . 'files/thumb/' . $sub_gallery->file_id, 'alt' => $sub_gallery->title)); ?>
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
<?php if ($gallery->enable_comments == 1): ?>
	<?php echo display_comments($gallery->id, 'gallery'); ?>
<?php endif; ?>