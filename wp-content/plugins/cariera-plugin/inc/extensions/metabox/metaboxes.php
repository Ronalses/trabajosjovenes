<?php

/**
*
* @package Cariera
*
* @since 1.1.0
* 
* ========================
* METABOXES
* ========================
*     
**/


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


    
add_filter( 'rwmb_meta_boxes', 'cariera_register_meta_boxes' );

function cariera_register_meta_boxes( $meta_boxes ) {
    
    /* get the registered sidebars */
    global $wp_registered_sidebars;

    $sidebars = array();
    foreach( $wp_registered_sidebars as $id=>$sidebar ) {
      $sidebars[ $id ] = $sidebar[ 'name' ];
	}
	
    /* ----------------------------------------------------- */
	// Page Settings
	/* ----------------------------------------------------- */
    $prefix = 'cariera_';

	$meta_boxes[] = array(
		'id' 		=> 'pagesettings',
		'title' 	=> 'Cariera Page Settings',
		'pages' 	=> array( 'page' ),
		'context' 	=> 'normal',
		'priority' 	=> 'high',

		'tabs'      => array(
			'main' 		=> array(
                'label' => esc_html__( 'Main', 'cariera' ),
            ),
            'header' 	=> array(
                'label' => esc_html__( 'Header', 'cariera' ),
            ),
            'footer' 	=> array(
                'label' => esc_html__( 'Footer', 'cariera' ),
            ),
        ),

        // Tab style: 'default', 'box' or 'left'. Optional
        'tab_style' => 'default',
	
		// List of meta fields
		'fields' => array(
            // Main Tab
			array(
				'name'		=> esc_html__( 'Page Title', 'cariera' ),
				'id'		=> $prefix . "show_page_title",
				'type'		=> 'select',
				'options'	=> array(
					'show'		=> 'Enable',
					'hide'		=> 'Disable'
				),
				'multiple'	=> false,
				'std'		=> array( 'show' ),
				'desc'      => esc_html__('Enable or disable the Page Header on this Page.', 'cariera'),
				'tab'       => 'main',
			),
            array(
                'name'              => esc_html__( 'Banner Image', 'cariera' ),
                'desc'              => esc_html__( 'The header image size should be at least 1600x200px', 'cariera' ),
                'id'                => $prefix . "page_header_bg",
                'type'              => 'image_advanced',
                'max_file_uploads'  => 1,
                'tab'       => 'main',
			),
			array(
				'name'		=> esc_html__( 'Page Layout', 'cariera' ),
				'id'		=> $prefix . 'page_layout',
				'type'		=> 'select',
				'options'	=> array(
					'fullwidth'     => esc_html__( 'Fullwidth', 'cariera' ),
					'sidebar'       => esc_html__( 'With Sidebar', 'cariera' ),
				),
				'multiple'	=> false,
				'std'		=> array( 'fullwidth' ),
				'desc'      => esc_html__( 'Choose the layout of your page.', 'cariera' ),
				'tab'       => 'main',
			),
			array(
				'id'		=> $prefix . 'select_page_sidebar',
				'name'		=> esc_html__( 'Select Sidebar', 'cariera' ),
				'desc'      => esc_html__( 'The sidebar will be shown only if you have chose a sidebar layout for the page in the "Page Layout" option.', 'cariera' ),
				'type'		=> 'select',
				'std'		=> array( 'sidebar-1' ),
				'options'	=> $sidebars,
				'multiple'	=> false,
				'tab'       => 'main',
			),
            
            // Header Tab
			array(
                'name'		=> esc_html__('Header', 'cariera'),
                'id'		=> $prefix . "show_header",
                'type'		=> 'select',
                'options'	=> array(
                    'show'		=> 'Enable',
                    'hide'		=> 'Disable'
                ),
                'multiple'	=> false,
                'std'		=> array( 'show' ),
                'desc' 		=> esc_html__('Enable or disable the Header on this Page.', 'cariera'),
                'tab'  		=> 'header',
			),
            array(
                'type' 		=> 'heading',
                'name' 		=> esc_html__('Header 1 - Extras', 'cariera'),
                'desc' 		=> '',
                'tab'  		=> 'header',
            ),
            array(
                'name'		=> esc_html__('Header Fixed Top', 'cariera'),
                'id'		=> $prefix . "header1_fixed_top",
                'type'		=> 'switch',
                'style'     => 'square',
                'on_label'  => esc_html__('Enable', 'cariera'),
                'off_label' => esc_html__('Disable', 'cariera'),
                'tab'  		=> 'header',
			),
            array(
                'name'		=> esc_html__('Header Transparent', 'cariera'),
                'id'		=> $prefix . "header1_transparent",
                'type'		=> 'switch',
                'style'     => 'square',
                'on_label'  => esc_html__('Enable', 'cariera'),
                'off_label' => esc_html__('Disable', 'cariera'),
                'tab'  		=> 'header',
			),
            array(
                'name'		=> esc_html__('Header White', 'cariera'),
                'id'		=> $prefix . "header1_white",
                'type'		=> 'switch',
                'style'     => 'square',
                'on_label'  => esc_html__('Enable', 'cariera'),
                'off_label' => esc_html__('Disable', 'cariera'),
                'tab'  		=> 'header',
			),
            
            // Footer Tab
			array(
                'name'		=> esc_html__('Footer', 'cariera'),
                'id'		=> $prefix . "show_footer",
                'type'		=> 'select',
                'options'	=> array(
                    'show'		=> 'Enable',
                    'hide'		=> 'Disable'
                ),
                'multiple'	=> false,
                'std'		=> array( 'show' ),
                'desc' 		=> esc_html__('Enable or disable the Footer on this Page.', 'cariera'),
                'tab'  		=> 'footer',
			),
            array(
                'name'		=> esc_html__('Footer Widgets', 'cariera'),
                'id'		=> $prefix . "show_footer_widgets",
                'type'		=> 'select',
                'options'	=> array(
                    'show'		=> 'Enable',
                    'hide'		=> 'Disable'
                ),
                'multiple'	=> false,
                'std'		=> array( 'show' ),
                'desc' 		=> esc_html__('Enable or disable the Footer Widgets on this Page.', 'cariera'),
                'tab'  		=> 'footer',
			),
		)
	);
    
    
    
    /* ----------------------------------------------------- */
	// Testimonials
	/* ----------------------------------------------------- */
	
	$meta_boxes[] = array(
		'id' 		=> 'testimonial_setting',
		'title' 	=> esc_html__( 'Testimonial Details', 'cariera'),
		'pages' 	=> array( 'testimonial' ),
		'context' 	=> 'normal',
		'priority' 	=> 'high',
	
		// List of meta fields
		'fields' => array(
			array(
				'name'	=> esc_html( 'Gravatar E-mail Address', 'cariera'),
				'desc'	=> esc_html__( 'Enter in an e-mail address, to use a Gravatar, instead of using the "Featured Image".', 'cariera'),
				'id'	=> $prefix . 'testimonial_gravatar',
				'type'	=> 'textarea',
                'std'   => ''
			),
            array(
				'name'	=> esc_html( 'Byline', 'cariera'),
				'desc'	=> esc_html__( 'Enter a byline for the customer giving this testimonial (for example: "CEO of Cariera").', 'cariera'),
				'id'	=> $prefix . 'testimonial_byline',
				'type'	=> 'textarea',
                'std'   => ''
			),
            array(
				'name'	=> esc_html( 'URL', 'cariera'),
				'desc'	=> esc_html__( 'Enter a URL that applies to this customer (for example: http://cariera.co/).', 'cariera'),
				'id'	=> $prefix . 'testimonial_url',
				'type'	=> 'textarea',
                'std'   => ''
			),
		)
	);
    
    
    
    /* ----------------------------------------------------- */
	// Blog Metaboxes
	/* ----------------------------------------------------- */
    
    // Audio Post Format
	$meta_boxes[] = array(
		'id' 		=> 'blog_audio',
		'title' 	=> esc_html__( 'Audio Settings', 'cariera'),
		'pages' 	=> array( 'post' ),
		'context' 	=> 'normal',
		'priority' 	=> 'high',
	
		// List of meta fields
		'fields' => array(
			array(
				'name'		=> esc_html__( 'Audio Embed Code', 'cariera'),
				'id'		=> $prefix . 'blog_audio',
				'desc'		=> esc_html__( 'Please enter the Audio Embed Code here.', 'cariera'),
				'clone'		=> false,
				'type'		=> 'textarea',
				'std'		=> ''
			),
		)
	);
    
    
    
    // Gallery Post Format
	$meta_boxes[] = array(
		'id' 		=> 'blog_gallery',
		'title' 	=> esc_html__( 'Gallery Settings', 'cariera'),
		'pages' 	=> array( 'post' ),
		'context' 	=> 'normal',
		'priority' 	=> 'high',
	
		// List of meta fields
		'fields' => array(
			array(
				'name'	=> esc_html__( 'Gallery', 'cariera'),
				'desc'	=> esc_html__( 'You can upload up to 10 gallery images for a slideshow', 'cariera'),
				'id'	=> $prefix . 'blog_gallery',
				'type'	=> 'image_advanced',
				'max_file_uploads' => 10
			)
		)
	);
    
    
    
	// Quote Post Format
	$meta_boxes[] = array(
		'id' 		=> 'blog_quote',
		'title' 	=> esc_html__( 'Quote Settings', 'cariera'),
		'pages' 	=> array( 'post' ),
		'context' 	=> 'normal',
		'priority' 	=> 'high',
	
		// List of meta fields
		'fields' => array(
			
            array(
				'name'		=> esc_html__( 'Quote Author', 'cariera'),
				'id'		=> $prefix . 'blog_quote_author',
				'desc'		=> esc_html__( 'Please enter the Authors Name of the Quote here.', 'cariera'),
				'clone'		=> false,
				'type'		=> 'text',
				'std'		=> ''
			),
			array(
				'name'		=> esc_html__( 'Quote Source', 'cariera'),
				'id'		=> $prefix . 'blog_quote_source',
				'desc'		=> esc_html__( 'Please enter the Source of the Quote here.', 'cariera'),
				'clone'		=> false,
				'type'		=> 'text',
				'std'		=> ''
			),
            array(
				'name'		=> esc_html__( 'Quote', 'cariera'),
				'id'		=> $prefix . 'blog_quote_content',
				'desc'		=> esc_html__( 'Please enter the text for your quote here.', 'cariera'),
				'clone'		=> false,
				'type'		=> 'textarea',
				'std'		=> ''
			),
		)
	);

    
    
	// Video Post Format
	$meta_boxes[] = array(
		'id' 		=> 'blog_video',
		'title' 	=> esc_html__( 'Video Settings', 'cariera'),
		'pages' 	=> array( 'post' ),
		'context' 	=> 'normal',
		'priority' 	=> 'high',
	
		// List of meta fields
		'fields' => array(
			array(
				'name'		=> esc_html__( 'Video Embed Code', 'cariera'),
				'id'		=> $prefix . 'blog_video_embed',
				'desc'		=> __( 'If you choose Video URL you can just insert the URL of the <a href="http://codex.wordpress.org/Embeds#Okay.2C_So_What_Sites_Can_I_Embed_From.3F" target="_blank">Supported Video Site</a>. Otherwise insert the full embed code.', 'cariera'),
				'clone'		=> false,
				'type'		=> 'textarea',
				'std'		=> '',
			),
		)
	);
	
	
    
    /* ----------------------------------------------------- */
	// Job Metaboxes
	/* ----------------------------------------------------- */
	
	$meta_boxes[] = array(
		'id' 		=> 'job_setting',
		'title' 	=> esc_html__( 'Featured Job Image', 'cariera'),
		'pages' 	=> array( 'job_listing' ),
		'context' 	=> 'side',
		'priority' 	=> 'low',
	
		// List of meta fields
		'fields' => array(
			array(
				'name'	=> '',
				'desc'	=> esc_html__( 'The featured image size should be at least 1600x200px. (Featured images that are uploaded from the front-end can not be viewed here.)', 'cariera'),
				'id'	=> $prefix . 'job_page_header',
				'type'	=> 'image_advanced',
				'max_file_uploads' => 1,
			)
		)
	);


    
    /* ----------------------------------------------------- */
    // Metabox Visibility for Blog Posts
    /* ----------------------------------------------------- */
    
    add_action( 'admin_print_scripts', 'displayMetaboxes', 1000 );

    if ( ! function_exists( 'displayMetaboxes' ) ) {

        function displayMetaboxes() {

            if ( get_post_type() == "post" || get_post_type() == "page" ) { ?>

                <script type="text/javascript">// <![CDATA[

                jQuery(document).ready(function($){

                    function displayMetaBox() {
                        $('#blog_quote, #blog_video, #blog_audio, #blog_gallery').hide();
                        var selectedformat = $("input[name='post_format']:checked").val();

                        if( selectedformat ) {
                            if( selectedformat == 'quote' ) {
                                $("#blog_quote").fadeIn();
                            }
                            if( selectedformat == 'video' ) {
                                $("#blog_video").fadeIn();
                            }
                            if( selectedformat == 'audio' ) {
                                $("#blog_audio").fadeIn();
                            }
                            if( selectedformat == 'gallery' ) {
                                $("#blog_gallery").fadeIn();
                            }
                        }
                    }

                    $(function() {
                        displayMetaBox();
                        $("input[name='post_format']").change(function() {
                            displayMetaBox();
                        });
                    });

                 });

                // ]]></script>
            <?php 
            }

        }
    }
    
    
    return $meta_boxes;
}