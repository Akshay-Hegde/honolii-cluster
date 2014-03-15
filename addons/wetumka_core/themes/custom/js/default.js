var mywindow, myheader;

mywindow = new WindowManager;
myheader = new HeaderManager('#header',mywindow);

// events
mywindow.$window.on('scroll',myheader, myheader.motion);

// About Section
about = function(setHeight){
	var $aboutSection, x, html, tempImg, images;
	if(setHeight){
		$splash = $('#about > .splash');
		$splash.height($splash.find('h1.impact').outerHeight());
	}else{
		
		$splash = $('#about > .splash');
		$splash.height($splash.find('h1.impact').outerHeight());
		
		images = [];
		html = [];
		
		for(x = 1; x < 9; x++){
			var img = new Image();
			img.src = '../addons/wetumka_core/themes/custom/img/seq/seq-hp-' + x + '.png';
			img.onload = function(){
				images.push(img.src);
				if(images.length == 8){
					$splash.addClass('animate');
				}
			};
			html.push('<div class="seq-set-1 seq-' + x + '" style="background-image:url(' + img.src + ')"></div>');
		}
		
		html.reverse();
		$splash.prepend(html.join(''));
		
	}
};

// call functions
about();

mywindow.$window.on('resize',true,about);