function RepositionNav(){var b=$(window).height(),e=$("#nav").height()/2,b=b/2-e;$("#nav").css({top:b})}
(function(b){b.fn.parallax=function(e,f,g,h){function m(a,e){e.each(function(){var c=b(this),d=c.offset().top,c=h==!0?c.outerHeight(!0):c.height();d+c>=a&&d+c-i<a&&k(a,c);d<=a&&d+c>=a&&d-i<a&&d+c-i>a&&k(a,c);d+c>a&&d-i<a&&d>a&&k(a,c)})}function k(a,b){j.css({backgroundPosition:e+" "+Math.round(-(b+a-f)*g)+"px"})}var l=b(window),i=b(window).height();l.scrollTop();var j=b(this);e==null&&(e="50%");f==null&&(f=0);g==null&&(g=0.1);h==null&&(h=!0);height=j.height();j.css({backgroundPosition:e+" "+Math.round(-(h+
f-g)*NaN)+"px"});l.bind("scroll",function(){var a=l.scrollTop();m(a,j);b("#pixels").html(a)})}})(jQuery);
