<?php
/**
*
* @package Cariera
*
* @since 1.4.0
* 
* ========================
* ELEMENTOR WIDGET - RESUMES
* ========================
*     
**/



namespace Elementor;



// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}




class Cariera_Resumes extends Widget_Base {

    /**
    * Get widget's name.
    */
    public function get_name() {
        return 'resumes';
    }

    
    
    /**
    * Get widget's title.
    */
    public function get_title() {
        return esc_html__( 'Resumes', 'cariera' );
    }

    
    
    /**
    * Get widget's icon.
    */
    public function get_icon() {
        return 'eicon-posts-justified';
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
            'layout',
            [
                'label'         => esc_html__( 'Resume Layout', 'cariera' ),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'list'              => esc_html__( 'List', 'cariera' ),
                    'grid'              => esc_html__( 'Grid', 'cariera' ),
                ],
                'default'       => 'list',
                'description'   => esc_html__( 'Choose the layout style for your resumes.', 'cariera' ),
            ]
        );
        $this->add_control(
            'list_layout',
            [
                'label'         => esc_html__( 'Resume List Styles', 'cariera' ),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    '1'              => esc_html__( 'Version 1', 'cariera' ),
                    '2'              => esc_html__( 'Version 2', 'cariera' ),
                ],
                'default'       => '1',
                'description'   => '',
                'condition' => [
					'layout' => 'list',
				],
            ]
        );
        $this->add_control(
            'grid_layout',
            [
                'label'         => esc_html__( 'Resume Grid Styles', 'cariera' ),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    '1'              => esc_html__( 'Version 1', 'cariera' ),
                    '2'              => esc_html__( 'Version 2', 'cariera' ),
                ],
                'default'       => '1',
                'description'   => '',
                'condition' => [
					'layout' => 'grid',
				],
            ]
        );
        $this->add_control(
            'per_page',
            [
                'label'         => esc_html__( 'Items per Page', 'cariera' ),
                'type'          => Controls_Manager::NUMBER,
                'default'       => '10',
                'description'   => esc_html__( 'How many items to show in the resumes list.', 'cariera' )
            ]
        );
        $this->add_control(
            'orderby',
            [
                'label'         => esc_html__( 'Order by', 'cariera' ),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'featured'          => esc_html__( 'Featured', 'cariera' ),
                    'date'              => esc_html__( 'Date', 'cariera' ),
                    'ID'                => esc_html__( 'ID', 'cariera' ),
                    'author'            => esc_html__( 'Author', 'cariera' ),
                    'title'             => esc_html__( 'Title', 'cariera' ),
                    'modified'          => esc_html__( 'Modified', 'cariera' ),
                    'rand'              => esc_html__( 'Random', 'cariera' ),
                ],
                'default'       => 'featured',
                'description'   => ''
            ]
        );
        $this->add_control(
            'order',
            [
                'label'         => esc_html__( 'Order', 'cariera' ),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'DESC'              => esc_html__( 'Descending', 'cariera' ),
                    'ASC'               => esc_html__( 'Ascending', 'cariera' ),
                ],
                'default'       => 'DESC',
                'description'   => ''
            ]
        );
        $this->add_control(
            'filters',
            [
                'label'         => esc_html__( 'Show Filters', 'cariera' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => esc_html__( 'Show', 'cariera' ),
				'label_off'     => esc_html__( 'Hide', 'cariera' ),
				'return_value'  => 'show',
                'default'       => 'show',
                'description'   => '',
            ]
        );
        $this->add_control(
            'hide_pagination',
            [
                'label'         => esc_html__( 'Hide Pagination', 'cariera' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => esc_html__( 'Hide', 'cariera' ),
				'label_off'     => esc_html__( 'Show', 'cariera' ),
				'return_value'  => 'true',
                'default'       => '',
                'description'   => '',
                'selectors' => [
					'{{WRAPPER}} .resumes nav.job-manager-pagination, {{WRAPPER}} .resumes .load_more_resumes'	 => 'display: none !important',
				],
            ]
        );
        $this->add_control(
            'pagination',
            [
                'label'         => esc_html__( 'Show Pagination', 'cariera' ),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'false'         => esc_html__( 'Load More', 'cariera' ),
                    'true'          => esc_html__( 'Numeric', 'cariera' ),
                ],
                'default'       => 'false',
                'description'   => '',                
                'condition' => [
					'hide_pagination' => '',
				],
            ]
        );
        $this->add_control(
            'featured',
            [
                'label'         => esc_html__( 'Featured', 'cariera' ),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'default'           => esc_html__( 'Default', 'cariera' ),
                    'show'              => esc_html__( 'Show', 'cariera' ),
                    'hide'              => esc_html__( 'Hide', 'cariera' ),
                ],
                'default'       => 'default',
                'description'   => esc_html__( 'Set to "Show" to show only featured jobs, "Hide" to hide the featured jobs, or default show both (featured first).', 'cariera'),
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


        if( $settings['layout'] == 'list' ) {
            $layout = '';
            if( $settings['list_layout'] != '1' ) {
                $layout_ver = 'resumes_list_version="' . $settings['list_layout'] . '"';
            } else {
                $layout_ver = '';
            }
        } 
        
        if( $settings['layout'] == 'grid' ) {
            $layout = 'resumes_layout="grid"';
            if( $settings['grid_layout'] != '1' ) {
                $layout_ver = 'resumes_grid_version="' . $settings['grid_layout'] . '"';
            } else {
                $layout_ver = '';
            }
        }

        if( !empty($settings['per_page']) ) {
            $per_page = 'per_page="' . $settings['per_page'] . '"';
        }

        if( !empty($settings['orderby']) ) {
            $orderby = 'orderby="' . $settings['orderby'] . '"';
        }

        if( !empty($settings['order']) ) {
            $order = 'order="' . $settings['order'] . '"';
        }

        if( $settings['filters'] != 'show' ) {
            $show_filters = 'show_filters="false"';
        } else {
            $show_filters = 'show_filters="true"';
        }

        if( !empty( $settings['pagination'])  ) {
            $pagination = 'show_pagination="' . $settings['pagination'] . '"';
        }

        if( $settings['featured'] == 'default' ) {
            $featured = '';
        } elseif ( $settings['featured'] == 'show' ) {
            $featured = 'featured="true"';
        } else {
            $featured = 'featured="false"';
        }

        $resume_attr = [ $layout, $layout_ver, $per_page, $orderby, $order, $show_filters, $pagination, $featured ];


        $output = '[resumes ' . join( ' ', $resume_attr ) . ']';

        echo do_shortcode($output);
    }
    
}