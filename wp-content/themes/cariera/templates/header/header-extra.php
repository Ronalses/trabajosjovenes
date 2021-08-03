<?php
/**
*
* @package Cariera
*
* @since    1.5.0
* @version  1.5.0
* 
* ========================
* TEMPLATE FOR HEADER EXTRA
* ========================
*     
**/


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


$login_registration = get_option('cariera_login_register_layout');
?>



<div class="extra-menu">
    <?php            
    $current_user = wp_get_current_user();
    
    // HEADER CART
    if ( cariera_get_option('header_cart') ) {
        if ( cariera_wc_is_activated() ) { 
            $cart_count = WC()->cart->get_cart_contents_count();            
            $cart_class = $cart_count < 1 ? 'counter-hidden' : ''; ?>

            <div class="extra-menu-item extra-shop mini-cart woocommerce">
                <a href="#shopping-cart-modal" class="cart-contents popup-with-zoom-anim">
                    <i class="icon-bag"></i>
                    <span class="notification-count cart-count <?php echo esc_html( $cart_class ); ?>"><?php echo number_format_i18n( $cart_count ); ?></span>
                </a>
            </div>
        <?php }
    }


    // HEADER QUICK SEARCH
    if ( cariera_get_option('header_quick_search') ) { ?>
        <div class="extra-menu-item extra-search">
            <a href="#quick-search-modal" class="header-search-btn popup-with-zoom-anim">
                <i class="icon-magnifier" aria-hidden="true"></i>
            </a>
        </div>
    <?php }


    // HEADER LOGIN & ACCOUNT
    if ( cariera_get_option('header_account') ) {
        if( !is_user_logged_in() ) { ?>
            <div class="extra-menu-item extra-user">

                <?php if ( $login_registration == 'popup' ) {
                    if( is_page_template( 'templates/login-register.php' ) ) { ?>
                        <a href="#">
                    <?php } else { ?>
                        <a href="#login-register-popup" class="popup-with-zoom-anim">
                    <?php }
                } else {
                    $login_registration_page        = get_option('cariera_login_register_page');
                    $login_registration_page_url    = get_permalink( $login_registration_page ); 
                    ?>
                        
                    <a href="<?php echo esc_url( $login_registration_page_url ); ?>">
                <?php } ?>
                    <i class="icon-user"></i>
                </a>
            </div>
        <?php } else { ?>

            <?php get_template_part( 'templates/header/notifications' ); ?>

            <div class="extra-menu-item extra-user">
                <?php
                $current_user           = wp_get_current_user();
                $user_id                = get_current_user_id();
                $user_img               = get_avatar( get_the_author_meta( 'ID', $user_id ), 40 );
                $dashboard_title        = get_page_by_title( 'Dashboard' );
                $dashboard_page         = get_option( 'cariera_dashboard_page' );
                $employer_dashboard     = get_option( 'job_manager_job_dashboard_page_id' );
                $company_dashboard      = get_option( 'cariera_company_dashboard_page' );
                $candidate_dashboard    = get_option( 'resume_manager_candidate_dashboard_page_id' );
                $profile                = get_option( 'cariera_dashboard_profile_page' );
                $roles                  = $current_user->roles;
                $role                   = array_shift( $roles );

                if ( cariera_wc_is_activated() ) {
                    $orders = wc_get_endpoint_url('orders', '', wc_get_page_permalink('myaccount'));
                }

                if( $dashboard_page ) {
                    $account_link = get_permalink( $dashboard_page );
                } else {
                    $account_link = get_permalink( $dashboard_title );
                } ?>

                <a href="#" id="user-account-extra">
                    <div class="login-status"></div>
                    <span class="avatar-img">
                        <?php echo wp_kses_post( $user_img ); ?>
                    </span>
                    <span class="user-name">
                        <?php echo esc_html( $current_user->user_login ); ?>
                    </span>                                
                </a>


                <!-- Header Account Widget -->
                <div class="header-account-widget header-account-widget-<?php echo esc_attr($role); ?>">
                    <div class="title-bar">
                        <h4 class="title"><?php echo esc_html( $current_user->first_name ) . ' ' . esc_html( $current_user->last_name ); ?></h4>
                        <small><?php echo esc_html( $current_user->user_email ); ?></small>
                    </div>

                    <!-- Main Content -->
                    <div class="main-content">
                        <ul class="account-nav">
                            <li class="header-widget-menu-item_dashboard">
                                <a href="<?php echo esc_url($account_link); ?>">
                                    <i class="icon-settings"></i><?php esc_html_e( 'Dashboard', 'cariera' ); ?>
                                </a>
                            </li>

                            <?php 
                            // Employer Dashboard Link
                            if( cariera_wp_job_manager_is_activated() ) {
                                if ( in_array( 'employer', (array) $current_user->roles ) || in_array( 'administrator', (array) $current_user->roles ) ) { ?>
                                    <li class="header-widget-menu-item_employer-dashboard">
                                        <a href="<?php echo esc_url(get_permalink($employer_dashboard)); ?>">
                                            <i class="icon-briefcase"></i><?php esc_html_e( 'My Jobs', 'cariera' ); ?>
                                        </a>
                                    </li>
                            <?php }
                            }

                            // Company Dashboard Link
                            if( cariera_wp_job_manager_is_activated() && cariera_wp_company_manager_is_activated() ) {
                                if ( in_array( 'employer', (array) $current_user->roles ) || in_array( 'administrator', (array) $current_user->roles ) ) { ?>
                                    <li class="header-widget-menu-item_company-dashboard">
                                        <a href="<?php echo esc_url(get_permalink($company_dashboard)); ?>">
                                            <i class="far fa-building"></i><?php esc_html_e( 'My Companies', 'cariera' ); ?>
                                        </a>
                                    </li>
                            <?php }
                            }

                            // Candidate Dashboard Link
                            if( cariera_wp_job_manager_is_activated() && cariera_wp_resume_manager_is_activated() ) {
                                if ( in_array( 'candidate', (array) $current_user->roles ) || in_array( 'administrator', (array) $current_user->roles ) ) { ?>
                                    <li class="header-widget-menu-item_candidate-dashboard">
                                        <a href="<?php echo esc_url(get_permalink($candidate_dashboard)); ?>">
                                            <i class="icon-layers"></i><?php esc_html_e( 'My Resumes', 'cariera' ); ?>
                                        </a>
                                    </li>
                                <?php }
                            }

                            // Orders Link 
                            if( cariera_wc_is_activated() ) { ?>
                                <li class="header-widget-menu-item_orders">
                                    <a href="<?php echo esc_url($orders); ?>">
                                        <i class="icon-credit-card"></i><?php esc_html_e( 'Orders', 'cariera'); ?>
                                    </a>            
                                </li>
                            <?php }

                            // My Profile ?>
                            <li class="header-widget-menu-item_my-profile">
                                <a href="<?php echo esc_url(get_permalink($profile)); ?>">
                                    <i class="icon-user"></i><?php esc_html_e( 'My Profile', 'cariera'); ?>
                                </a>
                            </li>

                            <?php do_action( 'cariera_header_widget_nav_end' ); ?>
                        </ul>
                    </div>

                    <!-- Logout Footer -->
                    <div class="logout-footer">
                        <a href="<?php echo wp_logout_url(home_url()); ?>"><i class="icon-power"></i><?php esc_html_e( 'Logout', 'cariera' );?></a>
                    </div>

                </div>
            </div>
        <?php }
    }

    
    get_template_part( 'templates/header/header-cta' ); ?>

</div>
<!-- ====== End of Extra Menu ====== -->