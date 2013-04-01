jQuery(document).ready(function($) {
	
	// Select nav for smaller resolutions
	// Select menu for smaller screens
	$("<select />").appendTo("nav#primary");

	// Create default option "Menu"
	$("<option />", {
	   "selected": "selected",
	   "value"   : "",
	   "text"    : "Menu"
	}).appendTo("nav#primary select");

	// Populate dropdown with menu items
	$("nav a").each(function() {
	 var el = $(this);
	 $("<option />", {
	     "value"   : el.attr("href"),
	     "text"    : el.text()
	 }).appendTo("nav#primary select");
	});

	$("nav#primary select").change(function() {
	  window.location = $(this).find("option:selected").val();
	});


	/****************** Core Functions ******************/
	/*
	/****************************************************/

	// toggle a site's addon and theme upload settings
	$('.addons-upload input:checkbox').live('click', function(e) {
		e.preventDefault();
		var checkbox = $(this);
		// disable to keep them from double clicking
		$(checkbox).attr('disabled', true);

		var site_ref = $(checkbox).attr('id');
		var state = $(checkbox).is(':checked') ? 1 : 0;

		$.post(SITE_URL + 'sites/settings/toggle_upload', { 'site_ref': site_ref, 'state': state }, function(data) {
			var result = $.parseJSON(data);
				
			if (result.status == 'success') {
				// Success!  Swap colors and words
				$(checkbox).parent().find('span').toggle();
				
				// Allow the checkbox to check (or uncheck)
				$(checkbox).prop('checked', state);
			}
			$(checkbox).removeAttr('disabled');
		});
	})
	
	// toggle a site's available/unavailable status
	$('.active input:checkbox').live('click', function(e) {
		e.preventDefault();
		var checkbox = $(this);
		// disable to keep them from double clicking
		$(checkbox).attr('disabled', true);

		var site_ref = $(checkbox).attr('id');
		var state = $(checkbox).is(':checked') ? 1 : 0;

		$.post(SITE_URL + 'sites/status', { 'site_ref': site_ref, 'state': state }, function(data) {
			var result = $.parseJSON(data);
				
			if (result.status == 'success') {
				// Success!  Swap colors and words
				$(checkbox).parent().find('span').toggle();
				
				// Allow the checkbox to check (or uncheck)
				$(checkbox).prop('checked', state);
			}
			$(checkbox).removeAttr('disabled');
		});
	})


	// Modals
	var current_module = $('#page-header h1 a').text();
	$('a[rel=modal], a.modal').colorbox({
		width: "60%",
		maxHeight: "90%",
		current: current_module + " {current} / {total}"
	});

	$('a[rel="modal-large"], a.modal-large').colorbox({
		width: "90%",
		height: "95%",
		iframe: true,
		scrolling: false,
		current: current_module + " {current} / {total}"
	});


	// Confirmation
	$('.confirm').on('click', function(e){
		e.preventDefault();

		var href		= $(this).attr('href');
		var removemsg	= $(this).attr('title');

		if (confirm(removemsg || DIALOG_MESSAGE))
		{
			//submit
			window.location.replace(href);
		}
	});


	// Addons tabs
	$('.tabs').tabs({
			cookie: { expires: 1 }
	});	
	$('#tabs').tabs({
		// This allows for the Back button to work.
		select: function(event, ui) {
			parent.location.hash = ui.tab.hash;
		},
		load: function(event, ui) {
			confirm_links();
			confirm_buttons();
		}
	});

});