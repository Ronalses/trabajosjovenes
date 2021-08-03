<?php
/**
*
* @package Cariera
*
* @since 1.4.0
* 
* ========================
* ELEMENTOR WIDGET - JOB SEARCH BOX
* ========================
*     
**/



namespace Elementor;



// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}




class Cariera_Job_Search_Box extends Widget_Base {

    /**
    * Get widget's name.
    */
    public function get_name() {
        return 'job_search_box';
    }

    
    
    /**
    * Get widget's title.
    */
    public function get_title() {
        return esc_html__( 'Job Search Box Form', 'cariera' );
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
            'title',
            [
                'label'         => esc_html__( 'Search Box Title', 'cariera' ),
                'type'          => Controls_Manager::TEXT,
				'default'       => '',
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
        


        // Keywords input 
        $keywords = '<div class="col-md-12 search-keywords">
            <label for="search-keywords">' . esc_html__( 'Keywords', 'cariera') . '</label>
            <input type="text" name="search_keywords" id="search_keywords" placeholder="' . esc_attr__('Keywords', 'cariera') . '" value="" autocomplete="off">
        </div>';


        // Location field
        $location = '';
		if( !empty($settings['location']) ) {
			$location = '<div class="col-md-12 search-location mt15">
                <label for="search-location">' . esc_html__( 'Location', 'cariera') . '</label>
                <input type="text" name="search_location" id="search_location" placeholder="' . esc_attr__('Location', 'cariera') . '" value="">
                <div class="geolocation"><i class="geolocate"></i></div>
            </div>';
		}
        

        // Regions Field
        $region = '';
        if ( class_exists('Astoundify_Job_Manager_Regions') ) {
            if( !empty($settings['region']) ) {

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
        }


        // Categories dropdown
        $categories = '';
        if( !empty($settings['categories']) ) {
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


        $search_result = '<div class="search-results"><div class="search-loader"><span></span></div><div class="job-listings"></div></div>';

        // Form Output
		$output = '<form class="job-search-form-box row" method="get" action="' . esc_url(get_permalink(get_option('job_manager_jobs_page_id'))) . '">
        <div class="col-md-12 form-title">
            <h4 class="title">' . esc_html($settings['title']) . '</h4>
        </div>' . $keywords . $location . $region . $categories . '
        <div class="col-md-12 search-submit mt15 mb30">
            <button type="submit" class="btn btn-main btn-effect"><i class="fas fa-search"></i>' . esc_attr__('search', 'cariera') . '</button>
        </div></form>';

        echo $output;
    }
    
}