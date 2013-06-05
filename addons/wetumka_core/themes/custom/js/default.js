var mywindow, myheader, workObj, mywork, $carousel;
mywindow = new WindowManager;
myheader = new HeaderManager('#header',mywindow);
workObj = {
    $filter:$('.work-filter','#content'),
    $thumbs:$('.mod-work-thumb','#content'),
    $details:$('#work')
};
mywork = new WorkManager(workObj,mywindow);

// carousel
$carousel = $('#workCarousel');
$carousel.find('ol > li').each(function(index){
    var $this;
    $this = $(this);
    if(index === 0){
        $this.addClass('active');
    }
    $this.attr('data-slide-to',index);
})
$carousel.find('.item:first').addClass('active');
$carousel.imagesLoaded(function(){
    $carousel.fadeIn('fast',function(){
        $carousel.carousel();
    })
})

// events
mywindow.$window.on('scroll',myheader, myheader.motion);
workObj.$thumbs.on('click',mywork,mywork.thumbClick);
workObj.$details.find('.close').on('click',mywork,mywork.cardClose);
workObj.$filter.find('li').on('click',mywork,mywork.filterClick);