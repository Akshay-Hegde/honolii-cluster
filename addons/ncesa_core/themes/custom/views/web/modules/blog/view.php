<div id="blog">
	<div id="col-2" class="span-17 font_size">
		<div class="box">
			{{theme:partial name="breadcrumbs"}}
			{{ post }}
			<div class="blog_post">
				<!-- Post heading -->
				<div class="post_heading">
					<h1>{{ title }}</h1>
					<!--<p class="author"><?= lang('blog:written_by_label'); ?>: {{ user:display_name user_id=created_by }}</p>-->
					<p class="post_date"><span class="post_date_label"><?php echo lang('blog:posted_label');?>: </span>{{ helper:date timestamp=created_on format="M jS, Y" }}</p>
					{{ if category }}
					<p class="post_category"> <?php echo lang('blog:category_label');?>: <a href="blog/category/{{ category:slug }}">{{ category:title }}</a> </p>
					{{ endif }}
				</div>
				<div class="post_body">
					{{ body }}
				</div>
			</div>
			{{ /post }}
			<?php /*
			{{session:messages success="success" notice="notice" error="error"}}
			<?php if (Settings::get('enable_comments')): ?>
			<?php echo display_comments($post->id); ?>
			<?php endif; */ ?>
		</div>
	</div>
	<div id="col-1" class="span-7 last font_size">
		{{widgets:area slug="blog-post"}}
	</div>
	<div class="clear">
	</div>
</div>