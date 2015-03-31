<?php

namespace TMaL;

class Admin {

    /**
     * Options to store the options page variables
     * @var array
     */
    private $options;

    /**
     * Settings inherited from the parent class
     * @var array
     */
    private $settings;

    public function __construct( $settings ) {
        $this->settings = $settings;

        add_action( 'admin_menu', array( $this, 'add_plugin_menu' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
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

    public function enqueue_scripts() {
        wp_enqueue_style( 'tmal-admin', $this->settings['url'] . '/assets/css/tmal-admin.css', array(), $this->settings['version'] );
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
            'account_sid',
            'Twilio Account SID',
            array( $this, 'account_sid_callback' ),
            'tmal-admin',
            'tmal-twilio-settings'
        );

        add_settings_field(
            'auth_key',
            'Twilio Auth Key',
            array( $this, 'auth_key_callback' ),
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

        if ( isset( $input['account_sid'] ) ) {
            $new_input['account_sid'] = $input['account_sid'];
        }

        if ( isset( $input['auth_key'] ) ) {
            $new_input['auth_key'] = $input['auth_key'];
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
     * Print out the Account SID input
     * @return void
     */
    public function account_sid_callback() {
        printf(
            '<input type="text" id="account_sid" name="tmal-settings[account_sid]" value="%s" class="tmal-key-input" />',
            isset( $this->options['account_sid'] ) ? esc_attr( $this->options['account_sid'] ) : ''
        );
    }

    /**
     * Print out the auth key input
     * @return void
     */
    public function auth_key_callback() {
        printf(
            '<input type="text" id="auth_key" name="tmal-settings[auth_key]" value="%s" class="tmal-key-input" />',
            isset( $this->options['auth_key'] ) ? esc_attr( $this->options['auth_key'] ) : ''
        );
    }
}