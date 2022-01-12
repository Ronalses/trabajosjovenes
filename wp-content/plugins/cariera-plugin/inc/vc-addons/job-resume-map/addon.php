<?php

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}




vc_map( array(
    'name'           => esc_html__( 'Job - Resume Map', 'cariera' ),
    'description'    => esc_html__( 'Display all your jobs and resumes on a map.', 'cariera' ),
    'base'           => 'job_resume_map',
    'category'       => 'Job Manager',
    'group'          => 'Job Manager',  
    "params" => array(
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Content source', 'cariera' ),
            'param_name' => 'type',
            'description' => esc_html__( 'Choose maps or resumes (if applicable)', 'cariera' ),
            'value' => array(
                esc_html__( 'Job Listings', 'cariera' ) => 'job_listing',
                esc_html__( 'Companies', 'cariera' )    => 'company',
                esc_html__( 'Resumes', 'cariera' )      => 'resume',
            ),
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Map height', 'cariera' ),
            'value' => '450px',
            'param_name' => 'map_height',
            'description' => esc_html__( 'Insert the height of the map. For example: 400px.', 'cariera' )
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Custom Class', 'cariera' ),
            'value' => '',
            'param_name' => 'class',
            'description' => esc_html__( 'Add your extra class for your map.', 'cariera' )
        ),
    )
) );