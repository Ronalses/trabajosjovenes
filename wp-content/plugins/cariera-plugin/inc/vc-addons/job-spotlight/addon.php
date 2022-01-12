<?php

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}



vc_map( array(
    'name'                       => esc_html__( 'Job Carousel', 'cariera' ),
    'description'                => esc_html__( 'Shows carousel with selected jobs', 'cariera' ),
    'base'                       => 'spotlight_jobs',
    "show_settings_on_create"    => true,
    "category"                   => 'Job Manager',
    "params"                     => array(
        array(
            'type'          => 'textfield',
            'heading'       => esc_html__('Total items', 'cariera'),
            'param_name'    => 'per_page',
            'value'         => '', // default value
            'description'   => esc_html__('Leave it blank to display all featured jobs.', 'cariera'),
        ),
        array(
            'type'          => 'textfield',
            'heading'       => esc_html__('Visible jobs', 'cariera'),
            'param_name'    => 'columns',
            'value'         => '1', // default value
            'description'   => esc_html__('This will change how many jobs will be visible per slide.', 'cariera'),
        ),
        array(
            "type"          => "checkbox",
            "heading"       => esc_html__("Autoplay", 'cariera'),
            "param_name"    => "autoplay",
            "value"         => array(
                'Enable'   => '1',
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
            'description'   => esc_html__('Add job categories.', 'cariera'),
            'settings'      => array(
                'multiple' => true,
                'sortable' => true,
            ),
        ),
        array(
            'type'          => 'autocomplete',
            'heading'       => esc_html__('From job types only', 'cariera'),
            'param_name'    => 'job_types',
            'description'   => esc_html__('Add job types.', 'cariera'),
            'settings'      => array(
                'multiple' => true,
                'sortable' => true,
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
        array(
            'type'          => 'dropdown',
            'heading'       => esc_html__('Filled', 'cariera'),
            'param_name'    => 'filled',
            'value'         => array(
                'Show all'          => 'null',
                'Show only filled'  => 'true',
                'Hide filled'       => 'false'
            ),
            'save_always'   => true,
        ),
    )
) );

    
    
/*
Shortcode logic how it should be rendered
*/
if ( !function_exists( 'spotlight_jobs' )) {
	function spotlight_jobs( $atts, $content = null ) {
        ob_start();
    
        extract( $atts = shortcode_atts( apply_filters( 'job_manager_output_jobs_defaults', array(
            'per_page'                  => get_option( 'job_manager_per_page' ),
            'columns'                   => '1',
            'autoplay'                  => '',
            'orderby'                   => 'featured',
            'order'                     => 'DESC',
            'title'                     => 'Job Spotlight',

            // Limit what jobs are shown based on category and type
            'categories'                => '',
            'job_types'                 => '',
            'featured'                  => 'true', // True to show only featured, false to hide featured, leave null to show both.
            'filled'                    => null, // True to show only filled, false to hide filled, leave null to show both/use the settings.    
        ) ), $atts ) );


        $randID = rand(1, 99); 

        if ( ! is_null( $filled ) ) {
            $filled = ( is_bool( $filled ) && $filled ) || in_array( $filled, array( '1', 'true', 'yes' ) ) ? true : false;
        }

        // Array handling
        $categories = is_array( $categories ) ? $categories : array_filter( array_map( 'trim', explode( ',', $categories ) ) );
        $job_types = is_array( $job_types ) ? $job_types : array_filter( array_map( 'trim', explode( ',', $job_types ) ) );
        if ( ! is_null( $featured ) ) {
            $featured = ( is_bool( $featured ) && $featured ) || in_array( $featured, array( '1', 'true', 'yes' ) ) ? true : false;
        }

        $jobs = get_job_listings(  array(
            'search_categories' => $categories,
            'job_types'         => $job_types,
            'orderby'           => $orderby,
            'order'             => $order,
            'posts_per_page'    => $per_page,
            'featured'          => $featured,
            'filled'            => $filled
        ) );
        
        if( !empty($autoplay) ) {
            $autoplay = 'true';
        }

        if ( $jobs->have_posts() ) : ?>

            <div class="job-carousel" data-columns="<?php echo esc_attr($columns); ?>" data-autoplay="<?php echo esc_attr($autoplay); ?>">
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
                                <a href="<?php the_permalink(); ?>" class="btn btn-main"><?php esc_html_e('Apply For This Job','cariera') ?></a>
                            </div>
                        </div>
                        <!-- End of Featured Job Info -->

                    </div>
                <?php endwhile; ?>                
            </div><?php  

        endif; 

        $job_listings_output = ob_get_clean();

        return $job_listings_output;
	}
}

add_shortcode('spotlight_jobs', 'spotlight_jobs');