$(document).ready(function() {

	var w,new_w,h, new_h, num;
	var b_w=150;
	var bb_w=0;
	var bb_w1=0;
	var bb_w2=0;
	var bb_w3=0;
	var bb_w4=0;
	var bb_w5=0;
	var bb_w6=0;
	
	setWidth();
	setHeight();
	w=new_w;h=new_h;
	setSize();
	function setWidth(){
		new_w=$(window).width();
	}
	function setHeight(){
		new_h=$(window).height();
	}
	function setSize(){
		
		if (new_h>780) {
					bg_h=new_h;
				} else {
					bg_h=780
			}
		
		$('.bg1').css({height:bg_h});
		
		/////////////////////

		if (new_h>780) {
					p_top=~~(new_h-780)/2+0;
				} else {
					p_top=0
			}			

		$('#content').stop().animate({paddingTop:p_top})
		
       //////////////////////////////////		
		
		if (new_w>1000) {
					
				} else {
					new_w = 1000;
			}	
		
		bb_w=(new_w-(b_w*6))/12;
		bb_w1=bb_w;
		bb_w2=(3*bb_w)+(1*b_w);
		bb_w3=(5*bb_w)+(2*b_w);
		bb_w4=(7*bb_w)+(3*b_w);
		bb_w5=(9*bb_w)+(4*b_w);
		bb_w6=(11*bb_w)+(5*b_w);
		
		
		
		$('#nav1').css({left:bb_w1});
		$('#nav2').css({left:bb_w2});
		$('#nav3').css({left:bb_w3});
		$('#nav4').css({left:bb_w4});
		$('#nav5').css({left:bb_w5});
		$('#nav6').css({left:bb_w6});
		
		
	}
	//setInterval(setNew,60);
	$(window).resize(setNew);
	
	function setNew(){
		setWidth();
		setHeight();		
		setSize();

		
	}
	
	
	

})
