(function(c){function g(a,e,b){var h=e.hash.slice(1),f=document.getElementById(h)||document.getElementsByName(h)[0];if(f){a&&a.preventDefault();var d=c(b.target);if(!(b.lock&&d.is(":animated")||b.onBefore&&b.onBefore.call(b,a,f,d)===!1)){b.stop&&d.stop(!0);if(b.hash){var a=f.id==h?"id":"name",g=c("<a> </a>").attr(a,h).css({position:"absolute",top:c(window).scrollTop(),left:c(window).scrollLeft()});f[a]="";c("body").prepend(g);location=e.hash;g.remove();f[a]=h}d.scrollTo(f,b).trigger("notify.serialScroll",
[f])}}}var i=location.href.replace(/#.*/,""),d=c.localScroll=function(a){c("body").localScroll(a)};d.defaults={duration:1E3,axis:"y",event:"click",stop:!0,target:window,reset:!0};d.hash=function(a){if(location.hash){a=c.extend({},d.defaults,a);a.hash=!1;if(a.reset){var e=a.duration;delete a.duration;c(a.target).scrollTo(0,a);a.duration=e}g(0,location,a)}};c.fn.localScroll=function(a){function e(){return!!this.href&&!!this.hash&&this.href.replace(this.hash,"")==i&&(!a.filter||c(this).is(a.filter))}
a=c.extend({},d.defaults,a);return a.lazy?this.bind(a.event,function(b){var d=c([b.target,b.target.parentNode]).filter(e)[0];d&&g(b,d,a)}):this.find("a,area").filter(e).bind(a.event,function(b){g(b,this,a)}).end().end()}})(jQuery);
