<?php
/**
*
* @package Cariera
*
* @since    1.1.0
* @version  1.5.1
* 
* ========================
* PRELOADERS TEMPLATE
* ========================
*     
**/



// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if( !cariera_get_option('cariera_preloader') ) { 
    return;
}

$preloader_ver = cariera_get_option( 'cariera_preloader_version' );


// PRELOADER VERSION 1
if ( $preloader_ver == 'preloader1') { ?>
    <div id="preloader">
        <div class="inner">
            <div class="loading_effect">
                <div class="object" id="object_one"></div>
            </div>
        </div>
    </div>
<?php }


// PRELOADER VERSION 2
if ( $preloader_ver == 'preloader2') { ?>
    <div id="preloader">
        <div class="inner">
            <div class="loading_effect2">
                <div class="object" id="object_one"></div>
                <div class="object" id="object_two"></div>
                <div class="object" id="object_three"></div>
            </div>
        </div>
    </div>
<?php }


// PRELOADER VERSION 3
if ( $preloader_ver == 'preloader3') { ?>
    <div id="preloader">
        <div class="inner">
            <div class="loading_effect3">
                <div class="object"></div>
                <p><?php esc_html_e('loading', 'cariera'); ?></p>
            </div>
        </div>
    </div>
<?php }


// PRELOADER VERSION 4
if ( $preloader_ver == 'preloader4') { ?>
    <div id="preloader" class="preloader4">
        <div class="inner">
            <div class="loading-container">
                <?php if ( cariera_get_option('logo') ) { ?>
                    <img src="<?php echo esc_url( cariera_get_option('logo') ); ?>" />
                <?php } else { ?>
                    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/logo.svg' ); ?>">
                <?php } ?>
                <div id="object_one" class="object"></div>
            </div>
        </div>
    </div>
<?php }