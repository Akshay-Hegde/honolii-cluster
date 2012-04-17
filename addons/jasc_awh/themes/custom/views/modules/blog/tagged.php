<!-- <?= lang('blog_tagged_label').': '.str_replace('-', ' ', $tag); ?> -->
<?php if ( ! empty($blog)): ?>
<div id="blog-short" class="mod blog-latest">
	<div class="bd">
	<?php foreach ($blog as $post): ?>
		<div class="post-wrapper clearfix">
			<div class="image-loader"></div>
			<h2 class="post-title"><?= anchor('blog/view/'. $post->slug, $post->title); ?></h2>
			<div class="post-meta">
				<p class="post-date"><?= lang('blog_posted_label');?>: <?= format_date($post->created_on); ?></p>
				<?php if ($post->category_slug): ?>
				<p class="post-category">
					<?= lang('blog_category_label');?>:
					<?= anchor('blog/category/'.$post->category_slug, $post->category_title);?>
				</p>
				<?php endif; ?>
				<?php if($post->keywords): ?>
				<p class="post-keywords">
					<?= lang('blog_tagged_label');?>:
					<?= $post->keywords; ?>
				</p>
				<?php endif; ?>
			</div>
			<div class="post-intro"><?= $post->intro; ?></div>
			<a class="post-link cta banner lite" href="/blog/view/<?= $post->slug ?>">Read More &raquo;</a>
		</div>
	<?php endforeach; ?>
	</div>
	<div class="fd">
		<?= $pagination['links']; ?>
	</div>
</div>
<?php else: ?>
	<p><?= lang('blog_currently_no_posts');?></p>
<?php endif; ?>