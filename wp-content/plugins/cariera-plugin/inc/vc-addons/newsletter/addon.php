<?php

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}



// get form id of mailchimp
$forms    = get_posts( 'post_type=mc4wp-form&number=-1' );
$form_ids = array(
    esc_html( 'Select a form', 'cariera' ) => '0',
);
foreach ( $forms as $form ) {
    $form_ids[ $form->post_title ] = $form->ID;
}


vc_map( array(
    'name'                      => esc_html__( 'Newsletter', 'cariera' ),
    'description'               => esc_html__( 'Add a Mailchimp Form.', 'cariera' ),
    'base'                      => 'cariera_newsletter',
    'class'                     => '',
    'category'                  => 'Cariera Custom',
    'group'                     => 'Cariera Custom',
    'params'                    => array(
        array(
            'type'       => 'dropdown',
            'heading'    => esc_html__( 'Style', 'cariera' ),
            'param_name' => 'style',
            'value'      => array(
                esc_html__( 'Style 1', 'cariera' ) => 'nl-style-1',
                esc_html__( 'Style 2', 'cariera' ) => 'nl-style-2',
            ),
        ),
        array(
            'type'       => 'textarea',
            'heading'    => esc_html__( 'Title', 'cariera' ),
            'param_name' => 'content',
            'value'      => '',
        ),
        array(
            'type'       => 'textarea',
            'heading'    => esc_html__( 'Description', 'cariera' ),
            'param_name' => 'desc',
            'value'      => '',
        ),
        array(
            'type'       => 'dropdown',
            'heading'    => esc_html__( 'Mailchimp Form', 'cariera' ),
            'param_name' => 'form',
            'value'      => $form_ids,
        ),
        array(
            'type'        => 'textfield',
            'heading'     => esc_html__( 'Extra class name', 'cariera' ),
            'param_name'  => 'el_class',
            'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'cariera' ),
        ),
    ),
));





/*
Shortcode logic how it should be rendered
*/
if ( !function_exists( 'cariera_newsletter' ) ) {
    function cariera_newsletter( $atts, $content = null ) {
        $args = array(
            'style'         => 'nl-style-1',
            'desc'          => '',
            'form'          => '',
            'el_class'      => '',
        );
	        
		extract(shortcode_atts($args, $atts));
        
        if ( $content ) {
			$content = sprintf( '<h4 class="title">%s</h4>', $content );
		}
        
        if ( $desc ) {
			$content .= sprintf( '<span class="description">%s</span>', $atts['desc'] );
		}
        
        $form_html = '';
		if ( $form ) {
			$form_html = sprintf( '<div class="nl-form">%s</div>', do_shortcode( '[mc4wp_form id="' . esc_attr( $atts['form'] ) . '"]' ) );
		}

		$col_left  = 'col-md-12 col-sm-12 col-xs-12';
		$col_right = 'col-md-12 col-sm-12 col-xs-12';

		if ( $style == 'nl-style-2' ) {
			$col_left  = 'col-md-5 col-sm-12 col-xs-12';
			$col_right = 'col-md-7 col-sm-12 col-xs-12';
		}
        
        
        $output = '<div class="cariera-newsletter ' . $style . ' ' . $el_class . '">
                    <div class="row">
                        <div class="' . esc_attr( $col_left ) . '">
                            ' . $content . '
                        </div>
                        <div class="' . esc_attr( $col_right ) . '">
                            ' . $form_html . '
                        </div>
                    </div>
                </div>';
        
        return $output;
        
    }
}

add_shortcode('cariera_newsletter', 'cariera_newsletter');