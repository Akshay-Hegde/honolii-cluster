var mywindow, myheader, workObj, mywork;
mywindow = new WindowManager;
myheader = new HeaderManager('#header',mywindow);
workObj = {
    $filter:$('.work-filter','#content'),
    $thumbs:$('.mod-work-thumb','#content'),
    $details:$('#work')
};
mywork = new WorkManager(workObj,mywindow);

// events
mywindow.$window.on('scroll',myheader, myheader.motion);
workObj.$thumbs.on('click',mywork,mywork.thumbClick);
workObj.$details.find('.close').on('click',mywork,mywork.cardClose);
workObj.$filter.find('li').on('click',mywork,mywork.filter);