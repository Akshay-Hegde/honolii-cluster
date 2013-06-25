$(function() {
  var $header,$headerToggle,$headerSubmenu, sectionManagerObj;
 
  // SECTION MANAGER - Home Page Only
  sectionManagerObj = {
      section:$('#content > section'),
      nav:$('#main-nav > .site-nav-1 > .nav li').not('.cta').children('a'),
      resize:false,
      offsetpx:50,
      offsetindex:1
  }
  sectionManager = new SectionManager(sectionManagerObj);
  $('#logo a').click(function(){
      sectionManager.navClick(null,0);
      return false;
  })
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
  
  /*
  $("#instagram").instagram({
      hash: 'love'
    //, clientId: 'YOUR-CLIENT-ID-HERE'
  });
  */
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