<?php
/**
*
* @package Cariera
*
* @since 1.4.5
* 
* ========================
* ELEMENTOR WIDGET - TESTIMONIALS
* ========================
*     
**/



namespace Elementor;



// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}




class Cariera_Testimonials extends Widget_Base {

    /**
    * Get widget's name.
    */
    public function get_name() {
        return 'testimonials';
    }

    
    
    /**
    * Get widget's title.
    */
    public function get_title() {
        return esc_html__( 'Testimonials', 'cariera' );
    }

    
    
    /**
    * Get widget's icon.
    */
    public function get_icon() {
        return 'eicon-slider-device';
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
            'layout_style',
            [
                'label'         => esc_html__( 'Testimonial Style', 'cariera' ),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'layout1'       => esc_html__( 'Layout 1', 'cariera' ),
                    'layout2'       => esc_html__( 'Layout 2', 'cariera' ),
                    'layout3'       => esc_html__( 'Layout 3', 'cariera' ),
                ],
                'default'       => 'layout1',
                'description'   => ''
            ]
        );
        $this->add_control(
            'layout_color',
            [
                'label'         => esc_html__( 'Layout Color', 'cariera' ),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'light-skin'    => esc_html__( 'Light', 'cariera' ),
                    'dark-skin'     => esc_html__( 'Dark', 'cariera' ),
                ],
                'default'       => 'light-skin',
                'description'   => '',
                'condition'     => [
					'layout_style'  => 'layout1',
				],
            ]
        );
        $this->add_control(
            'show_testimonials',
            [
                'label'         => esc_html__( 'Show Testimonials', 'cariera' ),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'random'        => esc_html__( 'Random', 'cariera' ),
                    'latest'        => esc_html__( 'Latest', 'cariera' ),
                    'selected'      => esc_html__( 'Selected IDs', 'cariera' ),
                ],
                'default'       => 'random',
                'description'   => ''
            ]
        );
        $this->add_control(
            'ids',
            [
                'label'         => esc_html__( 'Enter Post IDs', 'cariera' ),
                'type'          => Controls_Manager::TEXT,
                'default'       => '',
                'label_block'   => true,
                'description'   => esc_html__( 'Enter Post ID\'s separated by a comma. Leave empty to show all.', 'cariera' ),
                'condition'     => [
					'show_testimonials' => 'selected',
				],
            ]
        );
        $this->add_control(
            'posts_per_page',
            [
                'label'         => esc_html__( 'Posts to show', 'cariera' ),
                'type'          => Controls_Manager::NUMBER,
                'default'       => '5',
                'description'   => esc_html__( 'Number of testimonials to show (-1 for all).', 'cariera' ),
                'condition'     => [
					'show_testimonials' => ['random', 'latest'],
				],
            ]
        );
        
        $this->end_controls_section();
    }


    
    /**
    * Widget output
    */
    protected function render() {
        $settings   = $this->get_settings();

        if ( $settings['layout_style'] == 'layout1' ) {
            $layout_class = 'testimonials-carousel-style1';
        } elseif ( $settings['layout_style'] == 'layout2' ) {
            $layout_class = 'testimonials-carousel-style2';
        } elseif ( $settings['layout_style'] == 'layout3' ) {
            $layout_class = 'testimonials-carousel-style3';
        } elseif ( $settings['layout_style'] == 'layout4' ) {
            $layout_class = 'testimonials-carousel-style4';
        }

        if ( $settings['show_testimonials'] == 'selected') {
            $show_only_ids = explode(",", $settings['ids']);
            $args = array(
                'post_type' => 'testimonial',
                'post__in'  => $show_only_ids,
            );
        } else {
            $args = array(
                'post_type'      => 'testimonial',
                'posts_per_page' => $settings['posts_per_page'],
            );
        }



        echo '<div class="testimonials-carousel ' . $layout_class . '">';

        $posts_query = new \WP_Query($args);
        if( $posts_query->have_posts() ) {
            while( $posts_query->have_posts() ) : $posts_query->the_post(); 
                global $post;
                echo '<div class="testimonial-item">';

                    // TESTIMONIALS LAYOUT 1
                    if ( $settings['layout_style'] == 'layout1') {
                        $byline = get_post_meta( $post->ID, 'cariera_testimonial_byline', true );
                        $url = get_post_meta( $post->ID, 'cariera_testimonial_url', true );
                        ?>

                        <div class="testimonial <?php echo $settings['layout_color']; ?>">
                            <div class="review">
                                <blockquote><?php the_content(''); ?></blockquote>
                            </div>

                            <div class="customer">
                                <?php
                                    if( has_post_thumbnail() ) : ?>
                                        <?php if( !empty( $url ) ) : ?> 
                                            <a href="<?php echo esc_url($url); ?>" target="_blank" class="circle-img">
                                                <?php the_post_thumbnail( 'testimonial' ); ?>
                                            </a>
                                        <?php else : ?>
                                            <span class="circle-img">
                                                <?php the_post_thumbnail( 'testimonial' ); ?>
                                            </span>
                                        <?php endif;
                                    endif;
                                ?>

                                <h4 class="title"><?php echo get_the_title(); ?></h4>
                                <?php if( !empty( $url ) ) : ?>
                                    <a href="<?php echo esc_url($url); ?>" target="_blank"><cite title="<?php echo esc_html($byline); ?>"><?php echo esc_html($byline); ?></cite></a>
                                <?php else : ?>
                                    <cite title="<?php echo esc_html($byline); ?>"><?php echo esc_html($byline); ?></cite>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php
                    }


                    // TESTIMONIALS LAYOUT 1
                    elseif ( $settings['layout_style'] == 'layout2') {
                        get_template_part('/templates/content/content-testimonial2');
                    }


                    // TESTIMONIALS LAYOUT 3
                    elseif ( $settings['layout_style'] == 'layout3') {
                        $byline = get_post_meta( $post->ID, 'cariera_testimonial_byline', true );
                        ?>

                        <div class="testimonial">

                            <i class="fas fa-quote-left"></i>

                            <blockquote><?php the_content(''); ?></blockquote>

                            <div class="customer">
                                <?php
                                if( has_post_thumbnail() ) { ?>
                                    <div class="avatar">
                                        <?php the_post_thumbnail( 'testimonial' ); ?>
                                    </div>
                                <?php } ?>
                                
                                <div class="details">
                                    <h4 class="title"><?php echo get_the_title(); ?></h4>
                                    <cite title="<?php echo esc_html($byline); ?>"><?php echo esc_html($byline); ?></cite>
                                </div>                                
                            </div>                            
                        </div>
                        
                        <?php
                        //echo 'whats going on!!!';
                    }

                echo '</div>';

            endwhile;
        }

        echo '</div>';

        wp_reset_postdata();
    }

}