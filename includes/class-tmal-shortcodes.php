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
        <form action="/" class="tmal-form">
            <label class="tmal-label" for="tmal-number">Text Me a Link</label>
            <input type="tel" placeholder="Phone" name="tmal-number">
            <?php wp_nonce_field( 'tmal-submit-number' ); ?>
            <div class="tmal-helper">
                <p>Enter your number to have a link to this page sent to your phone.</p>
            </div>
        </form>
        <?php

        $html .= ob_get_clean();

        return $html;
    }
}

new Shortcodes();