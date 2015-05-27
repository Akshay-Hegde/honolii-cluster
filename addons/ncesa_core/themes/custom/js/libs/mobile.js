$(document).bind("mobileinit", function(){
  $.mobile.ajaxEnabled = false;
});
$(function(){
	$('#menu-button').live('tap',function(event) {
		$("#menu-nav").toggle(); //  toggles the visibility/display of the element.
	});
	$('#service-button').live('tap',function(event) {
		$("#service-nav").toggle(); //  toggles the visibility/display of the element.
	});
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