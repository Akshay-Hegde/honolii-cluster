$(function() {
  var $header,$headerToggle,$headerSubmenu, sectionManagerObj;
 
  // SECTION MANAGER - Home Page Only
  sectionManagerObj = {
      section:$('#content > section, div.section-slides'),
      nav:$('#main-nav > .site-nav-1 > .nav li').not('.cta').children('a'),
      resize:false,
      offsetpx:50,
      offsetindex:1
  }
  sectionManager = new SectionManager(sectionManagerObj);
  // SECTION SLIDE
  sectionSliderObj = {
      nav:$('.slide-nav > li','#productsgroups'),
      slides:$('.slide-wrapper > section','#productsgroups')
  }
  sectionSlider = new SectionSlider(sectionSliderObj);
  // TOUR Click Events
  if($('#home').length != 0){
      $('#logo a').click(function(){
          sectionManager.navClick(null,0);
          return false;
      })
      
      setTimeout(function(){
          $('#home h1').addClass('swing');
      },800)
  }
  
  $('#tour1').click(function(){
      sectionManager.navClick(null,1);
      return false;
  });
  $('#tour2').click(function(){
      sectionManager.navClick(null,2);
      return false;
  });
  $('#tour3').click(function(){
      sectionManager.navClick(null,3);
      return false;
  });
  $('#tour4').click(function(){
      sectionManager.navClick(null,4);
      return false;
  });
  $('#tour5').click(function(){
      sectionManager.navClick(null,5);
      return false;
  });
  $('#tour5').click(function(){
      sectionManager.navClick(null,5);
      return false;
  });
  $('#product1').click(function(){
      sectionSlider.slide(1);
      return false;
  });
  $('#product2').click(function(){
      sectionSlider.slide(2);
      return false;
  });
  $('#product3').click(function(){
      sectionSlider.slide(3);
      return false;
  });
  $('#product4').click(function(){
      sectionSlider.slide(4);
      return false;
  });
  $('#product5').click(function(){
      //sectionSlider.slide(5);
      sectionManager.navClick(null,3);
      return false;
  });
  $('#product6').click(function(){
      sectionManager.navClick(null,3);
      return false;
  });
  // FORM - RAQ
  $('#raq form').addClass('form-horizontal').find('input,select,textarea').addClass('input-block-level');
  // GALLERY
  if($('#gallery').length != 0){
    instagramGrid = new InstagramGrid(instagramOBJ.data,'#gallery');
    instagramGrid.resize();
  }
  
  
  // HEADER NAV FUNCTIONS
  $header = $('#header');
  $headerToggle = $('#headerToggle');
  $header.find('li.submenu').append('<i class="icon-bullet-box"></i>');
  $headerSubmenu = $('i.icon-bullet-box','#header');
  $headerToggle.click(function(event){
      console.log('okay')
      var $this;
      $this = $(this);
      if(!$this.hasClass('active')){
          $this.addClass('active');
          $header.addClass('active1');
      }else{
          $this.removeClass('active');
          $header.removeClass('active1').removeClass('active2');
      }
  })
  $headerSubmenu.click(function(event){
      if(!$header.hasClass('active2')){
          $header.addClass('active2');
      }else{
          $header.removeClass('active2');
      }
  })
  // auto deploy nav if over a certain width
  if(windowManager.width >= 1700){
      $headerToggle.trigger('click');
  }
});

