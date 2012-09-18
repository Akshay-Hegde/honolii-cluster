<div id="blog">
	<div id="col-2" class="span-17 font_size">
		<div class="box">
			{{session:messages success="success" notice="notice" error="error"}}
			<?php if (isset($category->title)): ?>
			<h1><?php echo $category->title; ?></h1>
			<?php else: ?>
			<h1>Blog</h1>
			<?php endif; ?>
			<?php if (!empty($blog)): ?>
			<?php foreach ($blog as $post): ?>
			<div class="blog_post">
				<!-- Post heading -->
				<div class="post_heading">
					<h2><?php echo  anchor('blog/' .date('Y/m', $post->created_on) .'/'. $post->slug, $post->title); ?></h2>
					<p class="post_date"><?php echo lang('blog_posted_label');?>: <?php echo format_date($post->created_on); ?></p>
					<?php if($post->category_slug): ?>
					<p class="post_category"> <?php echo lang('blog_category_label');?>: <?php echo anchor('blog/category/'.$post->category_slug, $post->category_title);?> </p>
					<?php endif; ?>
				</div>
				<div class="post_body">
					<?php echo $post->intro; ?>
				</div>
			</div>
			<?php endforeach; ?>
			<?php echo $pagination['links']; ?>
			<?php else: ?>
			<p><?php echo lang('blog_currently_no_posts');?></p>
			<?php endif; ?>
		</div>
	</div>
	<div id="col-1" class="span-7 last font_size">
		{{widgets:area slug="blog"}}
	</div>
	<div class="clear">
	</div>
</div>