/* 

Author: PyroCMS Dev Team

*/

// Title toggle
$('a.toggle').click(function() {
   $(this).parent().next('.item').slideToggle(500);
});

// Draggable / Droppable
$("#sortable").sortable({ 
	placeholder : 'dropzone',
    handle : '.draggable', 
    update : function () { 
      var order = $('#sortable').sortable('serialize'); 
    } 
}); 
	
// Drop Menu
$(".topbar ul ul").css({display: "none"});

$(".topbar ul li").click(function(){
    $(this).find('ul:first').slideToggle(400);
	return false;
});

$(document).click(function(e){
	if($('.topbar ul ul').is(':visible') && !$(e.target).is('.topbar ul li')){
		$('.topbar ul ul').slideUp(400);
	}
});

// Add class to show is dropdown
$(".topbar ul li:has(ul)").children("a").addClass("menu");

// Pretty Photo
$('#main a:has(img)').addClass('prettyPhoto');
$("a[class^='prettyPhoto']").prettyPhoto();

// Tipsy
$('.tooltip').tipsy({
	gravity: $.fn.tipsy.autoNS,
	fade: true,
	html: true
});

$('.tooltip-s').tipsy({
	gravity: 's',
	fade: true,
	html: true
});

$('.tooltip-e').tipsy({
	gravity: 'e',
	fade: true,
	html: true
});

$('.tooltip-w').tipsy({
	gravity: 'w',
	fade: true,
	html: true
});

// Tabs
$( "#main" ).tabs();

// Chosen
$('select').addClass('chzn');
$(".chzn").chosen();