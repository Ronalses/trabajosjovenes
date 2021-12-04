<?php
/**
*
* @package Cariera
*
* @since 1.5.0
* 
* ========================
* ELEMENTOR WIDGET - LOGIN & REGISTER FORMS
* ========================
*     
**/



namespace Elementor;



// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}




class Cariera_Login_Register extends Widget_Base {

    /**
    * Get widget's name.
    */
    public function get_name() {
        return 'login_register';
    }

    
    
    /**
    * Get widget's title.
    */
    public function get_title() {
        return esc_html__( 'Login & Register Form', 'cariera' );
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

        // SECTION
        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__( 'Content', 'cariera' ),
            ]
        );

        
        // CONTROLS
        $this->add_control(
            'form',
            [
                'label'         => esc_html__( 'Choose Form', 'cariera' ),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'login'         => esc_html__( 'Login Form', 'cariera' ),
                    'register'      => esc_html__( 'Register Form', 'cariera' ),
                ],
                'default'       => 'login',
                'description'   => ''
            ]
        );

        
        $this->end_controls_section();
    }

    
    
    /**
    * Widget output
    */
    protected function render() {
        $settings   = $this->get_settings();

        if ( $settings['form'] == 'login' ) {
            $output = do_shortcode('[cariera_login_form]');
        } else {
            $output = do_shortcode('[cariera_registration_form]');
        }

        echo $output;
    }
    
}