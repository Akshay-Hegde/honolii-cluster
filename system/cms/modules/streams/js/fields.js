function add_field_parameters(datasource)
{
	var data = document.getElementById("field_type").value;
	
	jQuery.ajax({
		dataType: "text",
		type: "POST",
		data: 'data='+data,
		url:  datasource,
		success: function(returned_html){
			jQuery('.streams_param_input').remove();
			jQuery('.form_inputs ul').append(returned_html);
			pyro.chosen();
		}
	});
}

jQuery(document).ready(function() {

	$('#field_name').keyup(function() {
  
 	 	$('#field_slug').val(slugify($('#field_name').val()));
 	   
	});

});
