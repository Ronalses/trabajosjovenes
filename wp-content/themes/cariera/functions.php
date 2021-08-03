<?php
/**
*
* @package Cariera
*
* @since 1.0.0
* 
* ========================
* MAIN FUNCTION FILE
* ========================
*     
**/


require get_template_directory() . '/inc/customizer.php';
require get_template_directory() . '/inc/theme-support.php';
require get_template_directory() . '/inc/enqueue.php';
require get_template_directory() . '/inc/extra.php';
require get_template_directory() . '/inc/breadcrumb.php';
require get_template_directory() . '/inc/dashboard.php';
require get_template_directory() . '/inc/dynamic-css.php';
require get_template_directory() . '/inc/mega-menu.php';
require get_template_directory() . '/inc/onboarding/onboarding.php';

// Require only if wc is activated
if ( cariera_wc_is_activated() ) {
    require get_template_directory() . '/inc/woocommerce.php';
}

// Require only if wpjm is activated
if ( cariera_wp_job_manager_is_activated() ) {
    require get_template_directory() . '/inc/wp-job-manager/wp-job-manager.php';  
    require get_template_directory() . '/inc/wp-job-manager/templates.php';  
}

// Require only if wpjm and bookmarks plugins are activated
if ( cariera_wp_job_manager_is_activated() && class_exists( 'WP_Job_Manager_Bookmarks' ) ) {
    require get_template_directory() . '/inc/wp-job-manager/bookmarks.php'; 
}

// Require only if wpjm and wp resume manager are activated
if ( cariera_wp_job_manager_is_activated() && cariera_wp_resume_manager_is_activated() ) {
    require get_template_directory() . '/inc/wp-resume-manager/wp-resume-manager.php';
    require get_template_directory() . '/inc/wp-resume-manager/templates.php'; 
}

// Require only if wpjm and wpjm field editor plugin are activated
if ( cariera_wp_job_manager_is_activated() && class_exists( 'WP_Job_Manager_Field_Editor' ) ) {
    require get_template_directory() . '/inc/wp-job-manager/wpjm-field-editor.php';
}

