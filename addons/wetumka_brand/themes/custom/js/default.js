// default.js
define (['lib/assets','snapsvg'], function (Assets) {
	"use strict";

	var _ = {};

	// init function
	_.init = function(){
		var timeout,scrollInt,that = this;
		
		// set scrollEventVisible elements
		this.scrollToggle = document.getElementsByClassName('scroll-visible');
		
		// scrolling delay event
		this.didScroll = true; //fires off one time to start
		scrollInt = setInterval(function(){
			var scrolled = that.windowScroll();
			if(scrolled){
				clearInterval(scrollInt);
			}
		}, 200);
		// scrolling event
		window.onscroll = function(){
	    clearTimeout(timeout);  
	    timeout = setTimeout(function() {
	    	_.didScroll = true;
	    }, 50);
		};
		
		// set images on document ready
		this.setImgSrc();

		// asset manager
		var assetsManager = new Assets();
		assetsManager.queueDownload([
	  	assetPath + '/img/svg/scn-1-waves.svg',
	  	assetPath + '/img/svg/wetumka-logo.svg'
	  ]);
	  assetsManager.downloadAll(this.setSceneSVG);

	};

	// Set SVG backgrounds and animations
	_.setSceneSVG = function(am){ // am = assetManager
		var wave = {}, logo = {};
			
		// ------------ WAVES -----------------
		
		wave.svgNode = document.createElementNS("http://www.w3.org/2000/svg", "svg");
		wave.wrapperNode = document.getElementById('wrapper');

		wave.svgNode.setAttribute('id','waves');
		wave.wrapperNode.parentNode.insertBefore(wave.svgNode,wave.wrapperNode);

		wave.canvasSVG = new Snap('#waves');
		wave.waveSVG = new Snap(am.getCachedAsset('scn-1-waves.svg'));

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
		

		// ------------ LOGO -----------------
		
		logo.svgNode = document.createElementNS("http://www.w3.org/2000/svg", "svg");
		logo.wrapperNode = document.getElementById('section_main');
		logo.wrapperNode = logo.wrapperNode.querySelector('h1')

		logo.svgNode.setAttribute('id','wetumka_logo');

		logo.svgWrapperNode = document.createElement('div');
		logo.svgWrapperNode.classList.add('wetumka_logo_wrapper');
		logo.svgWrapperNode.setAttribute('id','wetumka_logo_wrapper');

		logo.svgWrapperNode.appendChild(logo.svgNode);

		logo.wrapperNode.parentNode.insertBefore(logo.svgWrapperNode,logo.wrapperNode);

		logo.canvasSVG = new Snap('#wetumka_logo');
		logo.logoSVG = new Snap(am.getCachedAsset('wetumka-logo.svg'));

		//SVG drawing area
		logo.canvasSVG.attr({
			//viewBox:'0 0 300 300',
			preserveAspectRatio:'xMidYMid meet'
			//preserveAspectRatio:'none'
		});

		//SVG logos
		logo.logoSVG.attr({
			//viewBox:'480 245 320 200'
			viewBox:'240 800 245 195'
		});

		logo.logoSVG_1 = logo.logoSVG.select('.logo_group');

		// logo.pattern = logo.logoSVG.image(assetPath + '/img/watercolorTextureFade_1x.jpg',240,800,1500,570);
		// logo.logoSVG_1.attr({fill:"#fff"});
		// logo.pattern.attr({ mask: logo.logoSVG_1});
		
		logo.logoSVG_1.attr({fill:"L(0, 800, 0, 995)#1b7eaf:0-#67cedc:100"});
		logo.canvasSVG.append(logo.logoSVG);

		setTimeout(function(){
			document.getElementById('wetumka_logo_wrapper').classList.add('active');
		},10);
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
			inView = Math.floor(window.innerHeight / 2),
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
	_.windowScroll = function(){
		if(this.didScroll){
			this.didScroll = false;
			
			if(this.scrollToggle.length > 0){ // if length is zero, kill setInterval
				this.scrollEventVisible(this.scrollToggle);
				return false;
			}else{
				return true;
			}
		}
	};

	_.init();

	return _;
});