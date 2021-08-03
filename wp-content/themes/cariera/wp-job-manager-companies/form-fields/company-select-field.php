<?php

/**
*
* @package Cariera
*
* @since 1.4.0
* 
* ========================
* CUSTOM FIELD TO SELECT ALL COMPANIES
* ========================
*     
**/


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Get selected value.
if ( isset( $field['value'] ) ) {
	$selected = $field['value'];
} elseif ( isset( $field['default'] ) ) {
	$selected = $field['default'];
} elseif ( ! empty( $field['default'] ) && ( $company = get_posts(array('post_type' => 'company')) )) {
 	$selected = $company->ID;
} else {
	$selected = '';
}

// Select only supports 1 value.
if ( is_array( $selected ) ) {
	$selected = current( $selected );
}


wp_dropdown_pages( apply_filters( 'cariera_company_select_field_wp_dropdown_args', array(
    'post_type'         => 'company',
    'hierarchical'      => 1,
    'name'              => isset( $field['name'] ) ? $field['name'] : $key,
    'orderby'           => 'title',
    'show_option_none'  => esc_html__( 'Select Company', 'cariera' ),
    'selected'          => $selected,
	'class'             => 'cariera-select2-search',
    'hide_empty'        => false,
), $key, $field ) );

if ( ! empty( $field['description'] ) ) : ?><small class="description"><?php echo wp_kses_post( $field['description'] ); ?></small><?php endif; ?>