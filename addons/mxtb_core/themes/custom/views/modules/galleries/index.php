<div class="row-fluid">   
    <div id="col-main" class="span7">
        <div class="galleries-container" id="gallery-index">
        	<?php if ( ! empty($galleries)): foreach ($galleries as $gallery): if (empty($gallery->parent)):?>
        	<div class="gallery-item">
        		<div class="gallery-heading">
        			<?php if ( ! empty($gallery->filename)): ?>
        				<div class="gallery-title">
        				    <h3><span><?= $gallery->title ?></span></h3>
        				    <div class="cta">view photos</div>
        				</div>
        				<?= img(array('src' => site_url('files/thumb/'.$gallery->file_id.'/508/220/fit'), 'alt' => $gallery->description)); ?>
        				<a href="<?= site_url('galleries/'.$gallery->slug); ?>"></a>
        			<?php endif; ?>
        		</div>
        		<div class="gallery-sublist">
        		    <div class="gallery-sublist-content row-fluid">
        		        <?php foreach($galleries as $subgallery) :  if($gallery->id === $subgallery->parent): ?>
        		            <div class="span4">
        		                <div class="sublist-item">
            		                <?php if ( ! empty($subgallery->filename)): ?>
            		                  <a title="View - <?= $subgallery->title ?>" href="<?= site_url('galleries/'.$subgallery->slug); ?>">
            		                      <span class="item-headline"><span><?= $subgallery->title ?></span></span>
            		                      <?= img(array('src' => site_url('files/thumb/'.$subgallery->file_id.'/162/162/fit'), 'alt' => $subgallery->description)); ?>
            		                  </a>
            		                <?php endif; ?>
        		                </div>
        		            </div>
        		        <?php endif; endforeach; ?>
        		    </div>
        		</div>
        	</div>
        	<?php endif; endforeach; else: ?>
        	   <p><?= lang('galleries.no_galleries_error'); ?></p>
        	<?php endif; ?>
        </div>
    </div>
    <aside id="col-rail" class="span5">
        {{ widgets:area slug="gallery" }}
    </aside>
</div>