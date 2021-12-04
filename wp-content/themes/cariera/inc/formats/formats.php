<?php

/**
*
* @package Cariera
*
* @since 1.0
* 
* ========================
* POST FORMAT FUNCTIONS
* ========================
*     
**/



function cariera_format_scripts() {

	/* Add Theme Styles */
	wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/inc/formats/assets/format-style.css', array(), '1' );

	/* Include Format Scripts */
	wp_enqueue_script( 'format-script', get_template_directory_uri() . '/inc/formats/assets/format-script.js', array( 'jquery' ), '1', true );
    

	$post_formats = get_theme_support('post-formats');
	if (in_array('video', $post_formats[0])) {
		add_action('save_post', 'cariera_format_video_save_post');
	}
	if (in_array('audio', $post_formats[0])) {
		add_action('save_post', 'cariera_format_audio_save_post');
	}
    if (in_array('quote', $post_formats[0])) {
		add_action('save_post', 'cariera_format_quote_save_post');
	}
    if (in_array('gallery', $post_formats[0])) {
		add_action('save_post', 'cariera_format_gallery_save_post');
	}

}
add_action( 'admin_init', 'cariera_format_scripts' );




// Save Video Format
function cariera_format_video_save_post($post_id) {
	if (!defined('XMLRPC_REQUEST') && isset($_POST['_format_video_embed'])) {
		update_post_meta($post_id, '_format_video_embed', $_POST['_format_video_embed']);
	}
}



// Save Audio Format
function cariera_format_audio_save_post($post_id) {
	if (!defined('XMLRPC_REQUEST') && isset($_POST['_format_audio_embed'])) {
		update_post_meta($post_id, '_format_audio_embed', $_POST['_format_audio_embed']);
	}
}



// Save Quote Format
function cariera_format_quote_save_post($post_id) {
	if (!defined('XMLRPC_REQUEST') && isset($_POST['_format_quote_source_content'])) {
		update_post_meta($post_id, '_format_quote_source_content', $_POST['_format_quote_source_content']);
	}
}





function cariera_save_formats() {
    global $post;
    
    // check if post is exists
    if ( isset($post) ) :

        /* Get the post type object. */
        $post_type = get_post_type_object( $post->post_type );

        /* Check if the current user has permission to edit the post. */
        if ( !current_user_can( $post_type->cap->edit_post, $post->ID ) ) :
            return $post->ID;
        endif;

        $custom_meta_fields = array(
            '',
        );

        foreach ($custom_meta_fields as $custom_meta_field) {
            if (isset($_POST[$custom_meta_field])):
                update_post_meta($post->ID, $custom_meta_field, htmlspecialchars(stripslashes($_POST[$custom_meta_field])));
            else:
                if (isset($post->ID) && isset($custom_meta_field) && $custom_meta_field != '') {
                    delete_post_meta($post->ID, $custom_meta_field);
                }
            endif;
        }
    
    endif; // end if check if post is exists
}
add_action('save_post', 'cariera_save_formats');





function cariera_format_boxes_area($post_type) {
	if (post_type_supports($post_type, 'post-formats') && current_theme_supports('post-formats')) {
		add_action('edit_form_after_title', 'cariera_format_meta_boxes');
	}
}
add_action('edit_form_after_title', 'cariera_format_meta_boxes');





function cariera_format_meta_boxes() {
	$post_formats = get_theme_support('post-formats');
    
	if (!empty($post_formats[0]) && is_array($post_formats[0])) {
		global $post;
		$current_post_format = get_post_format(get_the_id());

		if (!empty($current_post_format) && !in_array($current_post_format, $post_formats[0])) {
			array_push($post_formats[0], get_post_format_string($current_post_format));
		}
		array_unshift($post_formats[0], 'standard');
		$post_formats = $post_formats[0];

		$formats = array(
			'link',
			'quote',
			'video',
			'gallery',
			'audio',
		);

		foreach ($formats as $format) {
			if (in_array($format, $post_formats)) {
				get_template_part( '/inc/formats/boxes/format', $format );
			}
		}
	}
}