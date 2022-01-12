<?php

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}



vc_map( array(
    'name'                       => esc_html__( 'Pricing Table', 'cariera' ),
    'description'                => esc_html__( 'Fancy pricing tables', 'cariera' ),
    'base'                       => 'pricing_table',
    'class'                      => '',
    'show_settings_on_create'    => true,
    'weight'                     => 1,
    'category'                   => 'Cariera Custom',
    'group'                      => 'Cariera Custom',
    'content_element'            => true,            
    "params"                     => array(
        array(
            "type"          => "dropdown",
            "class"         => "",
            "heading"       => esc_html__("Pricing Table Version", 'cariera'),
            "param_name"    => "pricing_version",
            "value"         => array(
                'Version 1'     => 'version1',
                'Version 2'     => 'version2',
            ),
        ),
        array(
            "type"          => "textfield",
            "heading"       => esc_html__("Title", 'cariera'),
            "param_name"    => "title",
            "value"         => "",
        ),
        array(
            "type"          => "textfield",
            "heading"       => esc_html__("Price", 'cariera'),
            "param_name"    => "price",
            "value"         => "",
        ),
        array(
            "type"          => "textfield",
            "heading"       => esc_html__("Description", 'cariera'),
            "param_name"    => "description",
            "value"         => "",
            'dependency'    => array(
                'element'       => 'pricing_version',
                'value'         => array('version2')
            ),
        ),
        array(
            "type"			=> "attach_image",
            "admin_label"	=> false,
            "class"			=> "",
            "heading"		=> esc_html__( "Background Image", 'cariera' ),
            "param_name"	=> "bg_img",
            "value"			=> "",
            "description"	=> esc_html__( "Select a background image for your pricing table.", 'cariera' ),
            'dependency'    => array(
                'element'       => 'pricing_version',
                'value'         => array('version2')
            ),
        ),
        array(
            'type'      => 'colorpicker',
            'param_name' => 'overlay_color',
            'heading'   => esc_html__('Pricing Header Overlay Color', 'cariera'),
            'std'       => '',
            'save_always'  => true,
            'dependency'    => array(
                'element'       => 'pricing_version',
                'value'         => array('version2')
            ),
        ),
        array(
            "type"          => "textarea_html",
            "heading"       => esc_html__("List", 'cariera'),
            "param_name"    => "content",
            "value"         => '<ul><li>List Item</li><li>List Item</li><li>List Item</li><li class="disable">List Item</li><li class="disable">List Item</li></ul>',
        ),
        array(
            'type'       => 'vc_link',
            'heading'    => esc_html__('Button', 'cariera'),
            'param_name' => 'button'
        ),
        array(
            "type"          => "checkbox",
            "heading"       => esc_html__("Highlight", 'cariera'),
            "param_name"    => "highlight",
            "value"         => "",
        ),
    ),
) );

    

/*
Shortcode logic how it should be rendered
*/
if ( !function_exists( 'pricing_table' ) ) {
    function pricing_table( $atts, $content = null ) {
		 $args = array(
            "pricing_version"   => "version1",
            "title"             => "",
            "price"             => "",
            "description"       => "",
            "bg_img"            => "",
            "overlay_color"     => "",
            "highlight"         => false,
            "button"            => "",
        );
	        
		extract(shortcode_atts($args, $atts));
        
        
        if( !$highlight ) {
            $highlight = '';
        } else {
            $highlight = 'pricing-table-featured';
        }
        
        $button = vc_build_link( $button );
        
        if ( $button['target'] == '' ) {
            $button_target = '_self';
        } else {
            $button_target = $button['target'];
        }
        
        
        $output = '';
        
        if( $pricing_version == 'version1' ) {
            $output .= '<div class="pricing-table ' . esc_attr($highlight) . ' shadow-hover">';
            $output .= '<div class="pricing-header"><h2>' . $title . '</h2></div>';
            $output .= '<div class="pricing"><span class="amount">' . $price . '</span></div>';
        } else {
            $output .= '<div class="pricing-table2 ' . esc_attr($highlight) . '">';
            
            if ( $bg_img ) {
                $img_src = wp_get_attachment_image_src( $bg_img, 'full' );
                $output .= '<div class="pricing-header" style="background-image: url(' . $img_src[0] . ');">';
            } else {
                $output .= '<div class="pricing-header">';
            }
            $output .= '<span class="title">' . $title . '</span>';
            $output .= '<div class="amount">' . $price . '</div>';
            $output .= '<p class="description">' . $description . '</p>';

            if ( !empty($overlay_color) ) {
                $output .= '<div class="overlay" style="background: ' . $overlay_color . ' "></div>';
            }

            if( $highlight ) {
                $output .= '<div class="featured"><i class="far fa-star"></i></div>';
            }
            $output .= '</div>';    
        } 
        
        $output .= '<div class="pricing-body">';
        $output .= $content;
        $output .= '</div>';
        
        $output .= '<div class="pricing-footer">';
        
        
        if( $pricing_version == 'version1' ) {
            $output .= '<a href="' . esc_url( $button['url'] ) . '" target="' . $button_target . '" class="btn btn-main btn-effect">' . $button['title'] . '</a>';
        } else {
            $output .= '<a href="' . esc_url( $button['url'] ) . '" target="' . $button_target . '">' . $button['title'] . '</a>';
        }

        $output .= '</div></div>'; 
            	    
	    return $output;
	}
}

add_shortcode('pricing_table', 'pricing_table');