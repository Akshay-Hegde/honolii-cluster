/* =========================================================
 * bootstrap-lightbox.js
 * ========================================================= */


!function ($) {
	// browser:true, jquery:true, node:true, laxbreak:true
	"use strict"; // jshint ;_;


/* LIGHTBOX CLASS DEFINITION
 * ========================= */

	var Lightbox = function (element, options)
	{
		this.options = options;
		this.$element = $(element)
			.delegate('[data-dismiss="lightbox"]', 'click.dismiss.lightbox', $.proxy(this.hide, this));

		this.options.remote && this.$element.find('.lightbox-body').load(this.options.remote);

		this.cloneSize();
	}

	Lightbox.prototype = {
		constructor: Lightbox,

		toggle: function ()
		{
			return this[!this.isShown ? 'show' : 'hide']();
		},

		show: function ()
		{
			var that = this;
			var e    = $.Event('show')

			this.$element.trigger(e);

			if (this.isShown || e.isDefaultPrevented()) return;


			this.isShown = true;

			this.escape();

			this.backdrop(function ()
			{
				var transition = $.support.transition && that.$element.hasClass('fade');

				if (!that.$element.parent().length)
				{
					that.$element.appendTo(document.body); //don't move modals dom position
				}

				that.$element
					.show();

				if (transition)
				{
					that.$element[0].offsetWidth; // force reflow
				}

				that.$element
					.addClass('in')
					.attr('aria-hidden', false);

				that.enforceFocus();

				transition ?
					that.$element.one($.support.transition.end, function () { that.centerImage(); that.$element.focus().trigger('shown'); }) :
					(function(){ that.centerImage(); that.$element.focus().trigger('shown'); })()

			});
		},
		hide: function (e)
		{
			e && e.preventDefault();

			var that = this;

			e = $.Event('hide');

			this.$element.trigger(e);

			if (!this.isShown || e.isDefaultPrevented()) return;

			this.isShown = false;

			this.escape();

			$(document).off('focusin.lightbox');

			this.$element
				.removeClass('in')
				.attr('aria-hidden', true);

			$.support.transition && this.$element.hasClass('fade') ?
				this.hideWithTransition() :
				this.hideLightbox();
		},
		enforceFocus: function ()
		{
			var that = this;
			$(document).on('focusin.lightbox', function (e)
			{
				if (that.$element[0] !== e.target && !that.$element.has(e.target).length)
				{
					that.$element.focus();
				}
			});
		},
		escape: function ()
		{
			var that = this;
			if (this.isShown && this.options.keyboard)
			{
				this.$element.on('keypress.dismiss.lightbox, keyup.dismiss.lightbox', function ( e )
				{
					e.which == 27 && that.hide();
				});
			}
			else if (!this.isShown)
			{
				this.$element.off('keypress.dismiss.lightbox, keyup.dismiss.lightbox');
			}
		},
		hideWithTransition: function ()
		{
			var that = this;
			var timeout = setTimeout(function ()
			{
				that.$element.off($.support.transition.end);
				that.hideLightbox();
			}, 500);

			this.$element.one($.support.transition.end, function ()
			{
				clearTimeout(timeout);
				that.hideLightbox();
			});
		},
		hideLightbox: function (that)
		{
			this.$element
				.hide()
				.trigger('hidden');

			this.backdrop();
		},
		removeBackdrop: function ()
		{
			this.$backdrop.remove();
			this.$backdrop = null;
		},
		backdrop: function (callback)
		{
			var that   = this;
			var animate = this.$element.hasClass('fade') ? 'fade' : '';

			if (this.isShown && this.options.backdrop)
			{
				var doAnimate = $.support.transition && animate;

				this.$backdrop = $('<div class="modal-backdrop ' + animate + '" />')
					.appendTo(document.body);

				this.$backdrop.click(
					this.options.backdrop == 'static' ?
						$.proxy(this.$element[0].focus, this.$element[0]) :
						$.proxy(this.hide, this)
				);

				if (doAnimate) this.$backdrop[0].offsetWidth; // force reflow

				this.$backdrop.addClass('in');

				doAnimate ?
					this.$backdrop.one($.support.transition.end, callback) :
					callback();

			}
			else if (!this.isShown && this.$backdrop)
			{
				this.$backdrop.removeClass('in');

				$.support.transition && this.$element.hasClass('fade')?
					this.$backdrop.one($.support.transition.end, $.proxy(this.removeBackdrop, this)) :
					this.removeBackdrop();

			} 
			else if (callback)
			{
				callback();
			}
		},
		centerImage: function()
		{
			var that = this;
			var resizedOffs = 0;
			var $img;

			that.h = that.$element.height();
			that.w = that.$element.width();
			
			if(that.options.resizeToFit)
			{
				
				resizedOffs = 10;
				$img = that.$element.find('.lightbox-content').find('img:first');
				// Save original filesize
				if(!$img.data('osizew')) $img.data('osizew', $img.width());
				if(!$img.data('osizeh')) $img.data('osizeh', $img.height());
				
				var osizew = $img.data('osizew');
				var osizeh = $img.data('osizeh');
				
				// Resize for window dimension < than image
				// Reset previous
				$img.css('max-width', 'none');
				$img.css('max-height', 'none');
				

				var wOffs = 50; // STYLE ?
				var hOffs = 40; // STYLE ?
				if(that.$element.find('.lightbox-header').length > 0)
				{
					wOffs += 40;
					hOffs += 10;
				}
				$img.css('max-width', $(window).width() - wOffs);
				$img.css('max-height', $(window).height() - hOffs);
				
				that.w = $img.width();
				that.h = $img.height();
			}

			that.$element.css({
				"position": "fixed",
				"left": ( $(window).width()  / 2 ) - ( that.w / 2 ),
				"top":  ( $(window).height() / 2 ) - ( that.h / 2 ) - resizedOffs
			});
			that.enforceFocus();
		},
		cloneSize: function() // The cloneSize function is only run once, but it helps keep image jumping down
		{
			var that = this;
			// Clone the element and append it to the body
			//  this allows us to get an idea for the size of the lightbox
			that.$clone = that.$element.filter(':first').clone()
			.css(
			{
				'position': 'absolute',
				'top'     : -2000,
				'display' : 'block',
				'visibility': 'visible',
				'opacity': 100
			})
			.removeClass('fade')
			.appendTo('body');

			that.h = that.$clone.height();
			that.w = that.$clone.width();
			that.$clone.remove();

			// try and center the element based on the
			//  height and width retrieved from the clone
			that.$element.css({
				"position": "fixed",
				"left": ( $(window).width()  / 2 ) - ( that.w / 2 ),
				"top":  ( $(window).height() / 2 ) - ( that.h / 2 )
			});
		}
	}


/* LIGHTBOX PLUGIN DEFINITION
 * ======================= */

	$.fn.lightbox = function (option)
	{
		return this.each(function ()
		{
			var $this   = $(this);
			var data    = $this.data('lightbox');
			var options = $.extend({}, $.fn.lightbox.defaults, $this.data(), typeof option == 'object' && option);
			if (!data) $this.data('lightbox', (data = new Lightbox(this, options)));

			if (typeof option == 'string')
				data[option]()
			else if (options.show)
				data.show()
		});
	};

	$.fn.lightbox.defaults = {
		backdrop: true,
		keyboard: true,
		show: true,
		resizeToFit: true
	};

	$.fn.lightbox.Constructor = Lightbox;


/* LIGHTBOX DATA-API
 * ================== */

	$(document).on('click.lightbox.data-api', '[data-toggle="lightbox"]', function (e)
	{
		var $this = $(this);
		var href  = $this.attr('href');
		var $target = $($this.attr('data-target') || (href && href.replace(/.*(?=#[^\s]+$)/, ''))); //strip for ie7
		var option = $target.data('lightbox') ? 'toggle' : $.extend({ remote:!/#/.test(href) && href }, $target.data(), $this.data());
		var img    = $this.attr('data-image') || false;
		var $imgElem;

		e.preventDefault();

		if(img)
		{
			$target.data('original-content', $target.find('.lightbox-content').html());
			$target.find('.lightbox-content').html('<img border="0" src="'+img+'" />');
		}

		$target
			.lightbox(option)
			.one('hide', function () 
			{
				$this.focus()
			})
			.one('hidden',function ()
			{
				if( img )
				{
					$target.find('.lightbox-content').html( $target.data('original-content') );
					img = undefined;
				}
			});
	})

}(window.jQuery);
/*!
 * jQuery imagesLoaded plugin v2.1.1
 * http://github.com/desandro/imagesloaded
 *
 * MIT License. by Paul Irish et al.
 */

/*jshint curly: true, eqeqeq: true, noempty: true, strict: true, undef: true, browser: true */
/*global jQuery: false */

;(function($, undefined) {
'use strict';

// blank image data-uri bypasses webkit log warning (thx doug jones)
var BLANK = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==';

$.fn.imagesLoaded = function( callback ) {
	var $this = this,
		deferred = $.isFunction($.Deferred) ? $.Deferred() : 0,
		hasNotify = $.isFunction(deferred.notify),
		$images = $this.find('img').add( $this.filter('img') ),
		loaded = [],
		proper = [],
		broken = [];

	// Register deferred callbacks
	if ($.isPlainObject(callback)) {
		$.each(callback, function (key, value) {
			if (key === 'callback') {
				callback = value;
			} else if (deferred) {
				deferred[key](value);
			}
		});
	}

	function doneLoading() {
		var $proper = $(proper),
			$broken = $(broken);

		if ( deferred ) {
			if ( broken.length ) {
				deferred.reject( $images, $proper, $broken );
			} else {
				deferred.resolve( $images );
			}
		}

		if ( $.isFunction( callback ) ) {
			callback.call( $this, $images, $proper, $broken );
		}
	}

	function imgLoadedHandler( event ) {
		imgLoaded( event.target, event.type === 'error' );
	}

	function imgLoaded( img, isBroken ) {
		// don't proceed if BLANK image, or image is already loaded
		if ( img.src === BLANK || $.inArray( img, loaded ) !== -1 ) {
			return;
		}

		// store element in loaded images array
		loaded.push( img );

		// keep track of broken and properly loaded images
		if ( isBroken ) {
			broken.push( img );
		} else {
			proper.push( img );
		}

		// cache image and its state for future calls
		$.data( img, 'imagesLoaded', { isBroken: isBroken, src: img.src } );

		// trigger deferred progress method if present
		if ( hasNotify ) {
			deferred.notifyWith( $(img), [ isBroken, $images, $(proper), $(broken) ] );
		}

		// call doneLoading and clean listeners if all images are loaded
		if ( $images.length === loaded.length ) {
			setTimeout( doneLoading );
			$images.unbind( '.imagesLoaded', imgLoadedHandler );
		}
	}

	// if no images, trigger immediately
	if ( !$images.length ) {
		doneLoading();
	} else {
		$images.bind( 'load.imagesLoaded error.imagesLoaded', imgLoadedHandler )
		.each( function( i, el ) {
			var src = el.src;

			// find out if this image has been already checked for status
			// if it was, and src has not changed, call imgLoaded on it
			var cached = $.data( el, 'imagesLoaded' );
			if ( cached && cached.src === src ) {
				imgLoaded( el, cached.isBroken );
				return;
			}

			// if complete is true and browser supports natural sizes, try
			// to check for image status manually
			if ( el.complete && el.naturalWidth !== undefined ) {
				imgLoaded( el, el.naturalWidth === 0 || el.naturalHeight === 0 );
				return;
			}

			// cached images don't fire load sometimes, so we reset src, but only when
			// dealing with IE, or image is complete (loaded) and failed manual check
			// webkit hack from http://groups.google.com/group/jquery-dev/browse_thread/thread/eee6ab7b2da50e1f
			if ( el.readyState || el.complete ) {
				el.src = BLANK;
				el.src = src;
			}
		});
	}

	return deferred ? deferred.promise( $this ) : $this;
};

})(jQuery);
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
	$('#form input,select').not('[type="checkbox"],[name="track_permits"],[name="stream_id"],[name="lot_photograph_file"],[name="lot_photograph"],[name="project_start_date"],[name="project_budget"]').attr('required','true');
	
	$('#form .tab-pane .next').click(function(e){
		var $inputs = $(this).parents('.tab-pane').find('input[required],select[required]');
		var valid = [];
		try{
			for(var x = 0; x < $inputs.length; x++){
				if(!$inputs[x].checkValidity()){
					$inputs.eq(x).addClass('not-valid');
					valid.push(x);
				}else{
					$inputs.eq(x).removeClass('not-valid');
				}
			}
			if(valid.length === 0){
				$(this).siblings('.target').tab('show');
			}
		}
		catch(err){
			$(this).siblings('.target').tab('show');
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
	    
	    $media.children('img').remove();
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
