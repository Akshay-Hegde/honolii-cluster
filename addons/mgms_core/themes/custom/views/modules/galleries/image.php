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
    <div id="comments" class="mod comments">
        <div id="existing-comments" class="hd comments">
            <h3 class="col-headline"><?= lang('comments:title'); ?></h3>
            <?php echo $this->comments->display() ?>
        </div>
        <div id="comments-form" class="bd comments-form">
            <?php echo $this->comments->form() ?>
        </div>
        <div class="fd"></div>
    </div>
<?php endif; ?>