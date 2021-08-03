<?php
/**
*
* @package Cariera
*
* @since 1.4.3
* 
* ========================
* ELEMENTOR WIDGET - RESUME SEARCH
* ========================
*     
**/



namespace Elementor;



// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}




class Cariera_Resume_Search extends Widget_Base {

    /**
    * Get widget's name.
    */
    public function get_name() {
        return 'resume_search';
    }

    
    
    /**
    * Get widget's title.
    */
    public function get_title() {
        return esc_html__( 'Resume Search Form', 'cariera' );
    }

    
    
    /**
    * Get widget's icon.
    */
    public function get_icon() {
        return 'eicon-search';
    }

    
    
    /**
    * Get widget's categories.
    */
    public function get_categories() {
        return [ 'cariera-elements' ];
    }
    
    
    
    /**
    * Register the controls for the widget
    */
    protected function _register_controls() {

        // SECTION
        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__( 'Content', 'cariera' ),
            ]
        );

        
        // CONTROLS
        $this->add_control(
            'search_style',
            [
                'label'         => esc_html__( 'Search Layout', 'cariera' ),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'style-1'        => esc_html__( 'Style 1', 'cariera' ),
                    'style-2'        => esc_html__( 'Style 2', 'cariera' ),
                ],
                'default'       => 'style-1',
                'description'   => esc_html__( 'Choose the layout version that you want your search to have.', 'cariera' )
            ]
        );
        $this->add_control(
            'location',
            [
                'label'         => esc_html__( 'Location', 'cariera' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => esc_html__( 'Show', 'cariera' ),
				'label_off'     => esc_html__( 'Hide', 'cariera' ),
				'return_value'  => 'yes',
				'default'       => 'yes',
            ]
        );
        $this->add_control(
            'region',
            [
                'label'         => esc_html__( 'Region', 'cariera' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => esc_html__( 'Show', 'cariera' ),
				'label_off'     => esc_html__( 'Hide', 'cariera' ),
				'return_value'  => 'yes',
				'default'       => '',
            ]
        );
        $this->add_control(
            'categories',
            [
                'label'         => esc_html__( 'Categories', 'cariera' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => esc_html__( 'Show', 'cariera' ),
				'label_off'     => esc_html__( 'Hide', 'cariera' ),
				'return_value'  => 'yes',
				'default'       => 'yes',
            ]
        );
        
        $this->end_controls_section();
    }

    
    
    /**
    * Widget output
    */
    protected function render( ) {
        $settings   = $this->get_settings();
        $attrs      = '';


        // Location field
        $location = '';
		if( !empty($settings['location']) ) {
			$location = '<div class="search-location"><input type="text" id="search_location" name="search_location" placeholder="' . esc_html__( "Location", "cariera" ) . '"><div class="geolocation"><i class="geolocate"></i></div></div>';
		}
        

        // Regions Field
        $region = '';
        if ( class_exists('Astoundify_Job_Manager_Regions') ) {
            if( !empty($settings['region']) ) {

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
        }


        // Categories dropdown
        $categories = '';
		if( !empty($settings['categories']) ) {
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
		$output = '<form method="GET" action="' . get_permalink(get_option('resume_manager_resumes_page_id')) . '" class="resume-search-form ' . $settings['search_style'] . '">
            <div class="search-keywords"><input type="text" id="search_keywords" name="search_keywords" placeholder="' . esc_html__("Keywords", "cariera") . '" autocomplete="off">' . $search_result . '</div>' . $location . $region . $categories . '<div class="search-submit"><input type="submit" class="btn btn-main btn-effect" value="'. esc_html__("Search", "cariera") . '"></div>
        </form>';

        echo $output;
    }
    
}