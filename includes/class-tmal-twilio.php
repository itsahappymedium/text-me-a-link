<?php

namespace TMaL;

use \Services_Twilio as Services_Twilio;

class Twilio {

    /**
     * The Twilio client object
     * @var object
     */
    private $client;

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
    }

    public function init() {
        $this->options = tmal()->get_options();
        $this->settings = tmal()->get_settings();

        // Include the proper library
        require_once $this->settings['dir'] . 'vendor/autoload.php';

        // Set up the Twilio client
        $this->_setup_twilio_client();
    }

    /**
     * Set up the Twilio client
     * @return object Client object
     */
    private function _setup_twilio_client() {
        if ( ! $this->is_ready_to_send() ) {
            throw new \Exception("You must enter the proper credentials on the Text Me a Link settings page.");
        }

        $client = new Services_Twilio( $this->options['account_sid'], $this->options['auth_key'] );

        $this->client = $client;

        return $client;
    }

    /**
     * Determine if Twilio is ready to use
     * @return boolean Whether an SID and Auth Key have been entered
     */
    private function is_ready_to_send() {
        return (
            isset( $this->options['account_sid'] )
            && ! empty( $this->options['account_sid'] )
            && isset( $this->options['auth_key'] )
            && ! empty( $this->options['auth_key'] )
            && isset( $this->options['phone_number'] )
            && ! empty( $this->options['phone_number'] )
        );
    }

    /**
     * Send a message to a number
     * @param  string $number  Phone number
     * @param  string $message Message
     * @return string          The SID of the message
     */
    public function send_message( $number, $message ) {
        $message = $this->client->account->messages->sendMessage(
            $this->options['phone_number'],
            $number,
            $message
        );

        return $message->sid;
    }

    /**
     * Send a verification code to a number
     * @param  string $number Phone number
     * @return string         Status
     */
    public function send_verification_code( $number ) {
        $phone = new Phone_Number( $number );

        if ( $phone->is_verified() ) {
            return;
        } else {
            $code = $phone->get_verification_code();
            $status = $this->send_message(
                $number,
                sprintf( "Here is your verification code: %s", $code )
            );

            return $status;
        }
    }
}