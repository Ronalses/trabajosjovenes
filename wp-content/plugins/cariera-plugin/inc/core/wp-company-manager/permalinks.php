<?php

/**
*
* @package Cariera
*
* @since 1.4.4
* 
* ========================
* CARIERA COMPANY MANAGER - PERMALINKS
* ========================
*     
**/


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}




class Cariera_Company_Manager_Permalinks {
    
    // The single instance of the class.
	private static $_instance = null;

	// Permalink settings.
	private $permalinks = array();


	/**
	 * Allows for accessing single instance of class. Class should only be constructed once per call.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}


	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->setup_fields();
		$this->settings_save();
		$this->permalinks = Cariera_Company_Manager_CPT::get_permalink_structure();
	}


	/**
	 * Add setting fields related to permalinks.
	 */
	public function setup_fields() {
		add_settings_field(
			'company_base_slug',
			esc_html__( 'Company base', 'cariera' ),
			[ $this, 'company_base_slug_input' ],
			'permalink',
			'optional'
		);
		add_settings_field(
			'company_category_slug',
			esc_html__( 'Company category base', 'cariera' ),
			[ $this, 'company_category_slug_input' ],
			'permalink',
			'optional'
		);
        add_settings_field(
            'companies_archive_slug',
            esc_html__( 'Company archive page', 'cariera' ),
            [ $this, 'companies_archive_slug_input' ],
            'permalink',
            'optional'
        );
    }
    
    

	/**
	 * Show a slug input box for company archive slug.
	 */
	public function companies_archive_slug_input() {
		?>
		<input name="companies_archive_slug" type="text" class="regular-text code" value="<?php echo esc_attr( $this->permalinks['companies_archive'] ); ?>" placeholder="<?php echo esc_attr_x( 'companies', 'Companies archive permalink - resave permalinks after changing this', 'cariera' ); ?>" />
		<?php
	}


	/**
	 * Show a slug input box for job post type slug.
	 */
	public function company_base_slug_input() {
		?>
		<input name="company_base_slug" type="text" class="regular-text code" value="<?php echo esc_attr( $this->permalinks['company_base'] ); ?>" placeholder="<?php echo esc_attr_x( 'company', 'Company permalink - resave permalinks after changing this', 'cariera' ); ?>" />
		<?php
	}


	/**
	 * Show a slug input box for job category slug.
	 */
	public function company_category_slug_input() {
		?>
		<input name="company_category_slug" type="text" class="regular-text code" value="<?php echo esc_attr( $this->permalinks['company_category'] ); ?>" placeholder="<?php echo esc_attr_x( 'company-category', 'Company category slug - resave permalinks after changing this', 'cariera' ); ?>" />
		<?php
	}



	/**
	 * Save the settings.
	 */
	public function settings_save() {
		if ( ! is_admin() ) {
			return;
		}

		if ( ! isset( $_POST['permalink_structure'] ) ) {
			return;
		}

		if ( function_exists( 'switch_to_locale' ) ) {
			switch_to_locale( get_locale() );
		}

		$permalink_settings = Cariera_Company_Manager_CPT::get_raw_permalink_settings();

		$permalink_settings['company_base']      = sanitize_title_with_dashes( wp_unslash( $_POST['company_base_slug'] ) );
		$permalink_settings['company_category']  = sanitize_title_with_dashes( wp_unslash( $_POST['company_category_slug'] ) );
		$permalink_settings['companies_archive'] = sanitize_title_with_dashes( wp_unslash( $_POST['companies_archive_slug'] ) );

		update_option( 'cariera_company_core_permalinks', wp_json_encode( $permalink_settings ) );

		if ( function_exists( 'restore_current_locale' ) ) {
			restore_current_locale();
        }
	}
}

Cariera_Company_Manager_Permalinks::instance();