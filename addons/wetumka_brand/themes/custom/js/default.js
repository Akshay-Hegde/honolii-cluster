// default.js
define ([], function () {
	"use strict";

	var _ = {};

	// init function
	_.init = function(){

		var that = this;
		
		// set scrollEventVisible elements
		this.toggleElements = document.getElementsByClassName('event-visible');
		
		// scrolling delay event
		this.didScroll = false;
		setInterval(function(){
			that.windowScroll();
		}, 300);
		window.onscroll = function(){
			_.didScroll = true;
		};
		
		// set images on document ready
		this.setImgSrc();
	};

	// Set images to use alternate size based on pixel density
	_.setImgSrc = function(){
		
		var images = document.getElementsByTagName('img'),
			pxRatioSize = null;
		
		for (var i = 0; i < images.length; i++) {
			if(images[i].hasAttribute('data-src')){
				pxRatioSize = images[i].width * window.devicePixelRatio;
				images[i].setAttribute('src',images[i].getAttribute('data-src') + '/' + pxRatioSize);
			}
		}
	};

	// Toggle element .event-active class when visible on window, hook class = .event-visible
	_.scrollEventVisible = function(){

		var winTop = window.pageYOffset,
			winBot = winTop + window.innerHeight,
			eleTop,eleBot,active;

		for (var i = 0; i < this.toggleElements.length; i++) {
			eleTop = this.toggleElements[i].offsetTop;
			eleBot = eleTop + this.toggleElements[i].offsetHeight;
			active = this.toggleElements[i].classList.contains('event-visible-active');

			if (winBot > eleTop && winTop < eleBot) {
				if (!active){
					this.toggleElements[i].classList.add('event-visible-active');
				}
			}else{
				if (active){
					this.toggleElements[i].classList.remove('event-visible-active');
				}
			}
		}

	};

	// Window scroll function
	_.windowScroll = function(){
		if(this.didScroll){
			this.didScroll = false;
			// list of functions to call on scroll delayed scroll event
			this.scrollEventVisible();
		}
	};

	_.init();

	return _;
});