<?php

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}



vc_map(array(
    "name"                      => esc_html__("Logo Carousel", "cariera"),
    "base"                      => "logo_carousel",
    "description"               => esc_html__("Show brand logos in a nice carousel", "cariera"),
    "as_parent"                 => array('only' => 'logo_item'),
    "content_element"           => true,
    "show_settings_on_create"   => true,
    "weight"                    => 1,
    "category"                  => 'Cariera Custom',
    "group"                     => 'Cariera Custom',
    "holder"                    => "div",
    "params"                    => array(
        array(
            "type"          => "checkbox",
            "heading"       => esc_html__("Autoplay", "cariera"),
            "param_name"    => "autoplay",
            "default"       => false,
            "description"   => '',            
            "value"         => array(
                'Enable'   => 'true',
            ),
        ),
        array(
            "type"          => "textfield",
            "heading"       => esc_html__("Autoplay Speed", "cariera"),
            "param_name"    => "autoplay_speed",
            "default"       => '1500',
            "description"   => esc_html__("Add the speed of the slider in milliseconds like 5000, which will be 5 seconds.", "cariera"),
            'dependency'        => array(
                'element'   => 'autoplay',
                'value'     => [ 'true' ]
            ),
        ),
        array(
            "type"          => "textfield",
            "heading"       => esc_html__("Extra class name", "cariera"),
            "param_name"    => "el_class",
            "description"   => esc_html__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "cariera")
        ),
    ),
    "js_view"                   => 'VcColumnView'
));

vc_map(array(
    "name"                      => esc_html__("Logo Item", "cariera"),
    "base"                      => "logo_item",
    "content_element"           => true,
    "as_child"                  => array(
        'only'      => 'logo_carousel'
    ),
    "show_settings_on_create"   => true,
    "holder"                    => "div",
    "params"                    => array(
        array(
            "type"          => "attach_image",
            "heading"       => esc_html__('Logo', 'cariera'),
            "param_name"    => "logo"
        ),
        array(
            "type"          => "vc_link",
            "heading"       => esc_html__("URL", "cariera"),
            "param_name"    => "link",
            "description"   => esc_html__("Optional.", "cariera")
        ),
    ),
));


if (class_exists('WPBakeryShortCodesContainer')) {
    class WPBakeryShortCode_Logo_Carousel extends WPBakeryShortCodesContainer {   
    }
}

if (class_exists('WPBakeryShortCode')) {
    class WPBakeryShortCode_Logo_Item extends WPBakeryShortCode {   
    }
}




if ( !function_exists( 'logo_carousel' ) ) {
    function logo_carousel($atts, $content = null) {
 
		extract(shortcode_atts(array(
            'autoplay'       => '',
            'autoplay_speed' => '',
		    'el_class'       => '',
		), $atts));

		if( !empty($el_class) ) { 
            $el_class = ' ' . $el_class; 
        }

        if( !empty($autoplay) ) {
            $autoplay = 'true';
            $autoplay_speed = 'data-autoplay-speed="' . $autoplay_speed . '"';
        }

		$output = '<div class="logo-carousel' . $el_class . '" data-autoplay="' . $autoplay . '" ' . $autoplay_speed . '>' . wpb_js_remove_wpautop($content) . '</div>';

		return $output;

	}
}

add_shortcode('logo_carousel', 'logo_carousel');




/*
Shortcode logic how it should be rendered
*/
if ( !function_exists( 'logo_item' ) ) {
    function logo_item($atts, $content = null) {
 
		extract(shortcode_atts(array(
		    'logo' => '',
		    'link' => '',
		), $atts));

		$link = ( $link == '||' ) ? '' : $link;
        $link = vc_build_link( $link );

		if ( strlen( $link['url'] ) > 0 ) {
			$link_open = '<a href="' . $link['url'] . '" title="' . $link['title'] . '" target="' . $link['target'] . '">';
			$link_close = '</a>';
		} else {
			$link['title'] = '';
			$link_open = $link_close = '';
		}

		$output = '<div>';

		if( !empty($logo) ) {
			$photo = wp_get_attachment_image_src( $logo, 'full' );
			$output .= $link_open . '<img src="' . $photo[0] . '" alt="' . $link['title'] . '">' . $link_close;
		}

		$output .= '</div>';

		return $output;

	}
}

add_shortcode('logo_item', 'logo_item');