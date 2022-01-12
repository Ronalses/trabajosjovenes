<?php

/**

 * Single job listing.

 *

 * This template can be overridden by copying it to yourtheme/job_manager/content-single-job_listing.php.

 *

 * @see         https://wpjobmanager.com/document/template-overrides/

 * @author      Automattic

 * @package     WP Job Manager

 * @category    Template

 * @since       1.0.0

 * @version     1.28.0

 */



if (!defined('ABSPATH')) {

    exit; // Exit if accessed directly.

}



global $post;







$id = get_post_meta($post->ID, 'cariera_job_page_header', true);



// Filtering the id to see if the image is uploaded from the backend or the frontend

if (filter_var($id, FILTER_VALIDATE_URL) === FALSE) {

    $image = wp_get_attachment_url($id);
} else {

    $image = get_post_meta($post->ID, 'cariera_job_page_header', true);
}



if (!empty($id)) { ?>

    <!-- ===== Start of Page Header ===== -->

    <section class="page-header page-header-bg job-header" style="background: url(<?php echo esc_attr($image); ?>);">

    <?php } else { ?>

        <!-- ===== Start of Page Header ===== -->

        <section class="page-header job-header">

        <?php } ?>





        <div class="container">

            <div class="row">



                <!-- Start of Job Title -->

                <div class="col-md-6 col-xs-12">

                    <h1 class="title pb15"><?php the_title(); ?></h1>



                    <?php $types = wpjm_get_the_job_types();

                    if (!empty($types)) :

                        foreach ($types as $type) : ?>

                            <span class="job-type <?php echo esc_attr(sanitize_title($type->slug)); ?>"><?php echo esc_html($type->name); ?></span>
                            <!-- Se busca si el trabajo es destacado o no y se le añaden estilos  -->
                            <?php $feature = is_position_featured();

                            if ($feature) {
                                echo '<span id="featured_symbol"  style = "position: relative;
                                background: #c70909 ;
                                color: #f6f6f6!important;
                                font-size: 14px;
                                padding: 5px 15px;
                                outline: 0;
                                border-radius: 3px;">Trabajo destacado ⭐</span>';
                            }

                            ?>

                    <?php endforeach;

                    endif;

                    ?>



                    <?php if (cariera_newly_posted()) : //If job is new than show the new tag

                        echo '<span class="job-type new-job-tag">' . esc_html__('New', 'cariera') . '</span>';

                    endif; ?>

                </div>

                <!-- End of Job Title -->



                <!-- Start of Bookmark -->

                <div class="col-md-6 col-xs-12 bookmark-wrapper">

                    <?php do_action('cariera_bookmark_hook'); ?>

                </div>

                <!-- End of Bookmark -->



            </div>

        </div>

        </section>

        <!-- ===== End of Page Header ===== -->







        <!-- ===== Start of Main Wrapper ===== -->

        <main class="ptb80">

            <div class="container">

                <div class="row">

                    <?php

                    do_action('cariera_single_job_listing_before');





                    $jobs_layout = cariera_get_option('cariera_single_job_layout');



                    if ('left-sidebar' == $jobs_layout) {

                        $layout = 'col-md-8 col-md-push-4 col-xs-12';
                    } elseif ('right-sidebar' == $jobs_layout) {

                        $layout = 'col-md-8 col-xs-12';
                    }





                    // Show if Job has expired

                    if (get_option('job_manager_hide_expired_content', 1) && 'expired' === $post->post_status) { ?>

                        <div class="col-md-12">

                            <div class="job-manager-message error"><?php esc_html_e('This listing has expired.', 'cariera'); ?></div>

                        </div>

                    <?php } else { ?>



                        <!-- ===== Start of Job Details ===== -->

                        <div class="<?php echo esc_attr($layout); ?>">

                            <div class="single-job-listing">

                                <?php

                                /**

                                 * single_job_listing_start hook

                                 *

                                 * @hooked job_listing_meta_display - 20

                                 * @hooked job_listing_company_display - 30

                                 */

                                do_action('single_job_listing_start');

                                ?>



                                <div class="job-description">

                                    <?php wpjm_the_job_description(); ?>

                                </div>



                                <?php

                                /**

                                 * single_job_listing_end hook

                                 */

                                do_action('single_job_listing_end');

                                ?>

                            </div>

                        </div>

                        <!-- ===== End of Job Details ===== -->



                    <?php get_sidebar('single-job');
                    }





                    do_action('cariera_single_job_listing_after');

                    ?>

                </div>

            </div>

        </main>

        <!-- ===== End of Main Wrapper ===== -->
