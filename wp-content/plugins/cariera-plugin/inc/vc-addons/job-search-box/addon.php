<?php

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}



vc_map( array(
    'name'                   => esc_html__( 'Job Search Box', 'cariera' ),
    'description'            => esc_html__( '', 'cariera' ),
    'base'                   => 'job_search_form_box',
    'class'                  => '',
    'category'               => 'Job Manager',
    'group'                  => 'Job Manager',          
    "params"                 => array(
        array(
            "type"			=> "textfield",
            "admin_label"	=> false,
            "class"			=> "",
            "heading"		=> esc_html__( "Search Box Title", 'cariera' ),
            "param_name"	=> "title",
            "value"			=> "",
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
if ( !function_exists( 'job_search_form_box' ) ) {
	function job_search_form_box( $atts, $content = null ) {
		 extract(shortcode_atts(array(
            'title'       => '',
		    'location'    => '',
            'region'      => '',
		    'categories'  => '',
		), $atts));

        
        
        // keywords input 
        $keywords = '<div class="col-md-12 search-keywords">
            <label for="search-keywords">' . esc_html__( 'Keywords', 'cariera') . '</label>
            <input type="text" name="search_keywords" id="search_keywords" placeholder="' . esc_attr__('Keywords', 'cariera') . '" value="" autocomplete="off">
        </div>';

        
        // Location Field
        if( !empty($location) ) {
            $location = '<div class="col-md-12 search-location mt15">
                <label for="search-location">' . esc_html__( 'Location', 'cariera') . '</label>
                <input type="text" name="search_location" id="search_location" placeholder="' . esc_attr__('Location', 'cariera') . '" value="">
                <div class="geolocation"><i class="geolocate"></i></div>
            </div>';
        }
        
        // Regions Field
        if ( class_exists('Astoundify_Job_Manager_Regions') ) {
            if( !empty($region) ) {

                ob_start(); ?>

                    <div class="col-md-12 search-region mt15">
                        <label for="search_region"><?php esc_html_e( 'Region', 'cariera'); ?></label>
                        <?php 
                        wp_dropdown_categories( apply_filters( 'job_manager_regions_dropdown_args', array(
                            'show_option_all'   => esc_html__( 'All Regions', 'cariera' ),
                            'hierarchical'      => true,
                            'orderby'           => 'name',
                            'taxonomy'          => 'job_listing_region',
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

            $show_category_multiselect = get_option( 'job_manager_enable_default_category_multiselect', false );

            ob_start(); ?>

                <div class="col-md-12 search-categories mt15">
                    <label for="search_categories"><?php esc_html_e( 'Categories', 'cariera'); ?></label>
    
                    <?php
                    cariera_job_manager_dropdown_category( array( 
                        'taxonomy'          => 'job_listing_category',
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
    
    
        // Form Output
		$output = '<form class="job-search-form-box row" method="get" action="' . esc_url(get_permalink(get_option('job_manager_jobs_page_id'))) . '">
            <div class="col-md-12 form-title">
                <h4 class="title">' . $title . '</h4>
            </div>' . $keywords . $location . $region . $categories . '
            <div class="col-md-12 search-submit mt15 mb30">
                <button type="submit" class="btn btn-main btn-effect"><i class="fas fa-search"></i>' . esc_attr__('search', 'cariera') . '</button>
            </div></form>';

		return $output;
		
	}
}

add_shortcode('job_search_form_box', 'job_search_form_box');