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

<?php if (Settings::get('enable_comments')): ?>
<div id="comments" class="mod comments">
    <div id="existing-comments" class="hd comments">
        <h3 class="col-headline"><?= lang('comments:title'); ?></h3>
        <?php echo $this->comments->display() ?>
    </div>
    <div id="comments-form" class="bd comments-form">
        <?php if ($form_display): ?>
            <?= $this->comments->form() ?>
        <?php else: ?>
            <?= sprintf(lang('blog:disabled_after'), strtolower(lang('global:duration:'.str_replace(' ', '-', $post[0]['comments_enabled'])))) ?>
        <?php endif ?>
    </div>
    <div class="fd"></div>
</div>
<?php endif ?>