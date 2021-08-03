<?php

/**
*
* @package Cariera
*
* @since 1.4.7
* 
* ========================
* CARIERA COMPANY MANAGER - EMAILS
* ========================
*     
**/


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}




class Cariera_Company_Manager_Email_Notifications {

	/**
	 * Constructor.
	 * 
	 * @since 1.4.7
	 */
	public function __construct() {
		if ( get_option( 'cariera_company_submission_notification' ) ) {
			add_action( 'cariera_company_submitted', [ $this, 'send_new_company_notification' ] );
		}
	}





	/**
	 * New company notification
	 * 
	 * @since 1.4.7
	 */
	public function send_new_company_notification( $company_id ) {

		$company 	 = get_post( $company_id );
		$admin_email = get_option( 'admin_email' );
		$subject     = sprintf( esc_html__( 'New Company Submission: %s', 'cariera' ), $company->post_title );
		$headers[]   = 'Content-type: text/html; charset: ' . get_bloginfo( 'charset' );

		ob_start();
		get_job_manager_template( 'emails/admin-new-company.php', array(
			'company'        => $company,
			'company_id'     => $company_id
		), 'wp-job-manager-companies' );

		$message = ob_get_clean();

		wp_mail(
			$admin_email,
			apply_filters( 'cariera_new_company_notification_subject', $subject, $company_id ),
			$message,
			apply_filters( 'cariera_new_company_notification_headers', $headers, $company_id )
		);
	}
}