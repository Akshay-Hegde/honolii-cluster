<section id="col-main" class="span12">
    <h2 id="page_title">{{ helper:lang line="blog:archive_title" }}</h2>
    <h3>{{ month_year }}</h3>
    {{ if posts }}
        {{ posts }}
            {{ if category }}
            <div class="mod block-post {{ category:slug }}-post">
            {{ else }}
            <div class="mod block-post nocat-post">
            {{ endif }}
                <div class="hd post-heading">
                    <h4><a href="{{ url }}">{{ title }}</a></h4>
                    <div class="post-date" title="{{ helper:date timestamp=created }}">
                        <span class="post-month">{{ helper:date timestamp=created_on format="F" }}</span>
                        <span class="post-day">{{ helper:date timestamp=created_on format="j" }}</span>
                    </div>
                    <div class="post-meta"> 
                        {{ if category }}
                        <span class="post-category">
                            <i class="icon-bookmark icon-white"></i>
                            <a href="blog/category/{{ category:slug }}">{{ category:title }}</a>
                        </span>
                        {{ endif }}
                        {{ if keywords }}
                        <span class="post-keywords">
                            <i class="icon-tags icon-white"></i>
                            {{ keywords }}
                                <a href="blog/tagged/{{ keyword }}">{{ keyword }}</a>
                            {{ /keywords }}
                        </span>
                        {{ endif }}
                    </div>
                </div>
                <div class="bd post-body">
                    {{ preview }}
                    <p><a href="{{ url }}">{{ helper:lang line="blog:read_more_label" }}</a></p>
                </div>
            </div>
        {{ /posts }}
        {{ pagination }}
    {{ else }}
        {{ helper:lang line="blog:currently_no_posts" }}
    {{ endif }}
</section>
<aside id="col-rail" class="span11 offset1">
    <div class="row">
        <div class="span8">{{ widgets:area slug="category" }}{{ widgets:area slug="default" }}</div>
        <div class="span3">&nbsp;</div>
    </div>
</aside>