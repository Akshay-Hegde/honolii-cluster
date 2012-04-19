$(function(){
	
	// Product page tabs
	$("#tabs").tabs("#tab_panels > div");
	// Hover over gallery images
	$('.mod.gallery-image, #main-nav .drop-down').hover(
	  function(){$(this).addClass('hover');},
	  function(){$(this).removeClass('hover');}
	);
	// Pretty Photo Lightbox
	$("a.prettyPhoto").prettyPhoto({theme: 'facebook',overlay_gallery: false});
	// Add class names to sidebar headers to add icons
	var sidebar_header = $('.sidebar .widget > h3:first-child');
	if(sidebar_header.length > 0){
		for(var j = sidebar_header.length; j--;){
			var x = $(sidebar_header[j])
			switch (x.html()){
				case "Products":
				case "Services":
				  x.addClass('icon services')
				  break;
				case "Portfolio":
				  x.addClass('icon portfolio')
				  break;
				case "About Us":
				  x.addClass('icon about_us')
				  break;
				case "Contact Us":
				  x.addClass('icon contact_us')
				  break;
				default:
				  //console.log('no value')
			}
		}
	}
	// Add class names to headers to add icons
	var main_header = $('#breadcrumbs')
	if(main_header.length > 0){
		for(var j = main_header.length; j--;){
			var x = $(main_header[j])
			switch (x.children('*:first-child').html()){
				case "Products":
				case "Services":
				  x.addClass('icon services')
				  break;
				case "Gallery":
				  x.addClass('icon portfolio')
				  break;
				case "About Us":
				  x.addClass('icon about_us')
				  break;
				case "Contact Us":
				  x.addClass('icon contact_us')
				  break;
				case "Request A Quote":
				  x.addClass('icon quote')
				  break;
				default:
				  //console.log('no value')
			}
		}
	}
	// replace text value with placeholder 
	var textfields = $("input[type='text']")
	if(textfields.length > 0){
		for(var x = textfields.length; x--;){
			var y = $(textfields[x]);
			if(y.val() == "" && y.attr('placeholder')){
				y.val(y.attr('placeholder'))
			}
			else if(y.val() !== "" && y.attr('placeholder') && y.val() != y.attr('placeholder')){
				if(y.parent('.input-wrapper').length > 0){	
					y.parent().addClass('active')
				}else{
					y.addClass('active')
				}
			}
			else if(y.val() !== "" && !y.attr('placeholder')){
				if(y.parent('.input-wrapper').length > 0){	
					y.parent().addClass('active')
				}else{
					y.addClass('active')
				}
			}
		}
	}
	textfields.focus(function () {
		var input = $(this);
		if(input.parent('.input-wrapper').length > 0){	
			input.parent().addClass('active')
		}else{
			input.addClass('active')
		}
		if(input.attr('placeholder') == input.val()){
			input.val('')
		}
    });
	textfields.blur(function () {
		var input = $(this);
		if(input.val() == '' && input.attr('placeholder')){
			input.val(input.attr('placeholder'))
			if(input.parent('.input-wrapper').length > 0){	
				input.parent().removeClass('active')
			}else{
				input.removeClass('active')
			}
		}else if(input.val() == '' && !input.attr('placeholder')){
			if(input.parent('.input-wrapper').length > 0){	
				input.parent().removeClass('active')
			}else{
				input.removeClass('active')
			}
		}
    });
	// Form validation
	$('form').validate()
	// needed for functions in document head
	try{window.$ready()}catch(err){/*do nothing*/}
});