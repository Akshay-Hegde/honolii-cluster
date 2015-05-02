/* Default JS */
$(document).ready(function() {
	var $toggleSidebar = $('#toggleSidebar');
	var $toggleMenu = $('#toggleMenu');
	var $toggleContact = $('#toggleContact');
	var $siteWrapper = $('#wrapper');
	var $sidebar = $('#sidebar');
	var $menu = $('#menu');
	var $contact = $('#contact');

	// Set pixel density class
  if (window.devicePixelRatio > 1) {
    $('body').addClass('px_density_2x');
  } else {
    $('body').addClass('px_density_1x');
  }
  // Toggle top sidebar
	$toggleSidebar.click(
		function(){
			if(!$toggleSidebar.hasClass('active')){
				//Open Toggle
				var height = $sidebar.children().height();
				$toggleSidebar.addClass('active');
				$siteWrapper.css('margin-top',height);
				$sidebar.css('height',height);
			}else{
				//Close Toggle
				$toggleSidebar.removeClass('active');
				$siteWrapper.css('margin-top',0);
				$sidebar.css('height',0);
			}
		}
	);
	// Toggle menu mobile
	$toggleMenu.click(
		function(){
			$toggleMenu.toggleClass('active');
			$menu.find('.mod-menu-hd').toggleClass('active');
		}
	);
	// Toggle contact mobile
	$toggleContact.click(
		function(){
			$toggleContact.toggleClass('active');
			$contact.find('.mod-widget-bd').toggleClass('active');
		}
	);
});