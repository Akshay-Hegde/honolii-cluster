(function(c){var e=function(a,b){this.init("tooltip",a,b)};e.prototype={constructor:e,init:function(a,b,d){this.type=a;this.$element=c(b);this.options=this.getOptions(d);this.enabled=!0;this.options.trigger!="manual"&&(a=this.options.trigger=="hover"?"mouseenter":"focus",b=this.options.trigger=="hover"?"mouseleave":"blur",this.$element.on(a,this.options.selector,c.proxy(this.enter,this)),this.$element.on(b,this.options.selector,c.proxy(this.leave,this)));this.options.selector?this._options=c.extend({},
this.options,{trigger:"manual",selector:""}):this.fixTitle()},getOptions:function(a){a=c.extend({},c.fn[this.type].defaults,a,this.$element.data());if(a.delay&&typeof a.delay=="number")a.delay={show:a.delay,hide:a.delay};return a},enter:function(a){var b=c(a.currentTarget)[this.type](this._options).data(this.type);!b.options.delay||!b.options.delay.show?b.show():(b.hoverState="in",setTimeout(function(){b.hoverState=="in"&&b.show()},b.options.delay.show))},leave:function(a){var b=c(a.currentTarget)[this.type](this._options).data(this.type);
!b.options.delay||!b.options.delay.hide?b.hide():(b.hoverState="out",setTimeout(function(){b.hoverState=="out"&&b.hide()},b.options.delay.hide))},show:function(){var a,b,d,c,e,f,g;if(this.hasContent()&&this.enabled){a=this.tip();this.setContent();this.options.animation&&a.addClass("fade");f=typeof this.options.placement=="function"?this.options.placement.call(this,a[0],this.$element[0]):this.options.placement;b=/in/.test(f);a.remove().css({top:0,left:0,display:"block"}).appendTo(b?this.$element:document.body);
d=this.getPosition(b);c=a[0].offsetWidth;e=a[0].offsetHeight;switch(b?f.split(" ")[1]:f){case "bottom":g={top:d.top+d.height,left:d.left+d.width/2-c/2};break;case "top":g={top:d.top-e,left:d.left+d.width/2-c/2};break;case "left":g={top:d.top+d.height/2-e/2,left:d.left-c};break;case "right":g={top:d.top+d.height/2-e/2,left:d.left+d.width}}a.css(g).addClass(f).addClass("in")}},setContent:function(){var a=this.tip();a.find(".tooltip-inner").html(this.getTitle());a.removeClass("fade in top bottom left right")},
hide:function(){function a(){var a=setTimeout(function(){b.off(c.support.transition.end).remove()},500);b.one(c.support.transition.end,function(){clearTimeout(a);b.remove()})}var b=this.tip();b.removeClass("in");c.support.transition&&this.$tip.hasClass("fade")?a():b.remove()},fixTitle:function(){var a=this.$element;if(a.attr("title")||typeof a.attr("data-original-title")!="string")a.attr("data-original-title",a.attr("title")||"").removeAttr("title")},hasContent:function(){return this.getTitle()},
getPosition:function(a){return c.extend({},a?{top:0,left:0}:this.$element.offset(),{width:this.$element[0].offsetWidth,height:this.$element[0].offsetHeight})},getTitle:function(){var a;a=this.$element;var b=this.options;a=a.attr("data-original-title")||(typeof b.title=="function"?b.title.call(a[0]):b.title);return a=a.toString().replace(/(^\s*|\s*$)/,"")},tip:function(){return this.$tip=this.$tip||c(this.options.template)},validate:function(){if(!this.$element[0].parentNode)this.hide(),this.options=
this.$element=null},enable:function(){this.enabled=!0},disable:function(){this.enabled=!1},toggleEnabled:function(){this.enabled=!this.enabled},toggle:function(){this[this.tip().hasClass("in")?"hide":"show"]()}};c.fn.tooltip=function(a){return this.each(function(){var b=c(this),d=b.data("tooltip"),h=typeof a=="object"&&a;d||b.data("tooltip",d=new e(this,h));if(typeof a=="string")d[a]()})};c.fn.tooltip.Constructor=e;c.fn.tooltip.defaults={animation:!0,delay:0,selector:!1,placement:"top",trigger:"hover",
title:"",template:'<div class="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>'}})(window.jQuery);
