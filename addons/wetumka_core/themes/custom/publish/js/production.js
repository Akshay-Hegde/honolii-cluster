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
// define WindowManager Class
function WindowManager(){
    this.$window = $(window);
    this.width = this.$window.width();
    this.height = this.$window.height();
    this.scrollTop;
    // listener actions on window changes
    this.$window.resize(this, this.resize);
    this.$window.scroll(this, this.scroll);
}
WindowManager.prototype.resize = function(event){
    var $window = event.data.$window;
    event.data.width = $window.width();
    event.data.height = $window.height();
};
WindowManager.prototype.scroll = function(event){
    var $window = event.data.$window;
    event.data.scrollTop = $window.scrollTop();
};
// define HeaderManager Class
function HeaderManager(headerEle,windowObj) {
    this.mywindow = windowObj;
    this.$head = $(headerEle);
}
HeaderManager.prototype.motion = function(event){
    if(!event.data.isVisible()){
        event.data.$head.addClass('fixed');
    }else if(event.data.$head.hasClass('fixed') && event.data.mywindow.scrollTop === 0){
        event.data.$head.removeClass('fixed');
    }
};
HeaderManager.prototype.isVisible = function(){
    var visible = null;
    if(this.mywindow.scrollTop > this.$head.outerHeight()){
        visible = false;
    }else{
        visible = true;
    }
    return visible;
};
var mywindow, myheader;

mywindow = new WindowManager;
myheader = new HeaderManager('#header',mywindow);

// events
mywindow.$window.on('scroll',myheader, myheader.motion);

// About Section
about = function(setHeight){
	var $aboutSection, x, html, tempImg, images;
	
	$splash = $('#about > .splash');
	
	if(setHeight){
		$splash.height($splash.children('.container').outerHeight());
	}else{

		images = [];
		html = [];
		
		for(x = 1; x < 18; x++){
			var img = new Image();
			img.src = '../addons/wetumka_core/themes/custom/img/seq/seq-hp-top-' + x + '.jpg';
			img.onload = function(){
				images.push(img.src);
				if(images.length == 17){
					$splash.height($splash.children('.container').outerHeight()).addClass('animate');
				}
			};
			html.push('<div class="seq-set-1 seq-' + x + '" style="background-image:url(' + img.src + ')"></div>');
		}
		
		//html.reverse();
		$splash.prepend(html.join(''));
		
	}
};

// call functions
about();

mywindow.$window.on('resize',true,about);