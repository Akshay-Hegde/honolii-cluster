var sliderOptions={productSlider:{items:".bd",circular:!0,speed:1E3,vertical:!0},splashSlider:{items:".bd",circular:!0,speed:1E3,vertical:!0},smallSlider:{items:".bd",circular:!0,speed:1E3,vertical:!1},autoScroller:{interval:3100,autoplay:!0,autopause:!0}};function imageInfo(b,c,a){this.imageID=b;this.imageWidth=c;this.imageHeight=a;this.modulePath="/files/"}
function imageWrite(b,c){with(this){imageWidth?(modulePath=modulePath+"thumb/"+imageID+"/"+imageWidth,imageHeight&&(modulePath=modulePath+"/"+imageHeight)):modulePath=modulePath+"large/"+imageID;if(c=="image")var a='<img class="image-injected" src="'+modulePath+'" />';else c=="background"&&(a='<div class="image-injected" style="background-image:url('+modulePath+')"></div>');$(b).append(a)}}imageInfo.prototype.imageWrite=imageWrite;
$(document).ready(function(){var b=$("nav > ul > li","#header"),c=$(".post-intro > div","#blog-short"),a=$(".slider","#product-slider"),d=$(".slider","#splash-slider"),f=$(".small-slider","#secondary"),e=$("form.contact-form");try{window.$ready()}catch(g){}b.find("a").append('<span class="arrow"></span>');b.hover(function(){$(this).addClass("hover")},function(){$(this).removeClass("hover")});$("dl.tabs").tabs("ul.tabs-content > li");c.each(function(){var a=$(this);(new imageInfo(a.attr("data-image"),
a.attr("data-imagew"),a.attr("data-imageh"))).imageWrite(a.parents(".post-wrapper").children(".image-loader"),"background")});a.length>0?(a.scrollable(sliderOptions.productSlider).autoscroll(sliderOptions.autoScroller).navigator({navi:".fd"}),d.data("scrollable")):d.length>0?(d.find(".fd li"),d.scrollable(sliderOptions.productSlider).autoscroll(sliderOptions.autoScroller),d.data("scrollable")):f.length>0&&(f.scrollable(sliderOptions.smallSlider).autoscroll(sliderOptions.autoScroller).navigator({navi:".fd"}),
d.data("scrollable"));e.addClass("nice");e.find(":text").addClass("input-text");e.find(".contact-button input").addClass("nice radius large blue button")});