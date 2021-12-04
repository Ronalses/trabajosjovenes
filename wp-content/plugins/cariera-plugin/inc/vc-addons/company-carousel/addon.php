<?php

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}






vc_map( array(
    'name'                       => esc_html__( 'Company Carousel', 'cariera' ),
    'description'                => esc_html__( 'Shows carousel with selected companies', 'cariera' ),
    'base'                       => 'company_carousel',
    "show_settings_on_create"    => true,
    "category"                   => 'Job Manager',
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
            'description'   => esc_html__('Leave it blank to display all companies.', 'cariera'),
        ),
        array(
            'type'          => 'textfield',
            'heading'       => esc_html__('Visible Companies', 'cariera'),
            'param_name'    => 'columns',
            'value'         => '1', // default value
            'description'   => esc_html__('This will change how many companies will be visible per slider.', 'cariera'),
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
            'type'          => 'dropdown',
            'heading'       => esc_html__('Featured', 'cariera'),
            'param_name'    => 'featured',
            'value'         => array(
                'Show all'              => '',
                'Show only featured'    => true,
                'Hide Featured'         => false,
            ),
            'save_always'   => true,
        ),
        array(
            'type'          => 'dropdown',
            'heading'       => esc_html__( 'Companies with Jobs', 'cariera' ),
            'param_name'    => 'companies_jobs',
            'value'         => array(
                'Show all'              => '',
                'Show only companies with jobs'    => true,
            ),
            'save_always'   => true,
        ),

    )
) );






/*
Shortcode logic how it should be rendered
*/
if ( !function_exists( 'company_carousel' ) ) {
    function company_carousel( $atts, $content = null ) {
        global $post;
        
        ob_start();

        extract( $atts = shortcode_atts( apply_filters(
            'companies_manager_output_companies_defaults', array(
                'version'                   => '1',
                'per_page'                  => get_option( 'cariera_companies_per_page' ),
                'columns'                   => '1',
                'autoplay'                  => '',
                'orderby'                   => 'featured',
                'order'                     => 'DESC',
                'featured'                  => '', // True to show only featured, false to hide featured, leave null to show both.
            ) 
        ), $atts ) );
        
        $randID = rand(1, 99); 
        
        if ( ! is_null( $featured ) ) {
            $featured = ( is_bool( $featured ) && $featured ) || in_array( $featured, array( '1', 'true', 'yes' ) ) ? true : false;
        }
        
        $companies = cariera_get_companies( array(
            'orderby'           => $orderby,
            'order'             => $order,
            'posts_per_page'    => $per_page,
            'featured'          => $featured
        ) );
        
        if ( empty($autoplay) ) {
            $autoplay = '0';
        }        
        
        
        if ( $companies->have_posts() ) { ?>

            <div class="company-carousel company-carousel-<?php echo esc_attr($version); ?>" data-columns="<?php echo esc_attr($columns); ?>" data-autoplay="<?php echo esc_attr($autoplay); ?>">
            
                <?php while ( $companies->have_posts() ) : $companies->the_post(); ?>
                    <div class="single-company">
                        
                        
                        <a href="<?php cariera_the_company_permalink(); ?>" id="company-link">
        
                            <!-- Company Logo -->
                            <?php if ( $version == '1') { ?>
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
        
        $companies_output =  ob_get_clean();

        return $companies_output;
    }
}

add_shortcode( 'company_carousel', 'company_carousel');