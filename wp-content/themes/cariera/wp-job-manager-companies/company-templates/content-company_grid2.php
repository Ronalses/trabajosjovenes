<?php

/**
*
* @package Cariera
*
* @since    1.4.5
* @version  1.5.0
* 
* ========================
* COMPANY LISTING CONTENT - GRID VER. 2
* ========================
*     
**/



global $post;
$company_class = 'company-grid single_company_2 col-lg-4 col-md-6 col-xs-12';

$logo       = get_the_company_logo();
$featured   = get_post_meta( get_the_ID(), '_featured', true ) == 1 ? 'featured' : '';

if ( !empty($logo) ) {
    $logo_img = $logo;
} else {
    $logo_img = get_template_directory_uri() . '/assets/images/company.png';
} ?>



<li <?php cariera_company_class(esc_attr($company_class)); ?> data-latitude="<?php echo esc_attr( $post->geolocation_lat ); ?>" data-longitude="<?php echo esc_attr( $post->geolocation_long ); ?>" data-thumbnail="<?php echo esc_attr($logo_img); ?>" data-id="listing-id-<?php echo get_the_ID(); ?>" data-featured="<?php echo esc_attr($featured); ?>">
    <div class="company-content-wrapper">
        <a href="<?php cariera_the_company_permalink(); ?>">

            <div class="company-logo-wrapper">          
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
</li>