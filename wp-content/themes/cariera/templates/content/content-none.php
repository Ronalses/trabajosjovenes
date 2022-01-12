<?php
/**
*
* @package Cariera
*
* @since 1.0.0
* 
* ========================
* TEMPLATE FOR DISPLAYING A MESSAGE WHEN NO POST CAN BE FOUND
* ========================
*     
**/



// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<h3><?php esc_attr_e( 'Nothing found!', 'cariera' ); ?></h3>
<p><?php esc_attr_e( 'Sorry, but nothing matched your search terms. Please search again.', 'cariera' ); ?></p>
