<?php

if ( ! class_exists('TMaL_Admin') ) :

class TMaL_Admin {

    /**
     * Options to store the options page variables
     * @var array
     */
    private $options;

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_plugin_menu' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add the options page to the menu
     * @return void
     */
    public function add_plugin_menu() {
        add_options_page(
            'Text Me a Link',
            'Text Me a Link',
            'manage_options',
            'tmal-options',
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Output the content for the options page
     * @return void
     */
    public function create_admin_page() {
        // Set class property
        $this->options = get_option( 'tmal-settings' );
        ?>
        <div class="wrap">
            <h2>Text Me a Link</h2>
            <form method="post" action="options.php">
                <?php settings_fields( 'tmal-options' ); ?>
                <?php do_settings_sections( 'tmal-admin' ); ?>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     * @return void
     */
    public function page_init() {
        register_setting(
            'tmal-options',
            'tmal-settings',
            array( $this, 'sanitize' )
        );

        add_settings_section(
            'tmal-twilio-settings',
            'Twilio API Settings',
            array( $this, 'print_section_info' ),
            'tmal-admin'
        );

        add_settings_field(
            'api_key',
            'Twilio API Key',
            array( $this, 'api_key_callback' ),
            'tmal-admin',
            'tmal-twilio-settings'
        );
    }

    /**
     * Sanitize the input
     * @param  array $input Inputs from the field
     * @return array        Sanitized inputs
     */
    public function sanitize( $input ) {
        $new_input = array();

        if ( isset( $input['api_key'] ) ) {
            $new_input['api_key'] = $input['api_key'];
        }

        return $new_input;
    }

    /**
     * Print the section instructions
     * @return void
     */
    public function print_section_info() {
        print 'Enter your Twilio API information below:';
    }

    /**
     * Print out the API key input
     * @return void
     */
    public function api_key_callback() {
        printf(
            '<input type="text" id="api_key" name="tmal-settings[api_key]" value="%s" />',
            isset( $this->options['api_key'] ) ? esc_attr( $this->options['api_key'] ) : ''
        );
    }
}

new TMaL_Admin();

endif;