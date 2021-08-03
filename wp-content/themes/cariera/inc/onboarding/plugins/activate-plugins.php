<?php

/**
*
* @package Cariera
*
* @since 1.0.0
* 
* ========================
* PLUGIN ACTIVATION FILE
* ========================
*     
**/


/**
 * Include the TGM_Plugin_Activation class.
 */
require_once get_template_directory() . '/inc/onboarding/plugins/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'cariera_register_required_plugins' );




/**
 * Register the required plugins for this theme.
 */
function cariera_register_required_plugins() {

	$plugins = array(        
        array(
			'name'               => 'Cariera Core',
			'slug'               => 'cariera-plugin',
			'source'             => 'https://cariera.co/plugins/MvyNII6zyzowFUrC/cariera-plugin.zip',
			'required'           => true,
			'version'            => '1.5.1',
			'force_activation'   => false,
		),
		array(
			'name'               => 'Revolution Slider', 
			'slug'               => 'revslider', 
			'source'             => 'https://cariera.co/plugins/revslider.zip',
			'required'           => true, 
			'version'            => '6.4.3',
			'force_activation'   => false,
		),
        array(
			'name'               => 'Envato Market', 
			'slug'               => 'envato-market', 
			'source'             => 'https://cariera.co/plugins/envato-market.zip', 
			'required'           => true,
			'version'            => '2.0.6',
			'force_activation'   => false,
		),
		array(
			'name'      			=> 'Classic Editor',
			'slug'      			=> 'classic-editor',
			'required'  			=> false,
		),
		array(
			'name'      			=> 'Elementor',
			'slug'      			=> 'elementor',
			'required'  			=> true,
		),
		array(
			'name'      			=> 'Kirki Framework',
			'slug'      			=> 'kirki',
			'required'  			=> true,
		),
		array(
			'name'      			=> 'Meta Box',
			'slug'      			=> 'meta-box',
			'required'  			=> true,
		),
        array(
			'name'      			=> 'WooCommerce',
			'slug'      			=> 'woocommerce',
			'required'  			=> true,
		),
        array(
	        'name'                  => 'WP Job Manager',
	        'slug'                  => 'wp-job-manager',
	        'required'              => true,
	    ),
        array(
			'name'               => 'WP Job Manager - Alerts', 
			'slug'               => 'wp-job-manager-alerts', 
			'source'             => 'https://cariera.co/plugins/wp-job-manager-alerts.zip',
			'required'           => false,
			'version'            => '1.5.4',
		),
        array(
			'name'               => 'WP Job Manager - Applications', 
			'slug'               => 'wp-job-manager-applications', 
			'source'             => 'https://cariera.co/plugins/wp-job-manager-applications.zip',
			'required'           => false,
			'version'            => '2.5.1',
		),
		array(
			'name'               => 'WP Job Manager - Application Deadline', 
			'slug'               => 'wp-job-manager-application-deadline', 
			'source'             => 'https://cariera.co/plugins/wp-job-manager-application-deadline.zip',
			'required'           => false,
			'version'            => '1.2.3',
		),
        array(
			'name'               => 'WP Job Manager - Bookmarks', 
			'slug'               => 'wp-job-manager-bookmarks', 
			'source'             => 'https://cariera.co/plugins/wp-job-manager-bookmarks.zip',
			'required'           => false,
			'version'            => '1.4.1',
		),
        array(
			'name'               => 'WP Job Manager - Resumes', 
			'slug'               => 'wp-job-manager-resumes', 
			'source'             => 'https://cariera.co/plugins/wp-job-manager-resumes.zip',
			'required'           => false,
			'version'            => '1.18.2',
		),
        array(
			'name'               => 'WP Job Manager - Tags', 
			'slug'               => 'wp-job-manager-tags', 
			'source'             => 'https://cariera.co/plugins/wp-job-manager-tags.zip',
			'required'           => false,
			'version'            => '1.4.1',
		),
        array(
			'name'               => 'WP Job Manager - WC Paid Listings', 
			'slug'               => 'wp-job-manager-wc-paid-listings', 
			'source'             => 'https://cariera.co/plugins/wp-job-manager-wc-paid-listings.zip',
			'required'           => false,
			'version'            => '2.9.2',
		),
        array(
			'name'      			=> 'Contact Form 7',
			'slug'      			=> 'contact-form-7',
			'required'  			=> false,
		),
        array(
	        'name'                  => 'MailChimp for WordPress',
	        'slug'                  => 'mailchimp-for-wp',
	        'required'              => false,
	    ),
	);

    
    
	/*
	 * Array of configuration settings. Amend each line as needed.
	 *
	 * TGMPA will start providing localized text strings soon. If you already have translations of our standard
	 * strings available, please help us make TGMPA even better by giving us access to these translations or by
	 * sending in a pull-request with .po file(s) with the translations.
	 *
	 * Only uncomment the strings in the config array if you want to customize the strings.
	 */
    
	$config = array(
		'id'           => 'cariera',                 // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to bundled plugins.
		'menu'         => 'install-required-plugins', // Menu slug.
		'parent_slug'  => 'themes.php',            // Parent menu slug.
		'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.
	);
    

	tgmpa( $plugins, $config );
}
