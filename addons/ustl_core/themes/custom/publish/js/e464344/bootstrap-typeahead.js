(function(b){var g=function(a,c){this.$element=b(a);this.options=b.extend({},b.fn.typeahead.defaults,c);this.matcher=this.options.matcher||this.matcher;this.sorter=this.options.sorter||this.sorter;this.highlighter=this.options.highlighter||this.highlighter;this.$menu=b(this.options.menu).appendTo("body");this.source=this.options.source;this.shown=!1;this.listen()};g.prototype={constructor:g,select:function(){this.$element.val(this.$menu.find(".active").attr("data-value"));return this.hide()},show:function(){var a=
b.extend({},this.$element.offset(),{height:this.$element[0].offsetHeight});this.$menu.css({top:a.top+a.height,left:a.left});this.$menu.show();this.shown=!0;return this},hide:function(){this.$menu.hide();this.shown=!1;return this},lookup:function(){var a=this,c;this.query=this.$element.val();if(!this.query)return this.shown?this.hide():this;c=b.grep(this.source,function(b){if(a.matcher(b))return b});c=this.sorter(c);return!c.length?this.shown?this.hide():this:this.render(c.slice(0,this.options.items)).show()},
matcher:function(a){return~a.toLowerCase().indexOf(this.query.toLowerCase())},sorter:function(a){for(var b=[],e=[],d=[],f;f=a.shift();)f.toLowerCase().indexOf(this.query.toLowerCase())?~f.indexOf(this.query)?e.push(f):d.push(f):b.push(f);return b.concat(e,d)},highlighter:function(a){return a.replace(RegExp("("+this.query+")","ig"),function(a,b){return"<strong>"+b+"</strong>"})},render:function(a){var c=this,a=b(a).map(function(a,d){a=b(c.options.item).attr("data-value",d);a.find("a").html(c.highlighter(d));
return a[0]});a.first().addClass("active");this.$menu.html(a);return this},next:function(){var a=this.$menu.find(".active").removeClass("active").next();a.length||(a=b(this.$menu.find("li")[0]));a.addClass("active")},prev:function(){var a=this.$menu.find(".active").removeClass("active").prev();a.length||(a=this.$menu.find("li").last());a.addClass("active")},listen:function(){this.$element.on("blur",b.proxy(this.blur,this)).on("keypress",b.proxy(this.keypress,this)).on("keyup",b.proxy(this.keyup,this));
if(b.browser.webkit||b.browser.msie)this.$element.on("keydown",b.proxy(this.keypress,this));this.$menu.on("click",b.proxy(this.click,this)).on("mouseenter","li",b.proxy(this.mouseenter,this))},keyup:function(a){a.stopPropagation();a.preventDefault();switch(a.keyCode){case 40:case 38:break;case 9:case 13:if(!this.shown)break;this.select();break;case 27:this.hide();break;default:this.lookup()}},keypress:function(a){a.stopPropagation();if(this.shown)switch(a.keyCode){case 9:case 13:case 27:a.preventDefault();
break;case 38:a.preventDefault();this.prev();break;case 40:a.preventDefault(),this.next()}},blur:function(a){var b=this;a.stopPropagation();a.preventDefault();setTimeout(function(){b.hide()},150)},click:function(a){a.stopPropagation();a.preventDefault();this.select()},mouseenter:function(a){this.$menu.find(".active").removeClass("active");b(a.currentTarget).addClass("active")}};b.fn.typeahead=function(a){return this.each(function(){var c=b(this),e=c.data("typeahead"),d=typeof a=="object"&&a;e||c.data("typeahead",
e=new g(this,d));if(typeof a=="string")e[a]()})};b.fn.typeahead.defaults={source:[],items:8,menu:'<ul class="typeahead dropdown-menu"></ul>',item:'<li><a href="#"></a></li>'};b.fn.typeahead.Constructor=g;b(function(){b("body").on("focus.typeahead.data-api",'[data-provide="typeahead"]',function(a){var c=b(this);c.data("typeahead")||(a.preventDefault(),c.typeahead(c.data()))})})})(window.jQuery);
