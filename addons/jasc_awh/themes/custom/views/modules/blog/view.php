<div id="blog-long" class="mod blog-post">
	<div class="hd">
		<h1><?php echo $post->title; ?></h1>
		<div class="post-meta">
			<p class="post-date"><?= lang('blog_posted_label');?>: <?= format_date($post->created_on); ?></p>
			<?php if($post->category->slug): ?>
			<p class="post-category">
				<?= lang('blog_category_label');?>: <?= anchor('blog/category/'.$post->category->slug, $post->category->title);?>
			</p>
			<?php endif; ?>
			<?php if($post->keywords): ?>
			<p class="post_keywords">
				<?= lang('blog_tagged_label');?>: <?= $post->keywords; ?>
			</p>
			<?php endif; ?>
		</div>
	</div>
	<div class="bd">
		<?= $post->body; ?>
	</div>
	<div class="fd"></div>
</div>
<?php if ($post->comments_enabled): ?>
	<?= display_comments($post->id); ?>
<?php endif; ?>