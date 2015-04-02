<?php

namespace TMaL;

class Shortcodes {
    public function __construct() {
        add_shortcode( 'tmal', array( $this, 'print_tmal_shortcode' ) );
    }

    public function print_tmal_shortcode() {
        $html = '';

        ob_start();
        ?>
        <form action="/" class="tmal-form" data-tmal-form>
            <div class="tmal-message" data-tmal-message></div>
            <div class="tmal-field active" data-tmal-number>
                <label class="tmal-label" for="tmal-number">Text Me a Link</label>
                <input type="tel" placeholder="Phone" name="tmal-number">
                <div class="tmal-helper">
                    <p>Enter your number to have a link to this page sent to your phone.</p>
                </div>
            </div>
            <div class="tmal-field" data-tmal-verify>
                <label for="tmal-verification-code" class="tmal-label">Verification Code</label>
                <input type="number" pattern="[0-9]*" name="tmal-verification-code">
                <input type="hidden" name="tmal-verify-phone-number">
                <div class="tmal-helper">
                    <p>Enter the four digit verification code sent to your phone.</p>
                </div>
            </div>
            <input type="hidden" name="post_id" value="<?php the_id(); ?>">
            <input type="submit" value="Submit" class="tmal-submit">
            <?php wp_nonce_field( 'tmal-submit-number' ); ?>
        </form>
        <?php

        $html .= ob_get_clean();

        return $html;
    }
}

new Shortcodes();