<h3 class="col-headline">Posts</h3>
	<?php $category_class = $post->category->slug ? $post->category->slug : 'nocat'; ?>
	<div class="mod page-post <?= $category_class ?>-post">
		<!-- Post heading -->
		<?php
			// Post Created quick format vars
			$pc_full = format_date($post->created_on);
			$pc_month = format_date($post->created_on,'F');
			$pc_date = format_date($post->created_on,'j');
		
		?>
		<div class="hd post-heading">
			<h1><?= $post->title; ?></h1>
			<?php if (isset($post->display_name)): ?>
			<p class="author">by: <?= $post->display_name ?></p>
			<?php endif; ?>
			<div class="post-date" title="<?= $pc_full ?>">
				<span class="post-month"><?= $pc_month ?></span>
				<span class="post-day"><?= $pc_date ?></span>
			</div>
			<div class="post-meta">
				<?php if ($post->category->slug): ?>
				<span class="post-category">
					<i class="icon-bookmark icon-white"></i>
					<?= anchor('blog/category/'.$post->category->slug, $post->category->title);?>
				</span>
				<?php endif; ?>
				
				<?php if($post->keywords): ?>
				<span class="post-keywords">
					<i class="icon-tags icon-white"></i>
					<?php echo $post->keywords; ?>
				</span>
				<?php endif; ?>
			</div>
		</div>
		<div class="bd post-body">
			<?php echo $post->body; ?>
		</div>
	</div>
	<?php if ($post->comments_enabled): ?>
		<?php echo display_comments($post->id); ?>
	<?php endif; ?>
