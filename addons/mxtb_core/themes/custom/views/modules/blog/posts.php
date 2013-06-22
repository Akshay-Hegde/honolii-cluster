<div class="row-fluid">    
    <div id="col-main" class="span7">
        {{ if category }}
        <h2 id="page_title" class="category-title {{ category:slug }}-title">{{ category:title }}</h2>
        {{ endif }}
        {{ if posts }}
            {{ posts }}
                {{ if category }}
                <div class="mod block-post {{ category:slug }}-post">
                {{ else }}
                <div class="mod block-post nocat-post">
                {{ endif }}
    
            		<!-- Post heading -->		
            		<div class="hd post-heading">
            			<h4><a title="Read - {{ title }}" href="{{ url }}">{{ title }}</a></h4>
            			<div class="post-date">
            			    {{ helper:date timestamp=created_on format="M jS, Y" }}
            			</div>
            		</div>
            		<div class="bd post-body">
            			<a title="Read - {{ title }}" href="{{ url }}" class="img-link">{{ post_image:img }}</a>
            			{{ preview }}
            		</div>
            		<div class="post-full">
            		    <a title="Read - {{ title }}" href="{{ url }}" class="cta">View More</a>
            		</div>
            		<hr />
            	</div>
 
            {{ /posts }}
            {{ pagination }}
        {{ else }}
            {{ helper:lang line="blog:currently_no_posts" }}
        {{ endif }}
    </div>
    <aside id="col-rail" class="span5">
    	{{ widgets:area slug="blog-cat" }}
    </aside>
</div>