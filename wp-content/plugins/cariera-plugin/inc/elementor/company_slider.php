<?php
/**
*
* @package Cariera
*
* @since 1.4.0
* 
* ========================
* ELEMENTOR WIDGET - COMPANY SLIDER
* ========================
*     
**/



namespace Elementor;



// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}




class Cariera_Company_Slider extends Widget_Base {

    /**
    * Get widget's name.
    */
    public function get_name() {
        return 'company_slider';
    }

    
    
    /**
    * Get widget's title.
    */
    public function get_title() {
        return esc_html__( 'Company Slider', 'cariera' );
    }

    
    
    /**
    * Get widget's icon.
    */
    public function get_icon() {
        return 'eicon-post-slider';
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
                'label'         => esc_html__( 'Layout Version', 'cariera' ),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    '1'               => esc_html__( 'Version 1', 'cariera' ),
                    '2'               => esc_html__( 'Version 2', 'cariera' ),
                ],
                'default'       => '1',
                'description'   => ''
            ]
        );
        $this->add_control(
            'per_page',
            [
                'label'         => esc_html__( 'Total Companies', 'cariera' ),
                'type'          => Controls_Manager::TEXT,
                'default'       => '',
                'description'   => esc_html__( 'Leave it blank to display all companies.', 'cariera' )
            ]
        );
        $this->add_control(
            'columns',
            [
                'label'         => esc_html__( 'Visible Companies', 'cariera' ),
                'type'          => Controls_Manager::NUMBER,
                'default'       => '1',
                'min'           => '1',
                'max'           => '10',
                'description'   => esc_html__( 'This will change how many jobs will be visible per slide.', 'cariera' )
            ]
        );
        $this->add_control(
            'autoplay',
            [
                'label'         => esc_html__( 'Autoplay', 'cariera' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => esc_html__( 'Enable', 'cariera' ),
				'label_off'     => esc_html__( 'Disable', 'cariera' ),
				'return_value'  => 'enable',
				'default'       => '',
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
        
        $this->end_controls_section();
    }


    
    /**
    * Widget output
    */
    protected function render( ) {
        $settings   = $this->get_settings();
        $attrs      = '';
        global $post;
        
        ob_start();

        $randID = rand(1, 99); 

        $companies = cariera_get_companies( array(
            'orderby'           => $settings['orderby'],
            'order'             => $settings['order'],
            'posts_per_page'    => $settings['per_page'],
        ) );
        
        if ( $settings['autoplay'] == 'enable' ) {
            $autoplay = '1';
        } else {
            $autoplay = '0';
        }


        if ( $companies->have_posts() ) { ?>

            <div class="company-carousel company-carousel-<?php echo esc_attr($settings['version']); ?>" data-columns="<?php echo esc_attr($settings['columns']); ?>" data-autoplay="<?php echo esc_attr($autoplay); ?>">
            
                <?php while ( $companies->have_posts() ) : $companies->the_post(); ?>
                    <div class="single-company">
                        
                        <a href="<?php cariera_the_company_permalink(); ?>" id="company-link">        
                            <!-- Company Logo -->
                            <?php if ( $settings['version'] == '1') { ?>
                                <div class="company-logo-wrapper">
                            <?php } else {
                                $image = get_post_meta( $post->ID, '_company_header_image', true); ?>
                                <div class="company-logo-wrapper" style="background-image: url(<?php echo esc_attr($image); ?>);">
                            <?php } ?>
                            
                                <div class="company-logo">
                                    <?php cariera_the_company_logo(); ?>
                                </div>
                            </div>
                            
                            <!-- Company Details -->
                            <div class="company-details">
                                <div class="company-title">
                                    <h5><?php the_title(); ?></h5>
                                </div>

                                <?php if ( !empty( cariera_get_the_company_location() )) { ?>
                                    <div class="company-location">
                                        <span><i class="icon-location-pin"></i><?php echo cariera_get_the_company_location(); ?></span>
                                    </div>
                                <?php } ?>

                                <div class="company-jobs">
                                    <span>
                                       <?php echo apply_filters( 'cariera_company_open_positions_info', esc_html( sprintf( _n( '%s Job', '%s Jobs', cariera_get_the_company_job_listing_count(), 'cariera' ), cariera_get_the_company_job_listing_count() ) ) ); ?>
                                    </span>
                                </div>
                            </div>
                        </a>
                    </div>  

                <?php endwhile; ?>
            </div>
        <?php }
        
        $output =  ob_get_clean();

        echo $output;
    }
    
}