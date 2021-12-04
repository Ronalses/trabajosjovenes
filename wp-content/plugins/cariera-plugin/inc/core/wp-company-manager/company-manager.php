<?php

/**
*
* @package Cariera
*
* @since 1.4.4
* 
* ========================
* CARIERA COMPANY MANAGER
* ========================
*     
**/


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}



class Cariera_Company_Manager {


    public function __construct() {
		include_once( 'company-manager-functions.php');
		include_once( 'company-manager-templates.php');

        // Init classes
        new Cariera_Company_Manager_Shortcodes();
		new Cariera_Company_Manager_Writepanels();
		new Cariera_Company_Manager_Geocode();
		new Cariera_Company_Manager_Email_Notifications();
		Cariera_Company_Manager_Lifecycle::instance();

		if( class_exists( 'WP_Job_Manager_Bookmarks' ) ) {
			new Cariera_Company_Manager_Bookmarks();
		}

		$this->post_types  = new Cariera_Company_Manager_CPT();
        $this->forms       = new Cariera_Company_Manager_Forms();
        $this->settings    = new Cariera_Company_Manager_Settings();
		$this->wpjm 	   = new Cariera_Company_Manager_WPJM();
		
		add_action( 'wp_enqueue_scripts', [ $this, 'frontend_scripts' ] );
    }






	/**
	 * Queries companies with certain criteria and returns them.
	 *
	 * @since  1.3.0
	 */
	public function frontend_scripts() {
		$ajax_filter_deps   = [ 'jquery', 'jquery-deserialize' ];

		wp_register_script( 'company-ajax-filters', CARIERA_URL . '/assets/dist/js/company-ajax-filters.js', $ajax_filter_deps, true );
		wp_localize_script( 'company-ajax-filters', 'cariera_company_ajax_filters', [
			'ajax_url'  => admin_url( 'admin-ajax.php', 'relative' ),
			'is_rtl'  	=> is_rtl() ? 1 : 0,
		] );

		wp_register_script( 'cariera-company-manager-submission', CARIERA_URL . '/assets/dist/js/company-submission.js', array( 'jquery', 'jquery-ui-sortable' ), false, true );
	}





    /**
     * Autoload classes and files
     *
     * @since  1.3.0
     */
    public static function autoload( $class ) {

		// Exit autoload if being called by a class
		if ( false === strpos( $class, 'Cariera_Company_Manager' ) ) {
			return;
		}

        $class_file = str_replace( 'Cariera_Company_Manager_', '', $class );
        $file_array = array_map( 'strtolower', explode( '_', $class_file ) );
        //var_dump( $file_array );

		$dirs = 0;
		$file = untrailingslashit( dirname( __FILE__ ) );

		foreach( $file_array as $dir ) {
			$dirs++;
			$maybe_file = implode( '-', array_slice( $file_array, $dirs - 1 ) );

			if( file_exists( $file . '/' . $maybe_file . '.php' ) ) {
				$file .= '/' . $maybe_file;
				break;
			} else {
				$file .= '/' . $dir;
			}
		}

		$file .= '.php';

		if ( ! file_exists( $file ) || $class === 'Cariera_Company_Manager' ) {
			return;
		}

		include $file;
    }

}

spl_autoload_register( array( 'Cariera_Company_Manager', 'autoload' ) );

$GLOBALS['cariera_company_manager'] = new Cariera_Company_Manager();