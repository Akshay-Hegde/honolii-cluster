{{ asset:js_inline }}
		requirejs(["pages/blog"]);
{{ /asset:js_inline }}

<section id="section_main" class="block-wrapper intro-section">
	<div class="block">
		<div class="block-inner">
			<!-- edit in admin/widgets/site-snippets/blog-header-block -->
			{{ widgets:instance id="18"}}
		</div>
	</div>
</section>
<section id="section_1" class="block-wrapper sub-section blog-list-section">
	<div class="block">
		<div class="block-inner">
			<div class="mod-posts">
				<!-- <header class="mod-posts-hd"></header> -->
				<section class="mod-posts-bd">
				{{ if posts }}
					{{ posts }}
							<article class="mod-post">
								<header class="mod-post-hd">
									<h2 class="post-headline"><a href="{{ url }}">{{ title }}</a></h2>
									<div class="post-meta">
										by <span class="author">{{ created_by:display_name }}</span> and published on <span class="date">{{ helper:date timestamp=created_on }}</span>
										<div class="category-keyword">
											{{ if category }}
												<strong>Category:</strong>
												<span class="category"><a href="{{ url:site }}blog/category/{{ category:slug }}">{{ category:title }}</a></span>
											{{ endif }}
											
											{{ if keywords }}
												<strong>Tag:</strong>
												{{ keywords }}
													<span class="keyword"><a href="{{ url:site }}blog/tagged/{{ keyword }}">{{ keyword }}</a></span>
												{{ /keywords }}
											{{ endif }}
										</div>
									</div>
									{{ if post_image }}
									<div class="post-image">
										<a href="{{ url }}"><img data-src="{{ post_image:image }}" alt="" /></a>
									</div>
									{{ endif }}
								</header>
								<div class="mod-post-bd">
										{{ preview }}
								</div>
								<footer class="mod-post-ft">
									<a class="cta" href="{{ url }}">Read More</a>
								</footer>
							</article>
					{{ /posts }}
					{{ pagination }}
				{{ else }}
						{{ helper:lang line="blog:currently_no_posts" }}
				{{ endif }}
				</section>
				<!-- <footer class="mod-posts-ft"></footer> -->
			</div>
		</div>
	</div>
</section>