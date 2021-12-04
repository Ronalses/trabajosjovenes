<?php
/**
*
* @package Cariera
*
* @since 1.4.0
* 
* ========================
* ELEMENTOR WIDGET - PRICING TABLES
* ========================
*     
**/



namespace Elementor;



// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}




class Cariera_Pricing_Tables extends Widget_Base {

    /**
    * Get widget's name.
    */
    public function get_name() {
        return 'pricing_tables';
    }

    
    
    /**
    * Get widget's title.
    */
    public function get_title() {
        return esc_html__( 'Pricing Tables', 'cariera' );
    }

    
    
    /**
    * Get widget's icon.
    */
    public function get_icon() {
        return 'eicon-price-table';
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
            'version',
            [
                'label'         => esc_html__( 'Pricing Table Layout', 'cariera' ),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'version1'          => esc_html__( 'Layout 1', 'cariera' ),
                    'version2'          => esc_html__( 'Layout 2', 'cariera' ),
                    'version3'          => esc_html__( 'Layout 3', 'cariera' ),
                ],
                'default'       => 'version1',
                'description'   => ''
            ]
        );
        $this->add_control(
            'title',
            [
                'label'         => esc_html__( 'Title', 'cariera' ),
                'type'          => Controls_Manager::TEXT,
                'default'       => 'Basic',
                'description'   => ''
            ]
        );
        $this->add_control(
            'price',
            [
                'label'         => esc_html__( 'Price', 'cariera' ),
                'type'          => Controls_Manager::TEXT,
                'default'       => '$59',
                'description'   => ''
            ]
        );
        $this->add_control(
            'description',
            [
                'label'         => esc_html__( 'Description', 'cariera' ),
                'type'          => Controls_Manager::TEXT,
                'default'       => 'This is a basic package',
                'description'   => '',
                'condition' => [
					'version' => 'version2',
				],
            ]
        );
        $this->add_control(
            'background_img',
            [
                'label'         => esc_html__( 'Background Image', 'cariera' ),
                'type'          => Controls_Manager::MEDIA,
                'default'       => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
                'description'   => '',
                'condition' => [
					'version' => 'version2',
				],
            ]
        );
        $this->add_control(
            'overlay_color',
            [
                'label'         => esc_html__( 'Pricing Header Overlay Color', 'cariera' ),
                'type'          => Controls_Manager::COLOR,
                'default'       => 'rgba(48, 58, 247, .5)',
                'description'   => '',
                'condition' => [
					'version' => 'version2',
				],
            ]
        );
        $this->add_control(
			'content',
			[
				'label'     => esc_html__( 'Pricing Details', 'cariera' ),
				'type'      => Controls_Manager::REPEATER,
				'fields'    => [					
					[
						'name'      => 'detail',
						'label'     => esc_html__( 'Detail', 'cariera' ),
                        'type'      => Controls_Manager::TEXT,
                        'default'   => 'List Item',
					],
				],
			]
        );
        $this->add_control(
            'button_text',
            [
                'label'         => esc_html__( 'Button Text', 'cariera' ),
                'type'          => Controls_Manager::TEXT,
                'default'       => 'Button',
                'description'   => ''
            ]
        );
        $this->add_control(
            'link',
            [
                'label'         => esc_html__( 'Button Link', 'cariera' ),
                'type'          => Controls_Manager::URL,
                'show_external' => true,
                'default'       => [
                    'url'           => 'http://',
                    'is_external'   => '',
                    'nofollow'      => '',
                ],
                'description'   => '',
            ]
        );
        $this->add_control(
            'highlight',
            [
                'label'         => esc_html__( 'Highlight', 'cariera' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => esc_html__( 'Enable', 'cariera' ),
				'label_off'     => esc_html__( 'Disable', 'cariera' ),
				'return_value'  => 'enable',
                'default'       => '',
                'description'   => '',
            ]
        );
        
        $this->end_controls_section();
    }

    
    
    /**
    * Widget output
    */
    protected function render() {
        $settings   = $this->get_settings();
        $attrs      = '';

        if( $settings['highlight'] != 'enable' ) {
            $highlight = '';
        } else {
            $highlight = 'pricing-table-featured';
        }

        $output = '';

        // Version 1
        if( $settings['version'] == 'version1' ) {
            $output .= '<div class="pricing-table ' . esc_attr($highlight) . ' shadow-hover">';
            $output .= '<div class="pricing-header"><h2>' . $settings['title'] . '</h2></div>';
            $output .= '<div class="pricing"><span class="amount">' . $settings['price'] . '</span></div>';
        } 
        
        // Version 2
        elseif( $settings['version'] == 'version2' ) {
            $output .= '<div class="pricing-table2 ' . esc_attr($highlight) . '">';
            
            if ( $settings['background_img'] ) {
                $img_src = wp_get_attachment_image_src( $settings['background_img']['id'], 'full' );
                $output .= '<div class="pricing-header" style="background-image: url(' . $img_src[0] . ');">';
            } else {
                $output .= '<div class="pricing-header">';
            }
            $output .= '<span class="title">' . $settings['title'] . '</span>';
            $output .= '<div class="amount">' . $settings['price'] . '</div>';
            $output .= '<p class="description">' . $settings['description'] . '</p>';

            if ( !empty($settings['overlay_color']) ) {
                $output .= '<div class="overlay" style="background: ' . $settings['overlay_color'] . ' "></div>';
            }

            if( $highlight ) {
                $output .= '<div class="featured"><i class="far fa-star"></i></div>';
            }
            $output .= '</div>';    
        }

        // Version 3
        elseif( $settings['version'] == 'version3' ) {
            $output .= '<div class="pricing-table3 ' . esc_attr($highlight) . '">';            
            $output .= '<div class="pricing-header">';
            $output .= '<span class="title">' . $settings['title'] . '</span>';
            $output .= '<div class="amount">' . $settings['price'] . '</div>';

            if( $highlight ) {
                $output .= '<div class="featured"><i class="far fa-star"></i></div>';
            }

            $output .= '<div class="svg-shape"><div>
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="none" x="0px" y="0px" viewBox="0 0 300 100" style="enable-background:new 0 0 300 100;" xml:space="preserve" class="injected-svg js-svg-injector" data-parent="#SVGwave1BottomShapeID-12initialId">
            <style type="text/css">
                .wave-bottom-1-0{clip-path:url(#waveBottom1ID2);fill:#FFFFFF;}
                .wave-bottom-1-1{clip-path:url(#waveBottom1ID2);fill:#FFFFFF;fill-opacity:0;}
                .wave-bottom-1-2{clip-path:url(#waveBottom1ID2);fill:#FFFFFF;}
            </style>
            <g>
                <defs>
                    <rect id="waveBottom1ID1" width="300" height="100"></rect>
                </defs>
                <clipPath id="waveBottom1ID2">
                    <use xlink:href="#waveBottom1ID1" style="overflow:visible;"></use>
                </clipPath>
                <path class="wave-bottom-1-0 fill-white" opacity=".4" d="M10.9,63.9c0,0,42.9-34.5,87.5-14.2c77.3,35.1,113.3-2,146.6-4.7C293.7,41,315,61.2,315,61.2v54.4H10.9V63.9z"></path>
                <path class="wave-bottom-1-0 fill-white" opacity=".4" d="M-55.7,64.6c0,0,42.9-34.5,87.5-14.2c77.3,35.1,113.3-2,146.6-4.7c48.7-4.1,69.9,16.2,69.9,16.2v54.4H-55.7   V64.6z"></path>
                <path class="wave-bottom-1-1 fill-white" opacity=".4" d="M23.4,118.3c0,0,48.3-68.9,109.1-68.9c65.9,0,98,67.9,98,67.9v3.7H22.4L23.4,118.3z"></path>
                <path class="wave-bottom-1-2 fill-white" d="M-54.7,83c0,0,56-45.7,120.3-27.8c81.8,22.7,111.4,6.2,146.6-4.7c53.1-16.4,104,36.9,104,36.9l1.3,36.7l-372-3   L-54.7,83z"></path>
            </g>
            </svg></div></div>';



            $output .= '</div>';    
        }

        $output .= '<div class="pricing-body">';

        // Has to be changed in a repeater
        $output .= '<ul>';
        foreach ((array) $settings['content'] as $item) { 
            $output .= '<li>';
            $output .= $settings['version'] == 'version3' ? '<span></span>' : '';
            $output .= $item['detail'] . '</li>';
        }
        $output .= '</ul>';

        $output .= '</div>';
        
        $output .= '<div class="pricing-footer">';
        
        
        $target = $settings['link']['is_external'] ? 'target="_blank"' : '';
        $follow = $settings['link']['nofollow'] ? 'rel="nofollow"' : '';


        // Version 1
        if( $settings['version'] == 'version1' ) {
            $output .= '<a href="' . esc_url( $settings['link']['url'] ) . '" ' . $target . ' class="btn btn-main btn-effect" ' . $follow . '>' . $settings['button_text'] . '</a>';
        }
        
        // Version 2
        elseif( $settings['version'] == 'version2' ) {
            $output .= '<a href="' . esc_url( $settings['link']['url'] ) . '" ' . $target . ' ' . $follow . '>' . $settings['button_text'] . '</a>';
        }

        // Version 3
        if( $settings['version'] == 'version3' ) {
            $output .= '<a href="' . esc_url( $settings['link']['url'] ) . '" ' . $target . ' class="btn btn-main btn-effect" ' . $follow . '>' . $settings['button_text'] . '</a>';
        }

        $output .= '</div></div>'; 

        echo $output;
    }
    
}