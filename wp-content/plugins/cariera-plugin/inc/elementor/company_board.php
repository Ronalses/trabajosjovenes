<?php
/**
*
* @package Cariera
*
* @since 1.4.0
* 
* ========================
* ELEMENTOR WIDGET - COMPANY BOARD
* ========================
*     
**/



namespace Elementor;



// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}




class Cariera_Company_Board extends Widget_Base {

    /**
    * Get widget's name.
    */
    public function get_name() {
        return 'company_board';
    }

    
    
    /**
    * Get widget's title.
    */
    public function get_title() {
        return esc_html__( 'Company Board', 'cariera' );
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
                'label'         => esc_html__( 'Company Layout', 'cariera' ),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'list'              => esc_html__( 'List', 'cariera' ),
                    'grid'              => esc_html__( 'Grid', 'cariera' ),
                ],
                'default'       => 'list',
                'description'   => esc_html__( 'Choose the layout style for your companies.', 'cariera' ),
            ]
        );
        $this->add_control(
            'list_layout',
            [
                'label'         => esc_html__( 'Company List Styles', 'cariera' ),
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
                'label'         => esc_html__( 'Company Grid Styles', 'cariera' ),
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
                'default'       => '5',
                'description'   => esc_html__( 'How many items to show in the company board.', 'cariera' )
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
                    'title'             => esc_html__( 'Title', 'cariera' ),
                    'ID'                => esc_html__( 'ID', 'cariera' ),
                    'name'              => esc_html__( 'Name', 'cariera' ),
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
					'{{WRAPPER}} .company_listings nav.company-manager-pagination, {{WRAPPER}} .company_listings .load_more_companies'	 => 'display: none !important',
				],
            ]
        );
        $this->add_control(
            'pagination',
            [
                'label'         => esc_html__( 'Pagination Style', 'cariera' ),
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
                $layout_ver = 'companies_list_version="' . $settings['list_layout'] . '"';
            } else {
                $layout_ver = '';
            }
        } 
        
        if( $settings['layout'] == 'grid' ) {
            $layout = 'companies_layout="grid"';
            if( $settings['grid_layout'] != '1' ) {
                $layout_ver = 'companies_grid_version="' . $settings['grid_layout'] . '"';
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

        
        $company_attr = [ $layout, $layout_ver, $per_page, $orderby, $order, $show_filters, $pagination ];

        $output = '[companies ' . join( ' ', $company_attr ) . ']';

        echo do_shortcode($output);
    }
    
}