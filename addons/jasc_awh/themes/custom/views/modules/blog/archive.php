{{ if posts }}
    <div id="blog-short" class="mod blog-latest">
        <div class="bd">
            {{ posts }}
                <div class="post-wrapper clearfix">
                    <div class="image-loader"></div>
                    <h2 class="post-title"><a href="{{ url }}">{{ title }}</a></h2>
                    <div class="post-meta">
                        <p class="post-date">Posted: {{ helper:date timestamp=created_on }}</p>
                        {{ if category }}
                            <p class="post-category">Category: <a href="blog/category/{{ category:slug }}">{{ category:title }}</a></p>
                        {{ endif }}
                        {{ if keywords }}
                            <p class="post-keywords">Keywords: 
                                {{ keywords }}
                                    <a href="blog/tagged/{{ keyword }}">{{ keyword }}</a>
                                {{ /keywords }}
                            </p>
                        {{ endif }}
                    </div>
                    <div class="post-intro">{{ preview }}</div>
                    <a class="post-link cta banner lite" href="{{ url }}">Read More &raquo;</a>
                </div>
            {{ /posts }}
        </div>
        <div class="fd">
            {{ pagination }}
        </div>
    </div>
{{ else }}
    {{ helper:lang line="blog:currently_no_posts" }}
{{ endif }}