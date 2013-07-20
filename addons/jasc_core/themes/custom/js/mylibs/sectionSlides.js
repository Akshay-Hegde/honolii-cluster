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
}
