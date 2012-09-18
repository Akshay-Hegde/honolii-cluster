fl=0;
$(document).ready(function() {
				
			
	/// follow us
	$('.follow').find('img').css({opacity:0.5})
	
	$('.follow img').hover(function(){
		$(this).stop().animate({opacity:1},400)							 
	}, function(){
		$(this).stop().animate({opacity:0.5},400)	
	})
	
	
		//// menu
	$('#menu > li').find('> span').stop().animate({opacity:0},1)
	
	
	$('#menu > li').hover(function(){
		$(this).find('> span').stop().animate({opacity:1},600)
	}, function(){
		if (!$(this).hasClass('active')) {
			$(this).find('> span').stop().animate({opacity:0},600)	
		}
	})
	
	
	////// submenu
	$('ul#menu').superfish({
      delay:       600,
      animation:   {opacity:'show'},
      speed:       600,
      autoArrows:  false,
      dropShadows: false
    });
	
	
	// video 
	$('.video1').find('span').css({opacity:0})
	
	$('.video1 > a').hover(function(){
		$(this).find('span').stop().animate({opacity:0.5},400)	
		$(this).find('.img_act').stop().animate({opacity:0},400)
	}, function(){
		$(this).find('span').stop().animate({opacity:0},400)
		$(this).find('.img_act').stop().animate({opacity:1},400)
		
	})
	
	//////// read more
	$('.button1 span').css({opacity:'0'})
	
	$('.button1').hover(function(){
		$(this).find('span').stop().animate({opacity:'1'})							 
	}, function(){
		$(this).find('span').stop().animate({opacity:'0'})							 
	})
	
	///////// solutions
	$('.photo1').find('span').css({opacity:0})
	
	$('.photo1 > a').hover(function(){
		$(this).find('span').stop().animate({opacity:0.5},400)								
	}, function(){
		$(this).find('span').stop().animate({opacity:0},400)								
	})
	

	
	
	

	// for lightbox
	 $("a[rel^='prettyPhoto']").prettyPhoto({animation_speed:'normal',theme:'dark_square',social_tools:false,allow_resize: true,default_width: 500,default_height: 344})


}); //// ready




$(window).load(function() {	
						
						
	
	
	// scroll
	$('.scroll').cScroll({
		duration:700,
		step:100,
		trackCl:'track',
		shuttleCl:'shuttle',
		upButtonCl:'_up-butt',
		downButtonCl:'_down-butt'
	})	
	
	
	
	//content switch
	var content=$('#content'),
		nav=$('.menu'),
		footer_nav=$('.footer_menu');
	nav.navs({
		useHash:true,
		hoverIn:function(li){
			$('> span',li).stop().animate({opacity:'1'},600);
		},
		hoverOut:function(li){
			$('> span',li).stop().animate({opacity:'0'},600);
		},
		hover:false
	})		
	footer_nav.navs({
		useHash:true,
		hover:true
	});
	nav.navs(function(n, _){
		content.cont_sw(n);
		footer_nav.navs(n);
	})
	content.cont_sw({
		showFu:function(){
			var _=this					
			$('#content').css({height:533});
				fl=1;
			$.when(_.li).then(function(){	
				$('#logo').stop().animate({height:'0'}, function(){
				_.next.css({display:'block'}).stop().animate({height:533});	
				})
			});

			//$('#content').stop().animate({paddingTop:p_top});
			$('.bg1').stop().animate({opacity:0})
		},
		hideFu:function(){
			var _=this
			//$('.bg1').stop().animate({top:-116})
			$('#content').css({height:0});
			fl=0;
			_.li.stop().animate({height:0}, function(){$(this).css({display:'none'})
			$('#logo').stop().animate({height:'76'});});
		
			//$('#content').stop().animate({paddingTop:p_top})
		},
		preFu:function(){
			var _=this
			_.li.css({position:'absolute', display:'none'});
		}
	})
	
	
	
	
	
}) /// load