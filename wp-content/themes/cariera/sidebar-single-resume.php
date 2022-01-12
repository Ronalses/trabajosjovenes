<?php
/**
*
* @package Cariera
*
* @since 1.3.0
* 
* ========================
* SIDEBAR - SINGLE RESUME
* ========================
*     
**/
?>



<div class="col-md-4 col-xs-12 resume-sidebar">       
    <?php
    get_job_manager_template( 'resume-overview.php', [], 'wp-job-manager-resumes' );

    $resume_map = cariera_get_option( 'cariera_resume_map' );
    $lng        = $post->geolocation_long;
    $logo       = get_the_candidate_photo();

    if ( !empty($logo) ) {
        $logo_img = $logo;
    } else {
        $logo_img = get_template_directory_uri() . '/assets/images/candidate.png';
    } 

    if ( $resume_map && !empty($lng) ) { ?>
        <aside class="mt40">
            <div id="resume-map" data-longitude="<?php echo esc_attr( $post->geolocation_long ); ?>" data-latitude="<?php echo esc_attr( $post->geolocation_lat ); ?>" data-thumbnail="<?php echo esc_attr($logo_img); ?>" data-id="listing-id-<?php echo get_the_ID(); ?>"></div>
        </aside>
    <?php } ?>

    <?php dynamic_sidebar('sidebar-single-resume'); ?>
</div>