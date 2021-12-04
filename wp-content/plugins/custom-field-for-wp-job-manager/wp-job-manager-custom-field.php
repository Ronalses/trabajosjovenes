<?php
/**
 * Plugin Name: Custom Field For WP Job Manager
 * Description: Custom field can be add in wp job manager like text field , select field
 * Version:     1.0
 * Author:      Gravity Master
 * License:     GPLv2 or later
 * Text Domain: cfwjm
 */

/* Stop immediately if accessed directly. */
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/* All constants should be defined in this file. */
if ( ! defined( 'CFWJM_PREFIX' ) ) {
	define( 'CFWJM_PREFIX', 'cfwjm' );
}
if ( ! defined( 'CFWJM_PLUGINDIR' ) ) {
	define( 'CFWJM_PLUGINDIR', plugin_dir_path( __FILE__ ) );
}
if ( ! defined( 'CFWJM_PLUGINBASENAME' ) ) {
	define( 'CFWJM_PLUGINBASENAME', plugin_basename( __FILE__ ) );
}
if ( ! defined( 'CFWJM_PLUGINURL' ) ) {
	define( 'CFWJM_PLUGINURL', plugin_dir_url( __FILE__ ) );
}

/* Auto-load all the necessary classes. */
if( ! function_exists( 'cfwjm_class_auto_loader' ) ) {
	
	function cfwjm_class_auto_loader( $class ) {
		
	 	$includes = CFWJM_PLUGINDIR . 'includes/' . $class . '.php';
		
		if( is_file( $includes ) && ! class_exists( $class ) ) {
			include_once( $includes );
			return;
		}
		
	}
}
spl_autoload_register('cfwjm_class_auto_loader');

new CFWJM_Admin();
new CFWJM_Frontend();
?>