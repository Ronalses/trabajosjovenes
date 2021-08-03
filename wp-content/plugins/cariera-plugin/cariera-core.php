<?php
/**
 * Plugin Name: Cariera Core
 * Plugin URI:  https://themeforest.net/item/cariera-job-board-wordpress-theme/20167356
 * Description: This is the Core plugin of Cariera Theme.
 * Version:     1.5.1
 * Author:      Gnodesign
 * Author URI:  https://themeforest.net/user/gnodesign
 * License:     GPLv2+
 * Text Domain: cariera
 * Domain Path: /lang
**/


defined( 'ABSPATH' ) or exit;

define( 'CARIERA_CORE', __FILE__ );
define( 'CARIERA_URL', plugins_url('', __FILE__) );
define( 'CARIERA_PATH', dirname(__FILE__) );
define( 'CARIERA_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );





/**
 * Loading Text Domain file for translations
 *
 * @since  1.2.2
 */
function cariera_load_textdomain() {
    load_plugin_textdomain( 'cariera', false, basename( dirname( __FILE__ ) ) . '/lang' ); 
}

add_action( 'init', 'cariera_load_textdomain' );



// Install Functionality
include_once( CARIERA_PATH . '/inc/install.php' );



/**
 * Including Plugin Functions
 *
 * @since  1.2.2
 */
function cariera_plugin_functions() {
    include_once( CARIERA_PATH . '/inc/functions.php' );
    include_once( CARIERA_PATH . '/inc/elementor.php' );

    // Importer
    include_once( CARIERA_PATH . '/inc/importer/core.php');
    include_once( CARIERA_PATH . '/inc/importer/importer/cariera-importer.php');
    include_once( CARIERA_PATH . '/inc/importer/init.php');

    // Extensions
    include_once( CARIERA_PATH . '/inc/extensions/account/user.php' );
    include_once( CARIERA_PATH . '/inc/extensions/metabox/metaboxes.php' );
    include_once( CARIERA_PATH . '/inc/extensions/recaptcha/recaptcha.php' );
    include_once( CARIERA_PATH . '/inc/extensions/social-share/social.php' );
    include_once( CARIERA_PATH . '/inc/extensions/testimonials/testimonials.php' );
    include_once( CARIERA_PATH . '/inc/extensions/dashboard/my-account.php' );
    include_once( CARIERA_PATH . '/inc/extensions/dashboard/my-dashboard.php' );
    include_once( CARIERA_PATH . '/inc/extensions/dashboard/reports.php' );
    include_once( CARIERA_PATH . '/inc/extensions/dashboard/views.php' );

    // Lib
    include_once( CARIERA_PATH . '/inc/lib/meta-box/meta-box-tabs/meta-box-tabs.php' );
    
    // Core
    if( class_exists('WP_Job_Manager') ) {
        include_once( CARIERA_PATH . '/inc/core/wp-job-manager/jobs.php' );
        include_once( CARIERA_PATH . '/inc/core/wp-job-manager/wp-job-manager-colors.php' );
        include_once( CARIERA_PATH . '/inc/core/wp-job-manager/wp-job-manager-maps.php' );
        include_once( CARIERA_PATH . '/inc/core/wp-job-manager/wp-job-manager-writepanels.php' );
        
        // Cariera Company Manager
        include_once( CARIERA_PATH . '/inc/core/wp-company-manager/company-manager.php');

        // Resume Manager
        if( class_exists('WP_Resume_Manager') ) {
            include_once( CARIERA_PATH . '/inc/core/wp-resume-manager/resumes.php' );
        }
    }
    
}

add_action( 'plugins_loaded', 'cariera_plugin_functions' );





/**
 * Adds image sizes
 * 
 * @since   1.4.8
 */
function cariera_image_size() {	
    add_image_size( 'cariera-avatar', 500, 500, true );
}

add_action( 'init', 'cariera_image_size' );





/**
 * Returns the main instance of Cariera Core to prevent the need to use globals.
 *
 * @since  1.4.3
 */
require_once( CARIERA_PATH . '/inc/class-cariera-core.php' );

function Cariera_Core() {
	return Cariera_Core::instance();
}

$GLOBALS['cariera_core'] = Cariera_Core();







// Loading Promotions
include( CARIERA_PATH . '/inc/core/promotions/promotions.php' );