<div id="col-main" class="span15">
    {{ post }}
        {{ if category }}
        <div class="mod page-post {{ category:slug }}-post">
        {{ else }}
        <div class="mod page-post nocat-post">
        {{ endif }}
            <div class="hd post-heading">
                <h2>{{ title }}</h2>
                <p class="author">by: <a href="user/{{ user:username user_id=created_by }}">{{ user:display_name user_id=created_by }}</a></p>
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
                {{ body }}
            </div>
        </div>
    {{ /post }}
    <div id="comments" class="mod comments">
        <div id="existing-comments" class="hd comments">
            <h4><?= lang('comments:title'); ?></h4>
        </div>
        <div class="bd" id="disqus_thread"></div>
        <noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript" rel="nofollow">comments powered by Disqus.</a></noscript>
        <div class="fd"></div>
    </div>
</div>
<aside id="col-rail" class="span8 offset1">
	<div class="row">
		<div class="span8">
		    {{ widgets:area slug="post" }}
		    {{ widgets:area slug="default" }}
	    </div>
	</div>
</aside>