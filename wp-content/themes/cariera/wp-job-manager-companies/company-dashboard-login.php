<?php
/**
*
* @package Cariera
*
* @since 1.4.4
* 
* ========================
* COMPANY DASHBOARD LOGIN
* ========================
*     
**/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} ?>


<div id="company-manager-company-dashboard">

	<p class="account-sign-in"><?php esc_html_e( 'You need to be signed in to manage your companies.', 'cariera' ); ?> </p>
    
	<?php 
    $login_registration = get_option('cariera_login_register_layout');

    if ( $login_registration == 'popup' ) { ?>
        <a href="#login-register-popup" class="btn btn-main btn-effect popup-with-zoom-anim">
    <?php } else {
        $login_registration_page     = get_option('cariera_login_register_page');
        $login_registration_page_url = get_permalink( $login_registration_page );?>

        <a href="<?php echo esc_url( $login_registration_page_url ); ?>" class="btn btn-main btn-effect">
    <?php }
        esc_html_e( 'Sign in', 'cariera' ); ?>
    </a>

</div>