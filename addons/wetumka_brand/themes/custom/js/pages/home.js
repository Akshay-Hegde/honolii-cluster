// home.js
define (['default','lib/pubsub','lib/assets','//www.youtube.com/iframe_api'], function (def,PubSub,Assets) {
	"use strict";

	// -------- PubSub Publish Events ---------------
	// event.click.pageCTA - (single) user triggered (click,touch) event, new page load
	// event.click.memberSocial - (multi) user triggered (click,touch) event, new page load - target _blank
	// event.click.partnerLink - (multi) user triggered (click,touch) event, new page load - target _blank

	var _ = {};

	_.init = function(){
		var assetsManager,bodyEle,htmlEle,scrollMe;
		// pubsub subscriptions
		PubSub.subscribe('event.video.ended', function(msg,data){_.videoPlayer.videoEnded(data);});
		PubSub.subscribe('asset.ready.svg.default', function(){_.wavesLoaded();});
		// asset manager
		assetsManager = new Assets();
		assetsManager.queueDownload([
	  	assetPath + '/img/svg/small-fish.svg',
	  	assetPath + '/img/svg/fish-hook.svg'
	  ]);
	  assetsManager.downloadAll(this.setSceneSVG);
	  // dom elements
	  bodyEle = document.body;
    htmlEle = document.documentElement;
    // document height
		this.docHeight = Math.max( bodyEle.scrollHeight, bodyEle.offsetHeight, htmlEle.clientHeight, htmlEle.scrollHeight, htmlEle.offsetHeight );
	  // video player init
	  this.videoPlayer.init();
	  // text
	  _.text.init('animate_text','.animate-text','span', 3000); // wrap characters for animation
	  // -------- Add Slider Button ---------------
	  scrollMe = document.createElement('div');
	  scrollMe.innerHTML = 'Scroll Down';
	  scrollMe.classList.add('scroll-teaser');
	  scrollMe.setAttribute('id','scrollMe');
	  document.getElementById('content').appendChild(scrollMe);
	  scrollMe.addEventListener("animationiteration", _.jumpBack);
		scrollMe.addEventListener("webkitAnimationIteration", _.jumpBack);
		scrollMe.addEventListener("mozAnimationIteration", _.jumpBack);
		scrollMe.addEventListener("MSAnimationIteration", _.jumpBack);
	  // fill window height
	  _.fillWindow(); // make .fit-window the min-height of window
	  window.onresize = function(){_.fillWindow();};
	};

	// scroll me text jump out
	_.jumpBack = function(event){
		//debugger;
		this.classList.remove('active');
	};

	// tour slide functions
	_.tourNext = function(){
		var yPos = window.pageYOffset;
		var winHeight = window.innerHeight;
		var index = Math.ceil(yPos / winHeight);
		if(yPos % winHeight === 0){
			index++;
		}
		if(yPos + winHeight >= _.docHeight){
			def.windowScrollTo(0,800);
		}else{
			def.windowScrollTo((index * winHeight),800);
		}
	};

	// vimeo video
	_.videoPlayer = {
		init : function(){
			var videoFrame = document.getElementById('videoPlayer');
			var iframe = document.createElement('div');
			var button = document.createElement('button');
			// iframe element
			iframe.classList.add('embed-responsive-item');
			iframe.setAttribute('id','youtubeIframe');
			iframe.setAttribute('webkitallowfullscreen','');
			iframe.setAttribute('mozallowfullscreen','');
			iframe.setAttribute('allowfullscreen','');
			// button element
			button.innerHTML = 'Play Video';
			button.classList.add('cta');
			videoFrame.appendChild(button);
			// video object embedded on page
			var videoStateChange = function(event){
				switch(event.data) {
			    case -1:
		        PubSub.publish('event.video.unstarted',{e:'unstarted'});
		        break;
			    case 0:
		        PubSub.publish('event.video.ended',{e:'ended'});
		        break;
		      case 1:
		        PubSub.publish('event.video.playing',{e:'playing'});
		        break;
		      case 2:
		        PubSub.publish('event.video.paused',{e:'paused'});
		        break;
		      case 3:
		        PubSub.publish('event.video.buffering',{e:'buffering'});
		        break;
		      case 5:
		        PubSub.publish('event.video.cue',{e:'video cue'});
		        break;
				}
			};
			
			button.addEventListener('click', function(){
				button.remove();
				videoFrame.appendChild(iframe);
				new YT.Player('youtubeIframe', {
          videoId: videoSrc.videoID,
          playerVars : videoSrc.playerVars,
          events: {
            'onStateChange': videoStateChange
          }
        });
			});
    },
    videoEnded : function(e){
    	var videoWrapper = document.getElementById('section_4').classList.add('active');
    	//var captureHeader = videoWrapper.querySelector('#modCaptureHeader');
    }
	};

	// set svg elements on scene
	_.setSceneSVG = function(am){
		var fish = {}, hooks = {};
		// -------------- FISH ------------------
		fish.wrapper = document.getElementById('section_2');

		for (var i = 3 - 1; i >= 0; i--) {
			fish.tempElement = document.createElement('div');
			fish.tempElement.classList.add('fish');
			fish.tempSVG = am.getCachedAsset('small-fish.svg').cloneNode(true);

			fish.tempElement.appendChild(fish.tempSVG);
			fish.wrapper.insertBefore(fish.tempElement,fish.wrapper.querySelector('.block'));

			fish.delay = def.randomNumber(80) * 100;
			fish.duration = def.randomNumber(15,80) * 100;
			fish.top = def.randomNumber(100,1);

			fish.styleString = '' +
			'-webkit-animation-delay:' + fish.delay + 'ms;'+
			'-moz-animation-delay:' + fish.delay + 'ms;'+
			'animation-delay:' + fish.delay + 'ms' +
			'-webkit-animation-duration:' + fish.duration + 'ms;'+
			'-moz-animation-duration:' + fish.duration + 'ms;'+
			'animation-duration:' + fish.duration + 'ms;'+
			'top:' + fish.top + '%;'+
			'width:' + def.randomNumber(60,80) + 'px;';

			fish.tempElement.setAttribute('style', fish.styleString);

			if(fish.top < 26){
				fish.tempElement.classList.add('style-1');
			}else if(fish.top > 25 && fish.top < 51){
				fish.tempElement.classList.add('style-2');
			}else if(fish.top > 50 && fish.top < 76){
				fish.tempElement.classList.add('style-3');
			}else if(fish.top > 75){
				fish.tempElement.classList.add('style-4');
			}
			// animation iteration event
			fish.tempElement.addEventListener("animationiteration", _.fishReset);
			fish.tempElement.addEventListener("webkitAnimationIteration", _.fishReset);
			fish.tempElement.addEventListener("mozAnimationIteration", _.fishReset);
			fish.tempElement.addEventListener("MSAnimationIteration", _.fishReset);
		}
		// ----------------- HOOKS -----------------
		hooks.wrapper = document.createElement('div');
		hooks.wrapper.classList.add('hooks');
		hooks.wrapper.setAttribute('id','hooks');

		hooks.nodeSibling = document.getElementById('section_2');
		hooks.nodeSibling.parentNode.insertBefore(hooks.wrapper,hooks.nodeSibling);

		for (var j = 4 - 1; j >= 0; j--) {
			hooks.tempElement = document.createElement('div');
			hooks.tempElement.classList.add('hook');
			hooks.svgNode = document.createElementNS("http://www.w3.org/2000/svg", "svg");

			hooks.tempElement.appendChild(hooks.svgNode);
			hooks.wrapper.appendChild(hooks.tempElement);

			hooks.tempSVG = am.getCachedAsset('fish-hook.svg').cloneNode(true);

			hooks.svgNode = new Snap(hooks.svgNode);
			hooks.hookSVG = new Snap(hooks.tempSVG);

			hooks.length = (window.innerHeight / 2) + def.randomNumber(200);

			hooks.hookLine = hooks.svgNode.paper.line(28,0,28,hooks.length - 48);
			hooks.svgNode.attr({
				viewBox:'0 0 50 ' + hooks.length
			});
			hooks.hookLine.attr({
				class:'hook-line'
			});
			hooks.hookSVG.attr({
				preserveAspectRatio: "xMidYMax meet"
			});

			if(j >= 2){
				hooks.hookSVG.attr({
					viewBox: '-235 0 300 300'
				});
				hooks.hookSVG.select('.hook-shape').transform('scale(-1,1) translate(-100,0)');
			}

			hooks.svgNode.append(hooks.hookSVG);
			hooks.delay = def.randomNumber(10) * 100;

			hooks.styleString =
				'-webkit-transition-delay:' + hooks.delay + 'ms;'+
				'-moz-transition-delay:' + hooks.delay + 'ms;'+
				'transition-delay:' + hooks.delay + 'ms';

			hooks.tempElement.setAttribute('style', hooks.styleString);

		}
  	setTimeout(function(){
  		document.body.classList.add('svg-home-active');
      PubSub.publish('asset.ready.svg.home');
    },10);
	};

	// make sections the height of window on load
	_.fillWindow = function(){
		var winHeight = window.innerHeight,
			css = '.fit-window { min-height: ' + winHeight + 'px;}',
			head = document.head || document.getElementsByTagName('head')[0],
			style = document.createElement('style'),
			ele = document.getElementById('fillWindowHeight');

		style.type = 'text/css';
		style.setAttribute('id','fillWindowHeight');

		if(ele){
			ele.remove();
		}

		if (style.styleSheet){
		  style.styleSheet.cssText = css;
		} else {
		  style.appendChild(document.createTextNode(css));
		}

		head.appendChild(style);

	};

	// animate text helper functions
	_.text = {
		// http://stackoverflow.com/questions/5062385/javascript-regular-expression-inserting-span-tag-for-each-character
    init : function(id,selector,element,time){
			var aniText, allTextNodes;

			aniText = document.getElementById(id).querySelector(selector);

			// get all text nodes recursively.
			allTextNodes = this.getTextNodes(aniText);
			// wrap each character in each text node thus gathered.
			allTextNodes.forEach(function(textNode) {
				_.text.wrapEachCharacter(textNode, element);
			});
			// init animation loop
			this.aniLoop(aniText);
			setInterval(this.aniLoop, time, aniText);
    },
    // recursively get all text nodes as an array for a given element
		getTextNodes : function(node) {
			var childTextNodes = [];

			if (!node.hasChildNodes()) {
				return;
			}

			var childNodes = node.childNodes;
			for (var i = 0; i < childNodes.length; i++) {
				if (childNodes[i].nodeType == Node.TEXT_NODE) {
					childTextNodes.push(childNodes[i]);
				}
				else if (childNodes[i].nodeType == Node.ELEMENT_NODE) {
					Array.prototype.push.apply(childTextNodes, _.text.getTextNodes(childNodes[i]));
				}
			}

			return childTextNodes;
		},
		// given a text node, wrap each character in the given tag.
		wrapEachCharacter : function(textNode, tag) {
			var text = textNode.nodeValue;
			var parent = textNode.parentNode;
			var characters = text.split('');
			var elements = [];
			var random = 0;

			characters.forEach(function(character,index) {
				var element = document.createElement(tag);
				var characterNode = document.createTextNode(character);
				var number = index * 100;
				var string = '-webkit-transition-delay:' + number + 'ms;' +
				'-moz-transition-delay:' + number + 'ms;' +
				'transition-delay:' + number + 'ms';

				if (character !== '\n'){
					element.appendChild(characterNode);
					if(character !== ' '){
						element.setAttribute('style', string);
					}else{
						element.classList.add('space');
					}
					if(character === '-'){
						element.classList.add('dash');
					}
					parent.insertBefore(element, textNode);
				}
			});

			parent.removeChild(textNode);
		},
		// loop animations
		aniLoop : function(ele){
			var active = ele.querySelector('.active');
			if(active){
				active.classList.remove('active');
				if(active.nextElementSibling){
					active.nextElementSibling.classList.add('active');
				}else{
					ele.children[0].classList.add('active');
				}
			}else{
				ele.children[0].classList.add('active');
			}
		}
	};

	// fish animate end function
	_.fishReset = function(e){
		var string = e.target.getAttribute('style');
		var top = def.randomNumber(100,1);
		string = string.substring(0,string.indexOf('top'));
		string = string +
		'top:' + top + '%;' +
		'width:' + def.randomNumber(60,80) + 'px;';

		e.target.setAttribute('style', string);

		for (var i = 4; i >= 1; i--) {
			e.target.classList.remove('style-' + i);
		}

		if(top < 26){
			e.target.classList.add('style-1');
		}else if(top > 25 && top < 51){
			e.target.classList.add('style-2');
		}else if(top > 50 && top < 76){
			e.target.classList.add('style-3');
		}else if(top > 75){
			e.target.classList.add('style-4');
		}
	};

	// waves loaded
	_.wavesLoaded = function(){
		var waves = document.getElementById('waves'),
			fishing = document.getElementById('section_2'),
			hooks = document.getElementById('hooks'),
			scrollFunction, timeout;

		waves.classList.add('fit-window');

		// scroll window
	  scrollFunction = function(){
	  	var offset = window.innerHeight - window.pageYOffset,
	  		winBot = window.pageYOffset + window.innerHeight,
	  		fishTop = fishing.offsetTop,
	  		fishBot = fishTop + fishing.offsetHeight;
	  	// conditional - waves section
	  	if(offset > 0){
	  		waves.classList.remove('hide');
	  		waves.setAttribute('style','height:' + offset + 'px;');
	  	}else{
	  		waves.classList.add('hide');
	  	}
      // conditonal - fishing section
      if(winBot >= fishTop + window.innerHeight /2 ){
      	hooks.classList.add('visible');
      }else{
      	hooks.classList.remove('visible');
      }
      if(window.pageYOffset >= fishTop){
      	hooks.classList.add('locked');
      }else{
      	hooks.classList.remove('locked');
      }
      // disable scroll me jumpy jumpy
      if(winBot >= fishBot + window.innerHeight){
      	clearTimeout(timeout);
      }else{
	      clearTimeout(timeout);  
	      timeout = setInterval(function() {
	        document.getElementById('scrollMe').classList.add('active');
	      }, 6000);
	    }
	    // disable animated text when off screen
	    if(window.pageYOffset > window.innerHeight - (window.innerHeight /4)){
	    	document.body.classList.add('disable-headline-animation');
	    }else{
	    	document.body.classList.remove('disable-headline-animation');
	    }
    };

    scrollFunction();
    window.onscroll = scrollFunction;
	};

	_.init();

	//return _;

});