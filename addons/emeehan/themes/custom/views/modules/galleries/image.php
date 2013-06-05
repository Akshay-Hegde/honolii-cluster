<h2 id="page_title"><?php echo $gallery_image->name; ?></h2>
<!-- Div containing all galleries -->
<div class="galleries_container" id="gallery_image">
	<div class="gallery clearfix">
		<!-- Div containing the full sized image -->
		<div class="gallery_image_full">
			<img src="<?php echo site_url('files/large/'.$gallery_image->file_id); ?>" alt="<?php echo $gallery_image->name; ?>" />
		</div>
		<?php if ( ! empty($gallery_image->description) ):?>
		<!-- An image needs a description.. -->
		<div class="gallery_image_description">
			<p><?php echo $gallery_image->description; ?></p>
		</div>
		<?php endif; ?>
	</div>
</div>
<?php if ($gallery->enable_comments == 1): ?>
<div id="comments" class="mod comments">
    <div id="existing-comments" class="hd comments">
        <h4><?= lang('comments:title'); ?></h4>
        <?= $this->comments->display() ?>
    </div>

    <?php echo $this->comments->form(); ?>
    <div class="fd"></div>
</div>
<?php endif; ?>