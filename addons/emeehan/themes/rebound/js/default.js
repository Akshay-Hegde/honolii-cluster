/* Default JS */
$(document).ready(function() {
// style form
var docForms = $('form'),
	twtList = $('.twitter_feed','#col-rail');

	// set class to form module buttons
	docForms.find('.contact-button input').addClass('btn');
	
	// set class to twitter feed
	$.each(twtList.find('li'),function(key,value){
		$(value).addClass('item-'+key);
	})
	
	// Run Parallax if page has function
	if($.isFunction($().parallax)){
		//$('.pblock-1','#col-main').parallax("50%", 0, 0.1, true);
		//$('.pblock-2','#col-main').parallax("50%", 0, 0.1, true);
		//$('.pblock-3','#col-main').parallax("50%", 2500, 0.4, true);
		//$('.pblock-4','#col-main').parallax("50%", 2750, 0.3, true);
		//$('.pblock-5','#col-main').parallax("50%", 2750, 0.3, true);
	}
});