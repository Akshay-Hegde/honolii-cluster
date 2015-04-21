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