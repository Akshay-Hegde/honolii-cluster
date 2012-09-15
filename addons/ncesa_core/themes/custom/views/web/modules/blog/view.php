<div id="blog">
	<div id="col-2" class="span-17 font_size">
		<div class="box">
			{{theme:partial name="breadcrumbs"}}
			
			<div class="blog_post">
				<!-- Post heading -->
				<div class="post_heading">
					<h1><?php echo $post->title; ?></h1>
					<?php if ($post->display_name): ?>
					<p class="author"><?= lang('blog_written_by_label'); ?>: <?=  $post->display_name; ?></p>
					<?php endif; ?>
					<p class="post_date"><span class="post_date_label"><?php echo lang('blog_posted_label');?>: </span><?php echo format_date($post->created_on); ?></p>
					<?php if($post->category->slug): ?>
					<p class="post_category"> <?php echo lang('blog_category_label');?>: <?php echo anchor('blog/category/'.$post->category->slug, $post->category->title);?> </p>
					<?php endif; ?>
				</div>
				<div class="post_body">
					<?php echo $post->body; ?>
				</div>
			</div>
			{{session:messages success="success" notice="notice" error="error"}}
			<?php if ($post->comments_enabled): ?>
			<?php echo display_comments($post->id); ?>
			<?php endif; ?>
		</div>
	</div>
	<div id="col-1" class="span-7 last font_size">
		{{widgets:area slug="blog-post"}}
	</div>
	<div class="clear">
	</div>
</div>