<?php

/**
*
* @package Cariera
*
* @since 1.4.4
* 
* ========================
* CARIERA COMPANY MANAGER - COMPANY SUBMIT
* ========================
*     
**/


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

include_once( 'submit-company.php' );



class Cariera_Company_Manager_Form_Edit_Company extends Cariera_Company_Manager_Form_Submit_Company {

    public $form_name = 'edit-company';

	protected static $_instance = null;

	/**
	 * Main Instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}



	/**
	 * Constructor.
	 * 
	 * @since 1.4.4
	 */
	public function __construct() {
		add_action( 'wp', [ $this, 'submit_handler' ] );
		
		$this->company_id = ! empty( $_REQUEST['company_id'] ) ? absint( $_REQUEST[ 'company_id' ] ) : 0;

		if  ( ! cariera_user_can_edit_company( $this->company_id ) ) {
			$this->company_id = 0;
		}
    }
    



    /**
	 * output function.
     * 
     * @since 1.4.4
	 */
	public function output( $atts = array() ) {
		$this->submit_handler();
		$this->submit();
	}





	/**
	 * Submit Step
     * 
     * @since 1.4.4
	 */
	public function submit() {
		global $post;

		$company = get_post( $this->company_id );

		if ( empty( $this->company_id  ) || ( $company->post_status !== 'publish' && $company->post_status !== 'private' ) ) {
			echo wpautop( esc_html__( 'Invalid Company', 'cariera' ) );
			return;
		}

		$this->init_fields();

		foreach ( $this->fields as $group_key => $group_fields ) {
			foreach ( $group_fields as $key => $field ) {
				if ( ! isset( $this->fields[ $group_key ][ $key ]['value'] ) ) {
					if ( 'company_name' === $key ) {
						$this->fields[ $group_key ][ $key ]['value'] = $company->post_title;

					} elseif ( 'company_content' === $key ) {
						$this->fields[ $group_key ][ $key ]['value'] = $company->post_content;

					} elseif ( ! empty( $field['taxonomy'] ) ) {
						$this->fields[ $group_key ][ $key ]['value'] = wp_get_object_terms( $company->ID, $field['taxonomy'], array( 'fields' => 'ids' ) );

					} else {
						$this->fields[ $group_key ][ $key ]['value'] = get_post_meta( $company->ID, '_' . $key, true );
					}
				}
			}
		}

		$this->fields = apply_filters( 'cariera_submit_company_form_fields_get_company_data', $this->fields, $company );

		get_job_manager_template( 'company-submit.php', array(
			'class'              => $this,
			'form'               => $this->form_name,
			'job_id'             => '',
			'company_id'         => $this->get_company_id(),
			'action'             => $this->get_action(),
			'company_fields'     => $this->get_fields( 'company_fields' ),
			'step'               => $this->get_step(),
			'submit_button_text' => esc_html__( 'Save changes', 'cariera' )
		), 'wp-job-manager-companies' );
	}





	/**
	 * Submit Step is posted
     * 
     * @since 1.4.4
	 */
	public function submit_handler() {
		if ( empty( $_POST['submit_company'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'submit_form_posted' ) ) {
			return;
		}

		try {

			// Init fields
			$this->init_fields();

			// Get posted values
			$values = $this->get_posted_fields();

			// Validate required
			if ( is_wp_error( ( $return = $this->validate_fields( $values ) ) ) ) {
				throw new Exception( $return->get_error_message() );
			}

			$original_post_status = get_post_status( $this->company_id );
			$save_post_status     = $original_post_status;

			// Update the company
			$this->save_company( $values['company_fields']['company_name'], $values['company_fields']['company_content'], $save_post_status, $values );
			$this->update_company_data( $values );

			// Successful
			$save_message = esc_html__( 'Your changes have been saved.', 'cariera' );
			$post_status  = get_post_status( $this->company_id );

			update_post_meta( $this->company_id, '_company_edited', time() );
			update_post_meta( $this->company_id, '_company_edited_original_status', $original_post_status );

			$published_statuses = [ 'publish', 'hidden' ];
			if ( 'publish' === $post_status ) {
				$save_message = $save_message . ' <a href="' . get_permalink( $this->company_id ) . '">' . esc_html__( 'View &rarr;', 'cariera' ) . '</a>';
			} elseif ( in_array( $original_post_status, $published_statuses, true ) && 'pending' === $post_status ) {
				$save_message = esc_html__( 'Your changes have been submitted and your company will be available again once approved.', 'cariera' );
			}

			// Change the message that appears when a user edits a company.
			$this->save_message = apply_filters( 'cariera_update_company_listings_message', $save_message, $this->company_id, $values );

			// Add the message and redirect to the candidate dashboard if possible.
			if ( Cariera_Company_Manager_Shortcodes::add_company_dashboard_message( $this->save_message ) ) {
				$company_dashboard_page_id = get_option( 'cariera_company_dashboard_page' );
				$company_dashboard_url     = get_permalink( $company_dashboard_page_id );
				if ( $company_dashboard_url ) {
					wp_safe_redirect( $company_dashboard_url );
					exit;
				}
			}

		} catch ( Exception $e ) {
			$this->save_error = $e->getMessage();
		}
	}

}