// extend array
/*
if (!Array.prototype.indexOf) {
    Array.prototype.indexOf = function(obj, start) {
         for (var i = (start || 0), j = this.length; i < j; i++) {
             if (this[i] === obj) { return i; }
         }
         return -1;
    }
}
Array.prototype.remove = function(from, to) {
  var rest = this.slice((to || from) + 1 || this.length);
  this.length = from < 0 ? this.length + from : from;
  return this.push.apply(this, rest);
};
*/
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
}
WindowManager.prototype.scroll = function(event){
    var $window = event.data.$window;
    event.data.scrollTop = $window.scrollTop();
}
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
}
HeaderManager.prototype.isVisible = function(){
    var visible = null;
    if(this.mywindow.scrollTop > this.$head.outerHeight()){
        visible = false;
    }else{
        visible = true;
    }
    return visible;
}
// define WorkManager Class
function WorkManager(workObj,windowObj){
    this.mywindow = windowObj;
    this.sorted = workObj.$thumbs;
    this.cardShow = false;
    this.$elements = workObj;
    this.currentCard = null;
    this.filtered = null;
        
}
WorkManager.prototype.filterClick = function(event){
    var $this,sortID,activeID;
    $this = $(event.currentTarget);
    sortID = $this.attr('data-sort');

    if(event.data.filtered === null){
        // new click
        $this.addClass('active');
        event.data.filtered = sortID;
    }else if(event.data.filtered === sortID){
        // un click
        $this.removeClass('active');
        event.data.filtered = null;
    }else{
        // toggle click
        $this.siblings().removeClass('active');
        $this.addClass('active');
        event.data.filtered = sortID;
    }
    event.data.filterControl();
}
WorkManager.prototype.filterControl = function(){
    if(this.filtered === null){
        this.$elements.$thumbs.stop();
        this.$elements.$thumbs.fadeIn('fast');
    }else{
        var $sorted;
        this.$elements.$thumbs.stop();
        $sorted = this.$elements.$thumbs.filter('[data-'+this.filtered+'!="true"]');
        $sorted.fadeOut('fast');
        this.sorted = this.$elements.$thumbs.not('[data-'+this.filtered+'!="true"]');
        this.sorted.fadeIn('slow');
    }
}
WorkManager.prototype.thumbClick = function(event){
    var currentID;
    currentID = event.data.sorted.index(event.currentTarget);
    event.data.currentCard = event.currentTarget;
    event.data.cardControl('show',currentID);
}
WorkManager.prototype.cardClose = function(event){
    event.data.cardControl('close');
}
WorkManager.prototype.cardNext = function(id){
    
}
WorkManager.prototype.cardPrev = function(id){
    
}
WorkManager.prototype.cardControl = function(type,id){
    if(type === 'show'){
        console.log(this)
        this.$elements.$details.css('top',this.mywindow.height+10)
        .addClass('active');
        $('body').addClass('active');
        setTimeout(
            function(){
                workObj.$details.css('top',0);
            },
            500
        )
    }else if(type === 'close'){
        this.$elements.$details.css('top',this.mywindow.height+10);
        setTimeout(
            function(){
                $('body').removeClass('active');
                workObj.$details.removeClass('active');
            },
            500
        )
    }
}