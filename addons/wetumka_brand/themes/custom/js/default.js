// default.js
define (['lib/assets','snapsvg'], function (Assets) {
	"use strict";

	var _ = {};

	// init function
	_.init = function(){

		var that = this;
		
		// set scrollEventVisible elements
		this.scrollToggle = document.getElementsByClassName('event-visible');
		
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

		// asset manager
		var assetsManager = new Assets();
		assetsManager.queueDownload([
	  	assetPath + '/img/svg/scn-1-waves.svg',
	  	assetPath + '/img/svg/wetumka-logo.svg'
	  ]);
	  assetsManager.downloadAll(this.setSceneSVG);

	};

	// Set SVG backgrounds and animations
	_.setSceneSVG = function(assetsManager){
		var svgObj = {};
		var svgNode = document.createElementNS("http://www.w3.org/2000/svg", "svg"); // svg is not just a node - name space that shit yo!
		var wrapperNode = document.getElementById('wrapper');

		svgNode.setAttribute('id','waves');
		svgNode.classList.add('waves-svg');
		wrapper.parentNode.insertBefore(svgNode,wrapper);

		svgObj.canvasSVG = new Snap('#waves');
		svgObj.waveSVG = new Snap(assetsManager.getCachedAsset('scn-1-waves.svg'));

		svgObj.waveSVG_w1 = svgObj.waveSVG.select('.wave_1 .stroke');
		svgObj.waveSVG_w2 = svgObj.waveSVG.select('.wave_2 .stroke');

    svgObj.waveSVG_w2.attr({fill:"l(.5, 0, .5, 1)#0286BA:10-#006798:90"});
    svgObj.waveSVG_w1.attr({fill:"l(.5, 0, .5, 1)#0286BA-#006798:60"});

		svgObj.canvasSVG.append(svgObj.waveSVG);

		//debugger;
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
	_.scrollEventVisible = function(elementObj){

		var winTop = window.pageYOffset,
			winBot = winTop + window.innerHeight,
			eleTop,eleBot,active;

		for (var i = 0; i < elementObj.length; i++) {
			eleTop = elementObj[i].offsetTop;
			eleBot = eleTop + elementObj[i].offsetHeight;
			active = elementObj[i].classList.contains('event-visible-active');

			if (winBot > eleTop && winTop < eleBot) {
				if (!active){
					elementObj[i].classList.add('event-visible-active');
				}
			}else{
				if (active){
					elementObj[i].classList.remove('event-visible-active');
				}
			}
		}

	};

	// Window scroll function
	_.windowScroll = function(){
		if(this.didScroll){
			this.didScroll = false;
			// list of functions to call on scroll delayed scroll event
			this.scrollEventVisible(this.scrollToggle);
		}
	};

	_.init();

	return _;
});