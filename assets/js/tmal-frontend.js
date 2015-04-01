/* global TMAL_AJAX */

(function($) {

    var registerEvents = function() {
        $('body').on('submit', '.tmal-form', function(e) {
            e.preventDefault();

            var formData = $(this).serialize();

            // Send the AJAX action along
            formData += '&action=tmal_number_submit';

            $.post(
                TMAL_AJAX.ajaxUrl,
                formData,
                function( results ) {
                    console.log(results);
                    if ( results.success ) {
                        console.log('yay');
                    } else {
                        console.log('nay');
                    }
                }
            );
        });
    }

    if ( $('.tmal-form').length ) {
        registerEvents();
    }
})(jQuery);