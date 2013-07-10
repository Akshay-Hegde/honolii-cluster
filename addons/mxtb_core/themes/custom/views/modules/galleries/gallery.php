<div class="galleries-container" id="gallery-gallery">
    <div class="gallery-single">
        <div class="gallery-title">
    	   <h2 id="page-title"><span><?php echo $gallery->title; ?></span></h2>
    	</div>
    	<div class="gallery-image" id="gallery-image-large" data-current-image="<?= $gallery->thumbnail_id ?>">
    	    <img src="<?php echo site_url('files/large/'.$gallery->thumbnail_id); ?>" alt="<?php echo $gallery->title; ?>" />
    	    <?php if ( empty($sub_galleries) ): ?>
    	    <div class="image-control">
                <a href="" class="carousel-control left">‹</a>
                <a href="" class="carousel-control right">›</a>
            </div>
            <?php endif; ?>
    	</div>
    	<?php if ( empty($sub_galleries) ): ?>
    	<div class="gallery-images" data-current-image="<?= $gallery->thumbnail_id ?>">	
    		<!-- The list containing the gallery images -->
    		<ul class="gallery-image-list">
    			<?php if ($gallery_images): ?>
    			<?php foreach ( $gallery_images as $image): ?>
    			<li class="gallery-image-item">
    				<a href="<?php echo site_url('galleries/'.$gallery->slug.'/'.$image->id); ?>" class="gallery-image" data-src="<?php echo site_url('files/large/'.$image->file_id); ?>" title="<?php echo $image->name; ?>">
    					<?php echo img(array('src' => site_url('files/thumb/'.$image->file_id.'/110/110/fit'), 'alt' => $image->name)); ?>
    				</a>
    			</li>
    			<?php endforeach; ?>
    			<?php endif; ?>
    		</ul>
    	</div>
    	<?php endif; ?>
    	<div class="gallery-description">
            <p><?php echo $gallery->description; ?></p>
        </div>
    </div>
</div>

<?php if ( ! empty($sub_galleries) ): ?>
<div class="gallery-sublist-items">
    <div class="gallery-sublist-content row-fluid">
        <?php foreach ($sub_galleries as $sub_gallery): ?>
        <div class="span3">
            <!-- Heading -->
            <div class="sublist-item">
                <a title="View - <?= $sub_gallery->title ?>" href="<?php echo site_url('galleries/'.$sub_gallery->slug); ?>">
                    <span class="item-headline"><span><?= $sub_gallery->title ?></span></span>
                    <?php echo img(array('src' => site_url('files/thumb/'.$sub_gallery->file_id.'/207/207/fit'), 'alt' => $sub_gallery->title)); ?>
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>