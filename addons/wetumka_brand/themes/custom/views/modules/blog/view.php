{{ asset:js_inline }}
	requirejs(["pages/blog"]);
{{ /asset:js_inline }}

{{ post }}
<section id="section_main" class="block-wrapper blog-post-section">
	<div class="block">
		<div class="block-inner">
			<article class="mod-post">
				<header class="mod-post-hd">
					<h1 class="post-headline">{{ title }}</h1>
					<div class="post-meta">
						by <span class="author">{{ created_by:display_name }}</span>, published <span class="date">{{ helper:date timestamp=created_on }}</span>
						<div class="category-keyword">
							<a href="#disqus_thread" class="disqus-comment-count" data-disqus-identifier="{{ id }}"></a>
							{{ if category }}
								<span class="category">Category: <a href="{{ url:site }}blog/category/{{ category:slug }}">{{ category:title }}</a></span>
							{{ endif }}
							{{ if keywords }}
								<span class="keyword">Tag: 
								{{ keywords }}
									<a href="{{ url:site }}blog/tagged/{{ keyword }}">{{ keyword }}</a>
								{{ /keywords }}
								</span>
							{{ endif }}
						</div>
					</div>
					{{ if post_image }}
					<div class="post-image">
						<a href="{{ url }}"><img data-src="{{ post_image:image }}" alt="" /></a>
					</div>
					{{ endif }}
				</header>
				<div class="mod-post-bd columns">
						{{ body }}
				</div>
				<footer class="mod-post-ft">
					<div class="mod-author">
						{{ user:profile user_id=author_id }}
							{{ streams:team_member namespace="pages" include_by="id" include=team_member }}
								<div class="mod-author-hd"><img src="" data-src="{{ member_avatar:thumb }}" /></div>
								<div class="mod-author-bd">{{ member_bio_short }}</div>
							{{ /streams:team_member }}
						{{ /user:profile }}
					</div>
				</footer>
			</article>
		</div>
	</div>
</section>
<section id="section_1" class="block-wrapper sub-section comments-section">
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