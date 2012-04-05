$(function(){
	//setOverlay()
	$("*[title]").tooltip({offset:[-30,0],predelay: 80,delay:0,layout:'<span class="tooltip"><span></span></span>'});
	
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
	// validate newsletter form
	$('#mailchimp_signup form').validate()
	
	// text input fields focus state
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
	// text input fields blur state
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
});


function setOverlay(){
	$("a[rel]").overlay({

		mask: '#333',
		effect: 'apple',
		
		onBeforeLoad: function() {
		var wrap = this.getOverlay().find(".contentWrap");
		var image = new Image();
		image.src = this.getTrigger().attr("href");
		wrap.append (image);
		},

		onClose: function() {
		$('.contentWrap').empty();
		}

	});
};