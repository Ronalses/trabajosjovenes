<?php

/**
*
* @package Cariera
*
* @since 1.4.0
* 
* ========================
* CARIERA ELEMENTOR CLASS
* ========================
*     
**/



// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}



class Cariera_Elementor {



    public function __construct() {		
        add_action( 'elementor/init', array( $this, 'elementor_add_category' ) );
        add_action( 'elementor/widgets/widgets_registered', array( $this, 'elementor_register_widgets' ) );

        // add support for Elementor Pro custom headers & footers
		add_action( 'elementor/theme/register_locations', [ $this, 'register_locations' ] );
    }




    
    /**
     * Add a custom category for panel widgets
     *
     * @since 1.4.0
     */
    public function elementor_add_category() {
        \Elementor\Plugin::$instance->elements_manager->add_category( 
            'cariera-elements',
            [
                'title' => esc_html__( 'Cariera Elements', 'cariera' ),
                'icon' => 'fa fa-gmap',
            ],
            1 // position
        );
    }





    /**
     * Register custom widgets for Elementor
     *
     * @since   1.4.0
     * @version 1.5.1
     */
    function elementor_register_widgets( $widgets_manager ) {
        
        // Widget names
        $elements = array(
            // Generic Elements
            'button',
            'blog_posts',
            'blog_slider',
            'contact_form7',
            'counter',
            'count_down',
            'testimonials',
            'logo_slider',
            'login_register',
            //'text_rotator',
            'pricing_tables',
            'video_popup',

            // Listing Elements
            'job_board',
            'submit_job',
            'job_slider',
            'job_categories_slider',
            'job_dashboard',
            'job_search',
            'job_search_box',
            'job_resume_search',
            'company_board',
            'company_dashboard',
            'submit_company',
            'company_list',
            'company_slider',
            'resumes',
            'submit_resume',
            'resume_dashboard',
            'resume_search',
            'resume_slider',
            'listing_map',
            'listing_categories_grid',
            'listing_categories_list',
        );
        
        foreach ( $elements as $element_name ) {
            $template_file = CARIERA_PATH . '/inc/elementor/' . $element_name . '.php';
            
            if ( $template_file && is_readable( $template_file ) ) {
                require_once $template_file;
                $class_name = '\Elementor\Cariera_' . ucwords($element_name,'_');
                $widgets_manager->register_widget_type( new $class_name() );
            }      
        }
    }




    /**
     * Register locations for Elementor Pro Theme Builder Support
     *
     * @since 1.4.6
     */
    public function register_locations( $location_manager ) {
		$location_manager->register_location( 'header' );
		$location_manager->register_location( 'footer' );
    }

}

new Cariera_Elementor();