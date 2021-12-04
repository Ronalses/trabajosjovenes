<?php

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

vc_map( array(
    'name'                      => esc_html__('Video Modal', 'cariera'),
    'description'               => esc_html__('Video modal', 'cariera'),
    'base'                      => 'video_modal',
    'class'                     => '',
    'category'                  => 'Cariera Custom',
    'group'                     => 'Cariera Custom',
    'params'                    => array(
        array(
            'type'          => 'textfield',
            'class'         => '',
            'heading'       => esc_html__('Video link', 'cariera'),
            'param_name'    => 'video_url',
            'value'         => '',
            'description'   => esc_html__('Enter link to video.', 'cariera'),
        ),
        array(
            'type'          => 'dropdown',
            'class'         => '',
            'heading'       => esc_html__('Cover image', 'cariera'),
            'param_name'    => 'video_image_source',
            'value'         => array(
                'Media library' => 'media_library',
                'External link' => 'external_link',
            ),
            'description'   => esc_html__('Select video preview image source.', 'cariera'),
            'save_always'   => true,
        ),
        array(
            'type'          => 'attach_image',
            'heading'       => esc_html__('Image', 'cariera'),
            'param_name'    => 'video_image',
            'description'   => esc_html__('Select image from media library.', 'cariera'),
            'dependency'    =>	array(
                'element'       => 'video_image_source',
                'value'         => array('media_library')
            ),
        ),
        array(
            'type'          => 'textfield',
            'class'         => '',
            'heading'       => esc_html__('Image external link', 'cariera'),
            'param_name'    => 'video_image_ext',
            'value'         => '',
            'description'   => esc_html__('Enter image external link.', 'cariera'),
            'dependency'    =>	array(
                'element'       => 'video_image_source',
                'value'         => array('external_link')
            ),
        ),
        array(
            'type'          => 'textfield',
            'class'         => '',
            'heading'       => esc_html__('Image size', 'cariera'),
            'param_name'    => 'ext_image_size',
            'value'         => '',
            'description'   => esc_html__('Enter image size in pixels. Example: 200x100 (Width x Height).', 'cariera'),
            'dependency'    =>	array(
                'element'       => 'video_image_source',
                'value'         => array('external_link')
            ),
        ),
        array(
            'type'          => 'dropdown',
            'class'         => '',
            'heading'       => esc_html__('Play button align', 'cariera'),
            'param_name'    => 'video_play_align',
            'value'         => array(
                'Center'        => 'play-button-center',
                'Left'          => 'play-button-left',
            ),
            'save_always'   => true,
        ),
        array(
            'type'          => 'dropdown',
            'class'         => '',
            'heading'       => esc_html__('Open video in', 'cariera'),
            'param_name'    => 'video_location',
            'value'         => array(
                'Modal'         => '',
                'New window'    => 'video_location_new',
            ),
            'save_always'   => true,
        ),
        array(
            'type'          => 'textfield',
            'class'         => '',
            'heading'       => esc_html__('Extra class name', 'cariera'),
            'param_name'    => 'video_extra_class',
            'value'         => '',
            'description'   => esc_html__('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'cariera')
        ),
    )
));





/*
Shortcode logic how it should be rendered
*/
if ( !function_exists( 'video_modal' ) ) {
    function video_modal( $atts, $content = null ) {
        $args = array(
            'video_title'           => '',
            'video_url'             => '',
            'video_image_source'    => '',
            'video_image'           => '',
            'video_image_ext'       => '',
            'ext_image_size'        => '',
            'video_play_align'      => '',
            'video_location'        => '',
            'video_extra_class'     => '',
        );
	        
		extract(shortcode_atts($args, $atts));
        
        
        $image = wpb_getImageBySize($params = array(
            'post_id'       => NULL,
            'attach_id'     => $video_image,
            'thumb_size'    => 'full',
            'class'         => ''
        ));
        
        $default_src = vc_asset_url( 'vc/no_image.png' );
        
        $video_id   = uniqid();
        $vheight    = !empty( $video_height ) ? $video_height : '400px';
        
        
        if ( $video_image_source == 'external_link' ) {
            
            $dimensions     = vcExtractDimensions( $ext_image_size );
            $hwstring       = $dimensions ? image_hwstring( $dimensions[0], $dimensions[1] ) : '';

            $video_image_ext = $video_image_ext ? esc_attr( $video_image_ext ) : $default_src;

            $image_media   .= '<img src="' . $video_image_ext . '" ' . $hwstring . ' />';
            $image_html     = wp_get_attachment_url( $video_image );
        
        } else {
          $image_media  = $image['thumbnail'];
          $image_html   = $video_image_ext;
        }
        
        $output = '<div class="video-container ' . $video_play_align . ' ' . $video_extra_class . '">';
        $output .= $image_media;
        
        if ($video_location == 'video_location_new')  {
          $output .= '<a href="' . $video_url . '" target="_blank">';
        } else {
            $output .= '<a href="' . $video_url . '" class="popup-video">';
        }
        
        $output .= '<span class="play-video"><span class="fas fa-play"></span></span></a></div>';

        
        return $output;
        
    }
}

add_shortcode('video_modal', 'video_modal');