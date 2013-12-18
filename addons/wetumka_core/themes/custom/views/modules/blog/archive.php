<section id="blog" class="blog-posts-section default-section">
    <div class="container">
        <div class="row">
            <aside class="col-md-3 hidden-xs">
                {{ widgets:area slug="blog-posts" }}
            </aside>
            <div class="col-md-7">        
                <h2 class="h4 archive-headline">{{ helper:lang line="blog:archive_title" }} : {{ month_year }}</h2>

                {{ if posts }}
                
                	{{ posts }}
                
                		<article class="post archive">
                            <h3><a href="{{ url }}">{{ title }}</a></h3>
                            <div class="meta">
                                <div class="date author">
                                    <span class="glyphicon glyphicon-user"></span>
                                    <span>{{ created_by:display_name }}</span>
                                    <span class="glyphicon glyphicon-time"></span>
                                    <span>{{ helper:date timestamp=created_on }}</span>
                                </div>
                                {{ if category }}
                                <div class="category">
                                    {{ helper:lang line="blog:category_label" }}
                                    <span><a href="{{ url:site }}blog/category/{{ category:slug }}">{{ category:title }}</a></span>
                                </div>
                                {{ endif }}
                                {{ if keywords }}
                                <div class="keywords">
                                    <span class="glyphicon glyphicon-tags"></span>
                                    {{ keywords }}
                                        <span class="keyword"><a href="{{ url:site }}blog/tagged/{{ keyword }}">{{ keyword }}</a></span>
                                    {{ /keywords }}
                                </div>
                                {{ endif }}
                            </div>
                            {{ if post_image }}
                                <img class="img-responsive img-thumbnail" src="{{ post_image:image }}" alt="" />
                            {{ endif }}
                            <div class="preview">
                                {{ preview }}
                            </div>
                            <p><a class="btn btn-primary" href="{{ url }}">Read More }</a></p>
                        </article>
                
                	{{ /posts }}
                
                	{{ pagination }}
                
                {{ else }}
                	
                	{{ helper:lang line="blog:currently_no_posts" }}
                
                {{ endif }}
            </div>
            <aside class="col-md-2 hidden-xs">
                
            </aside>
        </div>
    </div>
</section>