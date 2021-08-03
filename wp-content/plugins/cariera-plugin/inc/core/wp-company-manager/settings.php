<?php

/**
*
* @package Cariera
*
* @since 1.4.4
* 
* ========================
* CARIERA COMPANY MANAGER - SETTINGS
* ========================
*     
**/



// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! class_exists( 'WP_Job_Manager_Settings' ) ) {
    include( JOB_MANAGER_PLUGIN_DIR . '/includes/admin/class-wp-job-manager-settings.php' );
}



class Cariera_Company_Manager_Settings extends WP_Job_Manager_Settings {



	/**
	 * Construct
	 *
	 * @since  1.4.4
	 */
    public function __construct() {
        $this->settings_group = 'cariera_company_manager';
		
		// Register settings
		add_action( 'admin_init' , [ $this, 'register_settings' ] );
		
		// Add settings page to menu
		add_action( 'admin_menu' , [ $this, 'add_menu_item' ], 12 );
    }





	/**
     * Add settings page to admin menu
     * 
     * @since 1.4.4
     */
    public function add_menu_item () {
        add_submenu_page( 'edit.php?post_type=company', esc_html__( 'Settings', 'cariera' ), esc_html__( 'Settings', 'cariera' ), 'manage_options', 'cariera_company_manager_settings',  array( $this, 'output' ) );
    }





	/**
     * Initializes the configuration for the plugin's setting fields.
     * 
     * @since 1.4.4
     */
    protected function init_settings () {
		// Prepare roles option.
		$roles         = get_editable_roles();
		$account_roles = [];

		$singular = cariera_get_company_manager_singular_label();
		$plural   = cariera_get_company_manager_plural_label();

		foreach ( $roles as $key => $role ) {
			if ( 'administrator' === $key ) {
				continue;
			}
			$account_roles[ $key ] = $role['name'];
		}

		$prefix = 'cariera_';

		$this->settings = apply_filters(
			'cariera_company_manager_settings',
			array(

                /********** COMPANY LISTINGS OPTIONS **********/
				'company_listings'  => array(
					esc_html__( 'Company Listings', 'cariera' ),
					array(
                        array(
							'name'        => $prefix . 'companies_per_page',
							'std'         => '10',
							'placeholder' => '',
							'label'       => esc_html__( 'Listings Per Page', 'cariera' ),
							'desc'        => esc_html__( 'Number of job listings to display per page.', 'cariera' ),
							'attributes'  => array()
						),
						array(
							'name'      => $prefix . 'company_category',
							'std'       => '1',
							'label'     => esc_html__( 'Company Category', 'cariera' ),
							'cb_label'  => esc_html__( 'Enable Company Category', 'cariera' ),
							'desc'      => esc_html__( 'Enabling this option will show the Company Categories in sidebar, job posting and in the backend.', 'cariera' ),
							'type'      => 'checkbox',
						),
						array(
							'name'      => $prefix . 'company_team_size',
							'std'       => '1',
							'label'     => esc_html__( 'Team Size', 'cariera' ),
							'cb_label'  => esc_html__( 'Enable Team Size', 'cariera' ),
							'desc'      => esc_html__( 'Enabling this option will show the Team Size in sidebar, job posting and in the backend.', 'cariera' ),
							'type'      => 'checkbox',
						),
					),
				),


				/********** SINGLE COMPANY PAGE OPTIONS **********/
                'company_page'		=> array(
					esc_html__( 'Company Single Page', 'cariera' ),
					array(
                        array(
							'name'      => $prefix . 'single_company_contact_form',
							'std'       => '',
							'label'     => esc_html__( 'Single Company Contact Form', 'cariera' ),
							'desc'      => esc_html__( 'Select the form for single company contact form. This lets the plugin know the contact form of single company.', 'cariera' ),
							'type'      => 'select',
							'options'   => function_exists( 'cariera_get_forms' ) ? cariera_get_forms() : array( 0 => esc_html__( 'Please select a form', 'cariera' ) ),
						),
						array(
							'name'       => $prefix . 'single_company_active_jobs',
							'std'        => '1',
							'label'      => esc_html__( 'Active Jobs', 'cariera' ),
							'cb_label'   => esc_html__( 'Display active Jobs' ),
							'desc'       => sprintf( esc_html__( 'If the %s has active Jobs, a list will be output at the bottom of the page.' ), $singular ),
							'type'       => 'checkbox',
							'attributes' => array(),
						),
					),
				),


				/********** COMPANY SUBMISSION OPTIONS **********/
                'company_submission'	=> array(
					esc_html__( 'Company Submission', 'cariera' ),
					array(
						array(
							'name'       => $prefix . 'company_user_requires_account',
							'std'        => '1',
							'label'      => esc_html__( 'Account Required', 'cariera' ),
							'cb_label'   => esc_html__( 'Submitting listings requires an account', 'cariera' ),
							'desc'       => esc_html__( 'Limits company submissions to registered, logged-in users.', 'cariera' ),
							'type'       => 'checkbox',
							'attributes' => array(),
						),
						array(
							'name'       => $prefix . 'enable_company_registration',
							'std'        => '1',
							'label'      => esc_html__( 'Account Creation', 'cariera' ),
							'cb_label'   => esc_html__( 'Allow account creation', 'cariera' ),
							'desc'       => esc_html__( 'Includes account creation on the company submission form, to allow non-registered users to create an account and submit a company simultaneously.', 'cariera' ),
							'type'       => 'checkbox',
							'attributes' => array(),
						),
						array(
							'name'    => $prefix . 'company_registration_role',
							'std'     => 'employer',
							'label'   => esc_html__( 'Account Role', 'cariera' ),
							'desc'    => esc_html__( 'If you enable registration on your submission form, choose a role for the new user.', 'cariera' ),
							'type'    => 'select',
							'options' => $account_roles,
						),
						array(
							'name'       => $prefix . 'company_submission_requires_approval',
							'std'        => '1',
							'label'      => esc_html__( 'Approval Required', 'cariera' ),
							'cb_label'   => esc_html__( 'New submissions require admin approval', 'cariera' ),
							'desc'       => esc_html__( 'Sets all new submissions to "pending." They will not appear on your site until an admin approves them.', 'cariera' ),
							'type'       => 'checkbox',
							'attributes' => array(),
						),
						array(
							'name'       => $prefix . 'company_submission_notification',
							'std'        => '1',
							'label'      => esc_html__( 'Email New Submission', 'cariera' ),
							'cb_label'   => esc_html__( 'Email details to the admin', 'cariera' ),
							'desc'       => esc_html__( 'If enabled, the admin will be notified via email everytime there is a new company submission.', 'cariera' ),
							'type'       => 'checkbox',
							'attributes' => array(),
						),
						array(
							'name'        => $prefix . 'company_submission_limit',
							'std'         => '',
							'label'       => esc_html__( 'Listing Limit', 'cariera' ),
							'desc'        => sprintf( esc_html__( 'Limit users submission by adding a max number. Can be left blank to allow unlimited %s per account.', 'cariera' ), $plural ),
							'attributes'  => array(),
							'placeholder' => esc_html__( 'No limit', 'cariera' ),
						),
                        array(
							'name'     => $prefix . 'user_specific_company',
							'std'      => '1',
							'label'    => esc_html__( 'User Specific Companies', 'cariera' ),
							'cb_label' => esc_html__( 'Enable User Specific Companies', 'cariera' ),
							'desc'     => esc_html__( 'If enabled the user will be able to see only the companies created by the user under the "existing company". If disabled all companies will be visible, even for non logged in users.', 'cariera' ),
							'type'     => 'checkbox'
						),
					),
				),


				/********** WPJM INTEGRATION OPTIONS **********/
                'company_integration'		=> array(
					esc_html__( 'WPJM Integration', 'cariera' ),
					array(
                        array(
							'name'      => $prefix . 'company_manager_integration',
							'std'       => '1',
							'label'     => esc_html__( 'Cariera Company Manager', 'cariera' ),							
							'cb_label'  => esc_html__( 'WPJM Integration', 'cariera' ),
							'desc'      => sprintf( esc_html__( 'Replace all the default %s fields from WP Job Manager with the main Cariera Company Manager fields.', 'cariera' ), $singular),
							'type'      => 'checkbox',
						),
						array(
							'name'      => $prefix . 'add_new_company',
							'std'       => '1',
							'label'     => sprintf( esc_html__( 'Add New %s', 'cariera' ), $singular ),						
							'cb_label'  => sprintf( esc_html__( 'Enable new %s submission' ), $singular ),
							'desc'      => sprintf( esc_html__( 'If disabled you will not be able to post a new %s on job submission.' ), $singular ),
							'type'      => 'checkbox',
						),
					),
				),


				/********** PAGES OPTIONS **********/
                'company_pages'	=> array(
					esc_html__( 'Pages', 'cariera' ),
					array(
						array(
							'name'      => $prefix . 'submit_company_page',
							'std'       => '',
							'label'     => esc_html__( 'Submit Company Page', 'cariera' ),
							'desc'      => esc_html__( 'Select the page for company submission where you have placed the [submit_company] shortcode. This lets the plugin know the location of the company listings page.', 'cariera' ),
							'type'      => 'page'
						),
						array(
							'name'      => $prefix . 'company_dashboard_page',
							'std'       => '',
							'label'     => esc_html__( 'Company Dashboard Page', 'cariera' ),
							'desc'      => esc_html__( 'Select the page for company dashboard where you have placed the [company_dashboard] shortcode. This lets the plugin know the location of the company dashboard page.', 'cariera' ),
							'type'      => 'page'
						),
                        array(
							'name'      => $prefix . 'companies_page',
							'std'       => '',
							'label'     => esc_html__( 'Company Listings Page', 'cariera' ),
							'desc'      => esc_html__( 'Select the page for company listing. This lets the plugin know the location of the company listings page.', 'cariera' ),
							'type'      => 'page'
						),
					),
				),


				/********** VISIBILITY OPTIONS **********/
                'company_visibility'	=> array(
					sprintf( esc_html__( '%s Visibility', 'cariera' ), $singular ),
					array(
						array(
							'name'  => $prefix . 'company_manager_browse_company_capability',
							'std'   => '',
							'label' => esc_html__( 'Browse Capability', 'cariera' ),
							'type'  => 'input',
							// translators: Placeholder %s is the url to the WordPress core documentation for capabilities and roles.
							'desc'  => sprintf( __( 'Enter the <a href="%s">capability</a> required in order to browse. Supports a comma separated list of roles/capabilities.', 'cariera' ), 'https://wordpress.org/support/article/roles-and-capabilities/' ),
						),
						array(
							'name'  => $prefix . 'company_manager_view_company_capability',
							'std'   => '',
							'label' => esc_html__( 'View Capability', 'cariera' ),
							'type'  => 'input',
							// translators: Placeholder %s is the url to the WordPress core documentation for capabilities and roles.
							'desc'  => sprintf( __( 'Enter the <a href="%s">capability</a> required in order to view a single company. Supports a comma separated list of roles/capabilities.', 'cariera' ), 'https://wordpress.org/support/article/roles-and-capabilities/' ),
						),
						array(
							'name'       => $prefix . 'company_manager_discourage_company_search_indexing',
							'std'        => '0',
							'label'      => esc_html__( 'Search Engine Visibility', 'cariera' ),
							'cb_label'   => esc_html__( 'Discourage search engines from indexing company listings', 'cariera' ),
							'desc'       => esc_html__( 'Search engines choose whether to honor this request.', 'cariera' ),
							'type'       => 'checkbox',
							'attributes' => array(),
						),
					),
				),


				/********** OTHER OPTIONS **********/
				'company_other' => array(
					esc_html__( 'Other', 'cariera' ),
					array(
						array(
							'name'        => $prefix . 'company_manager_cpt_singular_label',
							'std'         => esc_html__( 'Company' ),
							'placeholder' => esc_html__( 'Company' ),
							'label'       => esc_html__( 'Singular Label', 'cariera' ),
							'desc'        => esc_html__( 'You can change the singular label and use a custom label instead of "Company".', 'cariera' ),
							'attributes'  => array(),
						),
						array(
							'name'        => $prefix . 'company_manager_cpt_plural_label',
							'std'         => esc_html__( 'Companies' ),
							'placeholder' => esc_html__( 'Companies' ),
							'label'       => esc_html__( 'Plural Label', 'cariera' ),
							'desc'        => esc_html__( 'You can change the plural label and use a custom label instead of "Companies".', 'cariera' ),
							'attributes'  => array(),
						),
					),
				),

			)
        );
	}
}