<?php
/**
*
* @package Cariera
*
* @since 1.5.1
* 
* ========================
* TEMPLATE FOR TOP HEADER
* ========================
*     
**/



// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( !cariera_get_option( 'cariera_top_header' ) ) {
    return;
}

$top_header_style = cariera_get_option( 'cariera_top_header_style' ); ?>

<div class="top-bar-header <?php echo esc_attr($top_header_style) ?>">
    <div class="container">
        <div class="row">
            <?php for ( $i = 1; $i <= 2; $i++ ) : ?> 
                <div class="col-md-6 col-xs-12">
                    <?php dynamic_sidebar( 'top-header-widget-area' . ( $i > 1 ? ( '-' . absint( $i ) ) : '' ) ); ?>
                </div>
            <?php endfor; ?>
        </div>
    </div>
</div>