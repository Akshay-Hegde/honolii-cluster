<div id="blog-long" class="mod blog-post">
    {{ post }}
    	<div class="hd">
    		<h1>{{ title }}</h1>
    		<div class="post-meta">
                <p class="post-date">Posted: {{ helper:date timestamp=created_on }}</p>
                {{ if category }}
                    <p class="post-category">Category: <a href="blog/category/{{ category:slug }}">{{ category:title }}</a></p>
                {{ endif }}
                {{ if keywords }}
                    <p class="post-keywords">Keywords: 
                        {{ keywords }}
                            <a href="blog/tagged/{{ keyword }}">{{ keyword }}</a>
                        {{ /keywords }}
                    </p>
                {{ endif }}
            </div>
    	</div>
    	<div class="bd">
    		{{ body }}
    	</div>
    	<div class="fd"></div>
	{{ /post }}
</div>
<?php if (Settings::get('enable_comments')): ?>
    <div id="comments" class="mod comments">
        <div id="existing-comments" class="hd comments">
            <h4><?= lang('comments:title'); ?></h4>
            <?= $this->comments->display() ?>
        </div>
    
        <?php if ($form_display): ?>
            <?= $this->comments->form() ?>
        <?php else: ?>
        <?= sprintf(lang('blog:disabled_after'), strtolower(lang('global:duration:'.str_replace(' ', '-', $post[0]['comments_enabled'])))) ?>
        <?php endif ?>
        <div class="fd"></div>
    </div>
<?php endif ?>