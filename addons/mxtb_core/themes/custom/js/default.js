/* Default JS */
$(document).ready(function() {
	
	// Home Page Carousel
	$('#myCarousel').carousel();
	
	// Gallery Index - SubGallery Slider
	$('.gallery-heading','#gallery-index').click(
	    function(event){
	        var $this = $(this);
	        var $sublistGallery = $this.siblings('.gallery-sublist');
	        
	        if($this.hasClass('active')){
	            $this.siblings('.gallery-sublist').height(0);
	            $this.removeClass('active');
	        }else{
	            $sublistGallery.height($sublistGallery.find('.gallery-sublist-content').outerHeight());
                $this.addClass('active');
	        }
	    }
	);
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
	       );
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
	// Style Form
	$('.crud_form').addClass('form-horizontal');
	$('input:radio[name="same_address"]').click(function(event) {
		var $this = $(this);
		var $trackAddress = $('.track-address');
		if($this.val() == true){
		    // copy contact address
		    $trackAddress.css({
                'height':0,
                'opacity':0
            });
            $('#track_address').val($('#address').val());
            $('#track_address_city').val($('#address_city').val());
            $('#track_address_state').val($('#address_state').val());
            $('#track_address_zip').val($('#address_zip').val());
		}else{
		    // clear track address and open fields
		    $trackAddress.css({
		        'height':'auto',
		        'opacity':1
		    });
		    $('#track_address').val(null);
            $('#track_address_city').val(null);
            $('#track_address_state').val(null);
            $('#track_address_zip').val(null);
		}
	});
	$('#track_type').change(function(event){
	    var $this = $(this);
	    var $trackPermits = $('.track-permits');
	    if($this.val() === 'Public'){
	       $trackPermits.addClass('active').css({
                'opacity':1
            });
	    }else{
	    	$trackPermits.removeClass('active').css({
                'opacity':0
            });
	    }
	});
	$('#track_style').change(function(event){
	   var $this = $(this);
       var $trackSupercross = $('.track-supercross');
       var $trackLength = $('.track-length');
       
       switch($this.val()){
           case 'Supercross':
               $trackSupercross.css({
                    'height':'auto',
                    'opacity':1
               });
               $trackLength.css({
                    'height':0,
                    'opacity':0
               });
           break;
           case 'Motocross':
           case 'ATV':
           case 'Other':
               $trackLength.css({
                    'height':'auto',
                    'opacity':1
               });
               $trackSupercross.css({
                    'height':0,
                    'opacity':0
               });
           break;
           default:
               $trackLength.css({
                    'height':0,
                    'opacity':0
               });
               $trackSupercross.css({
                    'height':0,
                    'opacity':0
               });
       }
	});
	$('#more_information').attr('placeholder','Comments...');
	//form validation
	$('#form input,select').not('[name="track_permits"],[name="stream_id"],[name="lot_photograph_file"],[name="lot_photograph"],[name="project_start_date"],[name="project_budget"]').attr('required','true');
	
	$('#form .tab-pane .next').click(function(e){
		var $inputs = $(this).parents('.tab-pane').find('input[required],select[required]');
		var valid = [];
		
		for(var x = 0; x < $inputs.length; x++){
			valid.push($inputs[x].checkValidity());
		}
		
		if(!valid.indexOf(false) > -1){
			$(this).parents('.tab-pane').next().tab('show');
		}
		
	});
	
	//bootstrap lightbox
	var $instagramImage = $('.instagram-feed-list li, .photo-list li');
	$instagramImage.click(function(event){
	    var $this = $(this);
	    var $img = $this.find('img');
	    var $modal = $('#media-lightbox');
	    var $media = $modal.find('.lightbox-content');
	    
	    $img.fadeTo(300, 0.2);
	    
	    $media.children('img').remove()
	    $media.append('<img width="612" height="612" src="'+ $img.attr('data-src') +'"/>');
	    
	    $media.find('.lightbox-caption').empty().append($img.attr('alt'));
	    $modal.imagesLoaded(function(){
	        $modal.lightbox();
	        $img.fadeTo(300, 1);
	    });
	});
	var $mainnav = $('#top-nav');
	$('button.btn-nav','#header').click(
	    function(){
	        $mainnav.toggle('fast');
	    }
	);
});
