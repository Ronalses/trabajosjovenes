<?php
/**
*
* @package Cariera
*
* @since 1.4.0
* 
* ========================
* ELEMENTOR WIDGET - JOB SLIDER
* ========================
*     
**/



namespace Elementor;



// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}




class Cariera_Job_Slider extends Widget_Base {

    /**
    * Get widget's name.
    */
    public function get_name() {
        return 'job_slider';
    }

    
    
    /**
    * Get widget's title.
    */
    public function get_title() {
        return esc_html__( 'Job Slider', 'cariera' );
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
            'per_page',
            [
                'label'         => esc_html__( 'Total Jobs', 'cariera' ),
                'type'          => Controls_Manager::TEXT,
                'default'       => '',
                'description'   => esc_html__( 'Leave it blank to display all featured jobs.', 'cariera' )
            ]
        );
        $this->add_control(
            'columns',
            [
                'label'         => esc_html__( 'Visible Jobs', 'cariera' ),
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
        $this->add_control(
            'featured',
            [
                'label'         => esc_html__( 'Featured', 'cariera' ),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'true'              => esc_html__( 'Show only featured', 'cariera' ),
                    'false'             => esc_html__( 'Show all', 'cariera' ),
                ],
                'default'       => 'true',
                'description'   => ''
            ]
        );
        $this->add_control(
            'filled',
            [
                'label'         => esc_html__( 'Filled', 'cariera' ),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'null'              => esc_html__( 'Show all', 'cariera' ),
                    'true'              => esc_html__( 'Show only filled', 'cariera' ),                    
                    'false'             => esc_html__( 'Hide filled', 'cariera' ),
                ],
                'default'       => 'null',
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
        
        ob_start();

        $randID = rand(1, 99); 

        if ( ! is_null( $settings['filled'] ) ) {
            $filled = ( is_bool( $settings['filled'] ) && $settings['filled'] ) || in_array( $settings['filled'], array( '1', 'true', 'yes' ) ) ? true : false;
        }

        if ( ! is_null( $settings['featured'] ) ) {
            $featured = ( is_bool( $settings['featured'] ) && $settings['featured'] ) || in_array( $settings['featured'], array( '1', 'true', 'yes' ) ) ? true : false;
        }

        // Get jobs
        $jobs = get_job_listings( array(
            'orderby'           => $settings['orderby'],
            'order'             => $settings['order'],
            'posts_per_page'    => $settings['per_page'],
            'featured'          => $featured,
            'filled'            => $filled
        ) );
        
        if ( $settings['autoplay'] == 'enable' ) {
            $autoplay = '1';
        } else {
            $autoplay = '0';
        }

        // Loop
        if ( $jobs->have_posts() ) { ?>
            <div class="job-carousel" data-columns="<?php echo esc_attr($settings['columns']); ?>" data-autoplay="<?php echo esc_attr($autoplay); ?>">
                <?php while ( $jobs->have_posts() ) : $jobs->the_post();
                    $id = get_the_id(); ?>
                    <div class="single-job">

                        <!-- Start of Company Logo -->
                        <div class="company">
                            <?php cariera_the_company_logo(); ?>
                        </div>

                        <!-- Start of Featured Job Info -->
                        <div class="job-info">

                            <!-- Job Title -->
                            <div class="job-title">
                                <h5 class="title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h5>
                                
                                <?php $types = wpjm_get_the_job_types(); ?>    
                                <?php if ( ! empty( $types ) ) : foreach ( $types as $type ) : ?>
                                    <span class="job-type <?php echo esc_attr( sanitize_title( $type->slug ) ); ?>"><?php echo esc_html( $type->name ); ?></span>
                                <?php endforeach; endif; ?>
                            </div>

                            <!-- Job Info -->
                            <div class="job-meta">
                                <span class="company-name"><i class="far fa-building"></i><?php the_company_name(); ?></span>
                                <span class="location"><i class="icon-location-pin"></i><?php the_job_location(); ?></span>
                            </div>

                            <div class="job-description"> 
                                <?php 
                                $excerpt = get_the_excerpt();
                                echo cariera_string_limit_words($excerpt,20); ?>...
                            </div>

                            <!-- View Job Button -->
                            <div class="text-center mt20">
                                <a href="<?php the_permalink(); ?>" class="btn btn-main"><?php esc_html_e( 'Apply For This Job', 'cariera' ); ?></a>
                            </div>
                        </div>
                        <!-- End of Featured Job Info -->

                    </div>
                <?php endwhile; ?>                
            </div><?php  

        } 

        $output = ob_get_clean();

        echo $output;
    }
    
}