<?php
/**
*
* @package Cariera
*
* @since    1.2.8
* @version  1.5.1
* 
* ========================
* Template Name: Half Map - Resumes
* ========================
*    
**/



get_header(); 

$half_map_side  = cariera_get_option('cariera_resume_half_map_layout');

if( $half_map_side == 'left-side' ) {
    $map_side = 'map-holder-right';
    $resume_side = 'job-holder-left';
} else {
    $map_side = 'map-holder-left';
    $resume_side = 'job-holder-right';
} ?>


<main class="half-map-wrapper resumes-half-map">
    <?php if( cariera_wp_resume_manager_is_activated() ) { ?>
        <!-- Map Holder -->
        <div class="map-holder <?php echo esc_attr($map_side); ?>">
            <a href="#" class="list-view"><i class="icon-list"></i><?php esc_html_e( 'List view', 'cariera' ); ?></a>

            <?php echo do_shortcode('[job_resume_map map_height="100%" type="resume"]'); ?>
        </div>


        <!-- Job Holder -->
        <div class="resume-holder <?php echo esc_attr($resume_side); ?>">
            <h3 class="title"><?php echo esc_html( cariera_get_option('cariera_resume_half_map_text') ); ?></h3>

            <a href="#" class="map-view"><i class="icon-map"></i><?php esc_html_e( 'Map view', 'cariera'); ?></a>

            <?php echo do_shortcode('[resumes]'); ?>
        </div> 
    <?php } else { ?>
        <div class="container">
            <div class="col-md-12">
                <div class="job-manager-message error mt80">
                    <span><?php esc_html_e( 'Please activate the "Resume Manager" plugin in order to make this template work.', 'cariera'); ?></span>
                </div>
            </div>        
        </div>
    <?php } ?>
</main>

            
<?php get_footer(); ?>