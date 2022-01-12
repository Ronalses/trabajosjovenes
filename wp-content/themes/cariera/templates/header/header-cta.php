<?php
/**
*
* @package Cariera
*
* @since    1.5.0
* @version  1.5.0
* 
* ========================
* TEMPLATE FOR HEADER CTA
* ========================
*     
**/



// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( !cariera_get_option('header_cta') ) { 
    return;
}

$current_user   = wp_get_current_user();
$main_cta       = get_permalink(get_option('cariera_header_emp_cta_link'));
$candi_cta      = get_permalink(get_option('cariera_header_candidate_cta_link'));
?>


<div class="extra-menu-item extra-add-listing">
    <?php if( !is_user_logged_in() ) { ?>
        <a href="<?php echo esc_url($main_cta); ?>" class="header-cta header-cta-job btn btn-main btn-effect btn-small">
            <?php esc_html_e( 'Post a Job', 'cariera' ); ?>
            <i class="icon-plus"></i>
        </a>
    <?php } else {
        if ( in_array( 'employer', (array) $current_user->roles ) || in_array( 'administrator', (array) $current_user->roles ) ) { ?>
            <a href="<?php echo esc_url($main_cta); ?>" class="header-cta header-cta-job btn btn-main btn-effect btn-small">
                <?php esc_html_e( 'Post a Job', 'cariera' ); ?>
                <i class="icon-plus"></i>
            </a>
        <?php }
    
        if ( in_array( 'candidate', (array) $current_user->roles ) ) { ?>
            <a href="<?php echo esc_url($candi_cta); ?>" class="header-cta header-cta-resume btn btn-main btn-effect btn-small">
                <?php esc_html_e( 'Post a Resume', 'cariera' ); ?>
                <i class="icon-plus"></i>
            </a>
        <?php }
    } ?>
</div>