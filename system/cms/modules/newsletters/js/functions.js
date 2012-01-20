jQuery(function($) {
	
	if(typeof(CKEDITOR) != 'undefined') {
		CKEDITOR.config.fullPage = true;
	}
	
	$(".colorbox").colorbox({
		height: '100%',
		width: '60%',
		iframe:true
	});
	
	$('.newsletter-send').click(function(e) {
		e.preventDefault();

		var id = $(this).attr('id');
		var msg = $(this).attr('title');
		var batch = $(this).hasClass('batch');
		
		if(confirm(msg))
		{
			send(id, batch);
		}
		
		function send(id, batch){
			$(".newsletter-messages, .sending").slideDown('400');
			$(".newsletter-messages, .sending").show();
				
			$.post(SITE_URL + 'admin/newsletters/send', { id : id, batch : batch },
						function(json){
							var news_data = jQuery.parseJSON(json);
							
							if (news_data != 'Error') {
								$('.newsletter-messages').append('<span class="message"><img src="' + MODULE_LOCATION +
																 'img/success.png" alt="success"/>' +
																 news_data.message + '</span><br />');
									
								if(news_data.status == 'Incomplete')
								{
									send(id);
								}
								else if(news_data.status == 'Finished')
								{
									$('.newsletter-messages .sending').hide();
								}
							}
							else {
								$('.newsletter-messages').append('<span class="message"><img src="' + MODULE_LOCATION +
																 'img/remove.png" alt="success"/> An error occurred. Check your email configuration.</span><br />');
								$('.newsletter-messages .sending').hide();
							}
			});
		}
	});
	
	//get the template they want to edit
	$('.select_edit').change(function(){
		var id = $(this).val();
		var edit = $(this).hasClass('edit');
		if(id > 0)
		{
			$('#template-loading').show();
			$.post(SITE_URL + 'admin/newsletters/templates/get_template', { id : id },
						function(json){
							var data = jQuery.parseJSON(json);

							//only set the title & id value on the template page
							if(edit)
							   {
									$('#title').val(data.title);
									$('input[name="id"]').val(data.id);
							   }
							$('#body').val(data.body);
							$('#template-loading').hide();
						});
			$('#new_label').replaceWith($('#edit_label').clone().show());
		}
	});
	
	//remove this url set
	$('.url-remove').live('click', function(e){
		e.preventDefault();
		$(this).parent().remove();
	});
	
	//add another url set
	$('.url-add').live('click', function(e){
		e.preventDefault();
			
		$(this).parent().after(function(){
			return $(this).next('li').clone().show();
		});
		
		$('li:hidden').children().val('');
		
		$(this).parent().next('li').children().val('');
		
		update_url();
	});
	
	//the function that monitors the input fields
	update_url = function() {
			//delay for the url update
			var typedelay = (function(){
				var timer = 0;
				return function(callback, ms){
					clearTimeout (timer);
					timer = setTimeout(callback, ms);
				}  
			})();
	
			//update 500 ms after they quit typing
			$('input:text.target').keyup(function () {
				var target = this;
				
				$(target).next('input:text.url').val('Generating tracking tag...');
				
				typedelay(function () {
					if($(target).val().length > '')
					{
						$(target).next('input:text.url').val(SITE_URL + 'newsletters/short/' + hash());
					}
					else
					{
						$(target).next('input:text.url').val('');
					}
				}, 2000);
			});
			
			function hash()
			{
				var string = '';
				var chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
			
				for( var i=0; i < 5; i++ )
					string += chars.charAt(Math.floor(Math.random() * chars.length));
			
				return string;
			}
		}
	//watch the target input for change
	$(document).ready(function(){
		update_url();
	});
});