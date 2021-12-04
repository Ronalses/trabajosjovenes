<?php

/**
*
* @package Cariera
*
* @since    1.4.4
* @version  1.5.0
* 
* ========================
* JOB LISTING CONTENT - LIST VER. 5
* ========================
*     
**/



global $post; 

$job_class = 'job-list single_job_listing_5';

$job_id     = get_the_ID();
$company    = get_post( cariera_get_the_company() );
$logo       = get_the_company_logo();
$featured   = get_post_meta( $job_id, '_featured', true ) == 1 ? 'featured' : '';

if ( !empty($logo) ) {
    $logo_img = $logo;
} else {
    $logo_img = apply_filters( 'job_manager_default_company_logo', get_template_directory_uri() . '/assets/images/company.png' );
} ?>



<li <?php job_listing_class(esc_attr($job_class)); ?> data-latitude="<?php echo esc_attr( $post->geolocation_lat ); ?>" data-longitude="<?php echo esc_attr( $post->geolocation_long ); ?>" data-thumbnail="<?php echo esc_attr($logo_img); ?>" data-id="listing-id-<?php echo get_the_ID(); ?>" data-featured="<?php echo esc_attr($featured); ?>">
    <div class="job-content-wrapper">

        <!-- Job Content Body -->
        <div class="job-content-body">
            <!-- Company Logo -->
            <div class="job-company">
                <?php
                // Make the logo link to the company if the core plugin is installed and activated
                if ( ! empty ( $company ) ) { ?>
                    <a href="<?php echo esc_url( get_permalink($company) ); ?>" title="<?php echo esc_attr__('Company page', 'cariera'); ?>">
                <?php }

                // Company Logo                    
                if ( !empty( $company ) && has_post_thumbnail( $company ) ) {
                    $logo = get_the_company_logo( $company, apply_filters( 'cariera_company_logo_size', 'thumbnail' ) );
                    echo '<img class="company_logo" src="' . esc_url( $logo ) . '" alt="' . esc_attr( get_the_company_name( $company ) ) . '" />';
                } else {
                    cariera_the_company_logo();
                }

                if ( ! empty ( $company ) ) { ?>
                    </a>
                <?php } ?>
            </div>

            <!-- Job Info -->
            <div class="job-info">
                <div class="job-title">
                    <a href="<?php the_job_permalink(); ?>">
                        <h5 class="title">
                            <?php the_title(); ?>
                            <?php do_action( 'cariera_job_listing_status' ); ?>    
                        </h5>
                    </a>
                </div>

                <?php if ( cariera_get_the_company() ) { ?>
                    <div class="company">
                        <a href="<?php echo esc_url( get_permalink($company) ); ?>">
                            <?php the_company_name(); ?>
                        </a>
                    </div>
                <?php } ?>
            </div>
        </div>


        <!-- Job Content Footer -->
        <div class="job-content-footer">
            <div class="job-details">
                <?php do_action( 'job_listing_info_start' ); ?>
                
                <div class="location">
                    <h5 class="title"><?php esc_html_e( 'Location', 'cariera' ); ?></h5>
                    <span><?php the_job_location( false ); ?></span>
                </div>

                <?php 
                $rate_min = get_post_meta( $post->ID, '_rate_min', true );                
                if ( $rate_min) { 
                    $rate_max = get_post_meta( $post->ID, '_rate_max', true );  ?>
                    <div class="rate">
                        <h5 class="title"><?php esc_html_e( 'Rate', 'cariera' ); ?></h5>
                        <span><?php cariera_job_rate(); ?></span>
                    </div>
                <?php } ?>

                <?php 
                $salary_min = get_post_meta( $post->ID, '_salary_min', true ); 
                if ( $salary_min ) {
                    $salary_max = get_post_meta( $post->ID, '_salary_max', true );  ?>
                    <div class="salary">
                        <h5 class="title"><?php esc_html_e( 'Salary', 'cariera' ); ?></h5>
                        <span><?php cariera_job_salary(); ?></span>
                    </div>
                <?php } ?>

                <div class="published">
                    <h5 class="title"><?php esc_html_e( 'Published', 'cariera' ); ?></h5>
                    <span><?php the_job_publish_date(); ?></span>
                </div>
                
                <?php do_action( 'job_listing_info_end' ); ?>

                <div class="job-types-container">
                    <?php if ( get_option( 'job_manager_enable_types' ) ) {
                        $types = wpjm_get_the_job_types();
                        if ( ! empty( $types ) ) { 
                            foreach ( $types as $type ) { ?>
                                <span class="job-type <?php echo esc_attr( sanitize_title( $type->slug ) ); ?>"><?php echo esc_html( $type->name ); ?></span>
                            <?php }
                        }
                    } ?>
                </div>
            </div>
        </div>
        
    </div>
</li>