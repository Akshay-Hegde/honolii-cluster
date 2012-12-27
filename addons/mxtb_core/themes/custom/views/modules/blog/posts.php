<div id="col-main" class="span7">
<?php if (isset($category->title)): ?>
<h2 id="page_title" class="category-title <?= $category->slug; ?>-title"><?= $category->title; ?></h2>
<?php endif; ?>

<?php if (!empty($blog)): ?>
<?php foreach ($blog as $post): ?>
	<?php $category_class = $post->category_slug ? $post->category_slug : 'nocat'; ?>
	<div class="mod block-post <?= $category_class ?>-post">
		<!-- Post heading -->		
		<div class="hd post-heading">
			<h4><?php echo anchor('blog/' .date('Y/m', $post->created_on) .'/'. $post->slug, $post->title); ?></h4>
			<div class="post-date">
				<?= format_date($post->created_on,'M jS, Y') ?>
			</div>
		</div>
		<div class="bd post-body">
			<?php echo $post->intro; ?>
		</div>
		<div class="post-full">
		    <a href="<?= 'blog/' .date('Y/m', $post->created_on) .'/'. $post->slug ?>" class="btn">View More</a>
		</div>
		<hr />
	</div>
<?php endforeach; ?>

<?php echo $pagination['links']; ?>

<?php else: ?>
	<p><?php echo lang('blog_currently_no_posts');?></p>
<?php endif; ?>

</div>
<aside id="col-rail" class="span5">
	{{ widgets:area slug="blog-cat" }}
</aside>