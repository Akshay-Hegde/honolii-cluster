{{ asset:js_inline }}
/* * * DON'T EDIT BELOW THIS LINE * * */
(function() {
    var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
    dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
    (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
})();
{{ /asset:js_inline }}

<article id="blog-post" class="mod-post-wrapper">
{{ post }}
  <script type="text/javascript">
  /* * * CONFIGURATION VARIABLES * * */
  var disqus_shortname = 'emeehan';
  var disqus_identifier = '{{ helper:date timestamp=created_on format="Y/m/" }}{{ slug }}';
  var disqus_title = '{{ title }}';
  </script>
  <div class="mod-post">
    <div class="mod-post-hd">
      <h1>{{ title }}</h1>
      <div class="post-meta">
        <div class="post-date">
          <span class="glyphicon glyphicon-time" aria-hidden="true"></span>
          {{ helper:date timestamp=created_on format="j F, Y" }}
        </div>
        <div class="post-comments">
          <span class="glyphicon glyphicon-comment" aria-hidden="true"></span>
          <a href="#comments" class="disqus-comment-count" data-disqus-identifier="{{ helper:date timestamp=created_on format="Y/m/" }}{{ slug }}">0 Comments</a>
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
    <div class="mod-post-bd">
      {{ body }}
    </div>
    <div id="comments" class="mod-post-ft">
      <div class="mod-comments">
        <div class="mod-comments-hd">
          <h2>Comments</h2>
        </div>
        <div class="mod-comments-bd" id="disqus_thread"></div>
        <noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript" rel="nofollow">comments powered by Disqus.</a></noscript>
      </div>
    </div>
  </div>
{{ /post }}
</article>

<aside id="col-rail" class="span8 offset1">
  <div class="row">
    <div class="span8">
        {{ widgets:area slug="post" }}
        {{ widgets:area slug="default" }}
      </div>
  </div>
</aside>