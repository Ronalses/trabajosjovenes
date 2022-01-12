<?php

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}



vc_map(array(
    "name"                      => esc_html__("Counter Up", "cariera"),
    "base"                      => "counterup",
    "description"               => esc_html__("Stat counter with animation", "cariera"),
    "as_parent"                 => array('only' => 'counterup_item'),
    "content_element"           => true,
    "show_settings_on_create"   => true,
    "weight"                    => 1,
    "category"                  => 'Cariera Custom',
    "group"                     => 'Cariera Custom',
    "holder"                    => "div",
    "params"                    => array(
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
    "name"                      => esc_html__("Counter Up Item", "cariera"),
    "base"                      => "counterup_item",
    "content_element" => true,
    "as_child" => array('only'  => 'logo_carousel'),
    "show_settings_on_create"   => true,
    "holder"                    => "div",
    "params"                    => array(
        array(
            "type"          => "dropdown",
            "heading"       => esc_html__("Value", "cariera"),
            "param_name"    => "value",
            "value"         => array(
                "Custom number"     => "custom",
                "Total jobs"        => "jobs",
                "Total resumes"     => "resumes",
                "Registered users"  => "users",
                "Companies"         => "companies",
            ),
        ),
        array(
            "type"              => "textfield",
            "heading"           => esc_html__('Number', 'cariera'),
            "param_name"        => "number",
            'dependency'        => array(
                'element'   => 'value',
                'value'     => array('custom')
            ),
        ),
        array(
            "type"              => "textfield",
            "heading"           => esc_html__("Title", "cariera"),
            "param_name"        => "title"
        ),
    ),
));


if (class_exists('WPBakeryShortCodesContainer')) {
    class WPBakeryShortCode_Counterup extends WPBakeryShortCodesContainer {   
    }
}

if (class_exists('WPBakeryShortCode')) {
    class WPBakeryShortCode_Counterup_Item extends WPBakeryShortCode {   
    }
}




if (!function_exists('counterup')) {
	function counterup( $atts, $content = null) {
 
	    extract( shortcode_atts( array(
	        "el_class" => ""
	    ), $atts ) );

	    if( !empty( $el_class ) ) {
		    $output = '<div class="counter-container ' . $el_class . '">';
	    } else {
		    $output = '<div class="counter-container">';
	    }

	    $output .= wpb_js_remove_wpautop($content) . '</div>';

	    return $output;

	}

}

add_shortcode('counterup', 'counterup');





if (!function_exists('counterup_item')) {
	function counterup_item( $atts, $content = null) {

		global $wpdb;

	    extract( shortcode_atts( array(
	        "value"    => "",
	        "number"   => "",
	        "title"    => "",
	        "el_class" => ""
        ), $atts ) );
        

	    switch ( $value ) {
	    	case 'jobs':
				$count  = wp_count_posts( 'job_listing' );
				$number = $count->publish;
	    		break;

	    	case 'resumes':
	    		if( class_exists( 'WP_Resume_Manager' ) ) {
					$count  = wp_count_posts( 'resume' );
					$number = $count->publish;
				} else {
					$number = 0;					
				}
	    		break;
	    	
	    	case 'companies':
                $count  = wp_count_posts( 'company' );
				$number = $count->publish;
				break; 

	    	case 'users':
				$number = count_users();
				$number = $number['total_users'];
				break;
	    }

        
	    if( !empty( $el_class ) ) {
		    $output = '<div class="counter ' . $el_class . '">';
	    } else {
		    $output = '<div class="counter">';
	    }

        if( !empty( $number ) ) {
            $output .= '<h4><span class="counter-number" data-from="0" data-to="' . $number . ' ">0</span></h4>';
        } else {
            $output .= '<div class="number">0</div>';	    		
        }

        if( !empty( $title ) ) {
            $output .= '<div class="description"><h4>' . $title . '</h4></div>';
        }

	    $output .= '</div>';

        
	    return $output;
	}

}

add_shortcode('counterup_item', 'counterup_item');