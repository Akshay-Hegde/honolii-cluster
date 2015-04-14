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