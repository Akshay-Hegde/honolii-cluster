$(function(){$("*[title]").tooltip({offset:[-30,0],predelay:80,delay:0,layout:'<span class="tooltip"><span></span></span>'});var d=$("input[type='text']");if(d.length>0)for(var c=d.length;c--;){var b=$(d[c]);b.val()==""&&b.attr("placeholder")?b.val(b.attr("placeholder")):b.val()!==""&&b.attr("placeholder")&&b.val()!=b.attr("placeholder")?b.parent(".input-wrapper").length>0?b.parent().addClass("active"):b.addClass("active"):b.val()!==""&&!b.attr("placeholder")&&(b.parent(".input-wrapper").length>0?
b.parent().addClass("active"):b.addClass("active"))}$("#mailchimp_signup form").validate();c=$("#column_1 form");c.validate();c.find(".contact-button").addClass("span-14 form-submit");c.find(".contact-button input").addClass("submit");d.focus(function(){var a=$(this);a.parent(".input-wrapper").length>0?a.parent().addClass("active"):a.addClass("active");a.attr("placeholder")==a.val()&&a.val("")});d.blur(function(){var a=$(this);a.val()==""&&a.attr("placeholder")?(a.val(a.attr("placeholder")),a.parent(".input-wrapper").length>
0?a.parent().removeClass("active"):a.removeClass("active")):a.val()==""&&!a.attr("placeholder")&&(a.parent(".input-wrapper").length>0?a.parent().removeClass("active"):a.removeClass("active"))});try{window.$ready()}catch(e){}});function setOverlay(){$("a[rel]").overlay({mask:"#333",effect:"apple",onBeforeLoad:function(){var d=this.getOverlay().find(".contentWrap"),c=new Image;c.src=this.getTrigger().attr("href");d.append(c)},onClose:function(){$(".contentWrap").empty()}})};