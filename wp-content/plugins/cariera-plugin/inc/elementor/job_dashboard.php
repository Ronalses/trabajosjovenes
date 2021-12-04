<?php
/**
*
* @package Cariera
*
* @since 1.4.4
* 
* ========================
* ELEMENTOR WIDGET - JOB DASHBOARD
* ========================
*     
**/



namespace Elementor;



// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}




class Cariera_Job_Dashboard extends Widget_Base {

    /**
    * Get widget's name.
    */
    public function get_name() {
        return 'job_dashboard';
    }

    
    
    /**
    * Get widget's title.
    */
    public function get_title() {
        return esc_html__( 'Job Dashboard', 'cariera' );
    }

    
    
    /**
    * Get widget's icon.
    */
    public function get_icon() {
        return 'eicon-post-list';
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

        
        $this->end_controls_section();
    }

    
    
    /**
    * Widget output
    */
    protected function render( ) {
        //$settings   = $this->get_settings();
        //$attrs      = '';
        
        $output = do_shortcode('[job_dashboard]');

        echo $output;
    }
    
}