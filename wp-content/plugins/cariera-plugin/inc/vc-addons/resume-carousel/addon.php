<?php

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}



vc_map( array(
    'name'                       => esc_html__( 'Resume Carousel', 'cariera' ),
    'description'                => esc_html__( 'Shows carousel with selected resumes', 'cariera' ),
    'base'                       => 'resume_carousel',
    "show_settings_on_create"    => true,
    "category"                   => 'Resume Manager',
    "params"                     => array(
        array(
            "type"          => "dropdown",
            "class"         => "",
            "heading"       => esc_html__("Layout Version", 'cariera'),
            "param_name"    => "version",
            "value"         => array(
                '1' => '1',
                '2' => '2',
            ),
            'save_always'   => true,
        ),
        array(
            'type'          => 'textfield',
            'heading'       => esc_html__('Total items', 'cariera'),
            'param_name'    => 'per_page',
            'value'         => '', // default value
            'description'   => esc_html__('Leave it blank to display all featured resumes.', 'cariera'),
        ),
        array(
            'type'          => 'textfield',
            'heading'       => esc_html__('Visible resumes', 'cariera'),
            'param_name'    => 'columns',
            'value'         => '1', // default value
            'description'   => esc_html__('This will change how many resumes will be visible per slider.', 'cariera'),
        ),
        array(
            "type"          => "checkbox",
            "heading"       => esc_html__("Autoplay", 'cariera'),
            "param_name"    => "autoplay",
            "value"         => array(
                'Enable'        => '1',
            ),
        ),
        array(
            'type'          => 'dropdown',
            'heading'       => esc_html__('Order by', 'cariera'),
            'param_name'    => 'orderby',
            'value'         => array(
                'Featured'      => 'featured',
                'Date'          => 'date',
                'ID'            => 'ID',
                'Author'        => 'author',
                'Title'         => 'title',
                'Modified'      => 'modified',
                'Random'        => 'rand',
            ),
        ),
        array(
            'type'          => 'dropdown',
            'heading'       => esc_html__('Order', 'cariera'),
            'param_name'    => 'order',
            'value'         => array(
                'Descending'    => 'DESC',
                'Ascending'     => 'ASC'
            ),
        ),
        array(
            'type'          => 'autocomplete',
            'heading'       => esc_html__('From Categories only', 'cariera'),
            'param_name'    => 'categories',
            'description'   => esc_html__('Add resume categories.', 'cariera'),
            'settings'      => array(
                'multiple'      => true,
                'sortable'      => true,
            ),
        ),
        array(
            'type'          => 'dropdown',
            'heading'       => esc_html__('Featured', 'cariera'),
            'param_name'    => 'featured',
            'value'         => array(
                'Show only featured'    => true,
                'Show all'              => false,
            ),
            'save_always'   => true,
        ),
    )
) );






/*
Shortcode logic how it should be rendered
*/
if ( !function_exists( 'resume_carousel' ) ) {
    function resume_carousel( $atts, $content = null ) {
        ob_start();
        
        extract( $atts = shortcode_atts( apply_filters(
            'resume_manager_output_resumes_defaults', array(
                'version'                   => '1',
                'per_page'                  => get_option( 'resume_manager_per_page' ),
                'columns'                   => '1',
                'autoplay'                  => '',
                'orderby'                   => 'featured',
                'order'                     => 'DESC',
                'categories'                => '',
                'featured'                  => 'true', // True to show only featured, false to hide featured, leave null to show both.
            ) 
        ), $atts ) );
        
        $randID = rand(1, 99); 
        
        
        $categories = is_array( $categories ) ? $categories : array_filter( array_map( 'trim', explode( ',', $categories ) ) );
        if ( ! is_null( $featured ) ) {
            $featured = ( is_bool( $featured ) && $featured ) || in_array( $featured, array( '1', 'true', 'yes' ) ) ? true : false;
        }
        
        $resumes = get_resumes( array(
            'search_categories' => $categories,
            'orderby'           => $orderby,
            'order'             => $order,
            'posts_per_page'    => $per_page,
            'featured'          => $featured
        ) );
        
        if ( empty($autoplay) ) {
            $autoplay = '0';
        }
        
        
        
        if ( $resumes->have_posts() ) { ?>

            <div class="resume-carousel resume-carousel-<?php echo esc_attr($version); ?>" data-columns="<?php echo esc_attr($columns); ?>" data-autoplay="<?php echo esc_attr($autoplay); ?>">
            
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
                            
                            <?php if ( $version == '1' ) { ?>
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
        
        $resumes_output =  ob_get_clean();

        return $resumes_output;
    }
}

add_shortcode( 'resume_carousel', 'resume_carousel');