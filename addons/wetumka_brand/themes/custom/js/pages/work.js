// work.js
define (['default','lib/sections-nav'], function (def,sections) {
	"use strict";

	var _ = {};
	
	_.init = function(){
		this.carousel.items = document.getElementById('section_2');
		this.carousel.items = this.carousel.items.getElementsByClassName('mod-testimonial');

		//this.carousel.init();

		//this.carousel.items[0].classList.add('active');

	};

	// make custom carosel
	_.carousel = {
		displayTime : 7000,
		init : function(){
			var pfx = ["webkit", "moz", "MS", "o", ""];

			function prefixedEvent(element, type, callback) {
				for (var p = 0; p < pfx.length; p++) {
					if (!pfx[p]) type = type.toLowerCase();
					element.addEventListener(pfx[p]+type, callback, false);
				}
			}

			for (var i = this.items.length - 1; i >= 0; i--) {
				// prefixedEvent(this.items[i], "AnimationStart", this.animationEvent);
				// prefixedEvent(this.items[i], "AnimationIteration", this.animationEvent);
				// prefixedEvent(this.items[i], "AnimationEnd", this.animationEvent);
				prefixedEvent(this.items[i], 'TransitionEnd', function(e){_.carousel.transitionEvent(e);});
			}
		},
		animationEvent : function(e){
			var type = e.type.toUpperCase();
			if(type.includes('START')){
				console.log('start',e);
			}else if(type.includes('ITERATION')){
				e.srcElement.classList.remove('play');
			}else if(type.includes('END')){
				console.log('end',e);
			}
		},
		transitionEvent : function(e){
			var current, next, type = e.type.toUpperCase();

			if(type.includes('END')){
				if(e.srcElement.classList.contains('active')){
					for (var i = this.items.length - 1; i >= 0; i--) {
						if(this.items[i].classList.contains('active')){
							current = i;
						}
					}

					if(this.items.length === current + 1){
						next = 0;
					}else{
						next = current + 1;
					}

					this.current = current;
					this.next = next;

					setTimeout(function(current,next){
						_.carousel.items[_.carousel.current].classList.remove('active');
						_.carousel.items[_.carousel.next].classList.add('active');
					}, this.displayTime);
				}
			}
		}
	};

	_.init();

	return _;
});