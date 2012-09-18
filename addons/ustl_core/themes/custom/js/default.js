/* Default JS */
$(document).ready(function(){
    $("#ajax-contact-form").submit(function(){
        var str = $(this).serialize();
        $.ajax({
            type: "POST",
            url: "contact.php",
            data: str,
            success: function(msg){
                if(msg == 'OK'){ 
                    result = '<div class="notification_ok">Your message has been sent. Thank you!<br> <a href="#" onclick="freset();return false;">send another mail</a></div>';
                    $("#fields").hide();
                }else{
                    result = msg;
                }
                $("#note").html(result);
            } 
        });
        return false;
    });
});

function freset(){
    $("#note").html('');
    document.getElementById('ajax-contact-form').reset();
    $("#fields").show();
}