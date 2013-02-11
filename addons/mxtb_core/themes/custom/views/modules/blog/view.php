<div class="row-fluid">    
    <div id="col-main" class="span7">
    	<?php $category_class = $post->category->slug ? $post->category->slug : 'nocat'; ?>
    	<div class="mod page-post <?= $category_class ?>-post">
    		<!-- Post heading -->
    		<div class="hd post-heading">
    			<h2><?= $post->title; ?></h2>
    			<div class="post-date">
                    <?= format_date($post->created_on,'M jS, Y') ?>
                </div>
    		</div>
    		<div class="bd post-body">
    			<?php echo $post->body; ?>
    		</div>
    	</div>
    	<?php /*
    	<?php if ($post->comments_enabled): ?>
    		<?php echo display_comments($post->id); ?>
    	<?php endif; ?>
         */ ?>
    </div>
    <aside id="col-rail" class="span5">
    	{{ widgets:area slug="blog-post" }}
    </aside>
</div>