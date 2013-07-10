<div class="galleries-container" id="gallery-image">
    <div class="gallery-single-image">        
        <div class="gallery-title">
           <h2 id="page_title">
               <span>
                   <?php 
                        if( strcasecmp($gallery_image->name, $gallery_image->filename) == 0 )
                            {echo $gallery->title;}
                        else
                            {echo $gallery_image->name;}
                    ?>
               </span>
            </h2>
        </div>
        <div class="gallery-image" id="gallery-image-large" data-current-image="<?= $gallery_image->file_id ?>">
            <img src="<?php echo site_url('files/large/'.$gallery_image->file_id); ?>" alt="<?php echo $gallery_image->name; ?>" />
            <div class="image-control">
                <a href="" class="carousel-control left">‹</a>
                <a href="" class="carousel-control right">›</a>
            </div>
        </div>
    	<div class="gallery-images">   
            <!-- The list containing the gallery images -->
            <ul class="gallery-image-list" data-current-image="<?= $gallery_image->file_id ?>">
            </ul>
        </div>
        <?php if ( ! empty($gallery_image->description) ):?>
        <!-- An image needs a description.. -->
        <div class="gallery-description">
            <p><?php echo $gallery_image->description; ?></p>
        </div>
        <?php endif; ?>
    </div>
</div>