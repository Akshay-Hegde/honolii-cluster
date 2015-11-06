{{ asset:js_inline }}
	requirejs(["pages/blog"]);
	/* Addthis Social Share */
	(function() {
    var addthis = document.createElement('script'); addthis.type = 'text/javascript'; addthis.async = true;
    addthis.src = '//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-53c49f8c1ac46186';
    (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(addthis);
	})();
{{ /asset:js_inline }}

{{ post }}
<section id="section_main" class="block-wrapper blog-post-section">
	<div class="block">
		<div class="block-inner">
			<article class="mod-post">
				<header class="mod-post-hd">
					{{ if category }}
						<a class="category" href="{{ url:site }}blog/category/{{ category:slug }}">{{ category:title }}</a>
					{{ endif }}
					<h1 class="post-headline">{{ title }}</h1>
					{{ if post_image }}
					<div class="post-image">
						<a href="{{ url }}"><img data-src="{{ post_image:image }}" alt="" /></a>
					</div>
					{{ endif }}
				</header>
				<div class="mod-post-bd">
					<div class="post-body">
						{{ body }}
					</div>
					<div class="mod-post-meta">
						<div class="mod-post-meta-bd">
							<div class="share">
								<h4>Share</h4>
								<!-- Go to www.addthis.com/dashboard to customize your tools -->
								<div class="addthis_sharing_toolbox"></div>
							</div>
							<div class="keywords">
								<h4>Tags</h4>
								{{ if keywords }}
									{{ keywords }}<span class="keyword"><a href="{{ url:site }}blog/tagged/{{ keyword }}">{{ keyword }}</a></span>{{ /keywords }}
								{{ endif }}
							</div>
							<div class="comments">
								<h4>Comments</h4>
								<a href="{{ url }}#disqus_thread" class="disqus-comment-count" data-disqus-identifier="{{ id }}"></a>
							</div>
							<div class="published">
								<h4>Date Posted</h4>
								<span class="date">{{ helper:date timestamp=created_on }}</span>
							</div>
							<div class="author">
								<h4>{{ created_by:display_name }}</h4>
								<div class="author-img"><img src="" data-src="{{ author_profile:member_avatar:thumb }}" /></div>
								<div class="author-bio">{{ author_profile:member_bio_short }}</div>
							</div>
						</div>
					</div>
				</div>
			</article>
		</div>
	</div>
</section>
<section id="section_2" class="block-wrapper sub-section comments-section">
	<div class="block">
		<div class="block-inner">
			<div class="mod-comments">
				<div id="disqus_thread"></div>
				<noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript" rel="nofollow">comments powered by Disqus.</a></noscript>
			</div>
		</div>
	</div>
</section>
<script type="text/javascript">
		/* * * CONFIGURATION VARIABLES * * */
	  var disqus_shortname = "wetumka";
	  var disqus_identifier = "{{ id }}";
		var disqus_title = "{{ title }}";
		var disqus_url = "{{ url }}";
	  
	  /* * * DON'T EDIT BELOW THIS LINE * * */
	  (function() {
	      var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
	      dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
	      (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
	  })();
</script>
{{ /post }}