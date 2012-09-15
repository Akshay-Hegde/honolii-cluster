<h3 class="col-headline">Posts: <?php echo lang('blog_archive_title');?> : <?php echo $month_year;?></h3>

<?php if (!empty($blog)): ?>
<?php foreach ($blog as $post): ?>
	<?php $category_class = $post->category_slug ? $post->category_slug : 'nocat'; ?>
	<div class="mod block-post <?= $category_class ?>-post clearfix">
		<!-- Post heading -->
		<?php
			// Post Created quick format vars
			$pc_full = format_date($post->created_on);
			$pc_month = format_date($post->created_on,'F');
			$pc_date = format_date($post->created_on,'j');
		
		?>
		<div class="hd post-heading">
			<div class="post-date" title="<?= $pc_full ?>">
				<span class="post-month"><?= $pc_month ?></span>
				<span class="post-day"><?= $pc_date ?></span>
			</div>
			<h2><?php echo anchor('blog/' .date('Y/m', $post->created_on) .'/'. $post->slug, $post->title); ?></h2>
			
			<div class="post-meta">
				<?php if ($post->category_slug): ?>
				<span class="post-category">
					<i class="icon-bookmark icon-white"></i>
					<?php echo anchor('blog/category/'.$post->category_slug, $post->category_title);?>
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
			<?php echo $post->intro; ?>
		</div>
		<div class="fd">
			<?= anchor('blog/' .date('Y/m', $post->created_on) .'/'. $post->slug, 'Read more &raquo;', 'class="cta"');?>
		</div>
	</div>
<?php endforeach; ?>

<?php echo $pagination['links']; ?>

<?php else: ?>
	<p><?php echo lang('blog_currently_no_posts');?></p>
<?php endif; ?>