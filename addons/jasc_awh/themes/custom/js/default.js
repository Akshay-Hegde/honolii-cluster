// slider options object
var sliderOptions = {
		productSlider:{
			items:'.bd',
			circular: true,
			speed: 1000,
			vertical: true
		},
		splashSlider:{
			items:'.bd',
			circular: true,
			speed: 1000,
			vertical: true
		},
		smallSlider:{
			items:'.bd',
			circular: true,
			speed: 1000,
			vertical: false
		},
		autoScroller:{
			interval: 3100,
			autoplay: true,
			autopause: true
		}
};

// Image write object and methods
function imageInfo(imageID,imageWidth,imageHeight){
	this.imageID = imageID;
	this.imageWidth = imageWidth;
	this.imageHeight = imageHeight;
	this.modulePath = '/files/';
}
function imageWrite(location,type){
	with (this){
		if(imageWidth){
			modulePath = modulePath+'thumb/'+imageID+'/'+imageWidth;
			if(imageHeight){
				modulePath = modulePath+'/'+imageHeight;
			}
		}else{
			modulePath = modulePath+'large/'+imageID;
		}
		if(type == 'image'){
			var template = '<img class="image-injected" src="'+modulePath+'" />';
		}else if(type == 'background'){
			var template = '<div class="image-injected" style="background-image:url('+modulePath+')"></div>';
		}
		
						
		$(location).append(template);
	}
}
imageInfo.prototype.imageWrite = imageWrite;

$(document).ready(function() {

	var topNav = $('nav > ul > li','#header'),
		blogintro = $('.post-intro > div','#blog-short'),
		scrollProduct = $('.slider','#product-slider'),
		scrollSplash = $('.slider','#splash-slider'),
		scrollSmall = $('.small-slider','#secondary'),
		pageform = $('form.contact-form'),
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
				y = new imageInfo(x.attr('data-image'),x.attr('data-imagew'),x.attr('data-imageh'));
			y.imageWrite(x.parents('.post-wrapper').children('.image-loader'),'background')
		}
	);
	
	// Scroller Condition
	if(scrollProduct.length > 0){
		scrollProduct.scrollable(sliderOptions.productSlider).autoscroll(sliderOptions.autoScroller).navigator({navi:'.fd'});
		scrollAPI = scrollSplash.data('scrollable');
	}else if(scrollSplash.length > 0){
		var x = scrollSplash.find('.fd li')
		scrollSplash.scrollable(sliderOptions.productSlider).autoscroll(sliderOptions.autoScroller);
		scrollAPI = scrollSplash.data('scrollable');
	}else if(scrollSmall.length > 0){
		scrollSmall.scrollable(sliderOptions.smallSlider).autoscroll(sliderOptions.autoScroller).navigator({navi:'.fd'});
		scrollAPI = scrollSplash.data('scrollable');
	}
	
	// Form 
	pageform.addClass('nice');
	pageform.find(':text').addClass('input-text');
	pageform.find('.contact-button input').addClass('nice radius large blue button')
	
	// needed for functions in document head
	try{window.$ready()}catch(err){/*do nothing*/}
  
});