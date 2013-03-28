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
WindowManager.prototype.resize = function(obj){
    var $window = obj.data.$window;
    obj.data.width = $window.width();
    obj.data.height = $window.height();
}
WindowManager.prototype.scroll = function(obj){
    var $window = obj.data.$window;
    obj.data.scrollTop = $window.scrollTop();
}
// define HeaderManager Class
function HeaderManager(headerEle,windowObj) {
    this.mywindow = windowObj;
    this.$head = $(headerEle);
}
HeaderManager.prototype.motion = function(obj){
    if(!obj.data.isVisible()){
        obj.data.$head.addClass('fixed');
    }else if(obj.data.$head.hasClass('fixed') && obj.data.mywindow.scrollTop === 0){
        obj.data.$head.removeClass('fixed');
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
        
}
WorkManager.prototype.filter = function(event){
    var $this;
    $this = $(event.currentTarget);
    if($this.hasClass('active')){
        $this.removeClass('active')
    }else{
        $this.addClass('active')
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
WorkManager.prototype.cardNext = function(id){
    
}
WorkManager.prototype.cardPrev = function(id){
    
}