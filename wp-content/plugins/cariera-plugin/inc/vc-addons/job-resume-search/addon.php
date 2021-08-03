<?php

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}



vc_map( array(
    'name'                   => esc_html__( 'Job - Resume Tab Search', 'cariera' ),
    'description'            => esc_html__( '', 'cariera' ),
    'base'                   => 'job_resume_tab_search',
    'class'                  => '',
    'category'               => 'Job Manager',
    'group'                  => 'Job Manager',          
    "params"                 => array(
        array(
            "type"          => "dropdown",
            "class"         => "",
            "heading"       => esc_html__("Layout Color", 'cariera'),
            "param_name"    => "layout",
            "value"         => array(
                'light-skin'        => esc_html__( 'Light', 'cariera' ), 
                'dark-skin'         => esc_html__( 'Dark', 'cariera' ),
            ),
            'save_always'   => true,
        ),
        array(
            "type"          => "dropdown",
            "class"         => "",
            "heading"       => esc_html__("Layout Version", 'cariera'),
            "param_name"    => "version",
            "value"         => array(
                '1' => '1',
                '2' => '2',
            ),
            'save_always'   => true,
        ),
    )
) );





/*
Shortcode logic how it should be rendered
*/
if ( !function_exists( 'job_resume_tab_search' )) {
	function job_resume_tab_search( $atts, $content = null ) {
        extract(shortcode_atts(array(
            'layout'     => 'light-skin',
		    'version'    => '1',
		), $atts));
        
        
        $output = '<div class="job-resume-tab-search version-' . $version . ' ' . $layout . '">';
            
        // TABS
        $output .= '<ul class="tabs-nav job-resume-search">
                        <li class="active">
                            <a href="#search-form-tab-jobs">
                                <i class="icon-briefcase"></i>' . esc_html__( 'Jobs' , 'cariera' ) . '
                            </a>
                        </li>' ;

        // If Resume manager is active show resume tab
        if ( class_exists( 'WP_Resume_Manager' ) ) {
            $output .= '<li class="">
                            <a href="#search-form-tab-resumes">
                                <i class="icon-graduation"></i>' . esc_html__( 'Resumes', 'cariera' ) . '
                            </a>
                        </li>';
        }

        $output .= '</ul>';
        
        
        // Job Categories
        ob_start(); ?>

            <div class="search-categories">
                <label for="search_category_jobs"><?php esc_html_e('Category', 'cariera'); ?></label>
                <?php
                cariera_job_manager_dropdown_category( array( 
                    'taxonomy'          => 'job_listing_category',
                    'hierarchical'      => 1, 
                    'show_option_all'   => esc_html__( 'Any category', 'cariera' ), 
                    'name'              => 'search_category',
                    'id'                => 'search_category_jobs',
                    'orderby'           => 'name', 
                    'selected'          => '', 
                    'multiple'          => false 
                ) );
                ?>
            </div>

        <?php
        $job_categories = ob_get_clean();
        
        
        // Resume Categories
        ob_start(); ?>

            <div class="search-categories">
                <label for="search_category_resumes"><?php esc_html_e('Category', 'cariera'); ?></label>
                <?php
                cariera_job_manager_dropdown_category( array( 
                    'taxonomy'          => 'resume_category',
                    'hierarchical'      => 1, 
                    'show_option_all'   => esc_html__( 'Any category', 'cariera' ), 
                    'name'              => 'search_category',
                    'id'                => 'search_category_resumes',
                    'orderby'           => 'name', 
                    'selected'          => '', 
                    'multiple'          => false 
                ) );
                ?>
            </div>

        <?php
        $resume_categories = ob_get_clean();
        
        
        $search_result = '<div class="search-results"><div class="search-loader"><span></span></div><div class="job-listings"></div></div>';
        
        $job_form = '<form method="GET" action="' . get_permalink(get_option('job_manager_jobs_page_id')) . '" class="job-search-form">
			<div class="search-keywords"><label for="search-keywords">' . esc_html__('Keywords', 'cariera') . '</label><input type="text" id="search_keywords" name="search_keywords" placeholder="' . esc_html__("Keywords", "cariera") . '" autocomplete="off">' . $search_result . '</div>
            <div class="search-location"><label for="search-location">' . esc_html__('Location', 'cariera') . '</label><input type="text" id="search_location" name="search_location" placeholder="' . esc_html__("Location", "cariera") . '"><div class="geolocation"><i class="geolocate"></i></div></div>'   
            . $job_categories . '<div class="search-submit"><label>' . esc_html__('Button', 'cariera') . '</label><input type="submit" class="btn btn-main btn-effect" value="'. esc_html__("Search", "cariera") . '"></div>
		</form>';
        
        
        if ( class_exists( 'WP_Resume_Manager' ) ) {
            $resume_form = '<form method="GET" action="' . get_permalink(get_option('resume_manager_resumes_page_id')) . '" class="resume-search-form">
                <div class="search-keywords"><label for="search-keywords">' . esc_html__('Keywords', 'cariera') . '</label><input type="text" id="search_keywords" name="search_keywords" placeholder="' . esc_html__("Keywords", "cariera") . '" autocomplete="off"></div>
                <div class="search-location"><label for="search-location">' . esc_html__('Location', 'cariera') . '</label><input type="text" id="search_location" name="search_location" placeholder="' . esc_html__("Location", "cariera") . '"></div>'   
                . $resume_categories . '<div class="search-submit"><label>' . esc_html__('Button', 'cariera') . '</label><input type="submit" class="btn btn-main btn-effect" value="'. esc_html__("Search", "cariera") . '"></div>
            </form>';
        }


        // CONTENT
        $output .= '<div class="tab-container">
                        <div class="tab-content" id="search-form-tab-jobs" style="display: none;">' . $job_form . '</div>';

        if ( class_exists( 'WP_Resume_Manager' ) ) {
            $output .= '<div class="tab-content" id="search-form-tab-resumes" style="display: none;">' . $resume_form . '</div>';
        }

        $output .= '</div>';

        $output .= '</div>';
        

        return $output;
        
    }
}

add_shortcode( 'job_resume_tab_search', 'job_resume_tab_search' );