<?php
/**
*
* @package Cariera
*
* @since 1.4.4
* 
* ========================
* COMPANY SUBMIT
* ========================
*     
**/



if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


$submission_limit       = get_option( 'cariera_company_submission_limit' );
$company_count          = cariera_count_user_companies();


wp_enqueue_script( 'cariera-company-manager-submission' ); ?>


<form action="<?php echo esc_url( $action ); ?>" method="post" id="submit-company-form" class="job-manager-form" enctype="multipart/form-data">

    <?php do_action( 'submit_company_form_start' ); ?>

    <?php if ( apply_filters( 'submit_company_form_show_signin', true ) ) : ?>
        <?php get_job_manager_template( 'account-signin.php', array( 'class' => $class ), 'wp-job-manager-companies' ); ?>
    <?php endif; ?>

    <?php if ( cariera_user_can_post_company() ) : ?>

        <!-- Company Fields -->
        <?php
        get_job_manager_template( 'company-submit-fields.php', array(
            'class'              => $class,
            'form'               => $form,
            'company_id'         => $company_id,
            'job_id'             => $job_id,
            'action'             => $action,
            'company_fields'     => $company_fields,
            'step'               => $step,
        ), 'wp-job-manager-companies' );
        ?>

        <p>
            <?php wp_nonce_field( 'submit_form_posted' ); ?>
            <input type="hidden" name="company_manager_form" value="<?php echo esc_attr( $form ); ?>" />
            <input type="hidden" name="company_id" value="<?php echo esc_attr( $company_id ); ?>" />
            <input type="hidden" name="job_id" value="<?php echo esc_attr( $job_id ); ?>" />
            <input type="hidden" name="step" value="<?php echo esc_attr( $step ); ?>" />
            <input type="submit" name="submit_company" class="button" value="<?php echo esc_attr( $submit_button_text ); ?>" />
        </p>

    <?php else : ?>
        <?php do_action( 'submit_company_form_disabled' ); ?>
    <?php endif; ?>

    <?php do_action( 'submit_company_form_end' ); ?>
</form>