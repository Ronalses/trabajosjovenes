<?php

/**
*
* @package Cariera
*
* @since 1.4.6
* 
* ========================
* JOB LISTING OVERVIEW
* ========================
*     
**/



if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $post;

do_action( 'single_job_listing_meta_before' ); ?>


<!-- Start of Job Overview -->
<h5 class="mt-0"><?php esc_html_e( 'Job Overview', 'cariera' ); ?></h5>
<aside class="widget widget-job-overview">

    <?php do_action( 'single_job_listing_meta_start' ); ?>

    <div class="single-job-overview-detail single-job-overview-date-posted">
        <div class="icon">
            <i class="icon-calendar"></i>
        </div>

        <div class="content">
            <h6><?php esc_html_e( 'Date Posted', 'cariera' ); ?></h6>
            <span><?php the_date(); ?></span>
        </div>
    </div>


    <?php 
    $expired_date = get_post_meta( $post->ID, '_job_expires', true );
    $hide_expiration = get_post_meta( $post->ID, '_hide_expiration', true );

    if(empty($hide_expiration )) {
        if(!empty($expired_date)) { ?>
            <div class="single-job-overview-detail single-job-overview-expiration-date">
                <div class="icon">
                    <i class="icon-reload"></i>
                </div>

                <div class="content">
                    <h6><?php esc_html_e( 'Expiration Date', 'cariera' ); ?></h6>
                    <span><?php echo date_i18n( get_option( 'date_format' ), strtotime( get_post_meta( $post->ID, '_job_expires', true ) ) ); ?></span>
                </div>
            </div>
        <?php }
    } ?>


    <?php 
    if ( $deadline = get_post_meta( $post->ID, '_application_deadline', true ) ) {
        $expiring_days = apply_filters( 'job_manager_application_deadline_expiring_days', 2 );
        $expiring = ( floor( ( time() - strtotime( $deadline ) ) / ( 60 * 60 * 24 ) ) >= $expiring_days );
        $expired  = ( floor( ( time() - strtotime( $deadline ) ) / ( 60 * 60 * 24 ) ) >= 0 );        
        ?>

        <div class="single-job-overview-detail application-deadline">
            <div class="icon">
                <i class="icon-close"></i>
            </div>

            <div class="content">
                <h6><?php if( $expired ) { esc_html_e( 'Applications Closed', 'cariera' ); } else { esc_html_e( 'Applications Close', 'cariera' ); }; ?></h6>
                <span><?php echo date_i18n( get_option( 'date_format' ), strtotime( $deadline ) ); ?></span>
            </div>
        </div>
    <?php } ?>


    <div class="single-job-overview-detail single-job-overview-location">
        <div class="icon">
            <i class="icon-location-pin"></i>
        </div>

        <div class="content">
            <h6><?php esc_html_e( 'Location', 'cariera' ); ?></h6>
            <span class="location" itemprop="jobLocation"><?php the_job_location(); ?></span>
        </div>
    </div>
    

    <?php
    $career_level = get_the_terms( $post->ID, 'job_listing_career_level' );
    if ( taxonomy_exists('job_listing_career_level') && !empty($career_level) ) { ?>
        <div class="single-job-overview-detail single-job-overview-career-level">
            <div class="icon">
                <i class="icon-chart"></i>
            </div>

            <div class="content">
                <h6><?php esc_html_e( 'Career Level', 'cariera' ); ?></h6>
                <span>
                    <?php 
                    foreach ( $career_level as $value ){
                        $output_career_level[] = $value->name;
                    }

                    echo esc_html( join( ', ', $output_career_level ) ); ?>
                </span>
            </div>
        </div>
    <?php } ?>


    <?php
    $experience = get_the_terms( $post->ID, 'job_listing_experience' );
    if ( taxonomy_exists('job_listing_experience') && !empty($experience) ) { ?>
        <div class="single-job-overview-detail single-job-overview-experience">
            <div class="icon">
                <i class="icon-layers"></i>
            </div>

            <div class="content">
                <h6><?php esc_html_e( 'Experience', 'cariera' ); ?></h6>
                <span>
                    <?php 
                    foreach ( $experience as $value ){
                        $output_experience[] = $value->name;
                    }

                    echo esc_html( join( ', ', $output_experience ) ); ?>
                </span>
            </div>
        </div>
    <?php } ?>


    <?php
    $qualification = get_the_terms( $post->ID, 'job_listing_qualification' );
    if ( taxonomy_exists('job_listing_qualification') && !empty($qualification) ) { ?>
        <div class="single-job-overview-detail single-job-overview-qualification">
            <div class="icon">
                <i class="icon-briefcase"></i>
            </div>

            <div class="content">
                <h6><?php esc_html_e( 'Qualification', 'cariera' ); ?></h6>
                <span>
                    <?php 
                    foreach ( $qualification as $value ){
                        $output_qualification[] = $value->name;
                    }

                    echo esc_html( join( ', ', $output_qualification ) ); ?>
                </span>
            </div>
        </div>
    <?php } ?>


    <?php 
    $hours = get_post_meta( $post->ID, '_hours', true ); 
    if ( $hours ) { ?>
        <div class="single-job-overview-detail single-job-overview-hours">
            <div class="icon">
                <i class="icon-clock"></i>
            </div>

            <div class="content">
                <h6><?php esc_html_e( 'Hours', 'cariera' ); ?></h6>
                <span><?php printf( esc_html__( '%s hr/week', 'cariera' ), $hours ); ?></span>
            </div>
        </div>
    <?php } ?>


    <?php
    $rate_min = get_post_meta( $post->ID, '_rate_min', true );  
    if ( $rate_min ) {
        $rate_max = get_post_meta( $post->ID, '_rate_max', true );  ?>

        <div class="single-job-overview-detail single-job-overview-rate">
            <div class="icon">
                <i class="far fa-money-bill-alt"></i>
            </div>

            <div class="content">
                <h6><?php esc_html_e( 'Rate', 'cariera' ); ?></h6>
                <span><?php cariera_job_rate(); ?></span>
            </div>
        </div>
    <?php } ?>


    <?php 
    $salary_min = get_post_meta( $post->ID, '_salary_min', true ); 
    if ( $salary_min ) { 
        $salary_max = get_post_meta( $post->ID, '_salary_max', true ); ?>

        <div class="single-job-overview-detail single-job-overview-salary">
            <div class="icon">
                <i class="far fa-money-bill-alt"></i>
            </div>

            <div class="content">
                <h6><?php esc_html_e( 'Salary', 'cariera' ); ?></h6>
                <span><?php cariera_job_salary(); ?></span>
            </div>
        </div>
    <?php } ?>


    <?php
    if ( class_exists('WP_Job_Manager_Applications') ) { ?>
        <div class="single-job-overview-detail single-job-overview-applications">
            <div class="icon">
                <i class="far fa-address-card"></i>
            </div>

            <div class="content">
                <h6><?php esc_html_e( 'Job Applications', 'cariera' ); ?></h6>
                <span><?php cariera_job_applications(); ?></span>
            </div>
        </div>
    <?php } ?>

    <?php do_action( 'single_job_listing_meta_end' ); ?>


    <?php if ( candidates_can_apply() ) {

        $external_apply     = get_post_meta( $post->ID, '_apply_link', true );
        
        if( !empty($external_apply) ) {
            echo '<div class="job_application application external-application">';
                
                // Check if Application is restricted to logged in users
                if ( get_option( 'job_application_form_require_login', 0 ) && ! is_user_logged_in() ) {
                    $login_registration = get_option('cariera_login_register_layout');

                    echo '<div class="job-manager-applications-applied-notice">' . esc_html__( 'Please login to apply for this job.', 'cariera' ) . '</div>';

                    if ( $login_registration == 'popup' ) {
                        echo '<a href="#login-register-popup" class="application_button btn btn-main btn-effect popup-with-zoom-anim">';
                    } else {
                        $login_registration_page        = get_option('cariera_login_register_page');
                        $login_registration_page_url    = get_permalink( $login_registration_page );

                        echo '<a href="' . esc_url( $login_registration_page_url ) . '" class="application_button btn btn-main btn-effect">';
                    }

                    echo esc_html__( 'Login', 'cariera' ) . '</a>';
                } else {
                    echo '<a href="' . esc_url($external_apply) . '" target="_blank" class="external_application_btn btn btn-main btn-effect">' . esc_html__( 'apply for job', 'cariera') . '</a>';
                    echo '<form method="post">';
                        echo '<input type="hidden" id="page-id" name="page-id" value="' . get_the_id() . '" />';
                    echo '</form>';
                }
            echo '</div>';
        } else {
            get_job_manager_template( 'job-application.php' ); 
        }
    } ?>
</aside>
<!-- End of Job Overview -->


<?php do_action( 'single_job_listing_meta_after' ); ?>