<?php

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}



vc_map( array(
    'name'                   => esc_html__( 'Job Search Form', 'cariera' ),
    'description'            => '',
    'base'                   => 'search_form',
    'class'                  => '',
    'category'               => 'Job Manager',
    'group'                  => 'Job Manager',          
    "params"                 => array(
        array(
            'type'          => 'dropdown',
            'heading'       => esc_html__( 'Search Layout', 'cariera' ),
            'param_name'    => 'search_style',
            'description'   => esc_html__( 'Choose the layout version that you want your search to have.', 'cariera' ),
            'value' => array(
                esc_html__( 'Style 1', 'cariera' )  => 'style-1',
                esc_html__( 'Style 2', 'cariera' )  => 'style-2',
            ),
        ),
        array(
            "type"          => "checkbox",
            "heading"       => esc_html__("Location", 'cariera'),
            "param_name"    => "location",
            "value"         => array(
                'Yes'   => 'yes',
            ),
        ),
        array(
            "type"          => "checkbox",
            "heading"       => esc_html__("Region", 'cariera'),
            "param_name"    => "region",
            "value"         => array(
                'Yes'   => 'yes',
            ),
        ),
        array(
            "type"          => "checkbox",
            "heading"       => esc_html__("Categories", 'cariera'),
            "param_name"    => "categories",
            "value"         => array(
                'Yes'   => 'yes',
            ),
        ),
    )
) );