(function($) {
    $(function() {
        $('li.answer').hide();
        
        $('li.question').live('click', function() {
            $('li.answer:visible').slideUp('normal');
            $(this).next('li.answer').slideDown('normal');
        });
        
    });
})(jQuery);