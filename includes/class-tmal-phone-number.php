<?php

namespace TMaL;

class Phone_Number {

    /**
     * Phone number
     * @var string
     */
    private $number;

    public function __construct( $number ) {
        $this->number = $number;

        if ( ! $this->is_valid_number() ) {
            throw new \Exception("You passed an invalid phone number");
        }
    }

    /**
     * Check to see if a code is valid
     * @param  int $code Code test
     * @return boolean       Whether the number has been verified
     */
    public function compare_verification_code( $code ) {
        if ( $code == $this->get_verification_code() ) {
            $this->verify_number();

            return true;
        } else {
            return false;
        }
    }

    /**
     * Generate a four digit verification code
     * @return [type] [description]
     */
    private function generate_verification_code() {
        return mt_rand(1000, 9999);
    }

    /**
     * Get the encoded version of the phone number
     * @return string Encoded phone
     */
    private function get_encoded_number() {
        return md5( $this->number );
    }

    /**
     * Get the phone number
     * @return string Phone number
     */
    public function get_number() {
        return $this->number;
    }

    /**
     * Determine if it's a valid phone number
     * @return boolean         Whether it's valid
     */
    public function is_valid_number() {
        // TODO: Validation
        return true;
    }

    /**
     * Determine if a phone number is verified in the system or not
     * @return boolean Whether it's verified
     */
    public function is_verified() {
        return ( '1' === get_option( 'tmal-' . $this->get_encoded_number() ) );
    }

    /**
     * Determine if the number is pending verification
     * @return boolean Whether it's pending verification
     */
    public function is_pending_verification() {
        $entry = get_option( 'tmal-' . $this->get_encoded_number() );

        return ( $entry && $entry !== '1' );
    }

    /**
     * Get (and possibly set) the verification for a number
     * @return string/boolean The code/true if it already exists
     */
    public function get_verification_code() {
        if ( $this->is_verified() ) {
            return true;
        }

        if ( $this->is_pending_verification() ) {
            $verification_code = get_option( 'tmal-' . $this->get_encoded_number() );
        } else {
            $verification_code = $this->set_verification_code();
        }

        return $verification_code;
    }

    /**
     * Set a verification code
     */
    private function set_verification_code() {
        if ( $this->is_verified() ) {
            return true;
        }

        $verification_code = $this->generate_verification_code();

        // Set up a transient for this phone number with a verification code
        add_option( 'tmal-' . $this->get_encoded_number(), $verification_code );

        return $verification_code;
    }

    /**
     * Update the number to be verified
     * @return void
     */
    private function verify_number() {
        update_option( 'tmal-' . $this->get_encoded_number(), true );
    }
}