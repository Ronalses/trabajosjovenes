<?php
/**
*
* @package Cariera
*
* @since 1.0.0
* 
* ========================
* SIDEBAR
* ========================
*     
**/



$blog_layout    = cariera_get_option( 'cariera_blog_layout' );
$sidebar_side   = ( $blog_layout == 'left-sidebar' ) ? 'sidebar-left' : ''; ?>

<div class="col-md-4 col-xs-12 <?php echo esc_attr($sidebar_side) ?>">
    <?php if ( is_active_sidebar( 'sidebar-1' ) ) { ?>
        <div class="sidebar">
            <?php dynamic_sidebar('sidebar-1'); ?>
        </div>
    <?php } ?>
</div>


