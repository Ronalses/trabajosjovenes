<?php
/**
*
* @package Cariera
*
* @since 1.4.1
* 
* ========================
* ELEMENTOR WIDGET - SUBMIT RESUME
* ========================
*     
**/



namespace Elementor;



// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}




class Cariera_Submit_Resume extends Widget_Base {

    /**
    * Get widget's name.
    */
    public function get_name() {
        return 'submit_resume';
    }

    
    
    /**
    * Get widget's title.
    */
    public function get_title() {
        return esc_html__( 'Submit Resume Form', 'cariera' );
    }

    
    
    /**
    * Get widget's icon.
    */
    public function get_icon() {
        return 'eicon-form-horizontal';
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

        // // SECTION
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
        
        $output = do_shortcode('[submit_resume_form]');

        echo $output;
    }
    
}