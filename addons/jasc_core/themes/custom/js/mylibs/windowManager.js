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
    var $window = event.data.$window;
    event.data.width = $window.width();
    event.data.height = $window.height();
}
WindowManager.prototype.scroll = function(event){// attached to window scroll event
    var $window = event.data.$window;
    event.data.scrollTop = $window.scrollTop();
}