/* global TMAL_AJAX */

(function($) {

    var registerEvents = function() {
        $('body').on('submit', '[data-tmal-form]', function(e) {
            e.preventDefault();

            var formData = $(this).serialize(),
                $inputNumber = $(this).find('input[name="tmal-number"]'),
                $inputVerify = $(this).find('input[name="tmal-verification-code"]'),
                $inputVerifyNumber = $(this).find('input[name="tmal-verify-phone-number"]'),
                $message = $(this).find('[data-tmal-message]'),
                $fieldNumber = $(this).find('[data-tmal-number]'),
                $fieldVerify = $(this).find('[data-tmal-verify]');

            // Send the AJAX action along
            formData += '&action=tmal_number_submit';

            // Sync up the number entry with the hidden field
            $inputVerifyNumber.val( $inputNumber.val() );

            // Disable the number input right away
            $inputNumber.prop('disabled', true);

            // Hide the message
            $message.html('').removeClass('active');

            $.post(
                TMAL_AJAX.ajaxUrl,
                formData,
                function( results ) {
                    console.log(results);
                    if ( results.success ) {

                        // Ask for a verification code
                        if ( results.data === 'verify' ) {
                            $fieldVerify.addClass('active');
                            $fieldNumber.removeClass('active');
                        }

                        if ( results.data === 'sent' ) {
                            $fieldVerify.removeClass('active');
                            $fieldNumber.removeClass('active');
                            $message.html('Successfully sent!').addClass('active')
                        }
                    } else {
                        $message.html('There was an error: ' + results.data);
                    }
                }
            );
        });
    }

    if ( $('[data-tmal-form]').length ) {
        registerEvents();
    }
})(jQuery);