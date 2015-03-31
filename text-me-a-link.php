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
     * Plugin settings
     * @var array
     */
    var $settings;

    /**
     * Settings from the options page
     * @var array
     */
    private $options;

    public function __construct() {
        $this->settings = array(
            'version'       => '0.1-alpha',
            'name'          => __( 'Text Me a Link', 'tmal' ),
            'dir'           => trailingslashit( plugin_dir_path( __FILE__ ) ),
            'url'           => trailingslashit( plugin_dir_url( __FILE__ ) ),
        );

        $this->options = get_option( 'tmal-settings' );

        if ( is_admin() ) {
            include( 'admin/class-tmal-admin.php' );
        }
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