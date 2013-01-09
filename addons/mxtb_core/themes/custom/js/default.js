/* Default JS */
$(document).ready(function() {
	
	// Home Page Carousel
	$('#myCarousel').carousel();
	
	// Gallery Index - SubGallery Slider
	$('.gallery-item','#gallery-index').hover(
	    function(event){
	        var $this = $(this);
	        var $sublistGallery = $this.find('.gallery-sublist-content');
	        
	        $this.find('.gallery-sublist').height($sublistGallery.outerHeight());
	        $this.addClass('hover');
	    },
	    function(event){
	        var $this = $(this);
	        $this.find('.gallery-sublist').height(0);
	        $this.removeClass('hover');
	    }
	)
	// Size gallery slider
	if($('#gallery-gallery').length){
	   var $thumblist = $('.gallery-image-list','#gallery-gallery');
	   var width = $thumblist.find('li.gallery-image-item').length * 128 - 18;
	   $thumblist.width(width);
	}
	// Gallery - add thumb nail to image view
	if($('#gallery-image').length){
	    var galleryImages = [];
	    var $thumblist = $('.gallery-image-list','#gallery-image');
        var width = gallery_images_json.length * 128 - 18;
        $thumblist.width(width);
	    
	    for(var x=gallery_images_json.length;x--;){
	        galleryImages.unshift(
	            '<li class="gallery-image-item">'+
	            '<a href="/galleries/'+gallery_json.slug+'/'+gallery_images_json[x].id+
	               '" class="gallery-image" rel="gallery-image" data-src="/files/large/'+gallery_images_json[x].file_id+'" title="'+gallery_images_json[x].name+'">'+
	            '<img src="/files/thumb/'+gallery_images_json[x].file_id+'/110/110/fit" alt="'+gallery_images_json[x].name+'" />'+
	            '</a></li>'
	       )
	    }
	    
	    $thumblist.append(galleryImages.join(''));
	}
	// Gallery Controls
	if($('#gallery-image-large').length && gallery_images_json.length > 2){
	    var $largeImage = $('#gallery-image-large');
	    var current = $largeImage.attr('data-current-image');
	    var currentIndex = undefined;
	    var nextID = undefined;
	    var prevID = undefined;
	    
	    for(var x=gallery_images_json.length;x--;){
	        if(gallery_images_json[x].file_id === current){
	            currentIndex = x;
	        }
	    }
	    
	    switch(currentIndex){
	        case 0:
	           nextID = 1;
	           prevID = gallery_images_json.length -1;
	           break;
	        case gallery_images_json.length -1:
	           nextID = 0;
	           prevID = currentIndex - 1;
	           break;
	        default:
	           nextID = currentIndex + 1;
	           prevID = currentIndex - 1;
	    }
	    
	    $largeImage.find('.image-control > .left').attr('href','/galleries/'+gallery_json.slug+'/'+gallery_images_json[prevID].id);
	    $largeImage.find('.image-control > .right').attr('href','/galleries/'+gallery_json.slug+'/'+gallery_images_json[nextID].id);
	}
	
	// Set image in latest news blog
	var $blogPreview = $('.blog-post','#home-blog-posts');
	$blogPreview.each(function(index,element){
	    var $this = $blogPreview.eq(index);
	    var $image = $this.find('.post-content .intro img').detach();
	    var imgSrc = $image.attr('src');
	    $image.attr({
	        src: imgSrc + '212/250/fit',
	        class: ''
	    });
	    $this.find('.post-image a').empty().prepend($image);
	})
	
	// Set image in side rail related news blog
    var $blogPreviewSide = $('.siderail_posts .blog-post','#col-rail');
    $blogPreviewSide.each(function(index,element){
        var $this = $blogPreviewSide.eq(index);
        var $image = $this.find('.post-content .intro img').detach();
        var imgSrc = $image.attr('src');
        $image.attr({
            src: imgSrc + '114/134/fit',
            class: ''
        });
        $this.find('.post-image a').empty().prepend($image);
    })
    
    // Set image category view of blog
    var $blogCat = $('.mod.block-post','#col-main');
    $blogCat.each(function(index,element){
        var $this = $blogCat.eq(index);
        var $image = $this.find('.post-body img').detach();
        var imgSrc = $image.attr('src');
        $image.attr({
            src: imgSrc + '480/300/fit',
            class: 'active'
        });
        $this.find('.post-body').prepend($image);
    })
	
	// Style Form
	$('.crud_form').addClass('form-horizontal');
	$('#populate-address').change(function(event) {
		var $this = $(this);
		console.log($this.val(),event)
	});
});
