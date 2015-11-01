// default.js
define (['lib/assets','lib/pubsub','lib/fpsmeter','lib/modernizr-custom','snapsvg','typekit'], function (Assets, PubSub) {
  "use strict";

  // -------- PubSub Publish Events ---------------
  // event.window.scrollDelay - (multi) a delayed scroll event that fires when scrolling stops
  // event.click.navToggle - (multi) user triggered (click,touch) event, toggles the main top nav
  // event.hover.navToggle - (multi) user triggered (mouse in/out) event, sets hover animation of top logo
  // end.scrollEventVisible - (single) event fires when all scroll-visible elements have been triggered

  var _ = {};

  // init function
  _.init = function(){
    var timeout,
      scrollEvent,
      bodyElement = document.querySelector('body'),
      that = this;

    // trigger typekit
    try{Typekit.load(this.typeKit);}catch(e){}

    // init socialShare
    this.socialShare();

    // setSectionClass
    this.setSectionClass();

    // ---------- PubSub Subscribers --------------
    PubSub.subscribe('event.window.scrollDelay', function(){_.windowScrolling();});
    PubSub.subscribe('asset.svgWaves.loaded', function(){_.checkAnimations(bodyElement);});
    
    // set scrollEventVisible elements
    this.scrollToggle = document.getElementsByClassName('scroll-visible');
    
    // scrolling delay event
    this.didScroll = true; //fires off one time to start
    this.windowTop = window.pageYOffset;

    setInterval(function(){
      if(that.didScroll){
        that.didScroll = false;
        if(that.windowTop === window.pageYOffset){
          PubSub.publish('event.window.scrollDelay',{scrollDir:'stop'});
        }else if(that.windowTop > window.pageYOffset){
          PubSub.publish('event.window.scrollDelay',{scrollDir:'up'});
        }else{
          PubSub.publish('event.window.scrollDelay',{scrollDir:'down'});
        }
        that.windowTop = window.pageYOffset;
      }
    }, 100);
    // scrolling event
    window.onscroll = function(){
      clearTimeout(timeout);  
      timeout = setTimeout(function() {
        _.didScroll = true;
      }, 10);
    };
    
    // set images on document ready
    this.setImgSrc();

    // asset manager
    var assetsManager = new Assets();
    assetsManager.queueDownload([
      assetPath + '/img/svg/scn-1-waves.svg',
      assetPath + '/img/svg/wetumka-logo.svg',
      assetPath + '/img/svg/hamburger-x-path.svg'
    ]);
    assetsManager.downloadAll(this.setSceneSVG);    
  };
  
  // typekit object
  _.typeKit = {
    async: true,
    classes: false,
    active: function(){
      PubSub.publish('asset.typeKit.loaded');
    }
  };

  // social share links in header
  _.socialShare = function(){
    var link, eTitle, social, eURL = encodeURIComponent(window.location.href);
    eTitle = encodeURIComponent(document.querySelector('title').text);
    social = [
      {
        url: 'https://www.facebook.com/sharer/sharer.php?u=' + eURL,
        lid: 'share-facebook'
      },
      {
        url: 'https://twitter.com/intent/tweet?url=' + eURL + '&text=' + eTitle + '&via=edward_wetumka',
        lid: 'share-twitter'
      },
      {
        url: 'https://www.linkedin.com/shareArticle?mini=true&url=' + eURL + '&summary=' + eTitle,
        lid: 'share-linkedin'
      }
    ];
    for (var i = social.length - 1; i >= 0; i--) {
      link = document.getElementById(social[i].lid);
      link.setAttribute('href', social[i].url);
    }
  };

  // Set class for section nav - so it can be hidden
  _.setSectionClass = function(){
    var navMenu = document.getElementById('jump-section-nav');

    if(navMenu !== null){
      navMenu.parentNode.parentNode.classList.add('hideNavList');
    }
  };

  // Set SVG backgrounds and animations
  _.setSceneSVG = function(am){ // am = assetManager
    var wave = {}, logo = {}, bodyElement = document.querySelector('body');
      
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
      bodyElement.classList.add('svg-waves-ready');
      PubSub.publish('asset.svgWaves.loaded');
      //document.getElementById('waves').classList.add('active');
    },10);

    // ------------ BIG LOGO -----------------
  
    logo.big = {};
    logo.big.svgNode = document.createElementNS("http://www.w3.org/2000/svg", "svg");
    logo.big.svgNode.setAttribute('id','wetumka_logo');
    logo.big.wrapperNode = document.getElementById('wetumka_logo_wrapper');
    logo.big.wrapperNode.appendChild(logo.big.svgNode);

    logo.big.canvasSVG = new Snap('#wetumka_logo');
    logo.big.logoSVG = new Snap(am.getCachedAsset('wetumka-logo.svg').cloneNode(true));

    //SVG drawing area
    logo.big.canvasSVG.attr({
      preserveAspectRatio:'xMidYMid meet'
    });

    //SVG logos
    logo.big.logoSVG.attr({
      viewBox:'240 800 245 195'
    });

    logo.big.canvasSVG.append(logo.big.logoSVG);

    setTimeout(function(){
      bodyElement.classList.add('svg-logo-active');
      //document.getElementById('wetumka_logo_wrapper').classList.add('active');
    },10);
    
    // ------------ Main Nav Button -----------------
    
    logo.small = {};

    logo.small.svgNode = document.createElementNS("http://www.w3.org/2000/svg", "svg");
    logo.small.svgNode.setAttribute('id','main-nav-button');
    //logo.small.svgNode.classList.add('site-header-toggle'); //todo: ie bug - svg classList method
    logo.small.svgNode.setAttribute('class','site-header-toggle');

    logo.small.wrapperNode = document.getElementById('header');
    logo.small.wrapperNode = logo.small.wrapperNode.querySelector('.site-header-wrapper');
    logo.small.wrapperNode.parentNode.insertBefore(logo.small.svgNode,logo.small.wrapperNode);

    logo.small.canvasSVG = new Snap('#main-nav-button');
    logo.small.menuSVG = new Snap(am.getCachedAsset('hamburger-x-path.svg').cloneNode(true));

    //SVG drawing area
    logo.small.canvasSVG.attr({
      preserveAspectRatio:'xMidYMid meet'
    });
    logo.small.menuSVG.attr({
      viewBox:'-100 -100 400 400'
    });

    logo.small.showCircle = logo.small.canvasSVG.circle(50, 50, 45).addClass('aniCircle');

    logo.small.canvasSVG.append(logo.small.menuSVG);

    logo.small.hitCircle = logo.small.canvasSVG.circle(50, 50, 50).attr('fill','transparent');
    logo.small.hitCircle.hover(
      function(e){PubSub.publish('event.hover.navToggle', e );},
      function(e){PubSub.publish('event.hover.navToggle', e );}
    );
    logo.small.hitCircle.click(function(e){PubSub.publish('event.click.navToggle', e );});

    setTimeout(function(){
      //document.getElementById('main-nav-button').classList.add('active');
      bodyElement.classList.add('svg-menu-active');
    },10);

    // ------------ FUNCTIONS -----------------
    
    logo.small.hover = function(event){
      if(event.type === 'mouseover'){
        bodyElement.classList.add('svg-menu-hover');
        bodyElement.classList.remove('svg-menu-active');
      }else if(event.type === 'mouseout'){
        bodyElement.classList.remove('svg-menu-hover');
        bodyElement.classList.add('svg-menu-active');
      }
    };

    logo.small.click = function(event){
      var clicked = bodyElement.classList.contains('svg-menu-selected');
      if(clicked){
        bodyElement.classList.remove('svg-menu-selected');
        bodyElement.classList.remove('active-main-nav');
      }else{
        bodyElement.classList.add('svg-menu-selected');
        bodyElement.classList.add('active-main-nav');
      }
    };

    // ------------- PubSub ------------------
    PubSub.subscribe( 'event.click.navToggle', function(msg,event){logo.small.click(event);});
    PubSub.subscribe( 'event.hover.navToggle', function(msg,event){logo.small.hover(event);});
  };

  // Animation FPS conditionals
  _.checkAnimations = function(bodyElement){
    var fpsObj, aniCookie = 'fpsCard';

    /* --------- TEMP USER AGENT SNIFFER -------------- */

    var isWebKit = (navigator.userAgent.indexOf('AppleWebKit') !== -1)? true : false;

    /* ------------------------------------------------ */

    fpsObj = {
      aniPhase : 0,
      framesLow : 20,
      framesHigh : 40,
      sampleCount : 0,
      sampleSize : 15,
      fpsSum : 0,
    };

    fpsObj.avg = function(){ // average FPS based on current samples
      return Math.round(this.fpsSum / this.sampleCount);
    };

    fpsObj.fpsEvent = function(e){ // function bound to fps event
      var avg, noChange = false;
      this.sampleCount = this.sampleCount + 1;
      this.fpsSum = this.fpsSum + e.fps;
      console.log(e.fps, this.avg());
      if(this.sampleCount === 1){ // first sample
        if(e.fps < this.framesLow){ // if fps is low before animations started, then don't start them
          bodyElement.classList.add('animation-speed-stopped');
          FPSMeter.stop();
          if(Modernizr.cookies){
            _.cookies.setItem(aniCookie,'animation-speed-stopped');
          }
        }else{
          bodyElement.classList.add('animation-speed-fast');
        }
      }

      if(this.sampleCount !== this.sampleSize){
        switch(this.aniPhase){
          case 0:
            if(e.fps < this.framesLow){
              this.aniPhase = 1;
            }else{
              noChange = true;
            }
          break;
          case 1:
            if(e.fps < this.framesLow){
              this.aniPhase = 2;
            } else if(e.fps > this.framesLow + 10){
              this.aniPhase = 0;
            } else {
              noChange = true;
            }
          break;
          case 2:
            if(e.fps > this.framesLow + 10){
              this.aniPhase = 1;
            } else {
              noChange = true;
            }
          break;
        }
        if(!noChange){
          this.fpsSpeed(this.aniPhase);
        }
      }else{
        FPSMeter.stop(); // stop after a few samples and set cookie;
        avg = this.avg();
        
        if(Modernizr.cookies){
          if(avg > 34){
            this.fpsSpeed(0);
          }else if(avg > 20 && avg < 35){
            this.fpsSpeed(1);
          }else if(avg > 11 && avg < 21){
            this.fpsSpeed(2);
          }else{
            bodyElement.classList.add('animation-speed-pause');
            _.cookies.setItem(aniCookie,'animation-speed-stopped');
          }
        }
      }
    };

    fpsObj.fpsSpeed = function(speedIndex){
      var classArray = ['animation-speed-fast','animation-speed-med','animation-speed-slow'];
      for (var i = classArray.length; i--;) {
        bodyElement.classList.remove(classArray[i]);
      }
      bodyElement.classList.add(classArray[speedIndex]);
      _.cookies.setItem(aniCookie,classArray[speedIndex]);
    };

    if(!Modernizr.webanimations && !isWebKit){
      bodyElement.classList.add('animation-speed-stopped');
      if(Modernizr.cookies){
        _.cookies.setItem(aniCookie,'animation-speed-stopped');
      }
    }else if(Modernizr.cookies && !_.cookies.hasItem(aniCookie)){ // run fps sample for the first time
      // Register a progress call-back
      document.addEventListener('fps', function(e){fpsObj.fpsEvent(e);}, false);
      FPSMeter.run(2);
    }else if(Modernizr.cookies && _.cookies.hasItem(aniCookie)){
      bodyElement.classList.add(_.cookies.getItem(aniCookie));
    }
  };

  // Set images to use alternate size based on pixel density
  _.setImgSrc = function(){
    
    var images = document.getElementsByTagName('img'),
      pxRatioSize = null,
      parent,
      width;
    
    for (var i = 0; i < images.length; i++) {
      if(images[i].hasAttribute('data-src')){

        parent = getComputedStyle(images[i].parentElement);
        width = images[i].parentElement.clientWidth;   // width with padding
        width -= parseFloat(parent.paddingLeft) + parseFloat(parent.paddingRight);

        pxRatioSize = width * window.devicePixelRatio || 1; // ie 9 bug - devicePixelRatio support
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

    if(this.scrollToggle.length === 0){
      PubSub.publish('end.scrollEventVisible');
    }
  };

  // Window scroll to location
  _.windowScrollTo = function(yLoc,duration){
    var scrollHeight = window.scrollY,
      scrollStep = Math.PI / ( duration / 15 ),
      cosParameter = Math.round((scrollHeight - yLoc) / 2),
      scrollCount = 0,
      scrollMargin;
    
    requestAnimationFrame(step);

    function step () {
      setTimeout(function() {
        var cos = Math.cos( scrollCount * scrollStep );
        scrollCount = scrollCount + 1;  
        scrollMargin = cosParameter - cosParameter * cos;

        
        if(cosParameter > 0){
          if (cosParameter - 1 > (cosParameter * cos) * -1) {
            requestAnimationFrame(step);
            window.scrollTo( 0, ( scrollHeight - scrollMargin ) );
          }else{
            window.scrollTo( 0, yLoc);
          }
        }else{
          if (cosParameter + 1 < (cosParameter * cos) * -1) {
            requestAnimationFrame(step);
            window.scrollTo( 0, ( scrollHeight - scrollMargin ) );
          }else{
            window.scrollTo( 0, yLoc);
          }
        }
        
      }, 15 );
    }
  };

  // Window scroll function
  _.windowScrolling = function(){
    if(this.scrollToggle.length > 0){ // if length is zero, kill scrollEventVisible call
      this.scrollEventVisible(this.scrollToggle);
    }else{
      PubSub.unsubscribe('end.scrollEventVisible');
    }
  };

  // Browser Feature Roadblock - tell users their browser sucks... nicely
  _.browserSucks = function(){
    window.wBrowserElement = document.getElementById('section_main');
    window.wBrowserElement = window.wBrowserElement.querySelector('.block-inner');
    window.aBrowserElement = document.createElement('a');
    window.dBrowserElement = document.createElement('div');
    window.tBrowserElement = document.createTextNode("Your browser does not support some of the features we use on this site. Sorry for the inconvenience, but hope you return to see us again with a modern browser. ");

    window.dBrowserElement.setAttribute('id','browser-dialog');
    window.dBrowserElement.setAttribute('class','browser-alert-box');
    window.dBrowserElement.setAttribute('title','click to close this dialog');
    window.dBrowserElement.appendChild(window.tBrowserElement);

    window.aBrowserElement.setAttribute('href','http://browsehappy.com/');
    window.tBrowserElement = document.createTextNode("Check out some modern browsers that work better.");
    window.aBrowserElement.appendChild(window.tBrowserElement);
    window.dBrowserElement.appendChild(window.aBrowserElement);

    wBrowserElement.insertBefore(window.dBrowserElement,wBrowserElement.firstChild);

    setTimeout(function(){
      document.getElementById('browser-dialog').onclick = function(){
        this.parentElement.removeChild(this);
        delete window.wBrowserElement;
        delete window.dBrowserElement;
        delete window.tBrowserElement;
        delete window.aBrowserElement;
      };
    },10);
  };

  // random numbers
  _.randomNumber = function(n1,n2){
    if(n1 === undefined) n1 = 100;
    if(n2 === undefined) n2 = 1;
    return Math.floor((Math.random() * n1) + n2);
  };

  // Cookie Methods
  _.cookies = {
    /*\
    |*|
    |*|  A complete cookies reader/writer framework with full unicode support.
    |*|
    |*|  Revision #1 - September 4, 2014
    |*|
    |*|  https://developer.mozilla.org/en-US/docs/Web/API/document.cookie
    |*|  https://developer.mozilla.org/User:fusionchess
    |*|
    |*|  This framework is released under the GNU Public License, version 3 or later.
    |*|  http://www.gnu.org/licenses/gpl-3.0-standalone.html
    |*|
    |*|  Syntaxes:
    |*|
    |*|  * docCookies.setItem(name, value[, end[, path[, domain[, secure]]]])
    |*|  * docCookies.getItem(name)
    |*|  * docCookies.removeItem(name[, path[, domain]])
    |*|  * docCookies.hasItem(name)
    |*|  * docCookies.keys()
    |*|
    \*/
    getItem: function (sKey) {
      if (!sKey) { return null; }
      return decodeURIComponent(document.cookie.replace(new RegExp("(?:(?:^|.*;)\\s*" + encodeURIComponent(sKey).replace(/[\-\.\+\*]/g, "\\$&") + "\\s*\\=\\s*([^;]*).*$)|^.*$"), "$1")) || null;
    },
    setItem: function (sKey, sValue, vEnd, sPath, sDomain, bSecure) {
      if (!sKey || /^(?:expires|max\-age|path|domain|secure)$/i.test(sKey)) { return false; }
      var sExpires = "";
      if (vEnd) {
        switch (vEnd.constructor) {
          case Number:
            sExpires = vEnd === Infinity ? "; expires=Fri, 31 Dec 9999 23:59:59 GMT" : "; max-age=" + vEnd;
            break;
          case String:
            sExpires = "; expires=" + vEnd;
            break;
          case Date:
            sExpires = "; expires=" + vEnd.toUTCString();
            break;
        }
      }
      document.cookie = encodeURIComponent(sKey) + "=" + encodeURIComponent(sValue) + sExpires + (sDomain ? "; domain=" + sDomain : "") + (sPath ? "; path=" + sPath : "") + (bSecure ? "; secure" : "");
      return true;
    },
    removeItem: function (sKey, sPath, sDomain) {
      if (!this.hasItem(sKey)) { return false; }
      document.cookie = encodeURIComponent(sKey) + "=; expires=Thu, 01 Jan 1970 00:00:00 GMT" + (sDomain ? "; domain=" + sDomain : "") + (sPath ? "; path=" + sPath : "");
      return true;
    },
    hasItem: function (sKey) {
      if (!sKey) { return false; }
      return (new RegExp("(?:^|;\\s*)" + encodeURIComponent(sKey).replace(/[\-\.\+\*]/g, "\\$&") + "\\s*\\=")).test(document.cookie);
    },
    keys: function () {
      var aKeys = document.cookie.replace(/((?:^|\s*;)[^\=]+)(?=;|$)|^\s*|\s*(?:\=[^;]*)?(?:\1|$)/g, "").split(/\s*(?:\=[^;]*)?;\s*/);
      for (var nLen = aKeys.length, nIdx = 0; nIdx < nLen; nIdx++) { aKeys[nIdx] = decodeURIComponent(aKeys[nIdx]); }
      return aKeys;
    }
  };

  // Browser feature test
  if(!Modernizr.classlist || !Modernizr.svg || !Modernizr.cookies){
    _.browserSucks();
  }
  _.init();
  

  return _;
});