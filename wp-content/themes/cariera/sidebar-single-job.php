<?php
/**
*
* @package Cariera
*
* @since 1.0.0
* 
* ========================
* SIDEBAR - SINGLE JOB
* ========================
*     
**/



$jobs_layout    = cariera_get_option( 'cariera_single_job_layout' );
$layout         = ( $jobs_layout == 'left-sidebar' ) ? 'sidebar-left' : ''; ?>


<div class="col-md-4 col-xs-12 job-sidebar <?php echo esc_attr($layout); ?>">
    <?php
    get_job_manager_template( 'job-listing-overview.php' );

    $job_map = cariera_get_option( 'cariera_job_map' );
    $lng     = $post->geolocation_long;

    if ( cariera_core_is_activated() ) {
        $company = get_post( cariera_get_the_company() );
    } else {
        $company = '';
    }

    if ( !empty( $company ) && has_post_thumbnail( $company ) ) {
        $logo = get_the_company_logo( $company, apply_filters( 'cariera_company_logo_size', 'thumbnail' ) );
    } else {
        $logo = get_the_company_logo();
    }


    if ( !empty($logo) ) {
        $logo_img = $logo;
    } else {
        $logo_img = get_template_directory_uri() . '/assets/images/company.png';
    }

    if( $job_map && !empty($lng) ) { ?>
        <aside class="mt40">
            <div id="job-map" data-longitude="<?php echo esc_attr( $post->geolocation_long ); ?>" data-latitude="<?php echo esc_attr( $post->geolocation_lat ); ?>" data-thumbnail="<?php echo esc_attr($logo_img); ?>" data-id="listing-id-<?php echo get_the_ID(); ?>"></div>
        </aside>
    <?php }

    dynamic_sidebar('sidebar-single-job'); ?>
</div>