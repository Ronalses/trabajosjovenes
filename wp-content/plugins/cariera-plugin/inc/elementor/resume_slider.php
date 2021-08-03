<?php
/**
*
* @package Cariera
*
* @since 1.4.0
* 
* ========================
* ELEMENTOR WIDGET - RESUME SLIDER
* ========================
*     
**/



namespace Elementor;



// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}




class Cariera_Resume_Slider extends Widget_Base {

    /**
    * Get widget's name.
    */
    public function get_name() {
        return 'resume_slider';
    }

    
    
    /**
    * Get widget's title.
    */
    public function get_title() {
        return esc_html__( 'Resume Slider', 'cariera' );
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
                'label'         => esc_html__( 'Total Resumes', 'cariera' ),
                'type'          => Controls_Manager::TEXT,
                'default'       => '',
                'description'   => esc_html__( 'Leave it blank to display all featured resumes.', 'cariera' )
            ]
        );
        $this->add_control(
            'columns',
            [
                'label'         => esc_html__( 'Visible Resumes', 'cariera' ),
                'type'          => Controls_Manager::NUMBER,
                'default'       => '1',
                'min'           => '1',
                'max'           => '10',
                'description'   => esc_html__( 'This will change how many resumes will be visible per slide.', 'cariera' )
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

        if ( ! is_null( $settings['featured'] ) ) {
            $featured = ( is_bool( $settings['featured'] ) && $settings['featured'] ) || in_array( $settings['featured'], array( '1', 'true', 'yes' ) ) ? true : false;
        }

        $resumes = get_resumes( array(
            'orderby'           => $settings['orderby'],
            'order'             => $settings['order'],
            'posts_per_page'    => $settings['per_page'],
            'featured'          => $featured
        ) );
        
        if ( $settings['autoplay'] == 'enable' ) {
            $autoplay = '1';
        } else {
            $autoplay = '0';
        }


        if ( $resumes->have_posts() ) { ?>
            <div class="resume-carousel resume-carousel-<?php echo esc_attr($settings['version']); ?>" data-columns="<?php echo esc_attr($settings['columns']); ?>" data-autoplay="<?php echo esc_attr($autoplay); ?>">
            
                <?php while ( $resumes->have_posts() ) : $resumes->the_post();
                    $id = get_the_id(); ?>
                    <div class="single-resume">       
                        <a href="<?php the_resume_permalink(); ?>" id="resume-link">
        
                            <!-- Candidate Photo -->
                            <div class="candidate-photo-wrapper">
                                <div class="candidate-photo">
                                <?php the_candidate_photo(); ?>
                                </div>
                            </div>
                            
                            <?php if ( $settings['version'] == '1' ) { ?>
                                <div class="candidate-title">
                                    <h5><?php the_title(); ?></h5>
                                </div>

                                <div class="candidate-info">
                                    <span class="occupation">
                                        <i class="icon-bulb"></i>
                                        <?php the_candidate_title(); ?>
                                    </span> 

                                    <span class="location">
                                        <i class="icon-location-pin"></i>
                                        <?php the_candidate_location( false ); ?>
                                    </span>
                                </div>
                            <?php } ?>
                
                        </a>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php }
        
        $output =  ob_get_clean();

        echo $output;
    }
    
}