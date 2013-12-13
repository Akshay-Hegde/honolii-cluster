function InstagramGrid(a,c){this.gridSize=306;this.imgRes="low_resolution";this.imgArray=[];this.gridHeight=this.gridWidth=null;this.gridArray=[];this.$element=$(c);for(var b in a){var d,e;d=new Image;e=a[b].images[this.imgRes].url;d.src=e;this.imgArray.push(e)}windowManager.$window.bind("windowResize",this,this.resize)}
InstagramGrid.prototype.resize=function(a){var c,b,d,e,f,g;a?(b=a.data.gridSize,gridWidth=a.data.gridWidth,gridHeight=a.data.gridHeight,e=a.data.imgArray):(b=this.gridSize,gridWidth=this.gridWidth,gridHeight=this.gridHeight,e=this.imgArray);c=Math.ceil(windowManager.width/b);b=Math.ceil(windowManager.height/b);d=c*b;g=e.length;f=[];if(c!=gridWidth||b!=gridHeight){if(g>d)f=e.slice(0,d);else{for(d-=g;d--;){var h=Math.floor(Math.random()*g);f.push(e[h])}f=e.concat(f)}a?(a.data.gridWidth=c,a.data.gridHeight=
b,a.data.gridArray=f,a.data.setGrid()):(this.gridWidth=c,this.gridHeight=b,this.gridArray=f,this.setGrid())}};
InstagramGrid.prototype.setGrid=function(){var a,c,b,d;d=this.gridArray.length;a='<div class="grid-mask"><div class="grid" style="height:'+windowManager.height+'">';b=this.$element.children(".grid-mask");for(c=0;c<d;c++)a+='<div class="grid-item '+(c%this.gridWidth===0?"clear":"not-clear")+'" style="background-image:url('+this.gridArray[c]+')"></div>';a+="</div></div>";b.length&&b.remove();this.$element.prepend(a)};
function SectionManager(a){this.isActive=!1;this.sectionIndex=0;this.$sections=a.section;this.$nav=a.nav;this.sectionPos=[];this.resize=typeof a.resize==="undefined"?!1:a.resize;this.offsetpx=typeof a.offsetpx==="undefined"?50:a.offsetpx;this.offsetindex=typeof a.offsetindex==="undefined"?0:a.offsetindex;this.time=typeof a.time==="undefined"?700:a.time;this.resize&&windowManager.$window.bind("windowResize",this,this.resize);windowManager.$window.bind("windowScrollTop",this,this.setActive);this.$nav.bind("click",
this,this.navClick);for(var a=this.$sections.length,c=0;c<a;c++)this.sectionPos[c]=this.$sections.eq(c).position().top}SectionManager.prototype.resize=function(a,c){a.data.$sections.each(function(){var a,d;a=$(this);d=a.attr("data-section-resize");isNaN(d)?a.css("height",c.height):a.css("height",c.height+parseInt(d))})};
SectionManager.prototype.setActive=function(a,c){var b,d,e;e=0;for(d=a.data.$sections.length;isNaN(b);){if(e===d||c.scrollTop+a.data.offsetpx<a.data.sectionPos[e])b=e-1;e++}if(b!==a.data.sectionIndex)a.data.$nav.parent("li").removeClass("active"),a.data.sectionIndex=b,a.data.offsetindex!==0?b>=a.data.offsetindex&&a.data.$nav.eq(b-a.data.offsetindex).parent("li").addClass("active"):a.data.$nav.eq(b).parent("li").addClass("active");if(!a.data.isActive)a.data.isActive=!0};
SectionManager.prototype.navClick=function(a,c){var b;a?(b=a.data.offsetindex,b=a.data.$nav.index($(a.currentTarget))+b):typeof c!=="undefined"&&(b=c);b=sectionManager.$sections.eq(b).position().top;$("html, body").stop().animate({scrollTop:b},sectionManager.time);return!1};
function SectionSlider(a){this.$slides=a.slides;this.$slidesNav=a.nav;this.setIndex=0;this.$slidesNav.click(this,this.slide);this.$slidesNav.eq(0).addClass("active")}
SectionSlider.prototype.slide=function(a){var b,c;typeof a==="object"?(b=a.data.$slidesNav.index($(this)),a=a.data):(b=a,a=this);if(b!=a.setIndex)a.$slidesNav.removeClass("active"),a.$slidesNav.eq(b).addClass("active"),c=a.$slides.eq(b).css("marginLeft"),c=c.slice(0,c.length-2),c>0?a.$slides.eq(a.setIndex).css("marginLeft","-100%"):a.$slides.eq(a.setIndex).css("marginLeft","100%"),a.$slides.eq(b).css("marginLeft",0),a.setIndex=b};
var windowManager;$(function(){windowManager=new WindowManager});function WindowManager(){this.$window=$(window);this.width=this.$window.width();this.height=this.$window.height();this.scrollTop=this.$window.scrollTop();this.$window.resize(this,this.resize);this.$window.scroll(this,this.scroll)}WindowManager.prototype.resize=function(b){var a,c;a=b.data.$window;c=a.width();a=a.height();b.data.width=c;b.data.height=a;b.data.$window.trigger("windowResize",{width:c,height:a})};
WindowManager.prototype.scroll=function(b){var a;a=b.data.$window.scrollTop();b.data.scrollTop=a;b.data.$window.trigger("windowScrollTop",{scrollTop:a})};
$(function(){var b,a,c;a={section:$("#content > section, div.section-slides"),nav:$("#main-nav > .site-nav-1 > .nav li").not(".cta").children("a"),resize:!1,offsetpx:50,offsetindex:1};sectionManager=new SectionManager(a);sectionSliderObj={nav:$(".slide-nav > li","#productsgroups"),slides:$(".slide-wrapper > section","#productsgroups")};sectionSlider=new SectionSlider(sectionSliderObj);$("#home").length!=0&&($("#logo a").click(function(){sectionManager.navClick(null,0);return!1}),setTimeout(function(){$("#home h1").addClass("bounceInDown")},
500));$("#tour1").click(function(){sectionManager.navClick(null,1);return!1});$("#tour2").click(function(){sectionManager.navClick(null,2);return!1});$("#tour3").click(function(){sectionManager.navClick(null,3);return!1});$("#tour4").click(function(){sectionManager.navClick(null,4);return!1});$("#tour5").click(function(){sectionManager.navClick(null,5);return!1});$("#tour5").click(function(){sectionManager.navClick(null,5);return!1});$("#product1").click(function(){sectionSlider.slide(1);return!1});
$("#product2").click(function(){sectionSlider.slide(2);return!1});$("#product3").click(function(){sectionSlider.slide(3);return!1});$("#product4").click(function(){sectionSlider.slide(4);return!1});$("#product5").click(function(){sectionManager.navClick(null,3);return!1});$("#product6").click(function(){sectionManager.navClick(null,3);return!1});$("#raq form").addClass("form-horizontal").find("input,select,textarea").addClass("input-block-level");$("#gallery").length!=0&&(instagramGrid=new InstagramGrid(instagramOBJ.data,
"#gallery"),instagramGrid.resize());b=$("#header");a=$("#headerToggle");b.find("li.submenu").append('<i class="icon-bullet-box"></i>');c=$("i.icon-bullet-box","#header");a.click(function(){console.log("okay");var a;a=$(this);a.hasClass("active")?(a.removeClass("active"),b.removeClass("active1").removeClass("active2")):(a.addClass("active"),b.addClass("active1"))});c.click(function(){b.hasClass("active2")?b.removeClass("active2"):b.addClass("active2")});windowManager.width>=1700&&a.trigger("click")});