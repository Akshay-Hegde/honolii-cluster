<div id="col-main" class="span7">
    <div class="galleries_container" id="gallery_index">
    	<?php if ( ! empty($galleries)): foreach ($galleries as $gallery): ?>
    	<div class="gallery">
    		<div class="gallery_heading">
    			<?php if ( ! empty($gallery->filename)): ?>
    			<a href="<?= site_url('galleries/'.$gallery->slug); ?>">
    				<?= img(array('src' => site_url('files/thumb/'.$gallery->file_id.'/508/200/fit'), 'alt' => $gallery->description)); ?>
    			</a>
    			<?php endif; ?>
    			<?= anchor('galleries/' . $gallery->slug, $gallery->title); ?>
    		</div>		
    	</div>
    	<?php endforeach; ?>
    		<?= $this->pagination->create_links(); ?>
    	<?php else: ?>
    	   <p><?= lang('galleries.no_galleries_error'); ?></p>
    	<?php endif; ?>
    </div>
</div>
<aside id="col-rail" class="span5">
    {{ widgets:area slug="gallery" }}
</aside>