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
    <div class="block">
        <div class="container">
            <aside class="row horz-sidebar">
				<div class="col-sm-4 col-xs-6">
					{{ widgets:area slug="blog-post-list" }}
				</div>
				<div class="col-sm-8 col-xs-6">
					<div class="widget mod blog_tags">
						<h5 class="hd">Tags</h5>
						<div class="bd">
							<ul class="row">
								
								{{ blog:tags }}
								
								<li class="col-sm-4">
									<a href="{{ url }}">{{ title }}</a>
								</li>
								
								{{ /blog:tags }}
								
							</ul>
						</div>
					</div>
				</div>
			</aside>
            <hr class="up" />
            <div class="posts-list">
                
                {{ if posts }}

                    {{ posts }}
                
                        <article class="post">
                            <h3 class="h1 text-center"><a href="{{ url }}">{{ title }}</a></h3>
                            <div class="post-meta text-center">
                                <div class="author">{{ created_by:display_name }}</div>
                                <div class="date">{{ helper:date timestamp=created_on }}</div>
                            </div>
                            {{ if post_image }}
                            	<div class="row post-image">
                            		<div class="col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
                            			<img class="img-thumbnail" src="{{ post_image:image }}" alt="" />
                            		</div>
                            	</div>
                                
                            {{ endif }}
                            <div class="post-preview">
                                {{ preview }}
                            </div>
                            <div class="text-center"><a class="btn btn-primary" href="{{ url }}">Read More }</a></div>
                            <hr class="up" />
                        </article>
                		
                    {{ /posts }}
                
                    {{ pagination }}
                
                {{ else }}
                    
                    {{ helper:lang line="blog:currently_no_posts" }}
                
                {{ endif }}
                
            </div>
        </div>
    </div>
</section>