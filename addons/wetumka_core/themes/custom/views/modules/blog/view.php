{{ asset:js_inline }}

	var script   = document.createElement("script");
	script.type  = "text/javascript";
	script.src   = "//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-53c49f8c1ac46186";    // use this for linked script
	document.body.appendChild(script);

{{ /asset:js_inline }}
<section id="blog" class="blog-posts-section">
	<div class="splash bottom-up text-center">
		<div class="container block">
			<div class="row">
				<div class="col-xs-12">
					<h1>The Blog</h1>
					<p>We ponder the world online. A lot. Here's the latest&hellip;</p>
				</div>
			</div>
		</div>
	</div>
	
	{{ post }}
    <div class="block">
        <div class="container">
            <div class="posts-single">
                <article id="blog-post" class="post">
                	<h2 class="h1 text-center">{{ title }}</h2>
            		<div class="post-meta text-center">
                        <div class="author">{{ created_by:display_name }}</div>
                        <div class="date">{{ helper:date timestamp=created_on }}</div>
                        <div class="category-keyword">
	                        
	                        {{ if category }}
	                			<strong>Category:</strong>
	                			<span class="category"><a href="{{ url:site }}blog/category/{{ category:slug }}">{{ category:title }}</a></span>
	                		{{ endif }}
	                		
	                		{{ if keywords }}
	                			<strong>Tag:</strong>
	                			{{ keywords }}
	                				<span class="keyword"><a href="{{ url:site }}blog/tagged/{{ keyword }}">{{ keyword }}</a></span>
	                			{{ /keywords }}
	                		{{ endif }}
                		
                    	</div>
                	</div>
                	
                	{{ if post_image }}	
                    	<div class="row post-image">
                    		<div class="col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
                    			<img class="img-thumbnail" src="{{ post_image:image }}" alt="" />
                    		</div>
                    	</div>
                    {{ endif }}
                		
                	<div class="body">
                		{{ body }}
                	</div>
                </article>
                <aside class="horz-sidebar">
					{{ widgets:area slug="blog-post-single" }}
				</aside>
                <hr class="up" />
                <?php if (Settings::get('enable_comments')): ?>
                <section id="comments" class="comments-section">
                
                	<div id="existing-comments">
                		<h3 class="text-center"><?php echo lang('comments:title') ?></h3>
                		<?php echo $this->comments->display() ?>
                	</div>
                
                	<?php if ($form_display): ?>
                		<hr class="up" />
                		<?php echo $this->comments->form() ?>
                	<?php else: ?>
                	<?php echo sprintf(lang('blog:disabled_after'), strtolower(lang('global:duration:'.str_replace(' ', '-', $post[0]['comments_enabled'])))) ?>
                	<?php endif ?>
                </section>
                <?php endif ?>
                
                <hr class="up" />
                <div class="row">
                	<h3 class="text-center">Similar Posts</h3>
                	{{ blog:posts limit="3" category=category:slug }}
                		<div class="col-sm-4 col-xs-6">
							<a class="thumbnail" href="{{url}}" title="{{title}}">
								{{ if post_image }}
									<img class="" src="{{ post_image:image }}" alt="" />
                    			{{ endif }}
								<span class="caption text-center small">
									{{ title }}
								</span>
							</a>
						</div>
					{{ /blog:posts }}
				</div>
            </div>
        </div>
    </div>
    {{ /post }}
    
</section>