<?php
/**
*
* @package Cariera
*
* @since 1.0.0
* 
* ========================
* ALL WOOCOMMERCE FUNCTIONS
* ========================
*     
**/


    
 

/**
 *  WooCommerce Style Enqueue
 *
 * @since  1.0
 */

function woocommerce_style() {
    wp_enqueue_style( 'woocommerce', get_template_directory_uri() . '/assets/dist/css/woocommerce.css'); 
}

add_action( 'wp_enqueue_scripts', 'woocommerce_style' );





/**
 * AJAXIFY the shopping page add to cart button.
 *
 * @since 1.0.0
 */
function woocommerce_header_add_to_cart_fragment( $fragments ) {
    if ( WC()->cart->get_cart_contents_count() < 1 ) {
        $fragments['span.notification-count.cart-count'] = '<span class="notification-count cart-count counter-hidden"></span>';
    } else {
        $fragments['span.notification-count.cart-count'] = sprintf(
            '<span class="notification-count cart-count">%s</span>',
            number_format_i18n( WC()->cart->get_cart_contents_count() )
        );
    }

    return $fragments;
}

add_filter('woocommerce_add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment');





/**
 * Removing Related Products from Customizer.
 *
 * @since 1.0.0
 */

if ( false == cariera_get_option('cariera_related_products', 'true') ) {
    remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
}





/**
 * Remove WooCommerce Default Actions
 *
 * @since 1.3.4
 */

remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );





/**
 * Remove Sidebar from Single Product Page
 *
 * @since 1.3.4
 */

function cariera_remove_sidebar_product_pages() {
    if ( is_product() ) {
        remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
    }
}

add_action( 'wp', 'cariera_remove_sidebar_product_pages' );





/**
 * Add sharing functionality via function
 *
 * @since 1.3.4
 */

function cariera_shop_product_share() {
    if ( cariera_get_option( 'cariera_product_share' ) ) {
        if ( function_exists ( 'cariera_share_media' ) ) {
            echo cariera_share_media();
        }
    } 
}

add_action( 'woocommerce_single_product_summary', 'cariera_shop_product_share', 70 );





/**
 * Hide WooCommerce Sidebar when layout is set to fullwidth
 *
 * @since 1.4.0
 */

function cariera_wc_sidebar() {
    $shop_layout = cariera_get_option( 'cariera_shop_layout' );
    
    if ( $shop_layout == 'fullwidth') {
        remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
    }
}

add_action( 'wp', 'cariera_wc_sidebar' );