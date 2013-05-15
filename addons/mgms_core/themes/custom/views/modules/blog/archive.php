<div id="col-main">
    <h3 class="col-headline" id="page_title">{{ helper:lang line="blog:archive_title" }} : {{ month_year }}</h3>
{{ if posts }}
    {{ posts }}
        {{ if category }}
        <div class="mod block-post {{ category:slug }}-post">
        {{ else }}
        <div class="mod block-post nocat-post">
        {{ endif }}
        <div class="hd post-heading">
                <h2><a href="{{ url }}">{{ title }}</a></h2>
                <div class="post-meta"> 
                    <div class="post-date">
                        {{ helper:date timestamp=created }}
                    </div>
                    {{ if category }}
                    <span class="post-category">
                        Category: <a href="blog/category/{{ category:slug }}">{{ category:title }}</a>
                    </span>
                    {{ endif }}
                    {{ if keywords }}
                    <span class="post-keywords">
                        Keywords:
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
</div>