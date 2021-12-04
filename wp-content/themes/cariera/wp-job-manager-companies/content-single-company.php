<?php
/**
*
* @package Cariera
*
* @since 1.3.0
* 
* ========================
* TEMPLATE FOR SINGLE COMPANY POST
* ========================
*     
**/



if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

global $post;



if ( cariera_user_can_view_company( $post->ID ) ) { ?>

    <!-- ===== Start of Company Header ===== -->
    <?php
    $image = get_post_meta($post->ID, '_company_header_image', true);

    if( !empty($image) ) { ?>
        <section class="page-header company-header overlay-gradient" style="background: url(<?php echo esc_attr($image); ?>);">
    <?php } else { ?>
        <section class="page-header company-header overlay-gradient">
    <?php } ?>

    </section>
    <!-- ===== End of Company Header ===== -->



    <!-- ===== Start of Main Wrapper ===== -->
    <main class="pb80">
        <div class="container">
            <article id="post-<?php the_ID(); ?>" <?php post_class('company-page'); ?>>


                <!-- Start of Company Info -->
                <div class="company-info">
                    <?php do_action('cariera_single_company_header_info'); ?>
                </div>
                <!-- End of Company Info -->



                <div class="row">
                    <div class="col-md-8 col-xs-12">
                        <!-- Start of Company Content -->
                        <div class="company-content-wrapper">
                            
                            <?php do_action('cariera_single_company_listing_start'); ?>

                            <?php do_action('cariera_single_company_listing'); ?>

                            <?php do_action('cariera_single_company_listing_end'); ?>

                        </div>
                        <!-- End of Company Content -->
                    </div>
                    
                    <?php get_sidebar('single-company'); ?>    
                </div> 


            </article>
        </div>
    </main>
    <!-- ===== End of Main Wrapper ===== -->

<?php } else {
    get_job_manager_template_part( 'access-denied', 'single-company', 'wp-job-manager-companies' );
}