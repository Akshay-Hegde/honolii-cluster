<div class="galleries-container" id="gallery-gallery">
    <div class="gallery-single">
        <div class="gallery-title">
    	   <h2 id="page-title"><span><?php echo $gallery->title; ?></span></h2>
    	</div>
    	<div class="gallery-image" id="gallery-image-large" data-current-image="<?= $gallery->thumbnail_id ?>">
    	    <img src="<?php echo site_url('files/large/'.$gallery->thumbnail_id); ?>" alt="<?php echo $gallery->title; ?>" />
    	    <div class="image-control">
                <a href="" class="carousel-control left">‹</a>
                <a href="" class="carousel-control right">›</a>
            </div>
    	</div>
    	<div class="gallery-images" data-current-image="<?= $gallery->thumbnail_id ?>">	
    		<!-- The list containing the gallery images -->
    		<ul class="gallery-image-list">
    			<?php if ($gallery_images): ?>
    			<?php foreach ( $gallery_images as $image): ?>
    			<li class="gallery-image-item">
    				<a href="<?php echo site_url('galleries/'.$gallery->slug.'/'.$image->id); ?>" class="gallery-image" rel="gallery-image" data-src="<?php echo site_url('files/large/'.$image->file_id); ?>" title="<?php echo $image->name; ?>">
    					<?php echo img(array('src' => site_url('files/thumb/'.$image->file_id.'/110/110/fit'), 'alt' => $image->name)); ?>
    				</a>
    			</li>
    			<?php endforeach; ?>
    			<?php endif; ?>
    		</ul>
    	</div>
    	<div class="gallery-description">
            <p><?php echo $gallery->description; ?></p>
        </div>
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

<?php if ($gallery->enable_comments == 1): ?>
	<?php echo display_comments($gallery->id);?>
<?php endif; ?>