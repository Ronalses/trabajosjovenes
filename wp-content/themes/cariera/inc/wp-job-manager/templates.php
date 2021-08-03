<?php
/**
*
* @package Cariera
*
* @since 1.3.0
* 
* ========================
* ALL WP JOB MANAGER TEMPLATE HOOKS
* ========================
*     
**/





/*
 * Remove Job Actions
 *
 * @since 1.3.0
 */
function cariera_remove_job_actions() {
    remove_action( 'single_job_listing_start', 'job_listing_meta_display', 20 );
}

add_action( 'init', 'cariera_remove_job_actions' );





/*
 * Job Listing status badges
 *
 * @since 1.4.8
 */
function cariera_job_listing_status_title() {
    global $post;

    if ( is_position_filled() ) {
        echo '<span class="job-listing-status-badge filled">' . esc_html__( 'filled', 'cariera' ) . '</span>';
    }

    if ( 'expired' === $post->post_status ) { 
        echo '<span class="job-listing-status-badge expired">' . esc_html__( 'expired', 'cariera' ) . '</span>';
    }
}

add_action( 'cariera_job_listing_status', 'cariera_job_listing_status_title' );




/* 
=====================================================
    SINGLE JOB PAGE
=====================================================
*/

/*
 * Adding Application message to Single Job Listing 
 *
 * @since   1.3.0
 * @version 1.5.0
 */
if ( !function_exists('cariera_single_job_application_msg') ) {
    function cariera_single_job_application_msg() {
        if ( !class_exists( 'WP_Job_Manager_Applications' ) ) {
            return;
        }

        if ( is_position_filled() ) { ?>
            <div class="job-manager-message success position-filled">
                <?php esc_html_e( 'This position has been filled', 'cariera' ); ?>
            </div>	
        <?php } elseif ( ! candidates_can_apply() ) { ?>
            <div class="job-manager-message error applications-closed">
                <?php esc_html_e( 'Applications have closed', 'cariera' ); ?>
            </div>
        <?php }
    }
}

add_action( 'single_job_listing_start', 'cariera_single_job_application_msg', 20 );




/*
 * Adding Related Jobs to Single Job Listing 
 *
 * @since 1.3.0
 */
if ( !function_exists('cariera_single_job_related_jobs') ) {
    function cariera_single_job_related_jobs() {
        $related_jobs = cariera_get_option('cariera_related_jobs');

        if( $related_jobs ) {
            get_template_part('templates/extra/related-jobs'); 
        }
    }
}

add_action( 'cariera_single_job_listing_end', 'cariera_single_job_related_jobs', 20 );




/*
 * Adding Share buttons to Single Job Listing 
 *
 * @since 1.3.0
 */
if ( !function_exists('cariera_single_job_share') ) {
    function cariera_single_job_share() {
        if ( cariera_get_option( 'cariera_job_share' ) ) {
            // check if function exists
            if ( function_exists ( 'cariera_share_media' ) ) {
                echo cariera_share_media();
            }
        }
    }
}

add_action( 'single_job_listing_end', 'cariera_single_job_share' );





/* 
=====================================================
    JOB SUBMISSION HTML MARKUP
=====================================================
*/


/*
 * Job Submission Flow
 *
 * @since 1.3.2
 */
function cariera_job_submission_flow() {
    // temporary variables
    $is_packages_enabled = false;

    // get page IDs
    $current_page_id     = get_queried_object_id();
    $job_submission_page = intval( get_option( 'job_manager_submit_job_form_page_id', false ) );

    // get job packages
    if ( function_exists('wc_get_products') ) {
        $job_packages        = wc_get_products( ['type' => 'job_package'] );
        $job_subscriptions   = wc_get_products( ['type' => 'job_package_subscription'] );
        $is_packages_enabled = class_exists( 'WC_Paid_Listings' ) && ( !empty( $job_packages ) || !empty( $job_subscriptions ) );
    }

    // display submission flow
    if ( !empty( $job_submission_page ) && ( $job_submission_page == $current_page_id ) ) { ?>
        <div class="submission-flow job-submission-flow">
            <ul>
                <?php if ( get_option('job_manager_paid_listings_flow') == 'before' && $is_packages_enabled ) { ?>
                    <li class="choose-package"><?php echo esc_html__( 'Choose Package', 'cariera' ); ?></li>
                <?php } ?>
                <li class="listing-details"><?php echo esc_html__( 'Listing Details', 'cariera' ); ?></li>
                <li class="preview-listing"><?php echo esc_html__( 'Preview Listing', 'cariera' ); ?></li>
                <?php if ( get_option('job_manager_paid_listings_flow') != 'before' && $is_packages_enabled ) { ?>
                    <li class="choose-package"><?php echo esc_html__( 'Choose Package', 'cariera' ); ?></li>
                <?php } ?>
            </ul>
        </div>
    <?php
    }
}

add_action( 'cariera_page_content_start', 'cariera_job_submission_flow' );
add_action( 'cariera_dashboard_content_start', 'cariera_job_submission_flow' );





/*
 * Job submission fields
 *
 * @since 1.4.0
 */
function cariera_submit_job_fields_start() {
    echo '<div class="submit-job-box submit-job_job-info">';
        echo '<h3 class="title">' . esc_html__( 'Job Details', 'cariera' ) . '</h3>';
        echo '<div class="form-fields">';
}

add_action( 'submit_job_form_job_fields_start', 'cariera_submit_job_fields_start' );

function cariera_submit_job_fields_end() {
    echo '</div></div>';
}

add_action( 'submit_job_form_job_fields_end', 'cariera_submit_job_fields_end' );





/*
 * Company submission fields
 *
 * @since 1.4.0
 */
function cariera_submit_company_fields_start() {
    echo '<div class="submit-job-box submit-job_company-info">';
        echo '<h3 class="title">' . esc_html__( 'Company Details', 'cariera' ) . '</h3>';
        echo '<div class="form-fields">';
}

add_action( 'submit_job_form_company_fields_start', 'cariera_submit_company_fields_start' );
add_action( 'submit_company_form_company_fields_start', 'cariera_submit_company_fields_start' );


function cariera_submit_company_fields_end() {
    echo '</div></div>';
}

add_action( 'submit_job_form_company_fields_end', 'cariera_submit_company_fields_end', 20 );
add_action( 'submit_company_form_company_fields_end', 'cariera_submit_company_fields_end' );





/*
 * Company selection 
 *
 * @since 1.4.0
 */
function cariera_submit_job_form_button_text( $text ) {
    return esc_html__( 'Preview Listing', 'cariera' ) ;
}

add_filter( 'submit_job_form_submit_button_text', 'cariera_submit_job_form_button_text' );