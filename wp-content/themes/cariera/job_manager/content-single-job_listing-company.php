<?php
/**
 * Single view Company information box
 *
 * Hooked into single_job_listing_start priority 30
 *
 * This template can be overridden by copying it to yourtheme/job_manager/content-single-job_listing-company.php.
 *
 * @see         https://wpjobmanager.com/document/template-overrides/
 * @author      Automattic
 * @package     WP Job Manager
 * @category    Template
 * @since       1.14.0
 * @version     1.31.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( cariera_core_is_activated() && ! cariera_get_the_company() ) {
	return;
} 


global $post;
$post = get_post( $post );

$job_id     = get_the_ID();

if ( cariera_core_is_activated() ) {
    $company    = get_post( cariera_get_the_company() );
} else {
    $company    = '';
}
?>





<!-- Company Info -->
<div class="company-info">

    <!-- Job Company Image -->
    <div class="job-company-wrapper">
        <?php if ( ! empty ( $company ) ) { ?>
            <a href="<?php echo esc_url( get_permalink($company) ); ?>">
        <?php } ?>
                
        <div class="job-company">
            <?php 
            // Company Logo                    
            if ( !empty( $company ) && has_post_thumbnail( $company ) ) {
                $logo = get_the_company_logo( $company, apply_filters( 'cariera_company_logo_size', 'thumbnail' ) );
                echo '<img class="company_logo" src="' . esc_url( $logo ) . '" alt="' . esc_attr( get_the_company_name( $company ) ) . '" />';
            } else {
                cariera_the_company_logo();
            }
            ?>
        </div>
                
        <?php if ( ! empty ( $company ) ) { ?>
            </a>
        <?php } ?>
    </div>

    <!-- Job Company Info -->
    <div class="job-company-info">            
        <?php if ( ! empty ( $company ) ) { ?>
            <h3 class="single-job-listing-company-name">
                <a href="<?php echo esc_url( get_permalink($company) ); ?>"><?php echo get_the_title($company); ?></a>
            </h3>
        <?php } else {
            the_company_name( '<h3 class="single-job-listing-company-name">', '</h3>' );
        } ?>
        
        <!-- Company contact details -->
        <div class="single-job-listing-company-contact">
            <?php
            do_action( 'single_job_listing_company_contact_start' );

            if( cariera_core_is_activated() ) {
                if ( !empty($company && $website = cariera_get_the_company_website_link($company)) ) { ?>
                    <a class="company-website" href="<?php echo esc_url( $website ); ?>" target="_blank" rel="nofollow">
                        <i class="icon-globe"></i> 
                        <?php echo esc_html( $website ); ?>
                    </a>
                <?php }
            } else {
                $website = get_the_company_website(); ?>
                <a class="company-website" href="<?php echo esc_url( $website ); ?>" target="_blank" rel="nofollow">
                    <i class="icon-globe"></i>
                    <?php echo wp_kses_post( $website ); ?>
                </a>
            <?php }
            
            if ( cariera_wp_company_manager_is_activated() ) {
                if ( !empty( $company && $phone = cariera_get_the_company_phone( $company, 'company' ) ) ) { ?>
                    <a href="tel:<?php echo esc_attr( $phone ); ?>" class="company-phone">
                        <i class="icon-phone"></i>
                        <?php echo esc_html( $phone ); ?>
                    </a>
                <?php }
            }
            
            if ( $apply = get_the_job_application_method() && isset( $apply->type ) && $apply->type == 'email' ) {
                $application_email = $apply->email; ?>
                <a class="company-application-email" href="mailto:<?php echo esc_url( $application_email ); ?>" target="_blank" rel="nofollow">
                    <i class="icon-envelope"></i>
                    <?php echo wp_kses_post( $application_email ); ?>
                </a>
            <?php } elseif( cariera_wp_company_manager_is_activated() ) {
                if ( !empty( $company && $email = cariera_get_the_company_email( $company ) ) ) { ?>
                    <a class="company-application-email" href="mailto:<?php echo esc_attr( $email ); ?>" target="_blank" rel="nofollow">
                        <i class="icon-envelope"></i>
                        <?php echo wp_kses_post( $email ); ?>
                    </a>
                <?php }
            } 
            
            
            do_action( 'single_job_listing_company_contact_end' ); ?>
        </div>
    </div>

</div>
<!-- end of company-info -->