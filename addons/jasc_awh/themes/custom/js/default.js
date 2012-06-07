$(document).ready(function() {

	var sliderOptions = { // slider options object
			productSlider:{
				items:'.bd',
				circular: true,
				speed: 1000,
				vertical: true
			},
			autoScroller:{
				interval: 3100,
				autoplay: true,
				autopause: true
			}
		},
		topNav = $('nav > ul > li','#header'),
		blogintro = $('.post-intro > div','#blog-short'),
		scrollSplash = $('.slider','#splash-slider'),
		pageform = $('form'),
		scrollAPI
	;
	
	topNav.find('a').append('<span class="arrow"></span>');
	topNav.hover(
		function(){
			$(this).addClass('hover');
		},
		function(){
			$(this).removeClass('hover');
		}
	);
	
	// Set tabs controller
	$("dl.tabs").tabs("ul.tabs-content > li");
	
	// pull image data and create image
	blogintro.each(
		function(i){
			var x = $(this),
				y = new imageInfo(x.attr('data-image'),x.attr('data-imagew'),x.attr('data-imageh'))
			;
			
			y.imageWrite(x.parents('.post-wrapper').children('.image-loader'),'background')
		}
	);
	
	// Scroller Condition
	if(scrollSplash.length > 0){
		var x = scrollSplash.find('.fd li')
		scrollSplash.scrollable(sliderOptions.productSlider).autoscroll(sliderOptions.autoScroller).navigator({navi:'.fd'});
		scrollAPI = scrollSplash.data('scrollable');
	};
	
	// Form 
	pageform.addClass('nice');
	pageform.find(':text').addClass('input-text');
	pageform.find('.contact-button input').addClass('nice radius large blue button');
	pageform.find('input + span.error').children().unwrap();
	  
});