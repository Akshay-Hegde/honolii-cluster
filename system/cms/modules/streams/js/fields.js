function add_field_parameters()
{
	var data = document.getElementById("field_type").value;
	
	jQuery.ajax({
		dataType: "text",
		type: "POST",
		data: 'data='+data,
		url:  BASE_URL+'index.php/streams_core/ajax/build_parameters',
		success: function(returned_html){
			jQuery('.streams_param_input').remove();
			jQuery('.form_inputs > ul').append(returned_html);
			pyro.chosen();
		}
	});
}

(function($)
{
	$(function() {

		$('#field_name').keyup(function() {
  
 	 		$('#field_slug').val(slugify($('#field_name').val()));
 	   
		});

	});
})(jQuery);