<?php

/**
*
* @package Cariera
*
* @since    1.2.5
* @version  1.5.0
* 
* ========================
* JOB LISTING CONTENT - GRID VER. 1
* ========================
*     
**/ 


global $post;

$job_class = 'job-grid single_job_listing_1 col-md-4 col-sm-6 col-xs-12';

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
        <?php
        // Show featured badge if job is featured
        if ( is_position_featured($post->ID) ) {
            echo '<span class="featured-badge">' . esc_html__( 'featured', 'cariera' ) . '</span>';
        }

        $id = get_post_meta($post->ID, 'cariera_job_page_header', true);

        // Filtering the id to see if the image is uploaded from the backend or the frontend
        if (filter_var($id, FILTER_VALIDATE_URL) === FALSE) {
            $image = wp_get_attachment_url($id);
        } else {
            $image = get_post_meta($post->ID, 'cariera_job_page_header', true);
        } ?>
        
        <!-- Listing Media -->
        <div class="listing-media">
            <?php if( !empty($id) ) { ?>
                <div class="job-company with-bg" style="background-image: url(<?php echo esc_attr($image); ?>);">
            <?php } else { ?>
                <div class="job-company">
            <?php }
            
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
                    
                    <!-- Tag Group -->
                    <div class="tag-group">
                        <?php if ( get_option( 'job_manager_enable_types' ) ) {
                            $types = wpjm_get_the_job_types();
                            if ( ! empty( $types ) ) { 
                                foreach ( $types as $type ) { ?>
                                    <span class="job-type <?php echo esc_attr( sanitize_title( $type->slug ) ); ?>"><?php echo esc_html( $type->name ); ?></span>
                                <?php }
                                
                                if( cariera_newly_posted() ) {
                                    echo '<span class="job-item-badge new-job">' . esc_html__( 'New', 'cariera' ) . '</span>';
                                }
                            }
                        } ?>
                    </div>
                    
                    <!-- Job Category -->
                    <div class="job-cat">
                        <?php
                        $job_categories = wp_get_post_terms( $post->ID, 'job_listing_category' ); 
                        if ( !empty( $job_categories ) ) {
                            echo '<span>' . $job_categories[0]->slug . '</span>';
                        } ?>
                    </div>
                    
                </div> 
        </div>
        
        
        
        <!-- Listing Body -->    
        <div class="listing-body">
            <div class="job-title">
                <a href="<?php the_job_permalink(); ?>">
                    <h5 class="title">
                        <?php the_title(); ?>
                        <?php do_action( 'cariera_job_listing_status' ); ?>    
                    </h5>
                </a>
            </div>

            <div class="job-info">
                <?php do_action( 'job_listing_info_start' ); ?>
                
                <?php if ( cariera_get_the_company() ) { ?>
                    <span class="company">
                        <strong><?php esc_attr_e( 'Company: ', 'cariera'); ?></strong>
                        <?php the_company_name(); ?>
                    </span>
                <?php } ?>
                
                <span class="location">
                    <strong><?php esc_attr_e( 'Location: ', 'cariera'); ?></strong>
                    <?php the_job_location( false ); ?>
                </span>

                <?php 
                $rate_min = get_post_meta( $post->ID, '_rate_min', true );                
                if ( $rate_min) { 
                    $rate_max = get_post_meta( $post->ID, '_rate_max', true );  ?>
                    <span class="rate">
                        <strong><?php esc_attr_e( 'Rate: ', 'cariera'); ?></strong>
                        <?php cariera_job_rate(); ?>
                    </span>
                <?php } ?>

                <?php 
                $salary_min = get_post_meta( $post->ID, '_salary_min', true ); 
                if ( $salary_min ) {
                    $salary_max = get_post_meta( $post->ID, '_salary_max', true );  ?>
                    <span class="salary">
                        <strong><?php esc_attr_e( 'Salary: ', 'cariera'); ?></strong>
                        <?php cariera_job_salary(); ?>
                    </span>
                <?php } ?>

                <?php do_action( 'job_listing_info_end' ); ?>
            </div>
            
            <!-- Job Actions -->
            <div class="job-actions">
                <a href="<?php the_job_permalink(); ?>" class="btn btn-main btn-effect"><?php esc_attr_e( 'view details', 'cariera'); ?></a>
                <a href="#quickview" class="job-quickview" data-id="<?php echo esc_attr($job_id); ?>"><i class="icon-eye"></i></a>
            </div>            
        </div>
        
    </div>
</li>