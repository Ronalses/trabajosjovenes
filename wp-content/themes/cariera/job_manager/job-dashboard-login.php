<?php
/**
 * Job dashboard shortcode content if user is not logged in.
 *
 * This template can be overridden by copying it to yourtheme/job_manager/job-dashboard-login.php.
 *
 * @see         https://wpjobmanager.com/document/template-overrides/
 * @author      Automattic
 * @package     wp-job-manager
 * @category    Template
 * @version     1.31.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>

<div id="job-manager-job-dashboard">
	<p class="account-sign-in"><?php esc_html_e( 'You need to be signed in to manage your listings.', 'cariera' ); ?> </p>
    
	<?php 
    $login_registration = get_option('cariera_login_register_layout');

    if ( $login_registration == 'popup' ) { ?>
        <a href="#login-register-popup" class="btn btn-main btn-effect popup-with-zoom-anim">
    <?php } else {
        $login_registration_page     = get_option('cariera_login_register_page');
        $login_registration_page_url = get_permalink( $login_registration_page ); ?>

        <a href="<?php echo esc_url( $login_registration_page_url ); ?>" class="btn btn-main btn-effect">
    <?php } ?>
        
        <?php esc_html_e( 'Sign in', 'cariera' ); ?>
    </a>
</div>