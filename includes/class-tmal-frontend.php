<?php

namespace TMaL;

class Frontend {
    /**
     * Settings from the parent class
     * @var array
     */
    private $settings;

    public function __construct( $settings ) {
        $this->settings = $settings;

        // Setup functions
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

        // Add AJAX listeners
        add_action( 'wp_ajax_tmal_number_submit', array( $this, 'ajax_handle_number_submit') );
        add_action( 'wp_ajax_nopriv_tmal_number_submit', array( $this, 'ajax_handle_number_submit') );
    }

    /**
     * Handle when a user submits a phone number over AJAX
     * @return void
     */
    public function ajax_handle_number_submit() {
        $response = array();

        check_ajax_referer( 'tmal-submit-number' );

        if ( isset($_POST['tmal-number']) ) {
            $number = $_POST['tmal-number'];

            // Initialize Twilio
            $twilio = new Twilio();
            $twilio->init();

            $phone = new Phone_Number( $number );
            $response['success'] = true;

            if ( ! $phone->is_verified() ) {
                $twilio->send_verification_code( $phone->get_number() );
                $response['data'] = 'verify';
            } else {
                $twilio->send_message( $phone->get_number(), 'This is the page' );
                $response['data'] = 'sent';
            }
        } else {
            $response['success'] = false;
        }

        wp_send_json( $response );
    }

    /**
     * Enqueue scripts
     * @return void
     */
    public function enqueue_scripts() {
        wp_enqueue_style(
            'tmal-frontend',
            $this->settings['url'] . 'assets/css/tmal-frontend.css',
            array(),
            $this->settings['version']
        );

        wp_register_script(
            'tmal-frontend',
            $this->settings['url'] . 'assets/js/tmal-frontend.js',
            array(),
            $this->settings['version'],
            true
        );

        wp_localize_script(
            'tmal-frontend',
            'TMAL_AJAX',
            array(
                'ajaxUrl'   => admin_url( 'admin-ajax.php' )
            )
        );

        wp_enqueue_script( 'tmal-frontend' );
    }
}