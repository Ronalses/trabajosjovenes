<?php
/**
*
* @package Cariera
*
* @since 1.3.0
* 
* ========================
* SIDEBAR - RESUME
* ========================
*     
**/
?>



<div class="col-md-4 col-xs-12 company-sidebar">
    <?php
    get_job_manager_template( 'company-overview.php', [], 'wp-job-manager-companies' );


    $company_map = cariera_get_option( 'cariera_company_map' );
    $lng         = $post->geolocation_long;
    $logo        = get_the_company_logo();
    
    if ( !empty($logo) ) {
        $logo_img = $logo;
    } else {
        $logo_img = get_template_directory_uri() . '/assets/images/company.png';
    }

    if( $company_map && !empty($lng) ) { ?>
        <aside class="mt40">
            <div id="company-map" data-longitude="<?php echo esc_attr( $post->geolocation_long ); ?>" data-latitude="<?php echo esc_attr( $post->geolocation_lat ); ?>" data-thumbnail="<?php echo esc_attr($logo_img); ?>" data-id="listing-id-<?php echo get_the_ID(); ?>"></div>
        </aside>
   <?php } ?>

   <?php dynamic_sidebar('sidebar-single-company'); ?>

</div>