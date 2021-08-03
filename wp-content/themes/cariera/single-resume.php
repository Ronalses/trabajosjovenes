<?php
/**
*
* @package Cariera
*
* @since 1.0.0
* 
* ========================
* SINGLE RESUME TEMPLATE
* ========================
*     
**/



get_header();
do_action( 'cariera_single_listing_data' );


while ( have_posts() ) : the_post();

    do_action( 'cariera_single_resume_before' );

    get_job_manager_template( 'content-single-resume.php' , array() , 'wp-job-manager-resumes' );

    do_action( 'cariera_single_resume_after' );
    
endwhile; // End of the loop.


get_footer();