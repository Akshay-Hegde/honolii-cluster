{{ asset:js_inline }}
    requirejs(["pages/blog"]);
{{ /asset:js_inline }}

<section id="section_main" class="block-wrapper intro-section">
  <div class="block">
    <div class="block-inner">
      <!-- edit in admin/widgets/site-snippets/blog-header-block -->
      {{ widgets:instance id="18"}}

      {{ if category }}
        <h3>{{ category:title }}</h3>
      {{ endif }}
      {{ if tag }}
        <h3><span>Tag:</span> {{ tag }}</h3>
      {{ endif }}
    </div>
  </div>
</section>
<section id="section_2" class="block-wrapper sub-section blog-list-section">
  <div class="block">
    <div class="block-inner">
      <div class="mod-posts">
        <!-- <header class="mod-posts-hd"></header> -->
        <section class="mod-posts-bd">
        {{ if posts }}
          {{ posts }}
              <article class="mod-post">
                <header class="mod-post-hd">
                  {{ if category }}
                    <a class="category" href="{{ url:site }}blog/category/{{ category:slug }}">{{ category:title }}</a>
                  {{ endif }}
                  <h2 class="post-headline"><a href="{{ url }}">{{ title }}</a></h2>
                  <div class="mod-post-meta">
                    <div class="mod-post-meta-hd"><img src="" data-src="{{ author_profile:member_avatar:thumb }}" /></div>
                    <div class="mod-post-meta-bd">
                      <div class="author-date">{{ created_by:display_name }} | {{ helper:date timestamp=created_on }}</div>
                      <div class="comments-keyword">
                        <a href="{{ url }}#disqus_thread" class="disqus-comment-count" data-disqus-identifier="{{ id }}"></a>
                        {{ if keywords }}
                          {{ keywords }}<span class="keyword"><a href="{{ url:site }}blog/tagged/{{ keyword }}">{{ keyword }}</a></span>{{ /keywords }}
                        {{ endif }}
                      </div>
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