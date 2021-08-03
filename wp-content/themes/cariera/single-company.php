<?php
/**
*
* @package Cariera
*
* @since 1.2.5
* 
* ========================
* SINGLE COMPANY TEMPLATE
* ========================
*     
**/



get_header(); 
do_action( 'cariera_single_listing_data' );


while ( have_posts() ) : the_post();

    do_action( 'cariera_single_company_before' );

    get_job_manager_template( 'content-single-company.php' , array() , 'wp-job-manager-companies' );

    do_action( 'cariera_single_company_after' );

endwhile; // End of the loop.


get_footer();