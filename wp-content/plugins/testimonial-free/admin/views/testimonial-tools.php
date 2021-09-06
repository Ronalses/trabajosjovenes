<?php if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.
//
// Set a unique slug-like ID.
//
$prefix = 'sp_testimonial_pro_tools';

//
// Create options.
//
SPFTESTIMONIAL::createOptions(
	$prefix,
	array(
		'menu_title'       => __( 'Tools', 'testimonial-free' ),
		'menu_slug'        => 'testimonial_tools',
		'menu_parent'      => 'edit.php?post_type=spt_testimonial',
		'menu_type'        => 'submenu',
		'ajax_save'        => false,
		'show_bar_menu'    => false,
		'save_defaults'    => false,
		'show_reset_all'   => false,
		'show_all_options' => false,
		'show_search'      => false,
		'show_footer'      => false,
		'show_buttons'     => false, // Custom show button option added for hide save button in tools page.
		'theme'            => 'light',
		'framework_title'  => __( 'Tools', 'testimonial-free' ),
		'framework_class'  => 'spt-main-class spftestimonial_tools',
	)
);
SPFTESTIMONIAL::createSection(
	$prefix,
	array(
		'title'  => __( 'Export', 'testimonial-free' ),
		'fields' => array(
			array(
				'id'       => 'spt_what_export',
				'type'     => 'radio',
				'class'    => 'spt_what_export',
				'title'    => __( 'Choose What To Export', 'testimonial-free' ),
				'multiple' => false,
				'options'  => array(
					'all_testimonial'         => __( 'All Testimonials', 'testimonial-free' ),
					'all_spt_shortcodes'      => __( 'All Testimonial Views (Shortcodes)', 'testimonial-free' ),
					'selected_spt_shortcodes' => __( 'Selected Testimonial Views (Shortcodes)', 'testimonial-free' ),
				),
				'default'  => 'all_testimonial',
			),
			array(
				'id'          => 'lcp_post',
				'class'       => 'spt_post_id',
				'type'        => 'select',
				'title'       => __( ' ', 'testimonial-free' ),
				'options'     => 'spt_shortcodes',
				'chosen'      => true,
				'sortable'    => false,
				'multiple'    => true,
				'placeholder' => __( 'Choose testimonial view(s)', 'testimonial-free' ),
				'query_args'  => array(
					'posts_per_page' => -1,
				),
				'dependency'  => array( 'spt_what_export', '==', 'selected_spt_shortcodes', true ),
			),
			array(
				'id'      => 'export',
				'class'   => 'spt_export',
				'type'    => 'button_set',
				'title'   => ' ',
				'options' => array(
					'' => 'Export',
				),
			),
		),
	)
);
SPFTESTIMONIAL::createSection(
	$prefix,
	array(
		'title'  => __( 'Import', 'testimonial-free' ),
		'fields' => array(
			array(
				'class' => 'spt_import',
				'type'  => 'custom_import',
				'title' => __( 'Import JSON File To Upload', 'testimonial-free' ),
			),
		),
	)
);
