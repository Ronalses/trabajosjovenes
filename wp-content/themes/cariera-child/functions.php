<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if ( !function_exists( 'chld_thm_cfg_locale_css' ) ):
    function chld_thm_cfg_locale_css( $uri ){
        if ( empty( $uri ) && is_rtl() && file_exists( get_template_directory() . '/rtl.css' ) )
            $uri = get_template_directory_uri() . '/rtl.css';
        return $uri;
    }
endif;
add_filter( 'locale_stylesheet_uri', 'chld_thm_cfg_locale_css' );
         
if ( !function_exists( 'child_theme_configurator_css' ) ):
    function child_theme_configurator_css() {
        wp_enqueue_style( 'chld_thm_cfg_child', trailingslashit( get_stylesheet_directory_uri() ) . 'style.css', array( 'bootstrap','cariera-select2','font-awesome-5','simple-line-icons','iconsmind','cariera-style','cariera-style','cariera-frontend' ) );
    }
endif;
add_action( 'wp_enqueue_scripts', 'child_theme_configurator_css', 10 );

// END ENQUEUE PARENT ACTION

//Manifest y SW
function inc_manifest_link()
{
    echo '<link rel="manifest" href="' . get_stylesheet_directory_uri() . '/manifest.json">';
}

function register_my_service_worker()
{
    echo '<script>if("serviceWorker" in navigator) {
  navigator.serviceWorker
           .register("./sw.js")
           .then(function() { console.log("Service Worker registrado") })
           .catch(function() { console.log("Service Worker no se puede registrar") });
}</script>';
}
//manifest file
add_action('wp_head', 'inc_manifest_link');

// Creates the link tag

add_action('wp_head', 'register_my_service_worker');
do_action('wp-head');