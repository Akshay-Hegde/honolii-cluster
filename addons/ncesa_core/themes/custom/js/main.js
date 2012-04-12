$(document).ready(function(){
	// needed for functions in document head
	try{window.$ready()}catch(err){/*do nothing*/}
	
	if($.cookie('font_size') == 'big_text'){
		textToggle()
	}
	//font toggle click
	$('#font-toggle').click(function(){
		textToggle($.cookie('font_size'),this)
	})
	//custom function: change size
	function textToggle(x,y){
		if(x != 'big_text'){
			$(y).addClass('font_size_big')
			$('.font_size').addClass('font_size_big')
			$.cookie('font_size','big_text',{path: '/'})
		}else{
			$(y).removeClass('font_size_big')
			$('.font_size').removeClass('font_size_big')
			$.cookie('font_size','normal_text',{path: '/'})
			
		}
	}
	//Overlay Int
	$(".overlay-hours").overlay({target: "#office_hours"});
	$(".overlay-directions").overlay({target: "#map_directions"});
});

function initialize_map(){
	var myLatlng = new google.maps.LatLng(33.038810, -117.285632),
		myOptions = {
			zoom: 14,
			center: myLatlng,
			mapTypeId: google.maps.MapTypeId.HYBRID
		},
		map = new google.maps.Map(document.getElementById("map_canvas"), myOptions),
		marker = new google.maps.Marker({
			position: myLatlng, 
			map: map, 
			title:pyro_vars.site_name
		}),
		contentString = '<div id="map_info_window">'+
			'<strong>'+ pyro_vars.site_name +'</strong><br />'+
			'<div id="bodyContent">'+
			'<a class="modal" rel="#map_directions">Get Directions</a><br />'+
			pyro_vars.address_1+' '+pyro_vars.address_1+'<br />'+
			pyro_vars.address_city+' '+pyro_vars.address_state+' '+pyro_vars.address_zip+'<br />'+
			pyro_vars.phone+
			'</div>',
		infowindow = new google.maps.InfoWindow({
			content: contentString
		});

		google.maps.event.addListener(marker, 'click', function() {
			infowindow.open(map,marker);
		});
}