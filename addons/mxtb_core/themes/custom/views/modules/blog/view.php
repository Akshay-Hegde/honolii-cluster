<div class="row-fluid">    
    <div id="col-main" class="span7">
    {{ post }}
    	{{ if category }}
        <div class="mod page-post {{ category:slug }}-post">
        {{ else }}
        <div class="mod page-post nocat-post">
        {{ endif }}	
    		<!-- Post heading -->
    		<div class="hd post-heading">
    			<h2>{{ title }}</h2>
    			<div class="post-date">
    			    {{ helper:date timestamp=created_on format="M jS, Y" }}
                </div>
    		</div>
    		<div class="bd post-body">
    			{{ post_image:img }}
    			{{ body }}
    			{{ if gallery }}
    			<p><a class="btn" href="{{gallery}}">View gallery</a></p>
    			{{ endif }}
    		</div>
    	</div>
    {{ /post }}
    	<?php /*
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
         */ ?>
    </div>
    <aside id="col-rail" class="span5">
    	{{ widgets:area slug="blog-post" }}
    </aside>
</div>