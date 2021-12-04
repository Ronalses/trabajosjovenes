<?php

/**
*
* @package Cariera
*
* @since 1.4.4
* 
* ========================
* CARIERA COMPANY MANAGER - FORMS
* ========================
*     
**/


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}



class Cariera_Company_Manager_Forms {


	public function __construct() {
		add_action( 'init', [ $this, 'load_posted_form' ] );
	}





    /**
     * If a form was posted, load its class so that it can be processed before display.
     *
     * @since  1.4.4
     */
	public function load_posted_form() {
		if ( ! empty( $_POST['company_manager_form'] ) ) {
			$this->load_form_class( sanitize_title( $_POST['company_manager_form'] ) );
		}
    }
    




	/**
	 * Load a form's class
	 *
	 * @since 1.4.4
	 */    
	private function load_form_class( $form_name ) {
		if ( ! class_exists( 'WP_Job_Manager_Form' ) ) {
			include( JOB_MANAGER_PLUGIN_DIR . '/includes/abstracts/abstract-wp-job-manager-form.php' );
		}

		// Now try to load the form_name
		$form_class  = 'Cariera_Company_Manager_Form_' . str_replace( '-', '_', $form_name );
		$form_file   = dirname(__FILE__) . '/form/' . $form_name . '.php';

		if ( class_exists( $form_class ) ) {
			return call_user_func( array( $form_class, 'instance' ) );
		}

		if ( ! file_exists( $form_file ) ) {
			return false;
		}

		if ( ! class_exists( $form_class ) ) {
			include $form_file;
		}

		// Init the form
		return call_user_func( array( $form_class, 'instance' ) );
	}





	/**
	 * get_form function.
	 *
	 * @since 1.4.4
	 */
	public function get_form( $form_name, $atts = array() ) {
		if ( $form = $this->load_form_class( $form_name ) ) {
			ob_start();
			$form->output( $atts );
			return ob_get_clean();
		}
	}
}