<?php

/**
*
* @package Cariera
*
* @since    1.4.5
* @version  1.5.0
* 
* ========================
* COMPANY LISTING CONTENT - GRID VER. 1
* ========================
*     
**/



global $post;
$company_class = 'company-grid single_company_1 col-lg-4 col-md-6 col-xs-12';

$logo       = get_the_company_logo();
$featured   = get_post_meta( get_the_ID(), '_featured', true ) == 1 ? 'featured' : '';

if ( !empty($logo) ) {
    $logo_img = $logo;
} else {
    $logo_img = get_template_directory_uri() . '/assets/images/company.png';
} ?>



<li <?php cariera_company_class(esc_attr($company_class)); ?> data-latitude="<?php echo esc_attr( $post->geolocation_lat ); ?>" data-longitude="<?php echo esc_attr( $post->geolocation_long ); ?>" data-thumbnail="<?php echo esc_attr($logo_img); ?>" data-id="listing-id-<?php echo get_the_ID(); ?>" data-featured="<?php echo esc_attr($featured); ?>">
    <div class="company-content-wrapper">

        <!-- Company Content Body -->
        <div class="company-content-body">
            <!-- Company Logo -->
            <div class="company-logo">
                <?php cariera_the_company_logo(); ?>
            </div>

            <!-- Company Info -->
            <div class="company-info">
                <div class="company-title">
                    <a href="<?php cariera_the_company_permalink(); ?>">
                        <h4 class="title"><?php the_title(); ?></h4>
                    </a>
                </div>
            </div>
        </div>


        <!-- Company Content Footer -->
        <div class="company-content-footer">
            <div class="company-details">

                <div class="location">
                    <h5 class="title"><?php esc_html_e( 'Location', 'cariera' ); ?></h5>
                    <span><?php echo cariera_get_the_company_location( false ); ?></span>
                </div>

                <div class="company-jobs">
                    <h5 class="title"><?php esc_html_e( 'Open Positions', 'cariera' ); ?></h5>
                    <span>
                        <?php echo apply_filters( 'cariera_company_open_positions_info', esc_html( sprintf( _n( '%s Job', '%s Jobs', cariera_get_the_company_job_listing_count(), 'cariera' ), cariera_get_the_company_job_listing_count() ) ) ); ?>
                    </span>
                </div>

            </div>
        </div>

    </div>
</li>