<?php
/**
*
* @package Cariera
*
* @since 1.2.8
* 
* ========================
* Template Name: Half Map - Jobs
* ========================
*    
**/



get_header(); 

$half_map_side  = cariera_get_option('cariera_job_half_map_layout');
$job_layout     = cariera_get_option('cariera_half_map_single_job_layout');

if( $half_map_side == 'left-side' ) {
    $map_side = 'map-holder-right';
    $job_side = 'job-holder-left';
} else {
    $map_side = 'map-holder-left';
    $job_side = 'job-holder-right';
}
?>


<main class="half-map-wrapper jobs-half-map">
    
    <!-- Map Holder -->           
    <div class="map-holder <?php echo esc_attr($map_side); ?>">
        <a href="#" class="list-view"><i class="icon-list"></i><?php esc_html_e( 'List view', 'cariera' ); ?></a>
           
        <?php echo do_shortcode('[job_resume_map map_height="100%"]'); ?>
    </div>
    
    
    <!-- Job Holder -->
    <div class="job-holder <?php echo esc_attr($job_side); ?>">
        <h3 class="title"><?php echo esc_html( cariera_get_option('cariera_job_half_map_text') ); ?></h3>
        
        <a href="#" class="map-view"><i class="icon-map"></i><?php esc_html_e( 'Map view', 'cariera'); ?></a>
            
        <?php if( $job_layout == '1' ) {
            echo do_shortcode('[jobs show_pagination="true"]');
        } elseif( $job_layout == '2' ) {
            echo do_shortcode('[jobs jobs_list_version="2"]');
        } elseif( $job_layout == '3' ) {
            echo do_shortcode('[jobs jobs_list_version="3"]');
        } elseif( $job_layout == '4' ) {
            echo do_shortcode('[jobs jobs_list_version="4"]');
        } else {
            echo do_shortcode('[jobs jobs_list_version="5"]');
        } ?>
    </div>

</main>

            
<?php get_footer(); ?>