
$(document).ready(function(){
 
$("h1").hover(function() {
$(this).next("em").stop(true, true).animate({opacity: "show", top: "60"}, "slow");
}, function() {
$(this).next("em").animate({opacity: "hide", top: "100"}, "fast");
});
 
});


