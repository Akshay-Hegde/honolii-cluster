function WindowManager(){this.$window=$(window),this.width=this.$window.width(),this.height=this.$window.height(),this.scrollTop,this.$window.resize(this,this.resize),this.$window.scroll(this,this.scroll)}function HeaderManager(a,b){this.mywindow=b,this.$head=$(a)}!function(a,b){"use strict";var c="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==";a.fn.imagesLoaded=function(d){function e(){var b=a(m),c=a(n);i&&(n.length?i.reject(k,b,c):i.resolve(k)),a.isFunction(d)&&d.call(h,k,b,c)}function f(a){g(a.target,"error"===a.type)}function g(b,d){b.src!==c&&-1===a.inArray(b,l)&&(l.push(b),d?n.push(b):m.push(b),a.data(b,"imagesLoaded",{isBroken:d,src:b.src}),j&&i.notifyWith(a(b),[d,k,a(m),a(n)]),k.length===l.length&&(setTimeout(e),k.unbind(".imagesLoaded",f)))}var h=this,i=a.isFunction(a.Deferred)?a.Deferred():0,j=a.isFunction(i.notify),k=h.find("img").add(h.filter("img")),l=[],m=[],n=[];return a.isPlainObject(d)&&a.each(d,function(a,b){"callback"===a?d=b:i&&i[a](b)}),k.length?k.bind("load.imagesLoaded error.imagesLoaded",f).each(function(d,e){var f=e.src,h=a.data(e,"imagesLoaded");return h&&h.src===f?void g(e,h.isBroken):e.complete&&e.naturalWidth!==b?void g(e,0===e.naturalWidth||0===e.naturalHeight):void((e.readyState||e.complete)&&(e.src=c,e.src=f))}):e(),i?i.promise(h):h}}(jQuery),WindowManager.prototype.resize=function(a){var b=a.data.$window;a.data.width=b.width(),a.data.height=b.height()},WindowManager.prototype.scroll=function(a){var b=a.data.$window;a.data.scrollTop=b.scrollTop()},HeaderManager.prototype.motion=function(a){a.data.isVisible()?a.data.$head.hasClass("fixed")&&0===a.data.mywindow.scrollTop&&a.data.$head.removeClass("fixed"):a.data.$head.addClass("fixed")},HeaderManager.prototype.isVisible=function(){var a=null;return a=this.mywindow.scrollTop>this.$head.outerHeight()?!1:!0};var mywindow,myheader;mywindow=new WindowManager,myheader=new HeaderManager("#header",mywindow),mywindow.$window.on("scroll",myheader,myheader.motion),about=function(a){var b,c,d;if($splash=$("#about > .splash"),a)$splash.height($splash.children(".container").outerHeight());else{for(d=[],c=[],b=1;18>b;b++){var e=new Image;e.src="../addons/wetumka_core/themes/custom/img/seq/seq-hp-top-"+b+".jpg",e.onload=function(){d.push(e.src),17==d.length&&$splash.height($splash.children(".container").outerHeight()).addClass("animate")},c.push('<div class="seq-set-1 seq-'+b+'" style="background-image:url('+e.src+')"></div>')}$splash.prepend(c.join(""))}},about(),mywindow.$window.on("resize",!0,about);