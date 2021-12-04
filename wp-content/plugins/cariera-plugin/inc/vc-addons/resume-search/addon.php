<?php

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}



vc_map( array(
    'name'                   => esc_html__( 'Resume Search Form', 'cariera' ),
    'description'            => '',
    'base'                   => 'resume_search_form',
    'class'                  => '',
    'category'               => 'Resume Manager',
    'group'                  => 'Resume Manager',          
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




/*
Shortcode logic how it should be rendered
*/
if ( !function_exists( 'resume_search_form' ) ) {
	function resume_search_form( $atts, $content = null ) {
		 extract(shortcode_atts(array(
            'search_style'  => 'stlye-1',
		    'location'      => '',
            'region'        => '',
		    'categories'    => '',
        ), $atts));
        

		// Location field
		if( !empty($location) ) {
			$location = '<div class="search-location"><input type="text" id="search_location" name="search_location" placeholder="' . esc_html__("Location", "cariera") . '"><div class="geolocation"><i class="geolocate"></i></div></div>';
		}
        
        
        // Regions Field
        if ( class_exists('Astoundify_Job_Manager_Regions') ) {
            if( !empty($region) ) {

                ob_start(); ?>

                    <div class="search-region">
                        <?php 
                        wp_dropdown_categories( apply_filters( 'job_manager_regions_dropdown_args', array(
                            'show_option_all'   => esc_html__( 'All Regions', 'cariera' ),
                            'hierarchical'      => true,
                            'orderby'           => 'name',
                            'taxonomy'          => 'resume_region',
                            'name'              => 'search_region',
                            'class'             => 'search_region',
                            'hide_empty'        => 0,
                            'selected'          => isset( $atts[ 'selected_region' ] ) ? $atts[ 'selected_region' ] : ''
                        ) ) ); ?>
                    </div>

                <?php
                $region = ob_get_clean();
            }
        } else {
            $region = '';
        }

        
		// Categories dropdown
		if( !empty($categories) ) {
			ob_start(); ?>

                <div class="search-categories">
                    
                    <?php
                    cariera_job_manager_dropdown_category( array( 
                        'taxonomy'          => 'resume_category',
                        'hierarchical'      => 1, 
                        'show_option_all'   => esc_html__( 'Any category', 'cariera' ), 
                        'name'              => 'search_category',
                        'id'                => 'search_category',
                        'orderby'           => 'name', 
                        'selected'          => '', 
                        'multiple'          => false 
                    ) );
                    ?>
                </div>

            <?php
			$categories = ob_get_clean();
        }
        
        
        $search_result = '<div class="search-results"><div class="search-loader"><span></span></div><div class="job-listings"></div></div>';
        
		// Form
		$output = '<form method="GET" action="' . get_permalink(get_option('resume_manager_resumes_page_id')) . '" class="resume-search-form ' . $search_style . '">
			<div class="search-keywords"><input type="text" id="search_keywords" name="search_keywords" placeholder="' . esc_html__("Keywords", "cariera") . '" autocomplete="off">' . $search_result . '</div>' . $location . $region . $categories . '<div class="search-submit"><input type="submit" class="btn btn-main btn-effect" value="'. esc_html__( "Search", "cariera" ) . '"></div>
		</form>';

		return $output;
	}
}

add_shortcode( 'resume_search_form', 'resume_search_form' );