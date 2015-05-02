{{ asset:js_inline }}
/* * * CONFIGURATION VARIABLES * * */
var disqus_shortname = 'emeehan';

/* * * DON'T EDIT BELOW THIS LINE * * */
(function () {
var s = document.createElement('script'); s.async = true;
s.type = 'text/javascript';
s.src = '//' + disqus_shortname + '.disqus.com/count.js';
(document.getElementsByTagName('HEAD')[0] || document.getElementsByTagName('BODY')[0]).appendChild(s);
}());
{{ /asset:js_inline }}

<section id="blog" class="mod-blog-wrapper">
  <div class="mod-blog {{ category:slug }}">
    <div class="mod-blog-hd">
      <h1>{{ helper:lang line="blog:archive_title" }}</h1>
      <h3>{{ month_year }}</h3>
    </div>
    {{ if posts }}
    <div class="mod-blog-bd">
    {{ posts }}
      <div class="mod-post">
        <div class="mod-post-hd">
          <h3><a href="{{ url }}">{{ title }}</a></h3>
          <div class="post-meta">
            <div class="post-date">
              <span class="glyphicon glyphicon-time" aria-hidden="true"></span>
              {{ helper:date timestamp=created_on format="j F, Y" }}
            </div>
            <div class="post-comments">
              <span class="glyphicon glyphicon-comment" aria-hidden="true"></span>
              <span class="disqus-comment-count" data-disqus-identifier="{{ helper:date timestamp=created_on format="Y/m/" }}{{ slug }}">0 Comments</span>
            </div>
            {{ if category }}
            <div class="post-category">
              <span class="glyphicon glyphicon-bookmark" aria-hidden="true"></span>
              <a href="blog/category/{{ category:slug }}">{{ category:title }}</a>
            </div>
            {{ endif }}
            {{ if keywords }}
            <div class="post-keywords">
              <span class="glyphicon glyphicon-tags" aria-hidden="true"></span>
              {{ keywords }}
                <a href="blog/tagged/{{ keyword }}">{{ keyword }}</a>
              {{ /keywords }}
            </div>
            {{ endif }}
          </div>
        </div>
        {{ if post_image }}
        <img class="post-image" alt="{{ post_image:filename }}" src="{{ post_image:image }}" />
        {{ endif }}
        <div class="mod-post-bd">
          {{ preview }}
          <div class="post-read-more">
            <a href="{{ url }}" class="btn btn-primary">Read more</a>
          </div>
        </div>
      </div>
    {{ /posts }}
    </div>
    <div class="mod-blog-ft">
      {{ pagination }}
    </div>
  {{ else }}
    <div class="mod-blog-bd no-posts">
      {{ helper:lang line="blog:currently_no_posts" }}
    </div>
  {{ endif }}
  </div>
</section>