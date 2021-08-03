<?php 
/**
*
* @package Cariera
*
* @since 	1.5.1
* @version 	1.5.1
* 
* ========================
* ONBOARDING MAIN TEMPLATE
* ========================
*     
**/



// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


$theme          = wp_get_theme();
$version        = $theme->get('Version');
$child          = false;
$child_status   = esc_html__( 'Not Active', 'cariera' );
$status         = self::$status;

// If Child theme is active
if ( $theme->parent() ) {
    $child          = true;
    $child_status   = esc_html__( 'Active', 'cariera' );;
    $theme          = $theme->parent();
    $version        = $theme->get('Version');
}

if ( $status ) {
    $req = '';
} else {
    $req = '<span class="badge">' . esc_html__( 'activation required', 'cariera' ) . '</span>';
}


wp_enqueue_script('cariera-onboarding');
wp_enqueue_style('cariera-onboarding');

// Notify
wp_enqueue_script('jquery-confirm');
wp_enqueue_style('jquery-confirm'); ?>



<div class="cariera-onboarding">
    <span id="cariera-home-url" style="display:none;"><?php echo esc_url( home_url() ); ?></span>

    <!-- Navigation -->
    <header class="onboarding-header">
        <div class="logo">
            <img src="<?php echo get_template_directory_uri() . '/assets/images/gnodesign-logo.svg' ?>" class="gnodesign-logo" />
        </div>

        <div class="navigation">
            <a href="#" data-tab="welcome" class="menu-item active">
                <div class="title"><?php esc_html_e( 'Welcome', 'cariera' ); ?></div>
                <div class="description"><?php esc_html_e( 'Get started with Cariera!', 'cariera' ); ?></div>
            </a>

            <a href="#" data-tab="plugins" class="menu-item">
                <div class="title"><?php esc_html_e( 'Plugins', 'cariera' ); echo wp_kses_post($req); ?></div>
                <div class="description"><?php esc_html_e( 'Install all required plugins', 'cariera' ); ?></div>
            </a>

            <a href="#" data-tab="import" class="menu-item">
                <div class="title"><?php esc_html_e( 'Import', 'cariera' ); echo wp_kses_post($req); ?></div>
                <div class="description"><?php esc_html_e( 'Full demo import', 'cariera' ); ?></div>
            </a>
            
            <a href="#" data-tab="addons" class="menu-item">
                <div class="title"><?php esc_html_e( 'Add-ons', 'cariera' ); ?></div>
                <div class="description"><?php esc_html_e( 'Compatible third party plugins', 'cariera' ); ?></div>
            </a>
            
            <a href="#" data-tab="themes" class="menu-item">
                <div class="title"><?php esc_html_e( 'Themes', 'cariera' ); ?></div>
                <div class="description"><?php esc_html_e( 'More themes by Gnodesign', 'cariera' ); ?></div>
            </a>
        </div>
    </header>


    <!-- Content -->
    <section class="onboarding-content">
        
        <!-- Welcome -->
        <div id="welcome" class="content-page active">

            <div class="theme-info">
                <div class="theme-preview">
                    <img height="160" src="<?php echo esc_url( $theme->get_screenshot() ); ?>">
                </div>

                <div class="details">                    
                    <h2 class="title"><?php esc_html_e( 'Welcome to Cariera!', 'cariera' ); ?></h2>

                    <ul>
                        <li class="child-theme"><strong><?php esc_html_e( 'Child theme:', 'cariera' ); ?></strong><span class="<?php echo esc_attr( $child == true ? 'green' : 'red' ); ?>"><?php echo esc_html( $child_status ); ?></span></li>
                        <li class="version"><strong><?php esc_html_e( 'Theme Version:', 'cariera' ); ?></strong><span><?php echo esc_html( $version ); ?></span></li>
                        <li class="changelog"><a href="https://youtu.be/6Q5JDTOuRkY" target="_blank"><?php esc_html_e( 'Installation Video', 'cariera'); ?></a> | <a href="https://1.envato.market/Dj5Yq" target="_blank"><?php esc_html_e( 'Full Changelog', 'cariera' ); ?></a> | <a href="https://www.facebook.com/groups/858997041561417" target="_blank"><?php esc_html_e( 'FB Group', 'cariera' ); ?></a></li>
                    </ul>                
                </div>
            </div>           

            <?php if ( !$status ) { ?>
                <h2><?php esc_html_e( 'Get Started!', 'cariera' ); ?></h2>
                <p><?php echo wp_kses_post( __( 'Activate the theme using your <strong>Envato Purchase Code</strong>. You can use 1 license for <strong>1 website</strong>, if you want to use your license on a different domain then manke sure to deregister your license first and activate it on your new domain.', 'cariera' ) ); ?></p>
            <?php } ?>

            <div class="license-container">
                <?php do_action('cariera_onboarding_license'); ?>
                <?php do_action('cariera_onboarding_license_sidebar'); ?>
            </div>
        </div>


        <!-- Required Plugins -->
        <div id="plugins" class="content-page">
            <h2 class="title"><?php esc_html_e( 'Required Plugins', 'cariera' ); ?></h2>
    
            <?php do_action('cariera_onboarding_plugins'); ?>
        </div>


        <!-- Import Demo Content -->
        <div id="import" class="content-page">
            <h2 class="title"><?php esc_html_e( 'Import Demo Content', 'cariera' ); ?></h2>

            <?php do_action('cariera_onboarding_import'); ?>
        </div>


        <!-- Compatible Plugins -->
        <div id="addons" class="content-page">
            <h2 class="title"><?php esc_html_e( 'Fully Compatible Third Party Plugins', 'cariera' ); ?></h2>
            <div class="onboarding-notice success">
                <p><?php esc_html_e( 'Please contact the author of the plugins regarding any presale or support related questions.', 'cariera' ); ?></p>
                <p><strong><?php echo esc_html( '-5% Coupon for all products & licenses:' ); ?></strong><span class="coupon-code"><?php echo esc_html('cariera2022'); ?></span></p>
            </div>

            <div class="onboarding-products">
                <!-- Product Item -->
                <div class="product-item">
                    <a href="https://plugins.smyl.es/wp-job-manager-search-and-filtering" target="_blank">
                        <div class="theme-img" style="background-image: url('<?php echo get_template_directory_uri() . '/assets/images/plugins/wpjm-sf.jpg'; ?>');"></div>
                        <div class="title"><?php echo esc_html( 'S&F for WPJM' ); ?></div>
                    </a>
                </div>

                <!-- Product Item -->
                <div class="product-item">
                    <a href="https://plugins.smyl.es/wp-job-manager-field-editor/" target="_blank">
                        <div class="theme-img" style="background-image: url('<?php echo get_template_directory_uri() . '/assets/images/plugins/wpjm-field-editor.jpg'; ?>');"></div>
                        <div class="title"><?php echo esc_html( 'WPJM Field Editor' ); ?></div>
                    </a>
                </div>

                <!-- Product Item -->
                <div class="product-item">
                    <a href="https://plugins.smyl.es/wp-job-manager-packages/" target="_blank">
                        <div class="theme-img" style="background-image: url('<?php echo get_template_directory_uri() . '/assets/images/plugins/wpjm-packages.jpg'; ?>');"></div>
                        <div class="title"><?php echo esc_html( 'WPJM Packages' ); ?></div>
                    </a>
                </div>

                <!-- Product Item -->
                <div class="product-item">
                    <a href="https://plugins.smyl.es/wp-job-manager-resume-alerts/" target="_blank">
                        <div class="theme-img" style="background-image: url('<?php echo get_template_directory_uri() . '/assets/images/plugins/wpjm-resume-alerts.jpg'; ?>');"></div>
                        <div class="title"><?php echo esc_html( 'Resume Alerts for WPJM' ); ?></div>
                    </a>
                </div>

                <!-- Product Item -->
                <div class="product-item">
                    <a href="https://plugins.smyl.es/wp-job-manager-visibility/" target="_blank">
                        <div class="theme-img" style="background-image: url('<?php echo get_template_directory_uri() . '/assets/images/plugins/wpjm-visibility.jpg'; ?>');"></div>
                        <div class="title"><?php echo esc_html( 'WPJM Visibility' ); ?></div>
                    </a>
                </div>

                <!-- Product Item -->
                <div class="product-item">
                    <a href="https://plugins.smyl.es/wp-job-manager-emails/" target="_blank">
                        <div class="theme-img" style="background-image: url('<?php echo get_template_directory_uri() . '/assets/images/plugins/wpjm-emails.jpg'; ?>');"></div>
                        <div class="title"><?php echo esc_html( 'WPJM Emails' ); ?></div>
                    </a>
                </div>
            </div>
        </div>


        <!-- Gnodesign Themes -->
        <div id="themes" class="content-page">
            <h2 class="title"><?php esc_html_e( 'More Quality Themes', 'cariera' ); ?>
                <a href="https://1.envato.market/MOKEn" class="title-btn envato" target="_blank"><?php echo esc_html( 'Envato Portfolio' ); ?></a> 
            </h2>

            <div class="onboarding-products">
                <!-- Product Item -->
                <div class="product-item">
                    <a href="https://1.envato.market/k72Rn" target="_blank">
                        <div class="theme-img" style="background-image: url('<?php echo get_template_directory_uri() . '/assets/images/themes/autohub.jpg'; ?>');">
                            <div class="price"><?php echo esc_html( '79$' ); ?></div>
                        </div>
                        <div class="title"><?php echo esc_html( 'Autohub - Automotive Directory Theme' ); ?></div>
                    </a>
                </div>

                <!-- Product Item -->
                <div class="product-item">
                    <a href="https://1.envato.market/qqMaL" target="_blank">
                        <div class="theme-img" style="background-image: url('<?php echo get_template_directory_uri() . '/assets/images/themes/cocoon.jpg'; ?>');">
                            <div class="price"><?php echo esc_html( '59$' ); ?></div>
                        </div>
                        <div class="title"><?php echo esc_html( 'Cocoon - WooCommerce WordPress Theme' ); ?></div>
                    </a>
                </div>
            </div>
        </div>
    </section>



    <!-- Sidebar -->
    <aside class="onboarding-sidebar">
        <div class="widget widget-documentation">
            <h3 class="title"><?php esc_html_e( 'Documentation', 'cariera' ); ?></h3>
            <p><?php esc_html_e( 'Regularly updated knowledge base that will help you get started with Cariera.', 'cariera' ); ?></p>
            <a href="https://docs.cariera.co/" class="btn-main" target="_blank"><?php esc_html_e( 'Read Documentation', 'cariera' ); ?></a>
        </div>

        <div class="widget widget-notice">
            <p><?php esc_html_e( 'If you like Cariera please support us by giving the theme a positive rating.', 'cariera' ); ?></p>
            <a href="https://themeforest.net/downloads" class="btn-link" target="_blank"><?php esc_html_e( 'Rate Cariera', 'cariera' ); ?></a>
        </div>

        <div class="widget widget-notice">
            <p><a target="_blank" href="https://themeforest.net/licenses/standard"><?php esc_html_e( 'One standard license ', 'cariera'); ?></a><?php printf( esc_html__( 'is valid only for %s. Running multiple websites on a single license is a copyright violation.', 'cariera'), '<strong>1 website</strong>' ); ?></p>
            <a href="https://1.envato.market/WL5MX" class="btn-link" target="_blank"><?php esc_html_e( 'Buy new license', 'cariera' ); ?></a>
        </div>

        <div class="widget">
            <h3 class="title"><?php esc_html_e( 'Recommended Hosting', 'cariera' ); ?></h3>
            <a href="https://www.cloudways.com/en/?id=759820&amp;a_bid=e6f8926f" target="_blank"><img src="//www.cloudways.com/affiliate/accounts/default1/banners/e6f8926f.jpg" alt="The Ultimate Managed Hosting Platform" title="The Ultimate Managed Hosting Platform" width="336" height="280" /></a><img style="border:0" src="https://www.cloudways.com/affiliate/scripts/imp.php?id=759820&amp;a_bid=e6f8926f" width="1" height="1" />
        </div>
    </aside>
</div>


<a href="https://support.gnodesign.com" class="cariera-support" target="_blank"><?php esc_html_e( 'Get Support', 'cariera' ); ?></a>