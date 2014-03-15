// define WindowManager Class
function WindowManager(){
    this.$window = $(window);
    this.width = this.$window.width();
    this.height = this.$window.height();
    this.scrollTop;
    // listener actions on window changes
    this.$window.resize(this, this.resize);
    this.$window.scroll(this, this.scroll);
}
WindowManager.prototype.resize = function(event){
    var $window = event.data.$window;
    event.data.width = $window.width();
    event.data.height = $window.height();
};
WindowManager.prototype.scroll = function(event){
    var $window = event.data.$window;
    event.data.scrollTop = $window.scrollTop();
};
// define HeaderManager Class
function HeaderManager(headerEle,windowObj) {
    this.mywindow = windowObj;
    this.$head = $(headerEle);
}
HeaderManager.prototype.motion = function(event){
    if(!event.data.isVisible()){
        event.data.$head.addClass('fixed');
    }else if(event.data.$head.hasClass('fixed') && event.data.mywindow.scrollTop === 0){
        event.data.$head.removeClass('fixed');
    }
};
HeaderManager.prototype.isVisible = function(){
    var visible = null;
    if(this.mywindow.scrollTop > this.$head.outerHeight()){
        visible = false;
    }else{
        visible = true;
    }
    return visible;
};