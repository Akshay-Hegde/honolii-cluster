// default.js
define (['lib/assets','snapsvg','lib/fpsmeter'], function (Assets) {
	"use strict";

	var _ = {};

	// init function
	_.init = function(){
		var timeout,body,scrollEvent,that = this;
		scrollEvent = new Event('scrolled'); // custom scrolled event
		body = document.querySelector('body');

		// set scrollEventVisible elements
		this.scrollToggle = document.getElementsByClassName('scroll-visible');
		
		// scrolling delay event
		this.didScroll = true; //fires off one time to start
		setInterval(function(){
			if(that.didScroll){
				that.didScroll = false;
				body.dispatchEvent(scrollEvent);
			}
		}, 100);
		// scrolling event
		window.onscroll = function(){
	    clearTimeout(timeout);  
	    timeout = setTimeout(function() {
	    	_.didScroll = true;
	    }, 50);
		};
		// scrolled event function
		body.addEventListener('scrolled', function(e){_.windowScroll(e);}, false);
		
		// set images on document ready
		this.setImgSrc();

		// asset manager
		var assetsManager = new Assets();
		assetsManager.queueDownload([
	  	assetPath + '/img/svg/scn-1-waves.svg',
	  	assetPath + '/img/svg/wetumka-logo.svg',
	  	assetPath + '/img/svg/wetumka-logo-stroke.svg',
	  	assetPath + '/img/svg/hamburger-x-path.svg'
	  ]);
	  assetsManager.downloadAll(this.setSceneSVG);
	  // monitor FPS to limit animations - keep it smoothe or not at all
	  this.checkAnimations(body);
	};

	// Set SVG backgrounds and animations
	_.setSceneSVG = function(am){ // am = assetManager
		var wave = {}, logo = {};
			
		// ------------ WAVES -----------------
		
		wave.svgNode = document.createElementNS("http://www.w3.org/2000/svg", "svg");
		wave.wrapperNode = document.getElementById('content');

		wave.svgNode.setAttribute('id','waves');
		wave.wrapperNode.parentNode.insertBefore(wave.svgNode,wave.wrapperNode);

		wave.canvasSVG = new Snap('#waves');
		wave.waveSVG = new Snap(am.getCachedAsset('scn-1-waves.svg').cloneNode(true));

		//SVG drawing area
		wave.canvasSVG.attr({
			viewBox:'0 0 1280 800',
			preserveAspectRatio:'none slice'
		});

		//SVG waves
		wave.waveSVG.attr({
			viewBox:'0 150 1280 800'
		});

		wave.waveSVG_w1 = wave.waveSVG.select('.wave_1 .stroke');
		wave.waveSVG_w2 = wave.waveSVG.select('.wave_2 .stroke');
		wave.waveSVG_w3 = wave.waveSVG.select('.wave_3 .stroke');
		wave.waveSVG_w4 = wave.waveSVG.select('.wave_4 .stroke');
		wave.waveSVG_w5 = wave.waveSVG.select('.wave_5 .stroke');

    wave.waveSVG_w1.attr({fill:"l(0, 0, 0, 1)#0177AA:0-#006798:100"});
    wave.waveSVG_w2.attr({fill:"l(0, 0, 0, 1)#0177AA:10-#006798:100"});
    wave.waveSVG_w3.attr({fill:"l(0, 0, 0, 1)#0286BA:20-#006798:100"});
    wave.waveSVG_w4.attr({fill:"l(0, 0, 0, 1)#0286BA:25-#006798:100"});
    wave.waveSVG_w5.attr({fill:"l(0, 0, 0, 1)#0286BA:30-#006798:100"});

		wave.canvasSVG.append(wave.waveSVG);
		setTimeout(function(){
			document.getElementById('waves').classList.add('active');
		},10);
		
		// ------------ BIG LOGO -----------------
	
		// logo.big = {};
		// logo.big.svgNode = document.createElementNS("http://www.w3.org/2000/svg", "svg");
		// logo.big.wrapperNode = document.getElementById('section_main');
		// logo.big.wrapperNode = logo.big.wrapperNode.querySelector('h1');

		// logo.big.svgNode.setAttribute('id','wetumka_logo');

		// logo.big.svgWrapperNode = document.createElement('div');
		// logo.big.svgWrapperNode.classList.add('wetumka_logo_wrapper');
		// logo.big.svgWrapperNode.setAttribute('id','wetumka_logo_wrapper');

		// logo.big.svgWrapperNode.appendChild(logo.big.svgNode);

		// logo.big.wrapperNode.parentNode.insertBefore(logo.big.svgWrapperNode,logo.big.wrapperNode);

		// logo.big.canvasSVG = new Snap('#wetumka_logo');
		// logo.big.logoSVG = new Snap(am.getCachedAsset('wetumka-logo.svg').cloneNode(true));

		// //SVG drawing area
		// logo.big.canvasSVG.attr({
		// 	preserveAspectRatio:'xMidYMid meet'
		// });

		// //SVG logos
		// logo.big.logoSVG.attr({
		// 	viewBox:'240 800 245 195'
		// });

		// logo.big.logoSVG_1 = logo.big.logoSVG.select('.logo_group');

		// // logo.big.pattern = logo.big.logoSVG.image(assetPath + '/img/watercolorTextureFade_1x.jpg',240,800,1500,570);
		// // logo.big.logoSVG_1.attr({fill:"#fff"});
		// // logo.big.pattern.attr({ mask: logo.big.logoSVG_1});
		
		// logo.big.logoSVG_1.attr({fill:"L(0, 800, 0, 995)#1b7eaf:0-#67cedc:100"});
		// logo.big.canvasSVG.append(logo.big.logoSVG);

		// setTimeout(function(){
		// 	document.getElementById('wetumka_logo_wrapper').classList.add('active');
		// },10);

		// ------------ SMALL HEADER LOGO -----------------
		
		logo.small = {};

		logo.small.svgNode = document.createElementNS("http://www.w3.org/2000/svg", "svg");
		logo.small.wrapperNode = document.getElementById('header');
		logo.small.wrapperNode = logo.small.wrapperNode.querySelector('.site-header-wrapper');

		logo.small.svgNode.setAttribute('id','header-logo');
		logo.small.svgNode.classList.add('site-header-logo');

		logo.small.wrapperNode.parentNode.insertBefore(logo.small.svgNode,logo.small.wrapperNode);

		logo.small.canvasSVG = new Snap('#header-logo');
		logo.small.logoSVG = new Snap(am.getCachedAsset('wetumka-logo-stroke.svg').cloneNode(true));
		logo.small.menuSVG = new Snap(am.getCachedAsset('hamburger-x-path.svg').cloneNode(true));

		//SVG drawing area
		logo.small.canvasSVG.attr({
			preserveAspectRatio:'xMidYMid meet'
		});
		logo.small.menuSVG.attr({
			viewBox:'-100 -100 400 400'
		});

		logo.small.showCircle = logo.small.canvasSVG.circle(50, 50, 45).addClass('aniCircle');

		logo.small.canvasSVG.append(logo.small.logoSVG);
		logo.small.canvasSVG.append(logo.small.menuSVG);

		logo.small.hitCircle = logo.small.canvasSVG.circle(50, 50, 50).attr('fill','transparent');
		logo.small.hitCircle.hover(function(e){logo.small.hoverOver(e);},function(e){logo.small.hoverOut(e);});
		logo.small.hitCircle.click(function(e){logo.small.click(e);});

		setTimeout(function(){
			logo.small.svgNode.classList.add('active');
		},10);

		// ------------ FUNCTIONS -----------------
		
		logo.small.hoverOver = function(event){
			this.svgNode.classList.add('hover');
			this.svgNode.classList.remove('active');
		};

		logo.small.hoverOut = function(event){
			this.svgNode.classList.remove('hover');
			this.svgNode.classList.add('active');
		};

		logo.small.click = function(event){
			var clicked = this.svgNode.classList.contains('selected');
			var body = document.querySelector('body');

			if(clicked){
				this.svgNode.classList.remove('selected');
				body.classList.remove('active-main-nav');
			}else{
				this.svgNode.classList.add('selected');
				body.classList.add('active-main-nav');
			}
		};
	};

	// Animation FPS conditionals
	_.checkAnimations = function(bodyElement){
		var fpsEvent, aniPhase = 0, framesLow = 15, framesHigh = 40, minWidth = 768;
		
		if(window.innerWidth >= minWidth){
			// Register a progress call-back
			document.addEventListener('fps', function(e){fpsEvent(e);}, false);

			// Start FPS analysis, optionnally specifying the rate at which FPS 
			// are evaluated (in seconds, defaults to 1).
			FPSMeter.run(1.5);
		}

		//FPSMeter.stop();
		fpsEvent = function(e){
			switch(aniPhase){
				case 0:
					if(e.fps < framesLow){
						aniPhase = 1;
						bodyElement.classList.add('animation-speed-okay');
						bodyElement.classList.remove('animation-speed-slowest');
						bodyElement.classList.remove('animation-speed-slow');
					} else if(e.fps > framesHigh){
						bodyElement.classList.remove('animation-speed-slowest');
						bodyElement.classList.remove('animation-speed-slow');
						bodyElement.classList.remove('animation-speed-okay');
					}
					break;
				case 1:
					if(e.fps < framesLow){
						aniPhase = 2;
						bodyElement.classList.add('animation-speed-slow');
						bodyElement.classList.remove('animation-speed-slowest');
						bodyElement.classList.remove('animation-speed-okay');
					} else if(e.fps > framesHigh){
						aniPhase = 0;
					}
					break;
				case 2:
					if(e.fps < framesLow){
						bodyElement.classList.add('animation-speed-slowest');
						bodyElement.classList.remove('animation-speed-slow');
						bodyElement.classList.remove('animation-speed-okay');
					} else if(e.fps > framesHigh){
						aniPhase = 1;
					}
					break;
			}
		};
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

		// set pixel density class for background-images - 1x & 2x only for now
		if(window.devicePixelRatio > 1){
			document.querySelector('html').classList.add('px-density-2x');
		}else{
			document.querySelector('html').classList.add('px-density-1x');
		}
	};

	// Toggle element .event-active class when visible on window, hook class = .event-visible
	_.scrollEventVisible = function(elementObj){
		var winTop = window.pageYOffset,
			winBot = winTop + window.innerHeight,
			inView = Math.floor(window.innerHeight / 4),
			eleTop,eleBot,active;

		for (var i = 0; i < elementObj.length; i++) {
			eleTop = elementObj[i].offsetTop + inView;
			eleBot = (eleTop + elementObj[i].offsetHeight) - inView;

			if (winBot > eleTop && winTop < eleBot) {
				elementObj[i].classList.add('event-visible-active');
				elementObj[i].classList.remove('scroll-visible');
			}
		}

		this.scrollToggle = document.getElementsByClassName('scroll-visible');
	};

	// Window scroll function
	_.windowScroll = function(event){
		if(this.scrollToggle.length > 0){ // if length is zero, kill scrollEventVisible call
			this.scrollEventVisible(this.scrollToggle);
		}
	};

	_.init();

	return _;
});