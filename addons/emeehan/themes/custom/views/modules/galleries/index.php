<section id="col-main" class="span24">
	<h2 id="page_title" class="category-title gallery-title"><?= lang('galleries.galleries_label'); ?></h2>
		
	<?php if ( ! empty($galleries)): ?>
	<div class="row">
	
		<?php foreach ($galleries as $gallery):?>
	<?= alternator('','','','</div><div class="row">'); /* row grouping */ ?>
		<div class="span8">
			<div class="mod block-gallery">
		
				<div class="hd gallery-heading">
					<h4><?= anchor('galleries/' . $gallery->slug, $gallery->title); ?></h4>
					<?php if ( ! empty($gallery->filename)): ?>
					<a href="<?= site_url('galleries/'.$gallery->slug); ?>">
						<?= img(array('src' => site_url('files/thumb/'.$gallery->file_id.'/310/310/fit'), 'alt' => $gallery->title)); ?>
					</a>
					<?php endif; ?>
				</div>
		
				<div class="bd gallery-body">
					<?= ( ! empty($gallery->description)) ? $gallery->description : lang('galleries.no_gallery_description'); ?>
				</div>
				
			</div>
		</div>
		<?php endforeach; ?>
	</div>
	<?= $this->pagination->create_links(); ?>
	
	<?php else: ?>
		
	<p><?= lang('galleries.no_galleries_error'); ?></p>
	
	<?php endif; ?>

</section>