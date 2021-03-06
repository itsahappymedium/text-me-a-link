<?php
/*
Plugin Name: Text Me a Link
Version: 0.1-alpha
Description: Allows users to easily text themselves a link using the Twilio API.
Author: Happy Medium
Author URI: http://itsahappymedium.com
Plugin URI: http://itsahappymedium.com
Text Domain: tmal
Domain Path: /languages
*/

namespace TMaL;

class TMaL {

    /**
     * Settings from the options page
     * @var array
     */
    private $options;

    /**
     * Plugin settings
     * @var array
     */
    private $settings;

    public function __construct() {
        $this->settings = array(
            'version'       => '0.1-alpha',
            'name'          => __( 'Text Me a Link', 'tmal' ),
            'dir'           => trailingslashit( plugin_dir_path( __FILE__ ) ),
            'url'           => trailingslashit( plugin_dir_url( __FILE__ ) ),
        );

        $this->options = get_option( 'tmal-settings' );

        if ( is_admin() ) {
            require_once 'admin/class-tmal-admin.php';
            new Admin( $this->settings );
        }

        $this->load_includes();
    }

    /**
     * Get options
     * @return array Plugin options
     */
    public function get_options() {
        return $this->options;
    }

    /**
     * Get settings for the plugin
     * @return array Settings
     */
    public function get_settings() {
        return $this->settings;
    }

    /**
     * Load the necessary include files
     * @return void
     */
    private function load_includes() {
        // Include various utility classes
        require_once 'includes/class-tmal-twilio.php';
        require_once 'includes/class-tmal-phone-number.php';
        require_once 'includes/class-tmal-shortcodes.php';

        // Load and init frontend
        require_once 'includes/class-tmal-frontend.php';
        new Frontend( $this->settings );
    }
}

function tmal() {
    global $tmal;

    if ( ! isset($tmal) ) {
        $tmal = new TMaL();
    }

    return $tmal;
}

// Initialize
tmal();