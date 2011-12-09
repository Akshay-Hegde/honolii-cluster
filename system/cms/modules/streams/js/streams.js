jQuery(document).ready(function() {

	jQuery('#field_choice').change(function() {
	
		var field = document.getElementById("field_choice").value;
			
		if( field == 'na' )
		{
			jQuery('#add_field_button').attr("disabled", "disabled");
		}
		else
		{
			jQuery('#add_field_button').removeAttr("disabled");
		}
	
	});

	function add_field()
	{
		var field = document.getElementById("field_choice").value;

		jQuery.ajax({
			dataType: "text",
			type: "POST",
			data: 'data='+data,
			url:  datasource,
			success: function(returned_html){
				$('#field_table').append(returned_html);
			}
		});

	}
	
});

