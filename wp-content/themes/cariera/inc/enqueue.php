<?php
/**
*
* @package Cariera
*
* @since    1.0.0
* @version  1.4.8
* 
* ========================
* ENQUEUE FUNCTIONS
* ========================
*     
**/



/* 
=====================================================
ENQUEUE SCRIPTS
=====================================================
*/

function cariera_scripts() {
    // Vendors
    wp_enqueue_script( 'imagesloaded' );
    wp_enqueue_script( 'select2', get_template_directory_uri() . '/assets/vendors/select2/select2.min.js', array( 'jquery' ), false, true );
    

    // Comment Reply Script
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
    
    
    // Main Theme JS File
    wp_enqueue_script( 'cariera-main', get_template_directory_uri() . '/assets/dist/js/frontend.js', array( 'jquery' ), false, true );
    wp_register_script( 'cariera-dashboard', get_template_directory_uri() . '/assets/dist/js/dashboard.js', array( 'jquery' ), false, true );
    
    $ajax_url = admin_url( 'admin-ajax.php', 'relative' );
    
    $translations = [
        'ajax_url'              => esc_url( $ajax_url ),
        'nonce'                 => wp_create_nonce( '_cariera_nonce' ),
        'theme_url'             => get_template_directory_uri(),
        'ajax_job_search'       => intval( cariera_get_option( 'cariera_job_ajax_search' )),
        'cookie_notice'         => intval( cariera_get_option( 'cariera_cookie_notice' ) ),
        'gdpr_check'            => intval( cariera_get_option( 'cariera_register_gdpr' )),
        'delete_account_text'   => esc_html__( 'Are you sure you want to delete your account?', 'cariera' ),
        'views_chart_label'     => esc_html__( 'Views', 'cariera' ),
        'views_statistics'      => intval( cariera_get_option('cariera_dashboard_views_statistics') ),
        'statistics_border'     => cariera_get_option('cariera_dashboard_statistics_border'),
        'statistics_background' => cariera_get_option('cariera_dashboard_statistics_background'),
        'wcpl_text'             => esc_html__( 'Select a package above and click the button.', 'cariera' ),        
        'wcpl_button'           => esc_html__( 'Select Package', 'cariera' ),
        'mmenu_text'            => esc_html__( 'Main Menu', 'cariera' ),
        'company_data_loading'  => esc_html__( 'Data Loading', 'cariera' ),
        'company_data_loaded'   => esc_html__( 'Company Data Loaded', 'cariera' ),
        'map_provider'          => cariera_get_option( 'cariera_map_provider'),
        'gmap_api_key'          => cariera_get_option( 'cariera_gmap_api_key' ),
    ];

	wp_localize_script( 'cariera-main', 'cariera_settings', $translations );

}

add_action( 'wp_enqueue_scripts', 'cariera_scripts' );





/* 
=====================================================
ENQUEUE STYLES
=====================================================
*/

function cariera_styles() {

    /*** Vendors Plugins ***/
	wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/assets/vendors/bootstrap/bootstrap.min.css' );
    wp_enqueue_style( 'cariera-select2', get_template_directory_uri() . '/assets/vendors/select2/select2.min.css' );
    
    /*** Icons ***/
    wp_enqueue_style( 'font-awesome-5', get_template_directory_uri() . '/assets/vendors/font-icons/all.min.css');
    wp_enqueue_style( 'simple-line-icons', get_template_directory_uri() . '/assets/vendors/font-icons/simple-line-icons.min.css');
    if ( get_option('cariera_font_iconsmind') ) {
        wp_enqueue_style( 'iconsmind', get_template_directory_uri() . '/assets/vendors/font-icons/iconsmind.min.css');
    }
    
    /*** Main Styles ***/
    wp_enqueue_style( 'cariera-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style( 'cariera-frontend', get_template_directory_uri() . '/assets/dist/css/frontend.css' );
}

add_action( 'wp_enqueue_scripts', 'cariera_styles' );




/*
=====================================================
ADMIN ENQUEUE SCRIPTS
=====================================================
*/

function cariera_admin_scripts( $hook ) {
    
    if( $hook=='edit-tags.php' || $hook == 'term.php' || $hook == 'post.php' ) {
        wp_enqueue_style( 'font-icon-picker', get_template_directory_uri(). '/assets/vendors/fonticon-picker/fonticonpicker.css');
        wp_enqueue_script( 'font-icon-picker', get_template_directory_uri() . '/assets/vendors/fonticon-picker/jquery.fonticonpicker.js', array('jquery'), false, true );
        
		wp_enqueue_style( 'font-awesome-5', get_template_directory_uri(). '/assets/vendors/font-icons/all.min.css');
        wp_enqueue_style( 'simple-line-icons', get_template_directory_uri() . '/assets/vendors/font-icons/simple-line-icons.min.css');
        if ( get_option('cariera_font_iconsmind') ) {
            wp_enqueue_style( 'iconsmind', get_template_directory_uri() . '/assets/vendors/font-icons/iconsmind.min.css');
        }
	}
    
    wp_enqueue_script( 'cariera-admin', get_template_directory_uri() . '/assets/dist/js/admin.js', array('jquery'), false, true );  
}

add_action( 'admin_enqueue_scripts', 'cariera_admin_scripts' );