(function(c){var e=function(a){this.element=c(a)};e.prototype={constructor:e,show:function(){var a=this.element,f=a.closest("ul:not(.dropdown-menu)"),b=a.attr("data-target"),g,d;b||(b=(b=a.attr("href"))&&b.replace(/.*(?=#[^\s]*$)/,""));a.parent("li").hasClass("active")||(g=f.find(".active:last a")[0],d=c.Event("show",{relatedTarget:g}),a.trigger(d),d.isDefaultPrevented()||(b=c(b),this.activate(a.parent("li"),f),this.activate(b,b.parent(),function(){a.trigger({type:"shown",relatedTarget:g})})))},activate:function(a,
f,b){function g(){d.removeClass("active").find("> .dropdown-menu > .active").removeClass("active");a.addClass("active");e?a.addClass("in"):a.removeClass("fade");a.parent(".dropdown-menu")&&a.closest("li.dropdown").addClass("active");b&&b()}var d=f.find("> .active"),e=b&&c.support.transition&&d.hasClass("fade");e?d.one(c.support.transition.end,g):g();d.removeClass("in")}};c.fn.tab=function(a){return this.each(function(){var f=c(this),b=f.data("tab");b||f.data("tab",b=new e(this));if(typeof a=="string")b[a]()})};
c.fn.tab.Constructor=e;c(document).on("click.tab.data-api",'[data-toggle="tab"], [data-toggle="pill"]',function(a){a.preventDefault();c(this).tab("show")})})(window.jQuery);
