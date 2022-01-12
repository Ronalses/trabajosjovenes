<?php
/**
*
* @package Cariera
*
* @since 1.4.0
* 
* ========================
* ELEMENTOR WIDGET - VIDEO POPUP
* ========================
*     
**/



namespace Elementor;



// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}




class Cariera_Video_Popup extends Widget_Base {

    /**
    * Get widget's name.
    */
    public function get_name() {
        return 'video_popup';
    }

    
    
    /**
    * Get widget's title.
    */
    public function get_title() {
        return esc_html__( 'Video Popup', 'cariera' );
    }

    
    
    /**
    * Get widget's icon.
    */
    public function get_icon() {
        return 'eicon-play';
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
            'link',
            [
                'label'         => esc_html__( 'Video Link', 'cariera' ),
                'type'          => Controls_Manager::URL,
                'default'       => [
                    'url'           => 'http://',
                    'is_external'   => '',
                    'nofollow'      => '',
                ],
                'show_external' => false, // Show the 'open in new tab' button.                
            ]
        );
        $this->add_control(
            'image',
            [
                'label'         => esc_html__( 'Image', 'cariera' ),
                'type'          => Controls_Manager::MEDIA,
                'default'       => [
                    'url'       => Utils::get_placeholder_image_src(),
                 ],
            ]
        );
        $this->add_control(
            'overlay',
            [
                'label'         => esc_html__( 'Overlay Color', 'cariera' ),
                'type'          => Controls_Manager::COLOR,
                'default'       => ''
            ]
        );
        $this->add_control(
            'open',
            [
                'label'         => esc_html__( 'Open Video in', 'cariera' ),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'popup'      => esc_html__( 'Popup', 'cariera' ), 
                    'window'    => esc_html__( 'New Window', 'cariera' ),
                ],
                'default'       => 'popup',
            ]
        );
        
        $this->end_controls_section();
    }

    
    
    /**
    * Widget output
    */
    protected function render( ) {
        $settings = $this->get_settings();

        echo '<div class="video-container">';

            if ( !empty( $settings['overlay'] ) ) {
                echo '<div class="overlay" style="background: ' . $settings['overlay'] . '"></div>';
            }

            echo '<img src="' . $settings['image']['url'] . '" />';

            if( $settings['open'] == 'window' ) {
                $open = 'target="_blank"';
            } else {
                $open = 'class="popup-video"';
            }

            echo '<a href="' . $settings['link']['url'] . '" ' . $open . '><span class="play-video"><span class="fas fa-play"></span></span></a>';
            
        echo '</div>';
    }

}