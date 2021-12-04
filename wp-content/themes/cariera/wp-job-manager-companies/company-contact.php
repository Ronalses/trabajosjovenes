<?php
/**
*
* @package Cariera
*
* @since    1.5.0
* @version  1.5.0
* 
* ========================
* COMPANY - COMPANY CONTACT
* ========================
*     
**/



if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


$form_id = get_option('cariera_single_company_contact_form');
        
if ( !empty( $form_id ) ) {
    $shortcode = sprintf( '[contact-form-7 id="%1$d" title="%2$s"]', $form_id, get_the_title( $form_id ) );
    echo '<div class="company-contact">';
        echo '<a href="#company-contact-popup" class="btn btn-main btn-effect popup-with-zoom-anim">' . esc_html__( 'Contact Us', 'cariera' ) . '</a>';
    
        echo '<div id="company-contact-popup" class="small-dialog zoom-anim-dialog mfp-hide">';
            echo '<div class="bookmarks-popup">';
                echo '<div class="small-dialog-headline"><h3 class="title">' . esc_html__( 'Contact Company', 'cariera' ) . '</h3></div>';
                echo '<div class="small-dialog-content text-left">';
                    echo do_shortcode( $shortcode );
                echo '</div>';
            echo '</div>';
        echo '</div>';
        
    echo '</div>';    
}