<?php
/**
*
* @package Cariera
*
* @since 1.0.0
* 
* ========================
* SINGLE JOB LISTING TEMPLATE
* ========================
*     
**/


get_header();
do_action( 'cariera_single_listing_data' );


while ( have_posts() ) : the_post();

    do_action( 'cariera_single_job_listing_start' );
    
    get_job_manager_template( 'content-single-job_listing.php' );

    do_action( 'cariera_single_job_listing_end' );

endwhile; // End of the loop.


get_footer();