<?php

/**
*
* @package Cariera
*
* @since 1.4.8
* 
* ========================
* MAIN RECAPTCHA FILE
* ========================
*     
**/


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}



class Cariera_Recaptcha {

    public static function init() {
        add_action( 'wp_enqueue_scripts', [ __CLASS__, 'enqueue' ] );
    }
    




    /**
	 * Enqueue Google reCaptcha script
	 *
	 * @since 1.4.8
	 */
    public static function enqueue() {
		if ( self::is_recaptcha_enabled() ) {
			wp_enqueue_script( 'recaptcha', '//www.google.com/recaptcha/api.js', array( 'jquery' ), false, true );
		}
    }





    /**
	 * Check if reCaptcha is enabled
	 *
	 * @since 1.4.8
	 */
    public static function is_recaptcha_enabled() {
		$site_key   = get_option( 'cariera_recaptcha_sitekey' );
		$secret_key = get_option( 'cariera_recaptcha_secretkey' );

        if ( ! empty( $site_key ) && ! empty( $secret_key ) ) {
            return true;
        }

        return false;
    }





    /**
	 * Check if reCaptcha is enabled
	 *
	 * @since 1.4.8
	 */
    public static function validate_fields( $return ) {
		if ( self::is_recaptcha_enabled() ) {
			$is_recaptcha_valid = array_key_exists( 'g-recaptcha-response', $_POST ) ? self::is_recaptcha_valid( sanitize_text_field( $_POST['g-recaptcha-response'] ) ) : false;
			if ( !$is_recaptcha_valid ) {
				return new WP_Error( 'validation-error', esc_html__( 'reCAPTCHA is a required field', 'cariera' ) );
			}
		}
		return $return;
    }





    /**
	 * Checks if reCAPTCHA is valid
	 *
	 * @since 1.4.8
	 */
	public static function is_recaptcha_valid( $recaptcha_response ) {
		$response = wp_remote_get(
			add_query_arg( [
					'secret'   => get_option( 'cariera_recaptcha_secretkey' ),
					'response' => $recaptcha_response
                ],
				'https://www.google.com/recaptcha/api/siteverify'
			)
        );
        
		if ( is_wp_error( $response ) || empty( $response['body'] ) ) {
			return false;
		}

		$json = json_decode( $response['body'] );
		if ( ! $json || ! $json->success ) {
			return false;
		}

		return true;
    }
    
}

Cariera_Recaptcha::init();