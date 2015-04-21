/*
 * Purl (A JavaScript URL parser) v2.3.1
 * Developed and maintanined by Mark Perkins, mark@allmarkedup.com
 * Source repository: https://github.com/allmarkedup/jQuery-URL-Parser
 * Licensed under an MIT-style license. See https://github.com/allmarkedup/jQuery-URL-Parser/blob/master/LICENSE for details.
 */

;(function(factory) {
    if (typeof define === 'function' && define.amd) {
        define(factory);
    } else {
        window.purl = factory();
    }
})(function() {

    var tag2attr = {
            a       : 'href',
            img     : 'src',
            form    : 'action',
            base    : 'href',
            script  : 'src',
            iframe  : 'src',
            link    : 'href'
        },

        key = ['source', 'protocol', 'authority', 'userInfo', 'user', 'password', 'host', 'port', 'relative', 'path', 'directory', 'file', 'query', 'fragment'], // keys available to query

        aliases = { 'anchor' : 'fragment' }, // aliases for backwards compatability

        parser = {
            strict : /^(?:([^:\/?#]+):)?(?:\/\/((?:(([^:@]*):?([^:@]*))?@)?([^:\/?#]*)(?::(\d*))?))?((((?:[^?#\/]*\/)*)([^?#]*))(?:\?([^#]*))?(?:#(.*))?)/,  //less intuitive, more accurate to the specs
            loose :  /^(?:(?![^:@]+:[^:@\/]*@)([^:\/?#.]+):)?(?:\/\/)?((?:(([^:@]*):?([^:@]*))?@)?([^:\/?#]*)(?::(\d*))?)(((\/(?:[^?#](?![^?#\/]*\.[^?#\/.]+(?:[?#]|$)))*\/?)?([^?#\/]*))(?:\?([^#]*))?(?:#(.*))?)/ // more intuitive, fails on relative paths and deviates from specs
        },

        isint = /^[0-9]+$/;

    function parseUri( url, strictMode ) {
        var str = decodeURI( url ),
        res   = parser[ strictMode || false ? 'strict' : 'loose' ].exec( str ),
        uri = { attr : {}, param : {}, seg : {} },
        i   = 14;

        while ( i-- ) {
            uri.attr[ key[i] ] = res[i] || '';
        }

        // build query and fragment parameters
        uri.param['query'] = parseString(uri.attr['query']);
        uri.param['fragment'] = parseString(uri.attr['fragment']);

        // split path and fragement into segments
        uri.seg['path'] = uri.attr.path.replace(/^\/+|\/+$/g,'').split('/');
        uri.seg['fragment'] = uri.attr.fragment.replace(/^\/+|\/+$/g,'').split('/');

        // compile a 'base' domain attribute
        uri.attr['base'] = uri.attr.host ? (uri.attr.protocol ?  uri.attr.protocol+'://'+uri.attr.host : uri.attr.host) + (uri.attr.port ? ':'+uri.attr.port : '') : '';

        return uri;
    }

    function getAttrName( elm ) {
        var tn = elm.tagName;
        if ( typeof tn !== 'undefined' ) return tag2attr[tn.toLowerCase()];
        return tn;
    }

    function promote(parent, key) {
        if (parent[key].length === 0) return parent[key] = {};
        var t = {};
        for (var i in parent[key]) t[i] = parent[key][i];
        parent[key] = t;
        return t;
    }

    function parse(parts, parent, key, val) {
        var part = parts.shift();
        if (!part) {
            if (isArray(parent[key])) {
                parent[key].push(val);
            } else if ('object' == typeof parent[key]) {
                parent[key] = val;
            } else if ('undefined' == typeof parent[key]) {
                parent[key] = val;
            } else {
                parent[key] = [parent[key], val];
            }
        } else {
            var obj = parent[key] = parent[key] || [];
            if (']' == part) {
                if (isArray(obj)) {
                    if ('' !== val) obj.push(val);
                } else if ('object' == typeof obj) {
                    obj[keys(obj).length] = val;
                } else {
                    obj = parent[key] = [parent[key], val];
                }
            } else if (~part.indexOf(']')) {
                part = part.substr(0, part.length - 1);
                if (!isint.test(part) && isArray(obj)) obj = promote(parent, key);
                parse(parts, obj, part, val);
                // key
            } else {
                if (!isint.test(part) && isArray(obj)) obj = promote(parent, key);
                parse(parts, obj, part, val);
            }
        }
    }

    function merge(parent, key, val) {
        if (~key.indexOf(']')) {
            var parts = key.split('[');
            parse(parts, parent, 'base', val);
        } else {
            if (!isint.test(key) && isArray(parent.base)) {
                var t = {};
                for (var k in parent.base) t[k] = parent.base[k];
                parent.base = t;
            }
            if (key !== '') {
                set(parent.base, key, val);
            }
        }
        return parent;
    }

    function parseString(str) {
        return reduce(String(str).split(/&|;/), function(ret, pair) {
            try {
                pair = decodeURIComponent(pair.replace(/\+/g, ' '));
            } catch(e) {
                // ignore
            }
            var eql = pair.indexOf('='),
                brace = lastBraceInKey(pair),
                key = pair.substr(0, brace || eql),
                val = pair.substr(brace || eql, pair.length);

            val = val.substr(val.indexOf('=') + 1, val.length);

            if (key === '') {
                key = pair;
                val = '';
            }

            return merge(ret, key, val);
        }, { base: {} }).base;
    }

    function set(obj, key, val) {
        var v = obj[key];
        if (typeof v === 'undefined') {
            obj[key] = val;
        } else if (isArray(v)) {
            v.push(val);
        } else {
            obj[key] = [v, val];
        }
    }

    function lastBraceInKey(str) {
        var len = str.length,
            brace,
            c;
        for (var i = 0; i < len; ++i) {
            c = str[i];
            if (']' == c) brace = false;
            if ('[' == c) brace = true;
            if ('=' == c && !brace) return i;
        }
    }

    function reduce(obj, accumulator){
        var i = 0,
            l = obj.length >> 0,
            curr = arguments[2];
        while (i < l) {
            if (i in obj) curr = accumulator.call(undefined, curr, obj[i], i, obj);
            ++i;
        }
        return curr;
    }

    function isArray(vArg) {
        return Object.prototype.toString.call(vArg) === "[object Array]";
    }

    function keys(obj) {
        var key_array = [];
        for ( var prop in obj ) {
            if ( obj.hasOwnProperty(prop) ) key_array.push(prop);
        }
        return key_array;
    }

    function purl( url, strictMode ) {
        if ( arguments.length === 1 && url === true ) {
            strictMode = true;
            url = undefined;
        }
        strictMode = strictMode || false;
        url = url || window.location.toString();

        return {

            data : parseUri(url, strictMode),

            // get various attributes from the URI
            attr : function( attr ) {
                attr = aliases[attr] || attr;
                return typeof attr !== 'undefined' ? this.data.attr[attr] : this.data.attr;
            },

            // return query string parameters
            param : function( param ) {
                return typeof param !== 'undefined' ? this.data.param.query[param] : this.data.param.query;
            },

            // return fragment parameters
            fparam : function( param ) {
                return typeof param !== 'undefined' ? this.data.param.fragment[param] : this.data.param.fragment;
            },

            // return path segments
            segment : function( seg ) {
                if ( typeof seg === 'undefined' ) {
                    return this.data.seg.path;
                } else {
                    seg = seg < 0 ? this.data.seg.path.length + seg : seg - 1; // negative segments count from the end
                    return this.data.seg.path[seg];
                }
            },

            // return fragment segments
            fsegment : function( seg ) {
                if ( typeof seg === 'undefined' ) {
                    return this.data.seg.fragment;
                } else {
                    seg = seg < 0 ? this.data.seg.fragment.length + seg : seg - 1; // negative segments count from the end
                    return this.data.seg.fragment[seg];
                }
            }

        };

    }
    
    purl.jQuery = function($){
        if ($ != null) {
            $.fn.url = function( strictMode ) {
                var url = '';
                if ( this.length ) {
                    url = $(this).attr( getAttrName(this[0]) ) || '';
                }
                return purl( url, strictMode );
            };

            $.url = purl;
        }
    };

    purl.jQuery(window.jQuery);

    return purl;

});
/*!
 * routie - a tiny hash router
 * v0.3.2
 * http://projects.jga.me/routie
 * copyright Greg Allen 2013
 * MIT License
*/
(function(w) {

  var routes = [];
  var map = {};
  var reference = "routie";
  var oldReference = w[reference];

  var Route = function(path, name) {
    this.name = name;
    this.path = path;
    this.keys = [];
    this.fns = [];
    this.params = {};
    this.regex = pathToRegexp(this.path, this.keys, false, false);

  };

  Route.prototype.addHandler = function(fn) {
    this.fns.push(fn);
  };

  Route.prototype.removeHandler = function(fn) {
    for (var i = 0, c = this.fns.length; i < c; i++) {
      var f = this.fns[i];
      if (fn == f) {
        this.fns.splice(i, 1);
        return;
      }
    }
  };

  Route.prototype.run = function(params) {
    for (var i = 0, c = this.fns.length; i < c; i++) {
      this.fns[i].apply(this, params);
    }
  };

  Route.prototype.match = function(path, params){
    var m = this.regex.exec(path);

    if (!m) return false;


    for (var i = 1, len = m.length; i < len; ++i) {
      var key = this.keys[i - 1];

      var val = ('string' == typeof m[i]) ? decodeURIComponent(m[i]) : m[i];

      if (key) {
        this.params[key.name] = val;
      }
      params.push(val);
    }

    return true;
  };

  Route.prototype.toURL = function(params) {
    var path = this.path;
    for (var param in params) {
      path = path.replace('/:'+param, '/'+params[param]);
    }
    path = path.replace(/\/:.*\?/g, '/').replace(/\?/g, '');
    if (path.indexOf(':') != -1) {
      throw new Error('missing parameters for url: '+path);
    }
    return path;
  };

  var pathToRegexp = function(path, keys, sensitive, strict) {
    if (path instanceof RegExp) return path;
    if (path instanceof Array) path = '(' + path.join('|') + ')';
    path = path
      .concat(strict ? '' : '/?')
      .replace(/\/\(/g, '(?:/')
      .replace(/\+/g, '__plus__')
      .replace(/(\/)?(\.)?:(\w+)(?:(\(.*?\)))?(\?)?/g, function(_, slash, format, key, capture, optional){
        keys.push({ name: key, optional: !! optional });
        slash = slash || '';
        return '' + (optional ? '' : slash) + '(?:' + (optional ? slash : '') + (format || '') + (capture || (format && '([^/.]+?)' || '([^/]+?)')) + ')' + (optional || '');
      })
      .replace(/([\/.])/g, '\\$1')
      .replace(/__plus__/g, '(.+)')
      .replace(/\*/g, '(.*)');
    return new RegExp('^' + path + '$', sensitive ? '' : 'i');
  };

  var addHandler = function(path, fn) {
    var s = path.split(' ');
    var name = (s.length == 2) ? s[0] : null;
    path = (s.length == 2) ? s[1] : s[0];

    if (!map[path]) {
      map[path] = new Route(path, name);
      routes.push(map[path]);
    }
    map[path].addHandler(fn);
  };

  var routie = function(path, fn) {
    if (typeof fn == 'function') {
      addHandler(path, fn);
      routie.reload();
    } else if (typeof path == 'object') {
      for (var p in path) {
        addHandler(p, path[p]);
      }
      routie.reload();
    } else if (typeof fn === 'undefined') {
      routie.navigate(path);
    }
  };

  routie.lookup = function(name, obj) {
    for (var i = 0, c = routes.length; i < c; i++) {
      var route = routes[i];
      if (route.name == name) {
        return route.toURL(obj);
      }
    }
  };

  routie.remove = function(path, fn) {
    var route = map[path];
    if (!route)
      return;
    route.removeHandler(fn);
  };

  routie.removeAll = function() {
    map = {};
    routes = [];
  };

  routie.navigate = function(path, options) {
    options = options || {};
    var silent = options.silent || false;

    if (silent) {
      removeListener();
    }
    setTimeout(function() {
      window.location.hash = path;

      if (silent) {
        setTimeout(function() { 
          addListener();
        }, 1);
      }

    }, 1);
  };

  routie.noConflict = function() {
    w[reference] = oldReference;
    return routie;
  };

  var getHash = function() {
    return window.location.hash.substring(1);
  };

  var checkRoute = function(hash, route) {
    var params = [];
    if (route.match(hash, params)) {
      route.run(params);
      return true;
    }
    return false;
  };

  var hashChanged = routie.reload = function() {
    var hash = getHash();
    for (var i = 0, c = routes.length; i < c; i++) {
      var route = routes[i];
      if (checkRoute(hash, route)) {
        return;
      }
    }
  };

  var addListener = function() {
    if (w.addEventListener) {
      w.addEventListener('hashchange', hashChanged, false);
    } else {
      w.attachEvent('onhashchange', hashChanged);
    }
  };

  var removeListener = function() {
    if (w.removeEventListener) {
      w.removeEventListener('hashchange', hashChanged);
    } else {
      w.detachEvent('onhashchange', hashChanged);
    }
  };
  addListener();

  w[reference] = routie;
   
})(window);

/*
 ---------------------------------------------------------------
  Instagram Grid - ERM - Wetumka Interactive LLC - wetumka.net
 ---------------------------------------------------------------
 Build for JASC Gallery
 
 Requires - jQuery, windowManager
 
 */ 

// define WindowManager Class
function InstagramGrid(dataObj,element){
    this.gridSize = 306; // low resolution instagram image
    this.imgRes = 'low_resolution';
    this.imgArray = [];
    this.gridWidth = null;
    this.gridHeight = null;
    this.gridArray = [];
    this.$element = $(element);
    
    for (var key in dataObj){
        var img,url;
        img = new Image();
        url = dataObj[key].images[this.imgRes].url;
        img.src = url;
        this.imgArray.push(url);
    }

    windowManager.$window.bind('windowResize',this,this.resize);
}
InstagramGrid.prototype.resize = function(event,obj){
    var width, height, total, gridSize, imgArray, gridArray, length; 
        
    if(!event){
        gridSize = this.gridSize;
        gridWidth = this.gridWidth;
        gridHeight = this.gridHeight;
        imgArray = this.imgArray;
    }else{
        gridSize = event.data.gridSize;
        gridWidth = event.data.gridWidth;
        gridHeight = event.data.gridHeight;
        imgArray = event.data.imgArray;
    }
    
    width = Math.ceil(windowManager.width / gridSize);
    height = Math.ceil(windowManager.height / gridSize);
    
    total = width * height;
    length = imgArray.length;
    gridArray = [];
    
    if(width != gridWidth || height != gridHeight){ // only change grid when needed
        if(length > total){
            var arrayLength;
            gridArray = imgArray.slice(0,total);
        }else{
            var x;
            x = total - length;
            for(x > 0; x--;){
                var random = Math.floor(Math.random()*length);
                gridArray.push(imgArray[random]);
            }
            gridArray = imgArray.concat(gridArray);
        }
        // reset obj values
        if(!event){
            this.gridWidth = width;
            this.gridHeight = height;
            this.gridArray = gridArray;
            this.setGrid();
        }else{
            event.data.gridWidth = width;
            event.data.gridHeight = height;
            event.data.gridArray = gridArray;
            event.data.setGrid();
        }
    }
};

InstagramGrid.prototype.setGrid = function(){

    var HTMLstring,x,element,length;
    length = this.gridArray.length;
    HTMLstring = '<div class="grid-mask"><div class="grid" style="height:'+windowManager.height+'">';
    element = this.$element.children('.grid-mask');
    for(x = 0;x < length; x++){
        var clear = (x % (this.gridWidth) === 0)? 'clear' : 'not-clear';
        HTMLstring += '<div class="grid-item '+clear+'" style="background-image:url('+this.gridArray[x]+')"></div>';
    }
    HTMLstring += '</div></div>';
    
    if(element.length){
        element.remove();
    }
    this.$element.prepend(HTMLstring);
};
/*
 ---------------------------------------------------------------
  Window Manager - ERM - Wetumka Interactive LLC - wetumka.net
 ---------------------------------------------------------------
 Adds an object with listeners for different window changes.
 
 Events: windowResize, windowScrollTop
 
 Requires - jQuery
 
 */ 

var windowManager;

$(function() {
    windowManager = new WindowManager();
});
// define WindowManager Class
function WindowManager(){
    this.$window = $(window);
    this.width = this.$window.width();
    this.height = this.$window.height();
    this.scrollTop = this.$window.scrollTop();

    // listener actions on window changes
    this.$window.resize(this, this.resize);
    this.$window.scroll(this, this.scroll);
}
WindowManager.prototype.resize = function(event){// attached to window resize event
    var $window,width,height;
    $window = event.data.$window;
    width = $window.width();
    height = $window.height();
    event.data.width = width;
    event.data.height = height;
    event.data.$window.trigger('windowResize',{width:width,height:height});
};
WindowManager.prototype.scroll = function(event){// attached to window scroll event
    var scrollTop;
    scrollTop = event.data.$window.scrollTop();
    event.data.scrollTop = scrollTop;
    event.data.$window.trigger('windowScrollTop',{scrollTop:scrollTop});
};
/*
 ---------------------------------------------------------------
  Section Manager - ERM - Wetumka Interactive LLC - wetumka.net
 ---------------------------------------------------------------
 Used to control single scoller pages, scoll to navigation.
 
 Requires - jQuery, windowManager.js
 
 Arguments:
    obj{
       section:$(), // section elements
       nav:$(), // nav elements - target child of list item
       resize:false, // use resize method, resize sections to fit window
       offsetpx:50, // offset the nav setActive by a few pixels
       offsetindex:0 // offset index for nav
       time:700 // animation time for window scrolling
    }
 */

// define SectionManager Class - requires WindowManager
function SectionManager(obj){
    var sectionLength;
    
    this.isActive = false;
    this.sectionIndex = 0;
    this.$sections = obj.section;
    this.$nav = obj.nav;
    this.sectionPos = [];
    this.resize = (typeof(obj.resize)==='undefined')? false : obj.resize;
    this.offsetpx = (typeof(obj.offsetpx)==='undefined')? 50 : obj.offsetpx;
    this.offsetindex = (typeof(obj.offsetindex)==='undefined')? 0 : obj.offsetindex;
    this.time = (typeof(obj.time)==='undefined')? 700 : obj.time;
    
    if(this.resize){ // disable resize method by default
        windowManager.$window.bind('windowResize',this,this.resize);
    }
    windowManager.$window.bind('windowScrollTop',this,this.setActive);
    //this.$nav.bind('click',this,this.navClick);
    
    //find positions of sections
    sectionLength = this.$sections.length;
    for(var x=0;x<sectionLength;x++){
        this.sectionPos[x] = this.$sections.eq(x).position().top;
    }
    
}
SectionManager.prototype.resize = function(event,obj){// attached to window resize event, called in init
    event.data.$sections.each(function(index){
        var $this, sectionResize;
        $this = $(this);
        sectionResize = $this.attr('data-section-resize'); // use data attribute to change height
        if(!isNaN(sectionResize)){
            $this.css('height', obj.height + parseInt(sectionResize));
        }else{
            $this.css('height', obj.height);
        }
    });
};
SectionManager.prototype.setActive = function(event,obj){ // sets the active item in panel nav while scrolling
    var sectionIndex,sectionLength,x;
    x=0;
    sectionLength = event.data.$sections.length;
    while(isNaN(sectionIndex)){
        if(x === sectionLength || obj.scrollTop + event.data.offsetpx < event.data.sectionPos[x]){
            sectionIndex = x-1;
        }
        x++;
    }
    // check if panel has changed
    if(sectionIndex !== event.data.sectionIndex){
        var hash = event.data.$sections.eq(sectionIndex).data('hash');
        event.data.$nav.parent('li').removeClass('active');
        event.data.sectionIndex = sectionIndex;
        routie.navigate(hash,{'silent':true});

        //window.location.hash = event.data.$sections.eq(sectionIndex).attr('id');
        if(event.data.offsetindex !== 0){
            if(sectionIndex >= event.data.offsetindex){
                event.data.$nav.eq(sectionIndex-event.data.offsetindex).parent('li').addClass('active');
            }
        }else{
            event.data.$nav.eq(sectionIndex).parent('li').addClass('active');
        }
    }
    if(!event.data.isActive){
        event.data.isActive = true;
    }
};
SectionManager.prototype.navClick = function(event,index){ // click event for panel nav
    var clickIndex , offsetTop, offsetIndex;

    if(event){
        //event.preventDefault();
        offsetIndex = event.data.offsetindex;
        clickIndex = event.data.$nav.index($(event.currentTarget)) + offsetIndex;
    }else if(typeof(index)!=="undefined"){
        clickIndex = index;
    }
    offsetTop = sectionManager.$sections.eq(clickIndex).position().top;
    // animate to panel
    $('html, body').stop().animate({
        scrollTop : offsetTop
    }, sectionManager.time);
    
};

// define SectionManager Class - requires WindowManager
function SectionSlider(obj){
    this.$slides = obj.slides;
    this.$slidesNav = obj.nav;
    this.setIndex = 0;
    //bind click events
    this.$slidesNav.click(this,this.slide);
    //set first item to active
    this.$slidesNav.eq(0).addClass('active');
}

SectionSlider.prototype.slide = function(data){
    var index, obj, location;
    if(typeof(data) === 'object'){
        index = data.data.$slidesNav.index($(this));
        obj = data.data;
    }else{
        index = data;
        obj = this;
    }
    // only change if differnt slide
    if(index != obj.setIndex){
        // set the active nav item
        obj.$slidesNav.removeClass('active');
        obj.$slidesNav.eq(index).addClass('active');
        // figure out offset
        location = obj.$slides.eq(index).css('marginLeft');
        location = location.slice(0,location.length - 2);
        // change the slide
        if(location > 0){
            obj.$slides.eq(obj.setIndex).css('marginLeft','-100%');
            obj.$slides.eq(index).css('marginLeft',0);
        }else{
            obj.$slides.eq(obj.setIndex).css('marginLeft','100%');
            obj.$slides.eq(index).css('marginLeft',0);
        }
        
        obj.setIndex = index;
    }
};

$(function() {
  var $header,$headerToggle,$headerSubmenu, sectionManagerObj;
 
  // SECTION MANAGER - Home Page Only
  sectionManagerObj = {
      section:$('#content > section, div.section-slides'),
      nav:$('#main-nav > .site-nav-1 > .nav li').not('.cta').children('a'),
      resize:false,
      offsetpx:50,
      offsetindex:1
  };
  sectionManager = new SectionManager(sectionManagerObj);
  // SECTION SLIDE
  sectionSliderObj = {
      nav:$('.slide-nav > li','#productgroups'),
      slides:$('.slide-wrapper > section','#productgroups')
  };
  sectionSlider = new SectionSlider(sectionSliderObj);
  // TOUR Click Events
  if ($('#home_id').length !== 0) {
    
    setTimeout(function(){
        $('#home_id h1').addClass('bounceInDown');
    },500);

    routie({
      'start': function() {
        sectionManager.navClick(null,0);
        ga('send', 'event', 'tour-click', 'start', 1);
      },
      'aboutus': function() {
        sectionManager.navClick(null,1);
        ga('send', 'event', 'tour-click', 'about', 1);
      },
      'products': function() {
        sectionManager.navClick(null,2);
        sectionSlider.slide(0);
        ga('send', 'event', 'tour-click', 'product', 1);
      },
      'slide/retailsigns': function() {
        sectionManager.navClick(null,2);
        sectionSlider.slide(1);
        ga('send', 'event', 'tour-click', 'product/retail', 1);
      },
      'slide/restaurantbarsigns': function() {
        sectionManager.navClick(null,2);
        sectionSlider.slide(2);
        ga('send', 'event', 'tour-click', 'product/restaurant', 1);
      },
      'slide/hotelsigns': function() {
        sectionManager.navClick(null,2);
        sectionSlider.slide(3);
        ga('send', 'event', 'tour-click', 'product/hotel', 1);
      },
      'slide/realestatesigns': function() {
        sectionManager.navClick(null,2);
        sectionSlider.slide(4);
        ga('send', 'event', 'tour-click', 'product/realestate', 1);
      },
      'services': function() {
        sectionManager.navClick(null,3);
        ga('send', 'event', 'tour-click', 'service', 1);
      },
      'gallery': function() {
        sectionManager.navClick(null,4);
        ga('send', 'event', 'tour-click', 'gallery', 1);
      },
      'contact': function() {
        sectionManager.navClick(null,5);
        ga('send', 'event', 'tour-click', 'contact', 1);
      }
    });
  }

  if ($('#raq form').length !== 0) {
    var $fieldset = $('fieldset');

    $fieldset.find('a.btn').click(function(event){
      var $t = $(this);
      var routeID = $t.attr('href').substring(1);
      routie.navigate(routeID);
    });

    routie({
      'tab1': function() {
        $fieldset.removeClass('active').eq(0).addClass('active');
        ga('send', 'event', 'raq-fieldset', 'contact-information', 1);
      },
      'tab2': function() {
        $fieldset.removeClass('active').eq(1).addClass('active');
        ga('send', 'event', 'raq-fieldset', 'sign-information', 1);
      },
      'tab3': function() {
        $fieldset.removeClass('active').eq(2).addClass('active');
        ga('send', 'event', 'raq-fieldset', 'other-information', 1);
      }
    });
  }
  
  // FORM - RAQ
  $('#raq form').addClass('form-horizontal').find('input,select,textarea').addClass('input-block-level');
  // GALLERY
  if($('#gallery_id').length !== 0){
    instagramGrid = new InstagramGrid(instagramOBJ.data,'#gallery_id');
    instagramGrid.resize();
  }
  // HEADER NAV FUNCTIONS
  $header = $('#header');
  $headerToggle = $('#headerToggle');
  $header.find('li.submenu').append('<i class="icon-bullet-box"></i>');
  $headerSubmenu = $('i.icon-bullet-box','#header');
  $headerToggle.click(function(event){
      var $this;
      $this = $(this);
      if(!$this.hasClass('active')){
          $this.addClass('active');
          $header.addClass('active1');
      }else{
          $this.removeClass('active');
          $header.removeClass('active1').removeClass('active2');
      }
  });
  $headerSubmenu.click(function(event){
      if(!$header.hasClass('active2')){
          $header.addClass('active2');
      }else{
          $header.removeClass('active2');
      }
  });
  // auto deploy nav if over a certain width
  if(windowManager.width >= 1700){
      $headerToggle.trigger('click');
  }
});

