<div id="blog">
	<div id="col-2" class="span-17 font_size">
		<div class="box">
			{{session:messages success="success" notice="notice" error="error"}}
			<h1>{{ helper:lang line="blog:archive_title" }} &raquo; {{ month_year }}</h1>
			{{ if posts }}
                {{ posts }}
    			<div class="blog_post">
    				<!-- Post heading -->
    				<div class="post_heading">
    					<h2><a title="Read - {{ title }}" href="{{ url }}">{{ title }}</a></h2>
    					<p class="post_date"><?php echo lang('blog:posted_label');?>: {{ helper:date timestamp=created_on format="M jS, Y" }}</p>
    					{{ if category }}
    					<p class="post_category"> <?php echo lang('blog:category_label');?>: <a href="blog/category/{{ category:slug }}">{{ category:title }}</a> </p>
    					{{ endif }}
    				</div>
    				<div class="post_body">
    					{{ preview }}
    				</div>
    			</div>
    			{{ /posts }}
                {{ pagination }}
            {{ else }}
                {{ helper:lang line="blog:currently_no_posts" }}
            {{ endif }}
		</div>
	</div>
	<div id="col-1" class="span-7 last font_size">
		{{widgets:area slug="blog"}}
	</div>
	<div class="clear">
	</div>
</div>
