<?php

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}



vc_map( array(
    'name'        => esc_html__( 'Button', 'cariera' ),
    'description' => esc_html__( '', 'cariera' ),
    'base'        => 'buttons',
    'class'       => '',
    'show_settings_on_create' => true,
    'weight' => 1,
    'category' => 'Cariera Custom',
    'group' => 'Cariera Custom',
    'content_element' => true,            
    "params" => array(
        array(
            "type" => "vc_link",
            "heading" => esc_html__("Link & Title", 'cariera'),
            "param_name" => "link",
        ),
        array(
            "type" => "dropdown",
            "class" => "",
            "heading" => esc_html__("Button Style", 'cariera'),
            "param_name" => "version",
            "value" => array(
                'Default'   => 'default',
                'Round'     => 'round',
                'Bordered'  => 'border',
            ),
        ),
        array(
            "type" => "dropdown",
            "class" => "",
            "heading" => esc_html__("Color", 'cariera'),
            "param_name" => "color",
            "value" => array(
                'Main Color' => 'btn-main',
                'Secondary Color' => 'btn-secondary',
            ),
        ),
        array(
            "type" => "dropdown",
            "class" => "",
            "heading" => esc_html__("Ripple Effect", 'cariera'),
            "param_name" => "effect",
            "value" => array(
                'Yes' => 'btn-effect',
                'No' => '',
            ),
            'dependency'        => array(
                'element'   => 'version',
                'value'     => array('default', 'round'),
            ),
        ),
        array(
            "type" => "dropdown",
            "class" => "",
            "heading" => esc_html__("Size", 'cariera'),
            "param_name" => "size",
            "value" => array(
                'Default' => '',
                'Large' => 'btn-large',
                'Small' => 'btn-small',
            ),
        ),
        array(
            "type" => "dropdown",
            "class" => "",
            "heading" => esc_html__("Full Width", 'cariera'),
            "param_name" => "fullwidth",
            "value" => array(
                'No' => '',
                'Yes' => 'btn-block',
            ),
        ),
        array(
            "type" => "dropdown",
            "class" => "",
            "heading" => esc_html__("Align", 'cariera'),
            "param_name" => "align",
            "value" => array(
                'Left' => 'left',
                'Center' => 'center',
                'Right' => 'right',
            ),
        ),
        array(
            "type" => "textfield",
            "heading" => esc_html__("Extra class name", 'cariera'),
            "param_name" => "el_class",
            "value" => "",
            "description" => esc_html__("You can add an extra class name to the button and refer to it in custom CSS.", 'cariera'),
        ),
    ),
) );

    
    
	/*
	Shortcode logic how it should be rendered
	*/

if ( !function_exists( 'buttons' ) ) {
	function buttons( $atts, $content = null ) {
		 $args = array(
            "version"   => "default",
            "color"     => "btn-main",
            "effect"    => "btn-effect",
            "size"      => "",
            "align"     => "",
            "fullwidth" => "",
            "link"      => "",
            "el_class"  => "",
        );
        extract(shortcode_atts($args, $atts));

        if( $version == 'default' ) {
            $version = '';
        } elseif( $version == 'round' ) {
            $version = 'btn-round ';
        }
        else {
            $version = 'btn-border ';
        }
        
        if( !empty( $size ) ) {
            $size = ' ' . $size;
        }
        
        if( !empty( $effect ) ) {
            $effect = ' ' . $effect;
        }
        
        if( !empty( $fullwidth ) ) {
            $fullwidth = ' ' . $fullwidth;
        }

        if( !empty( $el_class ) && !empty( $fullwidth ) ) {
            $el_class = ' ' . $el_class;
        }

        $link = ( $link == '||' ) ? '' : $link;
        $link = vc_build_link( $link );

        $use_link = false;
        
        if ( strlen( $link['url'] ) > 0 ) {
            $use_link = true;
            $a_href = $link['url'];
            $a_title = $link['title'];
            $a_target = strlen( $link['target'] ) > 0 ? $link['target'] : '_self';
        }

        $output = '<div class="text-' . $align . '">';

            if( !empty( $el_class ) ) {
                $output .= '<a class="btn ' . $version . $color . $size . $effect . $fullwidth . ' ' . $el_class . '"';
            } else {
                $output .= '<a class="btn ' . $version . $color . $size . $effect . $fullwidth . '"';
            }

            $output .= ' href="' . $a_href . '" target="' . $a_target . '">' . $a_title . '</a>';

        $output .= '</div>';
            	    
	    return $output;
	}
}

add_shortcode('buttons', 'buttons');