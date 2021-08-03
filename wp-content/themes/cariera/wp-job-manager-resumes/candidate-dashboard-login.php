<?php
/**
 * Message to show above resume submit form when submitting a new resume.
 *
 * This template can be overridden by copying it to yourtheme/wp-job-manager-resumes/candidate-dashboard-login.php.
 *
 * @see         https://wpjobmanager.com/document/template-overrides/
 * @author      Automattic
 * @package     wp-job-manager-resumes
 * @category    Template
 * @version     1.11.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div id="resume-manager-candidate-dashboard">
	<p class="account-sign-in"><?php esc_html_e( 'You need to be signed in to manage your resumes.', 'cariera' ); ?> </p>
    
    <?php 
    $login_registration = get_option('cariera_login_register_layout');

    if ( $login_registration == 'popup' ) { ?>
        <a href="#login-register-popup" class="btn btn-main btn-effect popup-with-zoom-anim">
    <?php } else {
        $login_registration_page     = get_option('cariera_login_register_page');
        $login_registration_page_url = get_permalink( $login_registration_page );?>

        <a href="<?php echo esc_url( $login_registration_page_url ); ?>" class="btn btn-main btn-effect">
    <?php } ?>
        
        <?php esc_html_e( 'Sign in', 'cariera' ); ?>
    </a>
</div>