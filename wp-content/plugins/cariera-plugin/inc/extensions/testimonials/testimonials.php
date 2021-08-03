<?php 
/**
*
* @package Cariera
*
* @since 1.2.7
* 
* ========================
* TESTIMONIALS CUSTOM POST TYPE
* ========================
*     
**/


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}



/*
 * Testimonials Post type
 *
 * @since 1.2.7
 */
function cariera_testimonial_cpt() {
  // Testimonial Labels
    $labels = array(
        'name' => _x( 'Testimonials', 'post type general name', 'cariera' ),
        'singular_name' => _x( 'Testimonial', 'post type singular name', 'cariera' ),
        'add_new' => _x( 'Add New', 'testimonial', 'cariera' ),
        'add_new_item' => sprintf( esc_html__( 'Add New %s', 'cariera' ), esc_html__( 'Testimonial', 'cariera' ) ),
        'edit_item' => sprintf( esc_html__( 'Edit %s', 'cariera' ), esc_html__( 'Testimonial', 'cariera' ) ),
        'new_item' => sprintf( esc_html__( 'New %s', 'cariera' ), esc_html__( 'Testimonial', 'cariera' ) ),
        'all_items' => sprintf( esc_html__( 'All %s', 'cariera' ), esc_html__( 'Testimonials', 'cariera' ) ),
        'view_item' => sprintf( esc_html__( 'View %s', 'cariera' ), esc_html__( 'Testimonial', 'cariera' ) ),
        'search_items' => sprintf( esc_html__( 'Search %a', 'cariera' ), esc_html__( 'Testimonials', 'cariera' ) ),
        'not_found' =>  sprintf( esc_html__( 'No %s Found', 'cariera' ), esc_html__( 'Testimonials', 'cariera' ) ),
        'not_found_in_trash' => sprintf( esc_html__( 'No %s Found In Trash', 'cariera' ), esc_html__( 'Testimonials', 'cariera' ) ),
        'parent_item_colon' => '',
        'menu_name' => esc_html__( 'Testimonials', 'cariera' )
    );

    
    $single_slug = apply_filters( 'cariera_testimonials_single_slug', _x( 'testimonial', 'single post url slug', 'cariera' ) );
	$archive_slug = apply_filters( 'cariera_testimonials_archive_slug', _x( 'testimonials', 'post archive url slug', 'cariera' ) );
    
    // Testimonial args
    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array( 'slug' => $single_slug, 'with_front' => false ),
        'capability_type' => 'post',
        'has_archive' => $archive_slug,
        'hierarchical' => false,
        'supports' => array( 'title', 'author' ,'editor', 'thumbnail', 'page-attributes', 'publicize', 'wpcom-markdown' ),
        'menu_position' => 5,
        'menu_icon' => 'dashicons-testimonial'
    );

    // Register Testimonial Custom Post Type
    register_post_type( 'testimonial', apply_filters( 'cariera_testimonials_post_type_args', $args ) );
}

add_action( 'init', 'cariera_testimonial_cpt' );




/*
 * Add custom columns for the "manage" screen of this post type.
 *
 * @since 1.2.7
 */
function cariera_register_custom_testimonial_columns ( $column_name, $id ) {
    global $wpdb, $post;

    $meta = get_post_custom( $id );

    switch ( $column_name ) {

        case 'image':
            $value = '';

            $value = cariera_testimonial_get_image( $id, 40 );

            echo $value;
        break;

        default:
        break;

    }
}




/*
 * Add custom column headings for the "manage" screen of this post type.
 *
 * @since 1.2.7
 */
function cariera_register_custom_testimonial_column_headings ( $defaults ) {
    $new_columns = array( 'image' => esc_html__( 'Image', 'cariera' ) );

    $last_item = '';

    if ( isset( $defaults['date'] ) ) { unset( $defaults['date'] ); }

    if ( count( $defaults ) > 2 ) {
        $last_item = array_slice( $defaults, -1 );

        array_pop( $defaults );
    }
    $defaults = array_merge( $defaults, $new_columns );

    if ( $last_item != '' ) {
        foreach ( $last_item as $k => $v ) {
            $defaults[$k] = $v;
            break;
        }
    }

    return $defaults;
}


// add_filter & add_action for the testimonials
if ( is_admin() ) {
    global $pagenow;
    
    if ( $pagenow == 'edit.php' && isset( $_GET['post_type'] ) && esc_attr( $_GET['post_type'] ) == 'testimonial' ) {
        add_filter( 'manage_edit-testimonial_columns', 'cariera_register_custom_testimonial_column_headings', 10, 1 );
        add_action( 'manage_posts_custom_column', 'cariera_register_custom_testimonial_columns', 10, 2 );
    }
}




/*
 * Get the image for the given ID. If no featured image, check for Gravatar e-mail.
 *
 * @since 1.2.7
 */
function cariera_testimonial_get_image ( $id, $size ) {
    $response = '';

    if ( has_post_thumbnail( $id ) ) {
        // If not a string or an array, and not an integer, default to 150x9999.
        if ( ( is_int( $size ) || ( 0 < intval( $size ) ) ) && ! is_array( $size ) ) {
            $size = array( intval( $size ), intval( $size ) );
        } elseif ( ! is_string( $size ) && ! is_array( $size ) ) {
            $size = array( 50, 50 );
        }
        $response = get_the_post_thumbnail( intval( $id ), $size, array( 'class' => 'avatar' ) );
    } else {
        $gravatar_email = get_post_meta( $id, '_gravatar_email', true );
        if ( '' != $gravatar_email && is_email( $gravatar_email ) ) {
            $response = get_avatar( $gravatar_email, $size );
        }
    }

    return $response;
}