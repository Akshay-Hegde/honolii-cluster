<section id="blog" class="blog-posts-section default-section">
    <div class="container">
        <div class="row">
            <aside class="col-md-3 hidden-xs">
                {{ widgets:area slug="blog-posts" }}
            </aside>
            <div class="col-md-9">
                {{ post }}
                
                <article id="blog-post" class="post single-post">
                	<h2>{{ title }}</h2>
                	<div class="meta">
                		<div class="date author">
                            <span class="glyphicon glyphicon-user"></span>
                            <span>{{ created_by:display_name }}</span>
                            <span class="glyphicon glyphicon-time"></span>
                            <span>{{ helper:date timestamp=created_on }}</span>
                        </div>
                		{{ if category }}
                		<div class="category">
                			{{ helper:lang line="blog:category_label" }}
                			<span><a href="{{ url:site }}blog/category/{{ category:slug }}">{{ category:title }}</a></span>
                		</div>
                		{{ endif }}
                		{{ if keywords }}
                		<div class="keywords">
                		    <span class="glyphicon glyphicon-tags"></span>
                			{{ keywords }}
                				<span class="keyword"><a href="{{ url:site }}blog/tagged/{{ keyword }}">{{ keyword }}</a></span>
                			{{ /keywords }}
                		</div>
                		{{ endif }}
                	</div>
                	<div class="body">
                		{{ body }}
                	</div>
                </article>
                
                {{ /post }}
                
                <?php if (Settings::get('enable_comments')): ?>
                
                <section id="comments" class="comments-section">
                
                	<div id="existing-comments">
                		<h5><?php echo lang('comments:title') ?></h5>
                		<?php echo $this->comments->display() ?>
                	</div>
                
                	<?php if ($form_display): ?>
                		<?php echo $this->comments->form() ?>
                	<?php else: ?>
                	<?php echo sprintf(lang('blog:disabled_after'), strtolower(lang('global:duration:'.str_replace(' ', '-', $post[0]['comments_enabled'])))) ?>
                	<?php endif ?>
                </section>
                
                <?php endif ?>
            </div>
        </div>
    </div>
</section>