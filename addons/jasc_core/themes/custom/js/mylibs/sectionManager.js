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
