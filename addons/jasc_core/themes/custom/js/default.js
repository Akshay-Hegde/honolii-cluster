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

