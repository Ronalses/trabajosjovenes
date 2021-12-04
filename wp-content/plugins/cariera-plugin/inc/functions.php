<?php
/**
*
* @package Cariera
*
* @since    1.2.4
* @version  1.5.1
* 
* ========================
* CARIERA CORE FUNCTIONS
* ========================
*     
**/


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}





/**
 * Initializing Cariera Core
 *
 * @since  1.2.4
 */
function cariera_init() {
    global $CarieraCore;
    
    $CarieraCore               = new CarieraCore();
    $CarieraCore['path']       = realpath( plugin_dir_path( __FILE__ ) ). DIRECTORY_SEPARATOR;
    $CarieraCore['url']        = plugin_dir_url( __FILE__ );
    $CarieraCore['CarieraDemoImporter'] = new CarieraDemoImporter();
    apply_filters( 'cariera/config', $CarieraCore );
    $CarieraCore->run();
}

add_action( 'init', 'cariera_init', 10, 1 );





/**
 * Changing the directory of the default VC Templates
 *
 * @since  1.3.4.2
 */
if ( class_exists('WPBakeryShortCode') ) {
    $dir = CARIERA_PATH . '/vc_templates/';
    
    vc_set_shortcodes_templates_dir( $dir );
}





/**
 * Add VC_ROW Overlay Elements
 *
 * @since  1.2.5
 */
if ( class_exists('WPBakeryShortCode') ) {
    function cariera_vc_rows() {

        vc_add_param('vc_row', array(
            'type'          => 'colorpicker',
            'heading'       => esc_html__( 'Overlay Color', 'cariera' ),
            'param_name'    => 'overlay_color',
            'value'         => ''
        ));
        vc_add_param('vc_row', array(
            'type'          => 'colorpicker',
            'heading'       => esc_html__( 'Background Gradient - Left.', 'cariera' ),
            'param_name'    => 'bg_gradient_left',
            'value'         => ''
        ));
        vc_add_param('vc_row', array(
            'type'          => 'colorpicker',
            'heading'       => esc_html__( 'Background Gradient - Right', 'cariera' ),
            'description'   => esc_html__( 'Notice: If you want to use Background Gradient, both Background Gradient Left and Background Gradient Right are required. This setting will override the Overlay Color setting above.', 'cariera' ),
            'param_name'    => 'bg_gradient_right',
            'value'         => ''
        ));    
    }
    
    add_action( 'init', 'cariera_vc_rows' );
}





/**
 *  Limits number of words from string.
 *
 * @since  1.0
 */
if ( ! function_exists( 'cariera_string_limit_words' ) ) {
    function cariera_string_limit_words($string, $word_limit) {
        $words = explode(' ', $string, ($word_limit + 1));
        if (count($words) > $word_limit) {
            array_pop($words);
            return implode(' ', $words) ;
        } else {
            return implode(' ', $words);
        }
    }
}





/**
 * Custom Job Manager Category Dropdown Function
 *
 * @since  1.2.7
 */
function cariera_job_manager_dropdown_category( $args = '' ) {
	$defaults = array(
		'orderby'         => 'id',
		'order'           => 'ASC',
		'show_count'      => 0,
		'hide_empty'      => 1,
		'parent'          => '',
		'child_of'        => 0,
		'exclude'         => '',
		'echo'            => 1,
		'selected'        => 0,
		'hierarchical'    => 0,
		'name'            => 'cat',
		'id'              => '',
		'class'           => 'job-manager-category-dropdown cariera-select2-search ' . ( is_rtl() ? 'chosen-rtl' : '' ),
		'depth'           => 0,
		'taxonomy'        => 'job_listing_category',
		'value'           => 'id',
		'multiple'        => true,
		'show_option_all' => false,
		'placeholder'     => esc_html__( 'Choose a category&hellip;', 'cariera' ),
		'no_results_text' => esc_html__( 'No results match', 'cariera' ),
		'multiple_text'   => esc_html__( 'Select Some Options', 'cariera' )
	);

	$r = wp_parse_args( $args, $defaults );

	if ( ! isset( $r['pad_counts'] ) && $r['show_count'] && $r['hierarchical'] ) {
		$r['pad_counts'] = true;
	}

	/** This filter is documented in wp-job-manager.php */
	$r['lang'] = apply_filters( 'wpjm_lang', null );

	extract( $r );

	// Store in a transient to help sites with many cats
	$categories_hash = 'jm_cats_' . md5( json_encode( $r ) . WP_Job_Manager_Cache_Helper::get_transient_version( 'jm_get_' . $r['taxonomy'] ) );
	$categories      = get_transient( $categories_hash );

	if ( empty( $categories ) ) {
		$categories = get_terms( $taxonomy, array(
			'orderby'         => $r['orderby'],
			'order'           => $r['order'],
			'hide_empty'      => $r['hide_empty'],
			'parent'          => $r['parent'],
			'child_of'        => $r['child_of'],
			'exclude'         => $r['exclude'],
			'hierarchical'    => $r['hierarchical']
		) );
		set_transient( $categories_hash, $categories, DAY_IN_SECONDS * 7 );
	}

	$name       = esc_attr( $name );
	$class      = esc_attr( $class );
	$id         = $id ? esc_attr( $id ) : $name;

	$output = "<select name='" . esc_attr( $name ) . "' id='" . esc_attr( $id ) . "' class='" . esc_attr( $class ) . "' " . ( $multiple ? "multiple='multiple'" : '' ) . " data-placeholder='" . esc_attr( $placeholder ) . "' data-no_results_text='" . esc_attr( $no_results_text ) . "' data-multiple_text='" . esc_attr( $multiple_text ) . "'>\n";

	if ( $show_option_all ) {
		$output .= '<option value="">' . esc_html( $show_option_all ) . '</option>';
	}

	if ( ! empty( $categories ) ) {
		include_once( JOB_MANAGER_PLUGIN_DIR . '/includes/class-wp-job-manager-category-walker.php' );

		$walker = new WP_Job_Manager_Category_Walker;

		if ( $hierarchical ) {
			$depth = $r['depth'];  // Walk the full depth.
		} else {
			$depth = -1; // Flat.
		}

		$output .= $walker->walk( $categories, $depth, $r );
	}

	$output .= "</select>\n";

	if ( $echo ) {
		echo $output;
	}

	return $output;
}




/**
 * Enqueue Iconsmind Icons to the VC Icon Picker
 *
 * @since  1.3.0
 */
function cariera_iconpicker_iconsmind_enqueue() {
	wp_enqueue_style( 'iconsmind', get_template_directory_uri() . '/assets/vendors/font-icons/iconsmind.min.css');
}

add_action( 'vc_backend_editor_enqueue_js_css', 'cariera_iconpicker_iconsmind_enqueue' );





/**
 * Add Iconsmind Icons to the VC Icon Picker
 *
 * @since  1.3.0
 */
function cariera_iconpicker_type_iconsmind( $icons ) {
    $iconsmind_icons = array(
        array( 'iconsmind-A-Z' => 'A-Z' ),
        array( 'iconsmind-Aa' => 'Aa' ),
        array( 'iconsmind-Add-Bag' => 'Add-Bag' ),
        array( 'iconsmind-Add-Basket' => 'Add-Basket' ),
        array( 'iconsmind-Add-Cart' => 'Add-Cart' ),
        array( 'iconsmind-Add-File' => 'Add-File' ),
        array( 'iconsmind-Add-SpaceAfterParagraph' => 'Add-SpaceAfterParagraph' ),
        array( 'iconsmind-Add-SpaceBeforeParagraph' => 'Add-SpaceBeforeParagraph' ),
        array( 'iconsmind-Add-User' => 'Add-User' ),
        array( 'iconsmind-Add-UserStar' => 'Add-UserStar' ),
        array( 'iconsmind-Add-Window' => 'Add-Window' ),
        array( 'iconsmind-Add' => 'Add' ),
        array( 'iconsmind-Address-Book' => 'Address-Book' ),
        array( 'iconsmind-Address-Book2' => 'Address-Book2' ),
        array( 'iconsmind-Administrator' => 'Administrator' ),
        array( 'iconsmind-Aerobics-2' => 'Aerobics-2' ),
        array( 'iconsmind-Aerobics-3' => 'Aerobics-3' ),
        array( 'iconsmind-Aerobics' => 'Aerobics' ),
        array( 'iconsmind-Affiliate' => 'Affiliate' ),
        array( 'iconsmind-Aim' => 'Aim' ),
        array( 'iconsmind-Air-Balloon' => 'Air-Balloon' ),
        array( 'iconsmind-Airbrush' => 'Airbrush' ),
        array( 'iconsmind-Airship' => 'Airship' ),
        array( 'iconsmind-Alarm-Clock' => 'Alarm-Clock' ),
        array( 'iconsmind-Alarm-Clock2' => 'Alarm-Clock2' ),
        array( 'iconsmind-Alarm' => 'Alarm' ),
        array( 'iconsmind-Alien-2' => 'Alien-2' ),
        array( 'iconsmind-Alien' => 'Alien' ),
        array( 'iconsmind-Aligator' => 'Aligator' ),
        array( 'iconsmind-Align-Center' => 'Align-Center' ),
        array( 'iconsmind-Align-JustifyAll' => 'Align-JustifyAll' ),
        array( 'iconsmind-Align-JustifyCenter' => 'Align-JustifyCenter' ),
        array( 'iconsmind-Align-JustifyLeft' => 'Align-JustifyLeft' ),
        array( 'iconsmind-Align-JustifyRight' => 'Align-JustifyRight' ),
        array( 'iconsmind-Align-Left' => 'Align-Left' ),
        array( 'iconsmind-Align-Right' => 'Align-Right' ),
        array( 'iconsmind-Alpha' => 'Alpha' ),
        array( 'iconsmind-Ambulance' => 'Ambulance' ),
        array( 'iconsmind-AMX' => 'AMX' ),
        array( 'iconsmind-Anchor-2' => 'Anchor-2' ),
        array( 'iconsmind-Anchor' => 'Anchor' ),
        array( 'iconsmind-Android-Store' => 'Android-Store' ),
        array( 'iconsmind-Android' => 'Android' ),
        array( 'iconsmind-Angel-Smiley' => 'Angel-Smiley' ),
        array( 'iconsmind-Angel' => 'Angel' ),
        array( 'iconsmind-Angry' => 'Angry' ),
        array( 'iconsmind-Apple-Bite' => 'Apple-Bite' ),
        array( 'iconsmind-Apple-Store' => 'Apple-Store' ),
        array( 'iconsmind-Apple' => 'Apple' ),
        array( 'iconsmind-Approved-Window' => 'Approved-Window' ),
        array( 'iconsmind-Aquarius-2' => 'Aquarius-2' ),
        array( 'iconsmind-Aquarius' => 'Aquarius' ),
        array( 'iconsmind-Archery-2' => 'Archery-2' ),
        array( 'iconsmind-Archery' => 'Archery' ),
        array( 'iconsmind-Argentina' => 'Argentina' ),
        array( 'iconsmind-Aries-2' => 'Aries-2' ),
        array( 'iconsmind-Aries' => 'Aries' ),
        array( 'iconsmind-Army-Key' => 'Army-Key' ),
        array( 'iconsmind-Arrow-Around' => 'Arrow-Around' ),
        array( 'iconsmind-Arrow-Back3' => 'Arrow-Back3' ),
        array( 'iconsmind-Arrow-Back' => 'Arrow-Back' ),
        array( 'iconsmind-Arrow-Back2' => 'Arrow-Back2' ),
        array( 'iconsmind-Arrow-Barrier' => 'Arrow-Barrier' ),
        array( 'iconsmind-Arrow-Circle' => 'Arrow-Circle' ),
        array( 'iconsmind-Arrow-Cross' => 'Arrow-Cross' ),
        array( 'iconsmind-Arrow-Down' => 'Arrow-Down' ),
        array( 'iconsmind-Arrow-Down2' => 'Arrow-Down2' ),
        array( 'iconsmind-Arrow-Down3' => 'Arrow-Down3' ),
        array( 'iconsmind-Arrow-DowninCircle' => 'Arrow-DowninCircle' ),
        array( 'iconsmind-Arrow-Fork' => 'Arrow-Fork' ),
        array( 'iconsmind-Arrow-Forward' => 'Arrow-Forward' ),
        array( 'iconsmind-Arrow-Forward2' => 'Arrow-Forward2' ),
        array( 'iconsmind-Arrow-From' => 'Arrow-From' ),
        array( 'iconsmind-Arrow-Inside' => 'Arrow-Inside' ),
        array( 'iconsmind-Arrow-Inside45' => 'Arrow-Inside45' ),
        array( 'iconsmind-Arrow-InsideGap' => 'Arrow-InsideGap' ),
        array( 'iconsmind-Arrow-InsideGap45' => 'Arrow-InsideGap45' ),
        array( 'iconsmind-Arrow-Into' => 'Arrow-Into' ),
        array( 'iconsmind-Arrow-Join' => 'Arrow-Join' ),
        array( 'iconsmind-Arrow-Junction' => 'Arrow-Junction' ),
        array( 'iconsmind-Arrow-Left' => 'Arrow-Left' ),
        array( 'iconsmind-Arrow-Left2' => 'Arrow-Left2' ),
        array( 'iconsmind-Arrow-LeftinCircle' => 'Arrow-LeftinCircle' ),
        array( 'iconsmind-Arrow-Loop' => 'Arrow-Loop' ),
        array( 'iconsmind-Arrow-Merge' => 'Arrow-Merge' ),
        array( 'iconsmind-Arrow-Mix' => 'Arrow-Mix' ),
        array( 'iconsmind-Arrow-Next' => 'Arrow-Next' ),
        array( 'iconsmind-Arrow-OutLeft' => 'Arrow-OutLeft' ),
        array( 'iconsmind-Arrow-OutRight' => 'Arrow-OutRight' ),
        array( 'iconsmind-Arrow-Outside' => 'Arrow-Outside' ),
        array( 'iconsmind-Arrow-Outside45' => 'Arrow-Outside45' ),
        array( 'iconsmind-Arrow-OutsideGap' => 'Arrow-OutsideGap' ),
        array( 'iconsmind-Arrow-OutsideGap45' => 'Arrow-OutsideGap45' ),
        array( 'iconsmind-Arrow-Over' => 'Arrow-Over' ),
        array( 'iconsmind-Arrow-Refresh' => 'Arrow-Refresh' ),
        array( 'iconsmind-Arrow-Refresh2' => 'Arrow-Refresh2' ),
        array( 'iconsmind-Arrow-Right' => 'Arrow-Right' ),
        array( 'iconsmind-Arrow-Right2' => 'Arrow-Right2' ),
        array( 'iconsmind-Arrow-RightinCircle' => 'Arrow-RightinCircle' ),
        array( 'iconsmind-Arrow-Shuffle' => 'Arrow-Shuffle' ),
        array( 'iconsmind-Arrow-Squiggly' => 'Arrow-Squiggly' ),
        array( 'iconsmind-Arrow-Through' => 'Arrow-Through' ),
        array( 'iconsmind-Arrow-To' => 'Arrow-To' ),
        array( 'iconsmind-Arrow-TurnLeft' => 'Arrow-TurnLeft' ),
        array( 'iconsmind-Arrow-TurnRight' => 'Arrow-TurnRight' ),
        array( 'iconsmind-Arrow-Up' => 'Arrow-Up' ),
        array( 'iconsmind-Arrow-Up2' => 'Arrow-Up2' ),
        array( 'iconsmind-Arrow-Up3' => 'Arrow-Up3' ),
        array( 'iconsmind-Arrow-UpinCircle' => 'Arrow-UpinCircle' ),
        array( 'iconsmind-Arrow-XLeft' => 'Arrow-XLeft' ),
        array( 'iconsmind-Arrow-XRight' => 'Arrow-XRight' ),
        array( 'iconsmind-Ask' => 'Ask' ),
        array( 'iconsmind-Assistant' => 'Assistant' ),
        array( 'iconsmind-Astronaut' => 'Astronaut' ),
        array( 'iconsmind-At-Sign' => 'At-Sign' ),
        array( 'iconsmind-ATM' => 'ATM' ),
        array( 'iconsmind-Atom' => 'Atom' ),
        array( 'iconsmind-Audio' => 'Audio' ),
        array( 'iconsmind-Auto-Flash' => 'Auto-Flash' ),
        array( 'iconsmind-Autumn' => 'Autumn' ),
        array( 'iconsmind-Baby-Clothes' => 'Baby-Clothes' ),
        array( 'iconsmind-Baby-Clothes2' => 'Baby-Clothes2' ),
        array( 'iconsmind-Baby-Cry' => 'Baby-Cry' ),
        array( 'iconsmind-Baby' => 'Baby' ),
        array( 'iconsmind-Back2' => 'Back2' ),
        array( 'iconsmind-Back-Media' => 'Back-Media' ),
        array( 'iconsmind-Back-Music' => 'Back-Music' ),
        array( 'iconsmind-Back' => 'Back' ),
        array( 'iconsmind-Background' => 'Background' ),
        array( 'iconsmind-Bacteria' => 'Bacteria' ),
        array( 'iconsmind-Bag-Coins' => 'Bag-Coins' ),
        array( 'iconsmind-Bag-Items' => 'Bag-Items' ),
        array( 'iconsmind-Bag-Quantity' => 'Bag-Quantity' ),
        array( 'iconsmind-Bag' => 'Bag' ),
        array( 'iconsmind-Bakelite' => 'Bakelite' ),
        array( 'iconsmind-Ballet-Shoes' => 'Ballet-Shoes' ),
        array( 'iconsmind-Balloon' => 'Balloon' ),
        array( 'iconsmind-Banana' => 'Banana' ),
        array( 'iconsmind-Band-Aid' => 'Band-Aid' ),
        array( 'iconsmind-Bank' => 'Bank' ),
        array( 'iconsmind-Bar-Chart' => 'Bar-Chart' ),
        array( 'iconsmind-Bar-Chart2' => 'Bar-Chart2' ),
        array( 'iconsmind-Bar-Chart3' => 'Bar-Chart3' ),
        array( 'iconsmind-Bar-Chart4' => 'Bar-Chart4' ),
        array( 'iconsmind-Bar-Chart5' => 'Bar-Chart5' ),
        array( 'iconsmind-Bar-Code' => 'Bar-Code' ),
        array( 'iconsmind-Barricade-2' => 'Barricade-2' ),
        array( 'iconsmind-Barricade' => 'Barricade' ),
        array( 'iconsmind-Baseball' => 'Baseball' ),
        array( 'iconsmind-Basket-Ball' => 'Basket-Ball' ),
        array( 'iconsmind-Basket-Coins' => 'Basket-Coins' ),
        array( 'iconsmind-Basket-Items' => 'Basket-Items' ),
        array( 'iconsmind-Basket-Quantity' => 'Basket-Quantity' ),
        array( 'iconsmind-Bat-2' => 'Bat-2' ),
        array( 'iconsmind-Bat' => 'Bat' ),
        array( 'iconsmind-Bathrobe' => 'Bathrobe' ),
        array( 'iconsmind-Batman-Mask' => 'Batman-Mask' ),
        array( 'iconsmind-Battery-0' => 'Battery-0' ),
        array( 'iconsmind-Battery-25' => 'Battery-25' ),
        array( 'iconsmind-Battery-50' => 'Battery-50' ),
        array( 'iconsmind-Battery-75' => 'Battery-75' ),
        array( 'iconsmind-Battery-100' => 'Battery-100' ),
        array( 'iconsmind-Battery-Charge' => 'Battery-Charge' ),
        array( 'iconsmind-Bear' => 'Bear' ),
        array( 'iconsmind-Beard-2' => 'Beard-2' ),
        array( 'iconsmind-Beard-3' => 'Beard-3' ),
        array( 'iconsmind-Beard' => 'Beard' ),
        array( 'iconsmind-Bebo' => 'Bebo' ),
        array( 'iconsmind-Bee' => 'Bee' ),
        array( 'iconsmind-Beer-Glass' => 'Beer-Glass' ),
        array( 'iconsmind-Beer' => 'Beer' ),
        array( 'iconsmind-Bell-2' => 'Bell-2' ),
        array( 'iconsmind-Bell' => 'Bell' ),
        array( 'iconsmind-Belt-2' => 'Belt-2' ),
        array( 'iconsmind-Belt-3' => 'Belt-3' ),
        array( 'iconsmind-Belt' => 'Belt' ),
        array( 'iconsmind-Berlin-Tower' => 'Berlin-Tower' ),
        array( 'iconsmind-Beta' => 'Beta' ),
        array( 'iconsmind-Betvibes' => 'Betvibes' ),
        array( 'iconsmind-Bicycle-2' => 'Bicycle-2' ),
        array( 'iconsmind-Bicycle-3' => 'Bicycle-3' ),
        array( 'iconsmind-Bicycle' => 'Bicycle' ),
        array( 'iconsmind-Big-Bang' => 'Big-Bang' ),
        array( 'iconsmind-Big-Data' => 'Big-Data' ),
        array( 'iconsmind-Bike-Helmet' => 'Bike-Helmet' ),
        array( 'iconsmind-Bikini' => 'Bikini' ),
        array( 'iconsmind-Bilk-Bottle2' => 'Bilk-Bottle2' ),
        array( 'iconsmind-Billing' => 'Billing' ),
        array( 'iconsmind-Bing' => 'Bing' ),
        array( 'iconsmind-Binocular' => 'Binocular' ),
        array( 'iconsmind-Bio-Hazard' => 'Bio-Hazard' ),
        array( 'iconsmind-Biotech' => 'Biotech' ),
        array( 'iconsmind-Bird-DeliveringLetter' => 'Bird-DeliveringLetter' ),
        array( 'iconsmind-Bird' => 'Bird' ),
        array( 'iconsmind-Birthday-Cake' => 'Birthday-Cake' ),
        array( 'iconsmind-Bisexual' => 'Bisexual' ),
        array( 'iconsmind-Bishop' => 'Bishop' ),
        array( 'iconsmind-Bitcoin' => 'Bitcoin' ),
        array( 'iconsmind-Black-Cat' => 'Black-Cat' ),
        array( 'iconsmind-Blackboard' => 'Blackboard' ),
        array( 'iconsmind-Blinklist' => 'Blinklist' ),
        array( 'iconsmind-Block-Cloud' => 'Block-Cloud' ),
        array( 'iconsmind-Block-Window' => 'Block-Window' ),
        array( 'iconsmind-Blogger' => 'Blogger' ),
        array( 'iconsmind-Blood' => 'Blood' ),
        array( 'iconsmind-Blouse' => 'Blouse' ),
        array( 'iconsmind-Blueprint' => 'Blueprint' ),
        array( 'iconsmind-Board' => 'Board' ),
        array( 'iconsmind-Bodybuilding' => 'Bodybuilding' ),
        array( 'iconsmind-Bold-Text' => 'Bold-Text' ),
        array( 'iconsmind-Bone' => 'Bone' ),
        array( 'iconsmind-Bones' => 'Bones' ),
        array( 'iconsmind-Book' => 'Book' ),
        array( 'iconsmind-Bookmark' => 'Bookmark' ),
        array( 'iconsmind-Books-2' => 'Books-2' ),
        array( 'iconsmind-Books' => 'Books' ),
        array( 'iconsmind-Boom' => 'Boom' ),
        array( 'iconsmind-Boot-2' => 'Boot-2' ),
        array( 'iconsmind-Boot' => 'Boot' ),
        array( 'iconsmind-Bottom-ToTop' => 'Bottom-ToTop' ),
        array( 'iconsmind-Bow-2' => 'Bow-2' ),
        array( 'iconsmind-Bow-3' => 'Bow-3' ),
        array( 'iconsmind-Bow-4' => 'Bow-4' ),
        array( 'iconsmind-Bow-5' => 'Bow-5' ),
        array( 'iconsmind-Bow-6' => 'Bow-6' ),
        array( 'iconsmind-Bow' => 'Bow' ),
        array( 'iconsmind-Bowling-2' => 'Bowling-2' ),
        array( 'iconsmind-Bowling' => 'Bowling' ),
        array( 'iconsmind-Box2' => 'Box2' ),
        array( 'iconsmind-Box-Close' => 'Box-Close' ),
        array( 'iconsmind-Box-Full' => 'Box-Full' ),
        array( 'iconsmind-Box-Open' => 'Box-Open' ),
        array( 'iconsmind-Box-withFolders' => 'Box-withFolders' ),
        array( 'iconsmind-Box' => 'Box' ),
        array( 'iconsmind-Boy' => 'Boy' ),
        array( 'iconsmind-Bra' => 'Bra' ),
        array( 'iconsmind-Brain-2' => 'Brain-2' ),
        array( 'iconsmind-Brain-3' => 'Brain-3' ),
        array( 'iconsmind-Brain' => 'Brain' ),
        array( 'iconsmind-Brazil' => 'Brazil' ),
        array( 'iconsmind-Bread-2' => 'Bread-2' ),
        array( 'iconsmind-Bread' => 'Bread' ),
        array( 'iconsmind-Bridge' => 'Bridge' ),
        array( 'iconsmind-Brightkite' => 'Brightkite' ),
        array( 'iconsmind-Broke-Link2' => 'Broke-Link2' ),
        array( 'iconsmind-Broken-Link' => 'Broken-Link' ),
        array( 'iconsmind-Broom' => 'Broom' ),
        array( 'iconsmind-Brush' => 'Brush' ),
        array( 'iconsmind-Bucket' => 'Bucket' ),
        array( 'iconsmind-Bug' => 'Bug' ),
        array( 'iconsmind-Building' => 'Building' ),
        array( 'iconsmind-Bulleted-List' => 'Bulleted-List' ),
        array( 'iconsmind-Bus-2' => 'Bus-2' ),
        array( 'iconsmind-Bus' => 'Bus' ),
        array( 'iconsmind-Business-Man' => 'Business-Man' ),
        array( 'iconsmind-Business-ManWoman' => 'Business-ManWoman' ),
        array( 'iconsmind-Business-Mens' => 'Business-Mens' ),
        array( 'iconsmind-Business-Woman' => 'Business-Woman' ),
        array( 'iconsmind-Butterfly' => 'Butterfly' ),
        array( 'iconsmind-Button' => 'Button' ),
        array( 'iconsmind-Cable-Car' => 'Cable-Car' ),
        array( 'iconsmind-Cake' => 'Cake' ),
        array( 'iconsmind-Calculator-2' => 'Calculator-2' ),
        array( 'iconsmind-Calculator-3' => 'Calculator-3' ),
        array( 'iconsmind-Calculator' => 'Calculator' ),
        array( 'iconsmind-Calendar-2' => 'Calendar-2' ),
        array( 'iconsmind-Calendar-3' => 'Calendar-3' ),
        array( 'iconsmind-Calendar-4' => 'Calendar-4' ),
        array( 'iconsmind-Calendar-Clock' => 'Calendar-Clock' ),
        array( 'iconsmind-Calendar' => 'Calendar' ),
        array( 'iconsmind-Camel' => 'Camel' ),
        array( 'iconsmind-Camera-2' => 'Camera-2' ),
        array( 'iconsmind-Camera-3' => 'Camera-3' ),
        array( 'iconsmind-Camera-4' => 'Camera-4' ),
        array( 'iconsmind-Camera-5' => 'Camera-5' ),
        array( 'iconsmind-Camera-Back' => 'Camera-Back' ),
        array( 'iconsmind-Camera' => 'Camera' ),
        array( 'iconsmind-Can-2' => 'Can-2' ),
        array( 'iconsmind-Can' => 'Can' ),
        array( 'iconsmind-Canada' => 'Canada' ),
        array( 'iconsmind-Cancer-2' => 'Cancer-2' ),
        array( 'iconsmind-Cancer-3' => 'Cancer-3' ),
        array( 'iconsmind-Cancer' => 'Cancer' ),
        array( 'iconsmind-Candle' => 'Candle' ),
        array( 'iconsmind-Candy-Cane' => 'Candy-Cane' ),
        array( 'iconsmind-Candy' => 'Candy' ),
        array( 'iconsmind-Cannon' => 'Cannon' ),
        array( 'iconsmind-Cap-2' => 'Cap-2' ),
        array( 'iconsmind-Cap-3' => 'Cap-3' ),
        array( 'iconsmind-Cap-Smiley' => 'Cap-Smiley' ),
        array( 'iconsmind-Cap' => 'Cap' ),
        array( 'iconsmind-Capricorn-2' => 'Capricorn-2' ),
        array( 'iconsmind-Capricorn' => 'Capricorn' ),
        array( 'iconsmind-Car-2' => 'Car-2' ),
        array( 'iconsmind-Car-3' => 'Car-3' ),
        array( 'iconsmind-Car-Coins' => 'Car-Coins' ),
        array( 'iconsmind-Car-Items' => 'Car-Items' ),
        array( 'iconsmind-Car-Wheel' => 'Car-Wheel' ),
        array( 'iconsmind-Car' => 'Car' ),
        array( 'iconsmind-Cardigan' => 'Cardigan' ),
        array( 'iconsmind-Cardiovascular' => 'Cardiovascular' ),
        array( 'iconsmind-Cart-Quantity' => 'Cart-Quantity' ),
        array( 'iconsmind-Casette-Tape' => 'Casette-Tape' ),
        array( 'iconsmind-Cash-Register' => 'Cash-Register' ),
        array( 'iconsmind-Cash-register2' => 'Cash-register2' ),
        array( 'iconsmind-Castle' => 'Castle' ),
        array( 'iconsmind-Cat' => 'Cat' ),
        array( 'iconsmind-Cathedral' => 'Cathedral' ),
        array( 'iconsmind-Cauldron' => 'Cauldron' ),
        array( 'iconsmind-CD-2' => 'CD-2' ),
        array( 'iconsmind-CD-Cover' => 'CD-Cover' ),
        array( 'iconsmind-CD' => 'CD' ),
        array( 'iconsmind-Cello' => 'Cello' ),
        array( 'iconsmind-Celsius' => 'Celsius' ),
        array( 'iconsmind-Chacked-Flag' => 'Chacked-Flag' ),
        array( 'iconsmind-Chair' => 'Chair' ),
        array( 'iconsmind-Charger' => 'Charger' ),
        array( 'iconsmind-Check-2' => 'Check-2' ),
        array( 'iconsmind-Check' => 'Check' ),
        array( 'iconsmind-Checked-User' => 'Checked-User' ),
        array( 'iconsmind-Checkmate' => 'Checkmate' ),
        array( 'iconsmind-Checkout-Bag' => 'Checkout-Bag' ),
        array( 'iconsmind-Checkout-Basket' => 'Checkout-Basket' ),
        array( 'iconsmind-Checkout' => 'Checkout' ),
        array( 'iconsmind-Cheese' => 'Cheese' ),
        array( 'iconsmind-Cheetah' => 'Cheetah' ),
        array( 'iconsmind-Chef-Hat' => 'Chef-Hat' ),
        array( 'iconsmind-Chef-Hat2' => 'Chef-Hat2' ),
        array( 'iconsmind-Chef' => 'Chef' ),
        array( 'iconsmind-Chemical-2' => 'Chemical-2' ),
        array( 'iconsmind-Chemical-3' => 'Chemical-3' ),
        array( 'iconsmind-Chemical-4' => 'Chemical-4' ),
        array( 'iconsmind-Chemical-5' => 'Chemical-5' ),
        array( 'iconsmind-Chemical' => 'Chemical' ),
        array( 'iconsmind-Chess-Board' => 'Chess-Board' ),
        array( 'iconsmind-Chess' => 'Chess' ),
        array( 'iconsmind-Chicken' => 'Chicken' ),
        array( 'iconsmind-Chile' => 'Chile' ),
        array( 'iconsmind-Chimney' => 'Chimney' ),
        array( 'iconsmind-China' => 'China' ),
        array( 'iconsmind-Chinese-Temple' => 'Chinese-Temple' ),
        array( 'iconsmind-Chip' => 'Chip' ),
        array( 'iconsmind-Chopsticks-2' => 'Chopsticks-2' ),
        array( 'iconsmind-Chopsticks' => 'Chopsticks' ),
        array( 'iconsmind-Christmas-Ball' => 'Christmas-Ball' ),
        array( 'iconsmind-Christmas-Bell' => 'Christmas-Bell' ),
        array( 'iconsmind-Christmas-Candle' => 'Christmas-Candle' ),
        array( 'iconsmind-Christmas-Hat' => 'Christmas-Hat' ),
        array( 'iconsmind-Christmas-Sleigh' => 'Christmas-Sleigh' ),
        array( 'iconsmind-Christmas-Snowman' => 'Christmas-Snowman' ),
        array( 'iconsmind-Christmas-Sock' => 'Christmas-Sock' ),
        array( 'iconsmind-Christmas-Tree' => 'Christmas-Tree' ),
        array( 'iconsmind-Christmas' => 'Christmas' ),
        array( 'iconsmind-Chrome' => 'Chrome' ),
        array( 'iconsmind-Chrysler-Building' => 'Chrysler-Building' ),
        array( 'iconsmind-Cinema' => 'Cinema' ),
        array( 'iconsmind-Circular-Point' => 'Circular-Point' ),
        array( 'iconsmind-City-Hall' => 'City-Hall' ),
        array( 'iconsmind-Clamp' => 'Clamp' ),
        array( 'iconsmind-Clapperboard-Close' => 'Clapperboard-Close' ),
        array( 'iconsmind-Clapperboard-Open' => 'Clapperboard-Open' ),
        array( 'iconsmind-Claps' => 'Claps' ),
        array( 'iconsmind-Clef' => 'Clef' ),
        array( 'iconsmind-Clinic' => 'Clinic' ),
        array( 'iconsmind-Clock-2' => 'Clock-2' ),
        array( 'iconsmind-Clock-3' => 'Clock-3' ),
        array( 'iconsmind-Clock-4' => 'Clock-4' ),
        array( 'iconsmind-Clock-Back' => 'Clock-Back' ),
        array( 'iconsmind-Clock-Forward' => 'Clock-Forward' ),
        array( 'iconsmind-Clock' => 'Clock' ),
        array( 'iconsmind-Close-Window' => 'Close-Window' ),
        array( 'iconsmind-Close' => 'Close' ),
        array( 'iconsmind-Clothing-Store' => 'Clothing-Store' ),
        array( 'iconsmind-Cloud--' => 'Cloud--' ),
        array( 'iconsmind-Cloud-' => 'Cloud-' ),
        array( 'iconsmind-Cloud-Camera' => 'Cloud-Camera' ),
        array( 'iconsmind-Cloud-Computer' => 'Cloud-Computer' ),
        array( 'iconsmind-Cloud-Email' => 'Cloud-Email' ),
        array( 'iconsmind-Cloud-Hail' => 'Cloud-Hail' ),
        array( 'iconsmind-Cloud-Laptop' => 'Cloud-Laptop' ),
        array( 'iconsmind-Cloud-Lock' => 'Cloud-Lock' ),
        array( 'iconsmind-Cloud-Moon' => 'Cloud-Moon' ),
        array( 'iconsmind-Cloud-Music' => 'Cloud-Music' ),
        array( 'iconsmind-Cloud-Picture' => 'Cloud-Picture' ),
        array( 'iconsmind-Cloud-Rain' => 'Cloud-Rain' ),
        array( 'iconsmind-Cloud-Remove' => 'Cloud-Remove' ),
        array( 'iconsmind-Cloud-Secure' => 'Cloud-Secure' ),
        array( 'iconsmind-Cloud-Settings' => 'Cloud-Settings' ),
        array( 'iconsmind-Cloud-Smartphone' => 'Cloud-Smartphone' ),
        array( 'iconsmind-Cloud-Snow' => 'Cloud-Snow' ),
        array( 'iconsmind-Cloud-Sun' => 'Cloud-Sun' ),
        array( 'iconsmind-Cloud-Tablet' => 'Cloud-Tablet' ),
        array( 'iconsmind-Cloud-Video' => 'Cloud-Video' ),
        array( 'iconsmind-Cloud-Weather' => 'Cloud-Weather' ),
        array( 'iconsmind-Cloud' => 'Cloud' ),
        array( 'iconsmind-Clouds-Weather' => 'Clouds-Weather' ),
        array( 'iconsmind-Clouds' => 'Clouds' ),
        array( 'iconsmind-Clown' => 'Clown' ),
        array( 'iconsmind-CMYK' => 'CMYK' ),
        array( 'iconsmind-Coat' => 'Coat' ),
        array( 'iconsmind-Cocktail' => 'Cocktail' ),
        array( 'iconsmind-Coconut' => 'Coconut' ),
        array( 'iconsmind-Code-Window' => 'Code-Window' ),
        array( 'iconsmind-Coding' => 'Coding' ),
        array( 'iconsmind-Coffee-2' => 'Coffee-2' ),
        array( 'iconsmind-Coffee-Bean' => 'Coffee-Bean' ),
        array( 'iconsmind-Coffee-Machine' => 'Coffee-Machine' ),
        array( 'iconsmind-Coffee-toGo' => 'Coffee-toGo' ),
        array( 'iconsmind-Coffee' => 'Coffee' ),
        array( 'iconsmind-Coffin' => 'Coffin' ),
        array( 'iconsmind-Coin' => 'Coin' ),
        array( 'iconsmind-Coins-2' => 'Coins-2' ),
        array( 'iconsmind-Coins-3' => 'Coins-3' ),
        array( 'iconsmind-Coins' => 'Coins' ),
        array( 'iconsmind-Colombia' => 'Colombia' ),
        array( 'iconsmind-Colosseum' => 'Colosseum' ),
        array( 'iconsmind-Column-2' => 'Column-2' ),
        array( 'iconsmind-Column-3' => 'Column-3' ),
        array( 'iconsmind-Column' => 'Column' ),
        array( 'iconsmind-Comb-2' => 'Comb-2' ),
        array( 'iconsmind-Comb' => 'Comb' ),
        array( 'iconsmind-Communication-Tower' => 'Communication-Tower' ),
        array( 'iconsmind-Communication-Tower2' => 'Communication-Tower2' ),
        array( 'iconsmind-Compass-2' => 'Compass-2' ),
        array( 'iconsmind-Compass-3' => 'Compass-3' ),
        array( 'iconsmind-Compass-4' => 'Compass-4' ),
        array( 'iconsmind-Compass-Rose' => 'Compass-Rose' ),
        array( 'iconsmind-Compass' => 'Compass' ),
        array( 'iconsmind-Computer-2' => 'Computer-2' ),
        array( 'iconsmind-Computer-3' => 'Computer-3' ),
        array( 'iconsmind-Computer-Secure' => 'Computer-Secure' ),
        array( 'iconsmind-Computer' => 'Computer' ),
        array( 'iconsmind-Conference' => 'Conference' ),
        array( 'iconsmind-Confused' => 'Confused' ),
        array( 'iconsmind-Conservation' => 'Conservation' ),
        array( 'iconsmind-Consulting' => 'Consulting' ),
        array( 'iconsmind-Contrast' => 'Contrast' ),
        array( 'iconsmind-Control-2' => 'Control-2' ),
        array( 'iconsmind-Control' => 'Control' ),
        array( 'iconsmind-Cookie-Man' => 'Cookie-Man' ),
        array( 'iconsmind-Cookies' => 'Cookies' ),
        array( 'iconsmind-Cool-Guy' => 'Cool-Guy' ),
        array( 'iconsmind-Cool' => 'Cool' ),
        array( 'iconsmind-Copyright' => 'Copyright' ),
        array( 'iconsmind-Costume' => 'Costume' ),
        array( 'iconsmind-Couple-Sign' => 'Couple-Sign' ),
        array( 'iconsmind-Cow' => 'Cow' ),
        array( 'iconsmind-CPU' => 'CPU' ),
        array( 'iconsmind-Crane' => 'Crane' ),
        array( 'iconsmind-Cranium' => 'Cranium' ),
        array( 'iconsmind-Credit-Card' => 'Credit-Card' ),
        array( 'iconsmind-Credit-Card2' => 'Credit-Card2' ),
        array( 'iconsmind-Credit-Card3' => 'Credit-Card3' ),
        array( 'iconsmind-Cricket' => 'Cricket' ),
        array( 'iconsmind-Criminal' => 'Criminal' ),
        array( 'iconsmind-Croissant' => 'Croissant' ),
        array( 'iconsmind-Crop-2' => 'Crop-2' ),
        array( 'iconsmind-Crop-3' => 'Crop-3' ),
        array( 'iconsmind-Crown-2' => 'Crown-2' ),
        array( 'iconsmind-Crown' => 'Crown' ),
        array( 'iconsmind-Crying' => 'Crying' ),
        array( 'iconsmind-Cube-Molecule' => 'Cube-Molecule' ),
        array( 'iconsmind-Cube-Molecule2' => 'Cube-Molecule2' ),
        array( 'iconsmind-Cupcake' => 'Cupcake' ),
        array( 'iconsmind-Cursor-Click' => 'Cursor-Click' ),
        array( 'iconsmind-Cursor-Click2' => 'Cursor-Click2' ),
        array( 'iconsmind-Cursor-Move' => 'Cursor-Move' ),
        array( 'iconsmind-Cursor-Move2' => 'Cursor-Move2' ),
        array( 'iconsmind-Cursor-Select' => 'Cursor-Select' ),
        array( 'iconsmind-Cursor' => 'Cursor' ),
        array( 'iconsmind-D-Eyeglasses' => 'D-Eyeglasses' ),
        array( 'iconsmind-D-Eyeglasses2' => 'D-Eyeglasses2' ),
        array( 'iconsmind-Dam' => 'Dam' ),
        array( 'iconsmind-Danemark' => 'Danemark' ),
        array( 'iconsmind-Danger-2' => 'Danger-2' ),
        array( 'iconsmind-Danger' => 'Danger' ),
        array( 'iconsmind-Dashboard' => 'Dashboard' ),
        array( 'iconsmind-Data-Backup' => 'Data-Backup' ),
        array( 'iconsmind-Data-Block' => 'Data-Block' ),
        array( 'iconsmind-Data-Center' => 'Data-Center' ),
        array( 'iconsmind-Data-Clock' => 'Data-Clock' ),
        array( 'iconsmind-Data-Cloud' => 'Data-Cloud' ),
        array( 'iconsmind-Data-Compress' => 'Data-Compress' ),
        array( 'iconsmind-Data-Copy' => 'Data-Copy' ),
        array( 'iconsmind-Data-Download' => 'Data-Download' ),
        array( 'iconsmind-Data-Financial' => 'Data-Financial' ),
        array( 'iconsmind-Data-Key' => 'Data-Key' ),
        array( 'iconsmind-Data-Lock' => 'Data-Lock' ),
        array( 'iconsmind-Data-Network' => 'Data-Network' ),
        array( 'iconsmind-Data-Password' => 'Data-Password' ),
        array( 'iconsmind-Data-Power' => 'Data-Power' ),
        array( 'iconsmind-Data-Refresh' => 'Data-Refresh' ),
        array( 'iconsmind-Data-Save' => 'Data-Save' ),
        array( 'iconsmind-Data-Search' => 'Data-Search' ),
        array( 'iconsmind-Data-Security' => 'Data-Security' ),
        array( 'iconsmind-Data-Settings' => 'Data-Settings' ),
        array( 'iconsmind-Data-Sharing' => 'Data-Sharing' ),
        array( 'iconsmind-Data-Shield' => 'Data-Shield' ),
        array( 'iconsmind-Data-Signal' => 'Data-Signal' ),
        array( 'iconsmind-Data-Storage' => 'Data-Storage' ),
        array( 'iconsmind-Data-Stream' => 'Data-Stream' ),
        array( 'iconsmind-Data-Transfer' => 'Data-Transfer' ),
        array( 'iconsmind-Data-Unlock' => 'Data-Unlock' ),
        array( 'iconsmind-Data-Upload' => 'Data-Upload' ),
        array( 'iconsmind-Data-Yes' => 'Data-Yes' ),
        array( 'iconsmind-Data' => 'Data' ),
        array( 'iconsmind-David-Star' => 'David-Star' ),
        array( 'iconsmind-Daylight' => 'Daylight' ),
        array( 'iconsmind-Death' => 'Death' ),
        array( 'iconsmind-Debian' => 'Debian' ),
        array( 'iconsmind-Dec' => 'Dec' ),
        array( 'iconsmind-Decrase-Inedit' => 'Decrase-Inedit' ),
        array( 'iconsmind-Deer-2' => 'Deer-2' ),
        array( 'iconsmind-Deer' => 'Deer' ),
        array( 'iconsmind-Delete-File' => 'Delete-File' ),
        array( 'iconsmind-Delete-Window' => 'Delete-Window' ),
        array( 'iconsmind-Delicious' => 'Delicious' ),
        array( 'iconsmind-Depression' => 'Depression' ),
        array( 'iconsmind-Deviantart' => 'Deviantart' ),
        array( 'iconsmind-Device-SyncwithCloud' => 'Device-SyncwithCloud' ),
        array( 'iconsmind-Diamond' => 'Diamond' ),
        array( 'iconsmind-Dice-2' => 'Dice-2' ),
        array( 'iconsmind-Dice' => 'Dice' ),
        array( 'iconsmind-Digg' => 'Digg' ),
        array( 'iconsmind-Digital-Drawing' => 'Digital-Drawing' ),
        array( 'iconsmind-Diigo' => 'Diigo' ),
        array( 'iconsmind-Dinosaur' => 'Dinosaur' ),
        array( 'iconsmind-Diploma-2' => 'Diploma-2' ),
        array( 'iconsmind-Diploma' => 'Diploma' ),
        array( 'iconsmind-Direction-East' => 'Direction-East' ),
        array( 'iconsmind-Direction-North' => 'Direction-North' ),
        array( 'iconsmind-Direction-South' => 'Direction-South' ),
        array( 'iconsmind-Direction-West' => 'Direction-West' ),
        array( 'iconsmind-Director' => 'Director' ),
        array( 'iconsmind-Disk' => 'Disk' ),
        array( 'iconsmind-Dj' => 'Dj' ),
        array( 'iconsmind-DNA-2' => 'DNA-2' ),
        array( 'iconsmind-DNA-Helix' => 'DNA-Helix' ),
        array( 'iconsmind-DNA' => 'DNA' ),
        array( 'iconsmind-Doctor' => 'Doctor' ),
        array( 'iconsmind-Dog' => 'Dog' ),
        array( 'iconsmind-Dollar-Sign' => 'Dollar-Sign' ),
        array( 'iconsmind-Dollar-Sign2' => 'Dollar-Sign2' ),
        array( 'iconsmind-Dollar' => 'Dollar' ),
        array( 'iconsmind-Dolphin' => 'Dolphin' ),
        array( 'iconsmind-Domino' => 'Domino' ),
        array( 'iconsmind-Door-Hanger' => 'Door-Hanger' ),
        array( 'iconsmind-Door' => 'Door' ),
        array( 'iconsmind-Doplr' => 'Doplr' ),
        array( 'iconsmind-Double-Circle' => 'Double-Circle' ),
        array( 'iconsmind-Double-Tap' => 'Double-Tap' ),
        array( 'iconsmind-Doughnut' => 'Doughnut' ),
        array( 'iconsmind-Dove' => 'Dove' ),
        array( 'iconsmind-Down-2' => 'Down-2' ),
        array( 'iconsmind-Down-3' => 'Down-3' ),
        array( 'iconsmind-Down-4' => 'Down-4' ),
        array( 'iconsmind-Down' => 'Down' ),
        array( 'iconsmind-Download-2' => 'Download-2' ),
        array( 'iconsmind-Download-fromCloud' => 'Download-fromCloud' ),
        array( 'iconsmind-Download-Window' => 'Download-Window' ),
        array( 'iconsmind-Download' => 'Download' ),
        array( 'iconsmind-Downward' => 'Downward' ),
        array( 'iconsmind-Drag-Down' => 'Drag-Down' ),
        array( 'iconsmind-Drag-Left' => 'Drag-Left' ),
        array( 'iconsmind-Drag-Right' => 'Drag-Right' ),
        array( 'iconsmind-Drag-Up' => 'Drag-Up' ),
        array( 'iconsmind-Drag' => 'Drag' ),
        array( 'iconsmind-Dress' => 'Dress' ),
        array( 'iconsmind-Drill-2' => 'Drill-2' ),
        array( 'iconsmind-Drill' => 'Drill' ),
        array( 'iconsmind-Drop' => 'Drop' ),
        array( 'iconsmind-Dropbox' => 'Dropbox' ),
        array( 'iconsmind-Drum' => 'Drum' ),
        array( 'iconsmind-Dry' => 'Dry' ),
        array( 'iconsmind-Duck' => 'Duck' ),
        array( 'iconsmind-Dumbbell' => 'Dumbbell' ),
        array( 'iconsmind-Duplicate-Layer' => 'Duplicate-Layer' ),
        array( 'iconsmind-Duplicate-Window' => 'Duplicate-Window' ),
        array( 'iconsmind-DVD' => 'DVD' ),
        array( 'iconsmind-Eagle' => 'Eagle' ),
        array( 'iconsmind-Ear' => 'Ear' ),
        array( 'iconsmind-Earphones-2' => 'Earphones-2' ),
        array( 'iconsmind-Earphones' => 'Earphones' ),
        array( 'iconsmind-Eci-Icon' => 'Eci-Icon' ),
        array( 'iconsmind-Edit-Map' => 'Edit-Map' ),
        array( 'iconsmind-Edit' => 'Edit' ),
        array( 'iconsmind-Eggs' => 'Eggs' ),
        array( 'iconsmind-Egypt' => 'Egypt' ),
        array( 'iconsmind-Eifel-Tower' => 'Eifel-Tower' ),
        array( 'iconsmind-eject-2' => 'eject-2' ),
        array( 'iconsmind-Eject' => 'Eject' ),
        array( 'iconsmind-El-Castillo' => 'El-Castillo' ),
        array( 'iconsmind-Elbow' => 'Elbow' ),
        array( 'iconsmind-Electric-Guitar' => 'Electric-Guitar' ),
        array( 'iconsmind-Electricity' => 'Electricity' ),
        array( 'iconsmind-Elephant' => 'Elephant' ),
        array( 'iconsmind-Email' => 'Email' ),
        array( 'iconsmind-Embassy' => 'Embassy' ),
        array( 'iconsmind-Empire-StateBuilding' => 'Empire-StateBuilding' ),
        array( 'iconsmind-Empty-Box' => 'Empty-Box' ),
        array( 'iconsmind-End2' => 'End2' ),
        array( 'iconsmind-End-2' => 'End-2' ),
        array( 'iconsmind-End' => 'End' ),
        array( 'iconsmind-Endways' => 'Endways' ),
        array( 'iconsmind-Engineering' => 'Engineering' ),
        array( 'iconsmind-Envelope-2' => 'Envelope-2' ),
        array( 'iconsmind-Envelope' => 'Envelope' ),
        array( 'iconsmind-Environmental-2' => 'Environmental-2' ),
        array( 'iconsmind-Environmental-3' => 'Environmental-3' ),
        array( 'iconsmind-Environmental' => 'Environmental' ),
        array( 'iconsmind-Equalizer' => 'Equalizer' ),
        array( 'iconsmind-Eraser-2' => 'Eraser-2' ),
        array( 'iconsmind-Eraser-3' => 'Eraser-3' ),
        array( 'iconsmind-Eraser' => 'Eraser' ),
        array( 'iconsmind-Error-404Window' => 'Error-404Window' ),
        array( 'iconsmind-Euro-Sign' => 'Euro-Sign' ),
        array( 'iconsmind-Euro-Sign2' => 'Euro-Sign2' ),
        array( 'iconsmind-Euro' => 'Euro' ),
        array( 'iconsmind-Evernote' => 'Evernote' ),
        array( 'iconsmind-Evil' => 'Evil' ),
        array( 'iconsmind-Explode' => 'Explode' ),
        array( 'iconsmind-Eye-2' => 'Eye-2' ),
        array( 'iconsmind-Eye-Blind' => 'Eye-Blind' ),
        array( 'iconsmind-Eye-Invisible' => 'Eye-Invisible' ),
        array( 'iconsmind-Eye-Scan' => 'Eye-Scan' ),
        array( 'iconsmind-Eye-Visible' => 'Eye-Visible' ),
        array( 'iconsmind-Eye' => 'Eye' ),
        array( 'iconsmind-Eyebrow-2' => 'Eyebrow-2' ),
        array( 'iconsmind-Eyebrow-3' => 'Eyebrow-3' ),
        array( 'iconsmind-Eyebrow' => 'Eyebrow' ),
        array( 'iconsmind-Eyeglasses-Smiley' => 'Eyeglasses-Smiley' ),
        array( 'iconsmind-Eyeglasses-Smiley2' => 'Eyeglasses-Smiley2' ),
        array( 'iconsmind-Face-Style' => 'Face-Style' ),
        array( 'iconsmind-Face-Style2' => 'Face-Style2' ),
        array( 'iconsmind-Face-Style3' => 'Face-Style3' ),
        array( 'iconsmind-Face-Style4' => 'Face-Style4' ),
        array( 'iconsmind-Face-Style5' => 'Face-Style5' ),
        array( 'iconsmind-Face-Style6' => 'Face-Style6' ),
        array( 'iconsmind-Facebook-2' => 'Facebook-2' ),
        array( 'iconsmind-Facebook' => 'Facebook' ),
        array( 'iconsmind-Factory-2' => 'Factory-2' ),
        array( 'iconsmind-Factory' => 'Factory' ),
        array( 'iconsmind-Fahrenheit' => 'Fahrenheit' ),
        array( 'iconsmind-Family-Sign' => 'Family-Sign' ),
        array( 'iconsmind-Fan' => 'Fan' ),
        array( 'iconsmind-Farmer' => 'Farmer' ),
        array( 'iconsmind-Fashion' => 'Fashion' ),
        array( 'iconsmind-Favorite-Window' => 'Favorite-Window' ),
        array( 'iconsmind-Fax' => 'Fax' ),
        array( 'iconsmind-Feather' => 'Feather' ),
        array( 'iconsmind-Feedburner' => 'Feedburner' ),
        array( 'iconsmind-Female-2' => 'Female-2' ),
        array( 'iconsmind-Female-Sign' => 'Female-Sign' ),
        array( 'iconsmind-Female' => 'Female' ),
        array( 'iconsmind-File-Block' => 'File-Block' ),
        array( 'iconsmind-File-Bookmark' => 'File-Bookmark' ),
        array( 'iconsmind-File-Chart' => 'File-Chart' ),
        array( 'iconsmind-File-Clipboard' => 'File-Clipboard' ),
        array( 'iconsmind-File-ClipboardFileText' => 'File-ClipboardFileText' ),
        array( 'iconsmind-File-ClipboardTextImage' => 'File-ClipboardTextImage' ),
        array( 'iconsmind-File-Cloud' => 'File-Cloud' ),
        array( 'iconsmind-File-Copy' => 'File-Copy' ),
        array( 'iconsmind-File-Copy2' => 'File-Copy2' ),
        array( 'iconsmind-File-CSV' => 'File-CSV' ),
        array( 'iconsmind-File-Download' => 'File-Download' ),
        array( 'iconsmind-File-Edit' => 'File-Edit' ),
        array( 'iconsmind-File-Excel' => 'File-Excel' ),
        array( 'iconsmind-File-Favorite' => 'File-Favorite' ),
        array( 'iconsmind-File-Fire' => 'File-Fire' ),
        array( 'iconsmind-File-Graph' => 'File-Graph' ),
        array( 'iconsmind-File-Hide' => 'File-Hide' ),
        array( 'iconsmind-File-Horizontal' => 'File-Horizontal' ),
        array( 'iconsmind-File-HorizontalText' => 'File-HorizontalText' ),
        array( 'iconsmind-File-HTML' => 'File-HTML' ),
        array( 'iconsmind-File-JPG' => 'File-JPG' ),
        array( 'iconsmind-File-Link' => 'File-Link' ),
        array( 'iconsmind-File-Loading' => 'File-Loading' ),
        array( 'iconsmind-File-Lock' => 'File-Lock' ),
        array( 'iconsmind-File-Love' => 'File-Love' ),
        array( 'iconsmind-File-Music' => 'File-Music' ),
        array( 'iconsmind-File-Network' => 'File-Network' ),
        array( 'iconsmind-File-Pictures' => 'File-Pictures' ),
        array( 'iconsmind-File-Pie' => 'File-Pie' ),
        array( 'iconsmind-File-Presentation' => 'File-Presentation' ),
        array( 'iconsmind-File-Refresh' => 'File-Refresh' ),
        array( 'iconsmind-File-Search' => 'File-Search' ),
        array( 'iconsmind-File-Settings' => 'File-Settings' ),
        array( 'iconsmind-File-Share' => 'File-Share' ),
        array( 'iconsmind-File-TextImage' => 'File-TextImage' ),
        array( 'iconsmind-File-Trash' => 'File-Trash' ),
        array( 'iconsmind-File-TXT' => 'File-TXT' ),
        array( 'iconsmind-File-Upload' => 'File-Upload' ),
        array( 'iconsmind-File-Video' => 'File-Video' ),
        array( 'iconsmind-File-Word' => 'File-Word' ),
        array( 'iconsmind-File-Zip' => 'File-Zip' ),
        array( 'iconsmind-File' => 'File' ),
        array( 'iconsmind-Files' => 'Files' ),
        array( 'iconsmind-Film-Board' => 'Film-Board' ),
        array( 'iconsmind-Film-Cartridge' => 'Film-Cartridge' ),
        array( 'iconsmind-Film-Strip' => 'Film-Strip' ),
        array( 'iconsmind-Film-Video' => 'Film-Video' ),
        array( 'iconsmind-Film' => 'Film' ),
        array( 'iconsmind-Filter-2' => 'Filter-2' ),
        array( 'iconsmind-Filter' => 'Filter' ),
        array( 'iconsmind-Financial' => 'Financial' ),
        array( 'iconsmind-Find-User' => 'Find-User' ),
        array( 'iconsmind-Finger-DragFourSides' => 'Finger-DragFourSides' ),
        array( 'iconsmind-Finger-DragTwoSides' => 'Finger-DragTwoSides' ),
        array( 'iconsmind-Finger-Print' => 'Finger-Print' ),
        array( 'iconsmind-Finger' => 'Finger' ),
        array( 'iconsmind-Fingerprint-2' => 'Fingerprint-2' ),
        array( 'iconsmind-Fingerprint' => 'Fingerprint' ),
        array( 'iconsmind-Fire-Flame' => 'Fire-Flame' ),
        array( 'iconsmind-Fire-Flame2' => 'Fire-Flame2' ),
        array( 'iconsmind-Fire-Hydrant' => 'Fire-Hydrant' ),
        array( 'iconsmind-Fire-Staion' => 'Fire-Staion' ),
        array( 'iconsmind-Firefox' => 'Firefox' ),
        array( 'iconsmind-Firewall' => 'Firewall' ),
        array( 'iconsmind-First-Aid' => 'First-Aid' ),
        array( 'iconsmind-First' => 'First' ),
        array( 'iconsmind-Fish-Food' => 'Fish-Food' ),
        array( 'iconsmind-Fish' => 'Fish' ),
        array( 'iconsmind-Fit-To' => 'Fit-To' ),
        array( 'iconsmind-Fit-To2' => 'Fit-To2' ),
        array( 'iconsmind-Five-Fingers' => 'Five-Fingers' ),
        array( 'iconsmind-Five-FingersDrag' => 'Five-FingersDrag' ),
        array( 'iconsmind-Five-FingersDrag2' => 'Five-FingersDrag2' ),
        array( 'iconsmind-Five-FingersTouch' => 'Five-FingersTouch' ),
        array( 'iconsmind-Flag-2' => 'Flag-2' ),
        array( 'iconsmind-Flag-3' => 'Flag-3' ),
        array( 'iconsmind-Flag-4' => 'Flag-4' ),
        array( 'iconsmind-Flag-5' => 'Flag-5' ),
        array( 'iconsmind-Flag-6' => 'Flag-6' ),
        array( 'iconsmind-Flag' => 'Flag' ),
        array( 'iconsmind-Flamingo' => 'Flamingo' ),
        array( 'iconsmind-Flash-2' => 'Flash-2' ),
        array( 'iconsmind-Flash-Video' => 'Flash-Video' ),
        array( 'iconsmind-Flash' => 'Flash' ),
        array( 'iconsmind-Flashlight' => 'Flashlight' ),
        array( 'iconsmind-Flask-2' => 'Flask-2' ),
        array( 'iconsmind-Flask' => 'Flask' ),
        array( 'iconsmind-Flick' => 'Flick' ),
        array( 'iconsmind-Flickr' => 'Flickr' ),
        array( 'iconsmind-Flowerpot' => 'Flowerpot' ),
        array( 'iconsmind-Fluorescent' => 'Fluorescent' ),
        array( 'iconsmind-Fog-Day' => 'Fog-Day' ),
        array( 'iconsmind-Fog-Night' => 'Fog-Night' ),
        array( 'iconsmind-Folder-Add' => 'Folder-Add' ),
        array( 'iconsmind-Folder-Archive' => 'Folder-Archive' ),
        array( 'iconsmind-Folder-Binder' => 'Folder-Binder' ),
        array( 'iconsmind-Folder-Binder2' => 'Folder-Binder2' ),
        array( 'iconsmind-Folder-Block' => 'Folder-Block' ),
        array( 'iconsmind-Folder-Bookmark' => 'Folder-Bookmark' ),
        array( 'iconsmind-Folder-Close' => 'Folder-Close' ),
        array( 'iconsmind-Folder-Cloud' => 'Folder-Cloud' ),
        array( 'iconsmind-Folder-Delete' => 'Folder-Delete' ),
        array( 'iconsmind-Folder-Download' => 'Folder-Download' ),
        array( 'iconsmind-Folder-Edit' => 'Folder-Edit' ),
        array( 'iconsmind-Folder-Favorite' => 'Folder-Favorite' ),
        array( 'iconsmind-Folder-Fire' => 'Folder-Fire' ),
        array( 'iconsmind-Folder-Hide' => 'Folder-Hide' ),
        array( 'iconsmind-Folder-Link' => 'Folder-Link' ),
        array( 'iconsmind-Folder-Loading' => 'Folder-Loading' ),
        array( 'iconsmind-Folder-Lock' => 'Folder-Lock' ),
        array( 'iconsmind-Folder-Love' => 'Folder-Love' ),
        array( 'iconsmind-Folder-Music' => 'Folder-Music' ),
        array( 'iconsmind-Folder-Network' => 'Folder-Network' ),
        array( 'iconsmind-Folder-Open' => 'Folder-Open' ),
        array( 'iconsmind-Folder-Open2' => 'Folder-Open2' ),
        array( 'iconsmind-Folder-Organizing' => 'Folder-Organizing' ),
        array( 'iconsmind-Folder-Pictures' => 'Folder-Pictures' ),
        array( 'iconsmind-Folder-Refresh' => 'Folder-Refresh' ),
        array( 'iconsmind-Folder-Remove-' => 'Folder-Remove-' ),
        array( 'iconsmind-Folder-Search' => 'Folder-Search' ),
        array( 'iconsmind-Folder-Settings' => 'Folder-Settings' ),
        array( 'iconsmind-Folder-Share' => 'Folder-Share' ),
        array( 'iconsmind-Folder-Trash' => 'Folder-Trash' ),
        array( 'iconsmind-Folder-Upload' => 'Folder-Upload' ),
        array( 'iconsmind-Folder-Video' => 'Folder-Video' ),
        array( 'iconsmind-Folder-WithDocument' => 'Folder-WithDocument' ),
        array( 'iconsmind-Folder-Zip' => 'Folder-Zip' ),
        array( 'iconsmind-Folder' => 'Folder' ),
        array( 'iconsmind-Folders' => 'Folders' ),
        array( 'iconsmind-Font-Color' => 'Font-Color' ),
        array( 'iconsmind-Font-Name' => 'Font-Name' ),
        array( 'iconsmind-Font-Size' => 'Font-Size' ),
        array( 'iconsmind-Font-Style' => 'Font-Style' ),
        array( 'iconsmind-Font-StyleSubscript' => 'Font-StyleSubscript' ),
        array( 'iconsmind-Font-StyleSuperscript' => 'Font-StyleSuperscript' ),
        array( 'iconsmind-Font-Window' => 'Font-Window' ),
        array( 'iconsmind-Foot-2' => 'Foot-2' ),
        array( 'iconsmind-Foot' => 'Foot' ),
        array( 'iconsmind-Football-2' => 'Football-2' ),
        array( 'iconsmind-Football' => 'Football' ),
        array( 'iconsmind-Footprint-2' => 'Footprint-2' ),
        array( 'iconsmind-Footprint-3' => 'Footprint-3' ),
        array( 'iconsmind-Footprint' => 'Footprint' ),
        array( 'iconsmind-Forest' => 'Forest' ),
        array( 'iconsmind-Fork' => 'Fork' ),
        array( 'iconsmind-Formspring' => 'Formspring' ),
        array( 'iconsmind-Formula' => 'Formula' ),
        array( 'iconsmind-Forsquare' => 'Forsquare' ),
        array( 'iconsmind-Forward' => 'Forward' ),
        array( 'iconsmind-Fountain-Pen' => 'Fountain-Pen' ),
        array( 'iconsmind-Four-Fingers' => 'Four-Fingers' ),
        array( 'iconsmind-Four-FingersDrag' => 'Four-FingersDrag' ),
        array( 'iconsmind-Four-FingersDrag2' => 'Four-FingersDrag2' ),
        array( 'iconsmind-Four-FingersTouch' => 'Four-FingersTouch' ),
        array( 'iconsmind-Fox' => 'Fox' ),
        array( 'iconsmind-Frankenstein' => 'Frankenstein' ),
        array( 'iconsmind-French-Fries' => 'French-Fries' ),
        array( 'iconsmind-Friendfeed' => 'Friendfeed' ),
        array( 'iconsmind-Friendster' => 'Friendster' ),
        array( 'iconsmind-Frog' => 'Frog' ),
        array( 'iconsmind-Fruits' => 'Fruits' ),
        array( 'iconsmind-Fuel' => 'Fuel' ),
        array( 'iconsmind-Full-Bag' => 'Full-Bag' ),
        array( 'iconsmind-Full-Basket' => 'Full-Basket' ),
        array( 'iconsmind-Full-Cart' => 'Full-Cart' ),
        array( 'iconsmind-Full-Moon' => 'Full-Moon' ),
        array( 'iconsmind-Full-Screen' => 'Full-Screen' ),
        array( 'iconsmind-Full-Screen2' => 'Full-Screen2' ),
        array( 'iconsmind-Full-View' => 'Full-View' ),
        array( 'iconsmind-Full-View2' => 'Full-View2' ),
        array( 'iconsmind-Full-ViewWindow' => 'Full-ViewWindow' ),
        array( 'iconsmind-Function' => 'Function' ),
        array( 'iconsmind-Funky' => 'Funky' ),
        array( 'iconsmind-Funny-Bicycle' => 'Funny-Bicycle' ),
        array( 'iconsmind-Furl' => 'Furl' ),
        array( 'iconsmind-Gamepad-2' => 'Gamepad-2' ),
        array( 'iconsmind-Gamepad' => 'Gamepad' ),
        array( 'iconsmind-Gas-Pump' => 'Gas-Pump' ),
        array( 'iconsmind-Gaugage-2' => 'Gaugage-2' ),
        array( 'iconsmind-Gaugage' => 'Gaugage' ),
        array( 'iconsmind-Gay' => 'Gay' ),
        array( 'iconsmind-Gear-2' => 'Gear-2' ),
        array( 'iconsmind-Gear' => 'Gear' ),
        array( 'iconsmind-Gears-2' => 'Gears-2' ),
        array( 'iconsmind-Gears' => 'Gears' ),
        array( 'iconsmind-Geek-2' => 'Geek-2' ),
        array( 'iconsmind-Geek' => 'Geek' ),
        array( 'iconsmind-Gemini-2' => 'Gemini-2' ),
        array( 'iconsmind-Gemini' => 'Gemini' ),
        array( 'iconsmind-Genius' => 'Genius' ),
        array( 'iconsmind-Gentleman' => 'Gentleman' ),
        array( 'iconsmind-Geo--' => 'Geo--' ),
        array( 'iconsmind-Geo-' => 'Geo-' ),
        array( 'iconsmind-Geo-Close' => 'Geo-Close' ),
        array( 'iconsmind-Geo-Love' => 'Geo-Love' ),
        array( 'iconsmind-Geo-Number' => 'Geo-Number' ),
        array( 'iconsmind-Geo-Star' => 'Geo-Star' ),
        array( 'iconsmind-Geo' => 'Geo' ),
        array( 'iconsmind-Geo2--' => 'Geo2--' ),
        array( 'iconsmind-Geo2-' => 'Geo2-' ),
        array( 'iconsmind-Geo2-Close' => 'Geo2-Close' ),
        array( 'iconsmind-Geo2-Love' => 'Geo2-Love' ),
        array( 'iconsmind-Geo2-Number' => 'Geo2-Number' ),
        array( 'iconsmind-Geo2-Star' => 'Geo2-Star' ),
        array( 'iconsmind-Geo2' => 'Geo2' ),
        array( 'iconsmind-Geo3--' => 'Geo3--' ),
        array( 'iconsmind-Geo3-' => 'Geo3-' ),
        array( 'iconsmind-Geo3-Close' => 'Geo3-Close' ),
        array( 'iconsmind-Geo3-Love' => 'Geo3-Love' ),
        array( 'iconsmind-Geo3-Number' => 'Geo3-Number' ),
        array( 'iconsmind-Geo3-Star' => 'Geo3-Star' ),
        array( 'iconsmind-Geo3' => 'Geo3' ),
        array( 'iconsmind-Gey' => 'Gey' ),
        array( 'iconsmind-Gift-Box' => 'Gift-Box' ),
        array( 'iconsmind-Giraffe' => 'Giraffe' ),
        array( 'iconsmind-Girl' => 'Girl' ),
        array( 'iconsmind-Glass-Water' => 'Glass-Water' ),
        array( 'iconsmind-Glasses-2' => 'Glasses-2' ),
        array( 'iconsmind-Glasses-3' => 'Glasses-3' ),
        array( 'iconsmind-Glasses' => 'Glasses' ),
        array( 'iconsmind-Global-Position' => 'Global-Position' ),
        array( 'iconsmind-Globe-2' => 'Globe-2' ),
        array( 'iconsmind-Globe' => 'Globe' ),
        array( 'iconsmind-Gloves' => 'Gloves' ),
        array( 'iconsmind-Go-Bottom' => 'Go-Bottom' ),
        array( 'iconsmind-Go-Top' => 'Go-Top' ),
        array( 'iconsmind-Goggles' => 'Goggles' ),
        array( 'iconsmind-Golf-2' => 'Golf-2' ),
        array( 'iconsmind-Golf' => 'Golf' ),
        array( 'iconsmind-Google-Buzz' => 'Google-Buzz' ),
        array( 'iconsmind-Google-Drive' => 'Google-Drive' ),
        array( 'iconsmind-Google-Play' => 'Google-Play' ),
        array( 'iconsmind-Google-Plus' => 'Google-Plus' ),
        array( 'iconsmind-Google' => 'Google' ),
        array( 'iconsmind-Gopro' => 'Gopro' ),
        array( 'iconsmind-Gorilla' => 'Gorilla' ),
        array( 'iconsmind-Gowalla' => 'Gowalla' ),
        array( 'iconsmind-Grave' => 'Grave' ),
        array( 'iconsmind-Graveyard' => 'Graveyard' ),
        array( 'iconsmind-Greece' => 'Greece' ),
        array( 'iconsmind-Green-Energy' => 'Green-Energy' ),
        array( 'iconsmind-Green-House' => 'Green-House' ),
        array( 'iconsmind-Guitar' => 'Guitar' ),
        array( 'iconsmind-Gun-2' => 'Gun-2' ),
        array( 'iconsmind-Gun-3' => 'Gun-3' ),
        array( 'iconsmind-Gun' => 'Gun' ),
        array( 'iconsmind-Gymnastics' => 'Gymnastics' ),
        array( 'iconsmind-Hair-2' => 'Hair-2' ),
        array( 'iconsmind-Hair-3' => 'Hair-3' ),
        array( 'iconsmind-Hair-4' => 'Hair-4' ),
        array( 'iconsmind-Hair' => 'Hair' ),
        array( 'iconsmind-Half-Moon' => 'Half-Moon' ),
        array( 'iconsmind-Halloween-HalfMoon' => 'Halloween-HalfMoon' ),
        array( 'iconsmind-Halloween-Moon' => 'Halloween-Moon' ),
        array( 'iconsmind-Hamburger' => 'Hamburger' ),
        array( 'iconsmind-Hammer' => 'Hammer' ),
        array( 'iconsmind-Hand-Touch' => 'Hand-Touch' ),
        array( 'iconsmind-Hand-Touch2' => 'Hand-Touch2' ),
        array( 'iconsmind-Hand-TouchSmartphone' => 'Hand-TouchSmartphone' ),
        array( 'iconsmind-Hand' => 'Hand' ),
        array( 'iconsmind-Hands' => 'Hands' ),
        array( 'iconsmind-Handshake' => 'Handshake' ),
        array( 'iconsmind-Hanger' => 'Hanger' ),
        array( 'iconsmind-Happy' => 'Happy' ),
        array( 'iconsmind-Hat-2' => 'Hat-2' ),
        array( 'iconsmind-Hat' => 'Hat' ),
        array( 'iconsmind-Haunted-House' => 'Haunted-House' ),
        array( 'iconsmind-HD-Video' => 'HD-Video' ),
        array( 'iconsmind-HD' => 'HD' ),
        array( 'iconsmind-HDD' => 'HDD' ),
        array( 'iconsmind-Headphone' => 'Headphone' ),
        array( 'iconsmind-Headphones' => 'Headphones' ),
        array( 'iconsmind-Headset' => 'Headset' ),
        array( 'iconsmind-Heart-2' => 'Heart-2' ),
        array( 'iconsmind-Heart' => 'Heart' ),
        array( 'iconsmind-Heels-2' => 'Heels-2' ),
        array( 'iconsmind-Heels' => 'Heels' ),
        array( 'iconsmind-Height-Window' => 'Height-Window' ),
        array( 'iconsmind-Helicopter-2' => 'Helicopter-2' ),
        array( 'iconsmind-Helicopter' => 'Helicopter' ),
        array( 'iconsmind-Helix-2' => 'Helix-2' ),
        array( 'iconsmind-Hello' => 'Hello' ),
        array( 'iconsmind-Helmet-2' => 'Helmet-2' ),
        array( 'iconsmind-Helmet-3' => 'Helmet-3' ),
        array( 'iconsmind-Helmet' => 'Helmet' ),
        array( 'iconsmind-Hipo' => 'Hipo' ),
        array( 'iconsmind-Hipster-Glasses' => 'Hipster-Glasses' ),
        array( 'iconsmind-Hipster-Glasses2' => 'Hipster-Glasses2' ),
        array( 'iconsmind-Hipster-Glasses3' => 'Hipster-Glasses3' ),
        array( 'iconsmind-Hipster-Headphones' => 'Hipster-Headphones' ),
        array( 'iconsmind-Hipster-Men' => 'Hipster-Men' ),
        array( 'iconsmind-Hipster-Men2' => 'Hipster-Men2' ),
        array( 'iconsmind-Hipster-Men3' => 'Hipster-Men3' ),
        array( 'iconsmind-Hipster-Sunglasses' => 'Hipster-Sunglasses' ),
        array( 'iconsmind-Hipster-Sunglasses2' => 'Hipster-Sunglasses2' ),
        array( 'iconsmind-Hipster-Sunglasses3' => 'Hipster-Sunglasses3' ),
        array( 'iconsmind-Hokey' => 'Hokey' ),
        array( 'iconsmind-Holly' => 'Holly' ),
        array( 'iconsmind-Home-2' => 'Home-2' ),
        array( 'iconsmind-Home-3' => 'Home-3' ),
        array( 'iconsmind-Home-4' => 'Home-4' ),
        array( 'iconsmind-Home-5' => 'Home-5' ),
        array( 'iconsmind-Home-Window' => 'Home-Window' ),
        array( 'iconsmind-Home' => 'Home' ),
        array( 'iconsmind-Homosexual' => 'Homosexual' ),
        array( 'iconsmind-Honey' => 'Honey' ),
        array( 'iconsmind-Hong-Kong' => 'Hong-Kong' ),
        array( 'iconsmind-Hoodie' => 'Hoodie' ),
        array( 'iconsmind-Horror' => 'Horror' ),
        array( 'iconsmind-Horse' => 'Horse' ),
        array( 'iconsmind-Hospital-2' => 'Hospital-2' ),
        array( 'iconsmind-Hospital' => 'Hospital' ),
        array( 'iconsmind-Host' => 'Host' ),
        array( 'iconsmind-Hot-Dog' => 'Hot-Dog' ),
        array( 'iconsmind-Hotel' => 'Hotel' ),
        array( 'iconsmind-Hour' => 'Hour' ),
        array( 'iconsmind-Hub' => 'Hub' ),
        array( 'iconsmind-Humor' => 'Humor' ),
        array( 'iconsmind-Hurt' => 'Hurt' ),
        array( 'iconsmind-Ice-Cream' => 'Ice-Cream' ),
        array( 'iconsmind-ICQ' => 'ICQ' ),
        array( 'iconsmind-ID-2' => 'ID-2' ),
        array( 'iconsmind-ID-3' => 'ID-3' ),
        array( 'iconsmind-ID-Card' => 'ID-Card' ),
        array( 'iconsmind-Idea-2' => 'Idea-2' ),
        array( 'iconsmind-Idea-3' => 'Idea-3' ),
        array( 'iconsmind-Idea-4' => 'Idea-4' ),
        array( 'iconsmind-Idea-5' => 'Idea-5' ),
        array( 'iconsmind-Idea' => 'Idea' ),
        array( 'iconsmind-Identification-Badge' => 'Identification-Badge' ),
        array( 'iconsmind-ImDB' => 'ImDB' ),
        array( 'iconsmind-Inbox-Empty' => 'Inbox-Empty' ),
        array( 'iconsmind-Inbox-Forward' => 'Inbox-Forward' ),
        array( 'iconsmind-Inbox-Full' => 'Inbox-Full' ),
        array( 'iconsmind-Inbox-Into' => 'Inbox-Into' ),
        array( 'iconsmind-Inbox-Out' => 'Inbox-Out' ),
        array( 'iconsmind-Inbox-Reply' => 'Inbox-Reply' ),
        array( 'iconsmind-Inbox' => 'Inbox' ),
        array( 'iconsmind-Increase-Inedit' => 'Increase-Inedit' ),
        array( 'iconsmind-Indent-FirstLine' => 'Indent-FirstLine' ),
        array( 'iconsmind-Indent-LeftMargin' => 'Indent-LeftMargin' ),
        array( 'iconsmind-Indent-RightMargin' => 'Indent-RightMargin' ),
        array( 'iconsmind-India' => 'India' ),
        array( 'iconsmind-Info-Window' => 'Info-Window' ),
        array( 'iconsmind-Information' => 'Information' ),
        array( 'iconsmind-Inifity' => 'Inifity' ),
        array( 'iconsmind-Instagram' => 'Instagram' ),
        array( 'iconsmind-Internet-2' => 'Internet-2' ),
        array( 'iconsmind-Internet-Explorer' => 'Internet-Explorer' ),
        array( 'iconsmind-Internet-Smiley' => 'Internet-Smiley' ),
        array( 'iconsmind-Internet' => 'Internet' ),
        array( 'iconsmind-iOS-Apple' => 'iOS-Apple' ),
        array( 'iconsmind-Israel' => 'Israel' ),
        array( 'iconsmind-Italic-Text' => 'Italic-Text' ),
        array( 'iconsmind-Jacket-2' => 'Jacket-2' ),
        array( 'iconsmind-Jacket' => 'Jacket' ),
        array( 'iconsmind-Jamaica' => 'Jamaica' ),
        array( 'iconsmind-Japan' => 'Japan' ),
        array( 'iconsmind-Japanese-Gate' => 'Japanese-Gate' ),
        array( 'iconsmind-Jeans' => 'Jeans' ),
        array( 'iconsmind-Jeep-2' => 'Jeep-2' ),
        array( 'iconsmind-Jeep' => 'Jeep' ),
        array( 'iconsmind-Jet' => 'Jet' ),
        array( 'iconsmind-Joystick' => 'Joystick' ),
        array( 'iconsmind-Juice' => 'Juice' ),
        array( 'iconsmind-Jump-Rope' => 'Jump-Rope' ),
        array( 'iconsmind-Kangoroo' => 'Kangoroo' ),
        array( 'iconsmind-Kenya' => 'Kenya' ),
        array( 'iconsmind-Key-2' => 'Key-2' ),
        array( 'iconsmind-Key-3' => 'Key-3' ),
        array( 'iconsmind-Key-Lock' => 'Key-Lock' ),
        array( 'iconsmind-Key' => 'Key' ),
        array( 'iconsmind-Keyboard' => 'Keyboard' ),
        array( 'iconsmind-Keyboard3' => 'Keyboard3' ),
        array( 'iconsmind-Keypad' => 'Keypad' ),
        array( 'iconsmind-King-2' => 'King-2' ),
        array( 'iconsmind-King' => 'King' ),
        array( 'iconsmind-Kiss' => 'Kiss' ),
        array( 'iconsmind-Knee' => 'Knee' ),
        array( 'iconsmind-Knife-2' => 'Knife-2' ),
        array( 'iconsmind-Knife' => 'Knife' ),
        array( 'iconsmind-Knight' => 'Knight' ),
        array( 'iconsmind-Koala' => 'Koala' ),
        array( 'iconsmind-Korea' => 'Korea' ),
        array( 'iconsmind-Lamp' => 'Lamp' ),
        array( 'iconsmind-Landscape-2' => 'Landscape-2' ),
        array( 'iconsmind-Landscape' => 'Landscape' ),
        array( 'iconsmind-Lantern' => 'Lantern' ),
        array( 'iconsmind-Laptop-2' => 'Laptop-2' ),
        array( 'iconsmind-Laptop-3' => 'Laptop-3' ),
        array( 'iconsmind-Laptop-Phone' => 'Laptop-Phone' ),
        array( 'iconsmind-Laptop-Secure' => 'Laptop-Secure' ),
        array( 'iconsmind-Laptop-Tablet' => 'Laptop-Tablet' ),
        array( 'iconsmind-Laptop' => 'Laptop' ),
        array( 'iconsmind-Laser' => 'Laser' ),
        array( 'iconsmind-Last-FM' => 'Last-FM' ),
        array( 'iconsmind-Last' => 'Last' ),
        array( 'iconsmind-Laughing' => 'Laughing' ),
        array( 'iconsmind-Layer-1635' => 'Layer-1635' ),
        array( 'iconsmind-Layer-1646' => 'Layer-1646' ),
        array( 'iconsmind-Layer-Backward' => 'Layer-Backward' ),
        array( 'iconsmind-Layer-Forward' => 'Layer-Forward' ),
        array( 'iconsmind-Leafs-2' => 'Leafs-2' ),
        array( 'iconsmind-Leafs' => 'Leafs' ),
        array( 'iconsmind-Leaning-Tower' => 'Leaning-Tower' ),
        array( 'iconsmind-Left--Right' => 'Left--Right' ),
        array( 'iconsmind-Left--Right3' => 'Left--Right3' ),
        array( 'iconsmind-Left-2' => 'Left-2' ),
        array( 'iconsmind-Left-3' => 'Left-3' ),
        array( 'iconsmind-Left-4' => 'Left-4' ),
        array( 'iconsmind-Left-ToRight' => 'Left-ToRight' ),
        array( 'iconsmind-Left' => 'Left' ),
        array( 'iconsmind-Leg-2' => 'Leg-2' ),
        array( 'iconsmind-Leg' => 'Leg' ),
        array( 'iconsmind-Lego' => 'Lego' ),
        array( 'iconsmind-Lemon' => 'Lemon' ),
        array( 'iconsmind-Len-2' => 'Len-2' ),
        array( 'iconsmind-Len-3' => 'Len-3' ),
        array( 'iconsmind-Len' => 'Len' ),
        array( 'iconsmind-Leo-2' => 'Leo-2' ),
        array( 'iconsmind-Leo' => 'Leo' ),
        array( 'iconsmind-Leopard' => 'Leopard' ),
        array( 'iconsmind-Lesbian' => 'Lesbian' ),
        array( 'iconsmind-Lesbians' => 'Lesbians' ),
        array( 'iconsmind-Letter-Close' => 'Letter-Close' ),
        array( 'iconsmind-Letter-Open' => 'Letter-Open' ),
        array( 'iconsmind-Letter-Sent' => 'Letter-Sent' ),
        array( 'iconsmind-Libra-2' => 'Libra-2' ),
        array( 'iconsmind-Libra' => 'Libra' ),
        array( 'iconsmind-Library-2' => 'Library-2' ),
        array( 'iconsmind-Library' => 'Library' ),
        array( 'iconsmind-Life-Jacket' => 'Life-Jacket' ),
        array( 'iconsmind-Life-Safer' => 'Life-Safer' ),
        array( 'iconsmind-Light-Bulb' => 'Light-Bulb' ),
        array( 'iconsmind-Light-Bulb2' => 'Light-Bulb2' ),
        array( 'iconsmind-Light-BulbLeaf' => 'Light-BulbLeaf' ),
        array( 'iconsmind-Lighthouse' => 'Lighthouse' ),
        array( 'iconsmind-Like-2' => 'Like-2' ),
        array( 'iconsmind-Like' => 'Like' ),
        array( 'iconsmind-Line-Chart' => 'Line-Chart' ),
        array( 'iconsmind-Line-Chart2' => 'Line-Chart2' ),
        array( 'iconsmind-Line-Chart3' => 'Line-Chart3' ),
        array( 'iconsmind-Line-Chart4' => 'Line-Chart4' ),
        array( 'iconsmind-Line-Spacing' => 'Line-Spacing' ),
        array( 'iconsmind-Line-SpacingText' => 'Line-SpacingText' ),
        array( 'iconsmind-Link-2' => 'Link-2' ),
        array( 'iconsmind-Link' => 'Link' ),
        array( 'iconsmind-Linkedin-2' => 'Linkedin-2' ),
        array( 'iconsmind-Linkedin' => 'Linkedin' ),
        array( 'iconsmind-Linux' => 'Linux' ),
        array( 'iconsmind-Lion' => 'Lion' ),
        array( 'iconsmind-Livejournal' => 'Livejournal' ),
        array( 'iconsmind-Loading-2' => 'Loading-2' ),
        array( 'iconsmind-Loading-3' => 'Loading-3' ),
        array( 'iconsmind-Loading-Window' => 'Loading-Window' ),
        array( 'iconsmind-Loading' => 'Loading' ),
        array( 'iconsmind-Location-2' => 'Location-2' ),
        array( 'iconsmind-Location' => 'Location' ),
        array( 'iconsmind-Lock-2' => 'Lock-2' ),
        array( 'iconsmind-Lock-3' => 'Lock-3' ),
        array( 'iconsmind-Lock-User' => 'Lock-User' ),
        array( 'iconsmind-Lock-Window' => 'Lock-Window' ),
        array( 'iconsmind-Lock' => 'Lock' ),
        array( 'iconsmind-Lollipop-2' => 'Lollipop-2' ),
        array( 'iconsmind-Lollipop-3' => 'Lollipop-3' ),
        array( 'iconsmind-Lollipop' => 'Lollipop' ),
        array( 'iconsmind-Loop' => 'Loop' ),
        array( 'iconsmind-Loud' => 'Loud' ),
        array( 'iconsmind-Loudspeaker' => 'Loudspeaker' ),
        array( 'iconsmind-Love-2' => 'Love-2' ),
        array( 'iconsmind-Love-User' => 'Love-User' ),
        array( 'iconsmind-Love-Window' => 'Love-Window' ),
        array( 'iconsmind-Love' => 'Love' ),
        array( 'iconsmind-Lowercase-Text' => 'Lowercase-Text' ),
        array( 'iconsmind-Luggafe-Front' => 'Luggafe-Front' ),
        array( 'iconsmind-Luggage-2' => 'Luggage-2' ),
        array( 'iconsmind-Macro' => 'Macro' ),
        array( 'iconsmind-Magic-Wand' => 'Magic-Wand' ),
        array( 'iconsmind-Magnet' => 'Magnet' ),
        array( 'iconsmind-Magnifi-Glass-' => 'Magnifi-Glass-' ),
        array( 'iconsmind-Magnifi-Glass' => 'Magnifi-Glass' ),
        array( 'iconsmind-Magnifi-Glass2' => 'Magnifi-Glass2' ),
        array( 'iconsmind-Mail-2' => 'Mail-2' ),
        array( 'iconsmind-Mail-3' => 'Mail-3' ),
        array( 'iconsmind-Mail-Add' => 'Mail-Add' ),
        array( 'iconsmind-Mail-Attachement' => 'Mail-Attachement' ),
        array( 'iconsmind-Mail-Block' => 'Mail-Block' ),
        array( 'iconsmind-Mail-Delete' => 'Mail-Delete' ),
        array( 'iconsmind-Mail-Favorite' => 'Mail-Favorite' ),
        array( 'iconsmind-Mail-Forward' => 'Mail-Forward' ),
        array( 'iconsmind-Mail-Gallery' => 'Mail-Gallery' ),
        array( 'iconsmind-Mail-Inbox' => 'Mail-Inbox' ),
        array( 'iconsmind-Mail-Link' => 'Mail-Link' ),
        array( 'iconsmind-Mail-Lock' => 'Mail-Lock' ),
        array( 'iconsmind-Mail-Love' => 'Mail-Love' ),
        array( 'iconsmind-Mail-Money' => 'Mail-Money' ),
        array( 'iconsmind-Mail-Open' => 'Mail-Open' ),
        array( 'iconsmind-Mail-Outbox' => 'Mail-Outbox' ),
        array( 'iconsmind-Mail-Password' => 'Mail-Password' ),
        array( 'iconsmind-Mail-Photo' => 'Mail-Photo' ),
        array( 'iconsmind-Mail-Read' => 'Mail-Read' ),
        array( 'iconsmind-Mail-Removex' => 'Mail-Removex' ),
        array( 'iconsmind-Mail-Reply' => 'Mail-Reply' ),
        array( 'iconsmind-Mail-ReplyAll' => 'Mail-ReplyAll' ),
        array( 'iconsmind-Mail-Search' => 'Mail-Search' ),
        array( 'iconsmind-Mail-Send' => 'Mail-Send' ),
        array( 'iconsmind-Mail-Settings' => 'Mail-Settings' ),
        array( 'iconsmind-Mail-Unread' => 'Mail-Unread' ),
        array( 'iconsmind-Mail-Video' => 'Mail-Video' ),
        array( 'iconsmind-Mail-withAtSign' => 'Mail-withAtSign' ),
        array( 'iconsmind-Mail-WithCursors' => 'Mail-WithCursors' ),
        array( 'iconsmind-Mail' => 'Mail' ),
        array( 'iconsmind-Mailbox-Empty' => 'Mailbox-Empty' ),
        array( 'iconsmind-Mailbox-Full' => 'Mailbox-Full' ),
        array( 'iconsmind-Male-2' => 'Male-2' ),
        array( 'iconsmind-Male-Sign' => 'Male-Sign' ),
        array( 'iconsmind-Male' => 'Male' ),
        array( 'iconsmind-MaleFemale' => 'MaleFemale' ),
        array( 'iconsmind-Man-Sign' => 'Man-Sign' ),
        array( 'iconsmind-Management' => 'Management' ),
        array( 'iconsmind-Mans-Underwear' => 'Mans-Underwear' ),
        array( 'iconsmind-Mans-Underwear2' => 'Mans-Underwear2' ),
        array( 'iconsmind-Map-Marker' => 'Map-Marker' ),
        array( 'iconsmind-Map-Marker2' => 'Map-Marker2' ),
        array( 'iconsmind-Map-Marker3' => 'Map-Marker3' ),
        array( 'iconsmind-Map' => 'Map' ),
        array( 'iconsmind-Map2' => 'Map2' ),
        array( 'iconsmind-Marker-2' => 'Marker-2' ),
        array( 'iconsmind-Marker-3' => 'Marker-3' ),
        array( 'iconsmind-Marker' => 'Marker' ),
        array( 'iconsmind-Martini-Glass' => 'Martini-Glass' ),
        array( 'iconsmind-Mask' => 'Mask' ),
        array( 'iconsmind-Master-Card' => 'Master-Card' ),
        array( 'iconsmind-Maximize-Window' => 'Maximize-Window' ),
        array( 'iconsmind-Maximize' => 'Maximize' ),
        array( 'iconsmind-Medal-2' => 'Medal-2' ),
        array( 'iconsmind-Medal-3' => 'Medal-3' ),
        array( 'iconsmind-Medal' => 'Medal' ),
        array( 'iconsmind-Medical-Sign' => 'Medical-Sign' ),
        array( 'iconsmind-Medicine-2' => 'Medicine-2' ),
        array( 'iconsmind-Medicine-3' => 'Medicine-3' ),
        array( 'iconsmind-Medicine' => 'Medicine' ),
        array( 'iconsmind-Megaphone' => 'Megaphone' ),
        array( 'iconsmind-Memory-Card' => 'Memory-Card' ),
        array( 'iconsmind-Memory-Card2' => 'Memory-Card2' ),
        array( 'iconsmind-Memory-Card3' => 'Memory-Card3' ),
        array( 'iconsmind-Men' => 'Men' ),
        array( 'iconsmind-Menorah' => 'Menorah' ),
        array( 'iconsmind-Mens' => 'Mens' ),
        array( 'iconsmind-Metacafe' => 'Metacafe' ),
        array( 'iconsmind-Mexico' => 'Mexico' ),
        array( 'iconsmind-Mic' => 'Mic' ),
        array( 'iconsmind-Microphone-2' => 'Microphone-2' ),
        array( 'iconsmind-Microphone-3' => 'Microphone-3' ),
        array( 'iconsmind-Microphone-4' => 'Microphone-4' ),
        array( 'iconsmind-Microphone-5' => 'Microphone-5' ),
        array( 'iconsmind-Microphone-6' => 'Microphone-6' ),
        array( 'iconsmind-Microphone-7' => 'Microphone-7' ),
        array( 'iconsmind-Microphone' => 'Microphone' ),
        array( 'iconsmind-Microscope' => 'Microscope' ),
        array( 'iconsmind-Milk-Bottle' => 'Milk-Bottle' ),
        array( 'iconsmind-Mine' => 'Mine' ),
        array( 'iconsmind-Minimize-Maximize-Close-Window' => 'Minimize-Maximize-Close-Window' ),
        array( 'iconsmind-Minimize-Window' => 'Minimize-Window' ),
        array( 'iconsmind-Minimize' => 'Minimize' ),
        array( 'iconsmind-Mirror' => 'Mirror' ),
        array( 'iconsmind-Mixer' => 'Mixer' ),
        array( 'iconsmind-Mixx' => 'Mixx' ),
        array( 'iconsmind-Money-2' => 'Money-2' ),
        array( 'iconsmind-Money-Bag' => 'Money-Bag' ),
        array( 'iconsmind-Money-Smiley' => 'Money-Smiley' ),
        array( 'iconsmind-Money' => 'Money' ),
        array( 'iconsmind-Monitor-2' => 'Monitor-2' ),
        array( 'iconsmind-Monitor-3' => 'Monitor-3' ),
        array( 'iconsmind-Monitor-4' => 'Monitor-4' ),
        array( 'iconsmind-Monitor-5' => 'Monitor-5' ),
        array( 'iconsmind-Monitor-Analytics' => 'Monitor-Analytics' ),
        array( 'iconsmind-Monitor-Laptop' => 'Monitor-Laptop' ),
        array( 'iconsmind-Monitor-phone' => 'Monitor-phone' ),
        array( 'iconsmind-Monitor-Tablet' => 'Monitor-Tablet' ),
        array( 'iconsmind-Monitor-Vertical' => 'Monitor-Vertical' ),
        array( 'iconsmind-Monitor' => 'Monitor' ),
        array( 'iconsmind-Monitoring' => 'Monitoring' ),
        array( 'iconsmind-Monkey' => 'Monkey' ),
        array( 'iconsmind-Monster' => 'Monster' ),
        array( 'iconsmind-Morocco' => 'Morocco' ),
        array( 'iconsmind-Motorcycle' => 'Motorcycle' ),
        array( 'iconsmind-Mouse-2' => 'Mouse-2' ),
        array( 'iconsmind-Mouse-3' => 'Mouse-3' ),
        array( 'iconsmind-Mouse-4' => 'Mouse-4' ),
        array( 'iconsmind-Mouse-Pointer' => 'Mouse-Pointer' ),
        array( 'iconsmind-Mouse' => 'Mouse' ),
        array( 'iconsmind-Moustache-Smiley' => 'Moustache-Smiley' ),
        array( 'iconsmind-Movie-Ticket' => 'Movie-Ticket' ),
        array( 'iconsmind-Movie' => 'Movie' ),
        array( 'iconsmind-Mp3-File' => 'Mp3-File' ),
        array( 'iconsmind-Museum' => 'Museum' ),
        array( 'iconsmind-Mushroom' => 'Mushroom' ),
        array( 'iconsmind-Music-Note' => 'Music-Note' ),
        array( 'iconsmind-Music-Note2' => 'Music-Note2' ),
        array( 'iconsmind-Music-Note3' => 'Music-Note3' ),
        array( 'iconsmind-Music-Note4' => 'Music-Note4' ),
        array( 'iconsmind-Music-Player' => 'Music-Player' ),
        array( 'iconsmind-Mustache-2' => 'Mustache-2' ),
        array( 'iconsmind-Mustache-3' => 'Mustache-3' ),
        array( 'iconsmind-Mustache-4' => 'Mustache-4' ),
        array( 'iconsmind-Mustache-5' => 'Mustache-5' ),
        array( 'iconsmind-Mustache-6' => 'Mustache-6' ),
        array( 'iconsmind-Mustache-7' => 'Mustache-7' ),
        array( 'iconsmind-Mustache-8' => 'Mustache-8' ),
        array( 'iconsmind-Mustache' => 'Mustache' ),
        array( 'iconsmind-Mute' => 'Mute' ),
        array( 'iconsmind-Myspace' => 'Myspace' ),
        array( 'iconsmind-Navigat-Start' => 'Navigat-Start' ),
        array( 'iconsmind-Navigate-End' => 'Navigate-End' ),
        array( 'iconsmind-Navigation-LeftWindow' => 'Navigation-LeftWindow' ),
        array( 'iconsmind-Navigation-RightWindow' => 'Navigation-RightWindow' ),
        array( 'iconsmind-Nepal' => 'Nepal' ),
        array( 'iconsmind-Netscape' => 'Netscape' ),
        array( 'iconsmind-Network-Window' => 'Network-Window' ),
        array( 'iconsmind-Network' => 'Network' ),
        array( 'iconsmind-Neutron' => 'Neutron' ),
        array( 'iconsmind-New-Mail' => 'New-Mail' ),
        array( 'iconsmind-New-Tab' => 'New-Tab' ),
        array( 'iconsmind-Newspaper-2' => 'Newspaper-2' ),
        array( 'iconsmind-Newspaper' => 'Newspaper' ),
        array( 'iconsmind-Newsvine' => 'Newsvine' ),
        array( 'iconsmind-Next2' => 'Next2' ),
        array( 'iconsmind-Next-3' => 'Next-3' ),
        array( 'iconsmind-Next-Music' => 'Next-Music' ),
        array( 'iconsmind-Next' => 'Next' ),
        array( 'iconsmind-No-Battery' => 'No-Battery' ),
        array( 'iconsmind-No-Drop' => 'No-Drop' ),
        array( 'iconsmind-No-Flash' => 'No-Flash' ),
        array( 'iconsmind-No-Smoking' => 'No-Smoking' ),
        array( 'iconsmind-Noose' => 'Noose' ),
        array( 'iconsmind-Normal-Text' => 'Normal-Text' ),
        array( 'iconsmind-Note' => 'Note' ),
        array( 'iconsmind-Notepad-2' => 'Notepad-2' ),
        array( 'iconsmind-Notepad' => 'Notepad' ),
        array( 'iconsmind-Nuclear' => 'Nuclear' ),
        array( 'iconsmind-Numbering-List' => 'Numbering-List' ),
        array( 'iconsmind-Nurse' => 'Nurse' ),
        array( 'iconsmind-Office-Lamp' => 'Office-Lamp' ),
        array( 'iconsmind-Office' => 'Office' ),
        array( 'iconsmind-Oil' => 'Oil' ),
        array( 'iconsmind-Old-Camera' => 'Old-Camera' ),
        array( 'iconsmind-Old-Cassette' => 'Old-Cassette' ),
        array( 'iconsmind-Old-Clock' => 'Old-Clock' ),
        array( 'iconsmind-Old-Radio' => 'Old-Radio' ),
        array( 'iconsmind-Old-Sticky' => 'Old-Sticky' ),
        array( 'iconsmind-Old-Sticky2' => 'Old-Sticky2' ),
        array( 'iconsmind-Old-Telephone' => 'Old-Telephone' ),
        array( 'iconsmind-Old-TV' => 'Old-TV' ),
        array( 'iconsmind-On-Air' => 'On-Air' ),
        array( 'iconsmind-On-Off-2' => 'On-Off-2' ),
        array( 'iconsmind-On-Off-3' => 'On-Off-3' ),
        array( 'iconsmind-On-off' => 'On-off' ),
        array( 'iconsmind-One-Finger' => 'One-Finger' ),
        array( 'iconsmind-One-FingerTouch' => 'One-FingerTouch' ),
        array( 'iconsmind-One-Window' => 'One-Window' ),
        array( 'iconsmind-Open-Banana' => 'Open-Banana' ),
        array( 'iconsmind-Open-Book' => 'Open-Book' ),
        array( 'iconsmind-Opera-House' => 'Opera-House' ),
        array( 'iconsmind-Opera' => 'Opera' ),
        array( 'iconsmind-Optimization' => 'Optimization' ),
        array( 'iconsmind-Orientation-2' => 'Orientation-2' ),
        array( 'iconsmind-Orientation-3' => 'Orientation-3' ),
        array( 'iconsmind-Orientation' => 'Orientation' ),
        array( 'iconsmind-Orkut' => 'Orkut' ),
        array( 'iconsmind-Ornament' => 'Ornament' ),
        array( 'iconsmind-Over-Time' => 'Over-Time' ),
        array( 'iconsmind-Over-Time2' => 'Over-Time2' ),
        array( 'iconsmind-Owl' => 'Owl' ),
        array( 'iconsmind-Pac-Man' => 'Pac-Man' ),
        array( 'iconsmind-Paint-Brush' => 'Paint-Brush' ),
        array( 'iconsmind-Paint-Bucket' => 'Paint-Bucket' ),
        array( 'iconsmind-Paintbrush' => 'Paintbrush' ),
        array( 'iconsmind-Palette' => 'Palette' ),
        array( 'iconsmind-Palm-Tree' => 'Palm-Tree' ),
        array( 'iconsmind-Panda' => 'Panda' ),
        array( 'iconsmind-Panorama' => 'Panorama' ),
        array( 'iconsmind-Pantheon' => 'Pantheon' ),
        array( 'iconsmind-Pantone' => 'Pantone' ),
        array( 'iconsmind-Pants' => 'Pants' ),
        array( 'iconsmind-Paper-Plane' => 'Paper-Plane' ),
        array( 'iconsmind-Paper' => 'Paper' ),
        array( 'iconsmind-Parasailing' => 'Parasailing' ),
        array( 'iconsmind-Parrot' => 'Parrot' ),
        array( 'iconsmind-Password-2shopping' => 'Password-2shopping' ),
        array( 'iconsmind-Password-Field' => 'Password-Field' ),
        array( 'iconsmind-Password-shopping' => 'Password-shopping' ),
        array( 'iconsmind-Password' => 'Password' ),
        array( 'iconsmind-pause-2' => 'pause-2' ),
        array( 'iconsmind-Pause' => 'Pause' ),
        array( 'iconsmind-Paw' => 'Paw' ),
        array( 'iconsmind-Pawn' => 'Pawn' ),
        array( 'iconsmind-Paypal' => 'Paypal' ),
        array( 'iconsmind-Pen-2' => 'Pen-2' ),
        array( 'iconsmind-Pen-3' => 'Pen-3' ),
        array( 'iconsmind-Pen-4' => 'Pen-4' ),
        array( 'iconsmind-Pen-5' => 'Pen-5' ),
        array( 'iconsmind-Pen-6' => 'Pen-6' ),
        array( 'iconsmind-Pen' => 'Pen' ),
        array( 'iconsmind-Pencil-Ruler' => 'Pencil-Ruler' ),
        array( 'iconsmind-Pencil' => 'Pencil' ),
        array( 'iconsmind-Penguin' => 'Penguin' ),
        array( 'iconsmind-Pentagon' => 'Pentagon' ),
        array( 'iconsmind-People-onCloud' => 'People-onCloud' ),
        array( 'iconsmind-Pepper-withFire' => 'Pepper-withFire' ),
        array( 'iconsmind-Pepper' => 'Pepper' ),
        array( 'iconsmind-Petrol' => 'Petrol' ),
        array( 'iconsmind-Petronas-Tower' => 'Petronas-Tower' ),
        array( 'iconsmind-Philipines' => 'Philipines' ),
        array( 'iconsmind-Phone-2' => 'Phone-2' ),
        array( 'iconsmind-Phone-3' => 'Phone-3' ),
        array( 'iconsmind-Phone-3G' => 'Phone-3G' ),
        array( 'iconsmind-Phone-4G' => 'Phone-4G' ),
        array( 'iconsmind-Phone-Simcard' => 'Phone-Simcard' ),
        array( 'iconsmind-Phone-SMS' => 'Phone-SMS' ),
        array( 'iconsmind-Phone-Wifi' => 'Phone-Wifi' ),
        array( 'iconsmind-Phone' => 'Phone' ),
        array( 'iconsmind-Photo-2' => 'Photo-2' ),
        array( 'iconsmind-Photo-3' => 'Photo-3' ),
        array( 'iconsmind-Photo-Album' => 'Photo-Album' ),
        array( 'iconsmind-Photo-Album2' => 'Photo-Album2' ),
        array( 'iconsmind-Photo-Album3' => 'Photo-Album3' ),
        array( 'iconsmind-Photo' => 'Photo' ),
        array( 'iconsmind-Photos' => 'Photos' ),
        array( 'iconsmind-Physics' => 'Physics' ),
        array( 'iconsmind-Pi' => 'Pi' ),
        array( 'iconsmind-Piano' => 'Piano' ),
        array( 'iconsmind-Picasa' => 'Picasa' ),
        array( 'iconsmind-Pie-Chart' => 'Pie-Chart' ),
        array( 'iconsmind-Pie-Chart2' => 'Pie-Chart2' ),
        array( 'iconsmind-Pie-Chart3' => 'Pie-Chart3' ),
        array( 'iconsmind-Pilates-2' => 'Pilates-2' ),
        array( 'iconsmind-Pilates-3' => 'Pilates-3' ),
        array( 'iconsmind-Pilates' => 'Pilates' ),
        array( 'iconsmind-Pilot' => 'Pilot' ),
        array( 'iconsmind-Pinch' => 'Pinch' ),
        array( 'iconsmind-Ping-Pong' => 'Ping-Pong' ),
        array( 'iconsmind-Pinterest' => 'Pinterest' ),
        array( 'iconsmind-Pipe' => 'Pipe' ),
        array( 'iconsmind-Pipette' => 'Pipette' ),
        array( 'iconsmind-Piramids' => 'Piramids' ),
        array( 'iconsmind-Pisces-2' => 'Pisces-2' ),
        array( 'iconsmind-Pisces' => 'Pisces' ),
        array( 'iconsmind-Pizza-Slice' => 'Pizza-Slice' ),
        array( 'iconsmind-Pizza' => 'Pizza' ),
        array( 'iconsmind-Plane-2' => 'Plane-2' ),
        array( 'iconsmind-Plane' => 'Plane' ),
        array( 'iconsmind-Plant' => 'Plant' ),
        array( 'iconsmind-Plasmid' => 'Plasmid' ),
        array( 'iconsmind-Plaster' => 'Plaster' ),
        array( 'iconsmind-Plastic-CupPhone' => 'Plastic-CupPhone' ),
        array( 'iconsmind-Plastic-CupPhone2' => 'Plastic-CupPhone2' ),
        array( 'iconsmind-Plate' => 'Plate' ),
        array( 'iconsmind-Plates' => 'Plates' ),
        array( 'iconsmind-Plaxo' => 'Plaxo' ),
        array( 'iconsmind-Play-Music' => 'Play-Music' ),
        array( 'iconsmind-Plug-In' => 'Plug-In' ),
        array( 'iconsmind-Plug-In2' => 'Plug-In2' ),
        array( 'iconsmind-Plurk' => 'Plurk' ),
        array( 'iconsmind-Pointer' => 'Pointer' ),
        array( 'iconsmind-Poland' => 'Poland' ),
        array( 'iconsmind-Police-Man' => 'Police-Man' ),
        array( 'iconsmind-Police-Station' => 'Police-Station' ),
        array( 'iconsmind-Police-Woman' => 'Police-Woman' ),
        array( 'iconsmind-Police' => 'Police' ),
        array( 'iconsmind-Polo-Shirt' => 'Polo-Shirt' ),
        array( 'iconsmind-Portrait' => 'Portrait' ),
        array( 'iconsmind-Portugal' => 'Portugal' ),
        array( 'iconsmind-Post-Mail' => 'Post-Mail' ),
        array( 'iconsmind-Post-Mail2' => 'Post-Mail2' ),
        array( 'iconsmind-Post-Office' => 'Post-Office' ),
        array( 'iconsmind-Post-Sign' => 'Post-Sign' ),
        array( 'iconsmind-Post-Sign2ways' => 'Post-Sign2ways' ),
        array( 'iconsmind-Posterous' => 'Posterous' ),
        array( 'iconsmind-Pound-Sign' => 'Pound-Sign' ),
        array( 'iconsmind-Pound-Sign2' => 'Pound-Sign2' ),
        array( 'iconsmind-Pound' => 'Pound' ),
        array( 'iconsmind-Power-2' => 'Power-2' ),
        array( 'iconsmind-Power-3' => 'Power-3' ),
        array( 'iconsmind-Power-Cable' => 'Power-Cable' ),
        array( 'iconsmind-Power-Station' => 'Power-Station' ),
        array( 'iconsmind-Power' => 'Power' ),
        array( 'iconsmind-Prater' => 'Prater' ),
        array( 'iconsmind-Present' => 'Present' ),
        array( 'iconsmind-Presents' => 'Presents' ),
        array( 'iconsmind-Press' => 'Press' ),
        array( 'iconsmind-Preview' => 'Preview' ),
        array( 'iconsmind-Previous' => 'Previous' ),
        array( 'iconsmind-Pricing' => 'Pricing' ),
        array( 'iconsmind-Printer' => 'Printer' ),
        array( 'iconsmind-Professor' => 'Professor' ),
        array( 'iconsmind-Profile' => 'Profile' ),
        array( 'iconsmind-Project' => 'Project' ),
        array( 'iconsmind-Projector-2' => 'Projector-2' ),
        array( 'iconsmind-Projector' => 'Projector' ),
        array( 'iconsmind-Pulse' => 'Pulse' ),
        array( 'iconsmind-Pumpkin' => 'Pumpkin' ),
        array( 'iconsmind-Punk' => 'Punk' ),
        array( 'iconsmind-Punker' => 'Punker' ),
        array( 'iconsmind-Puzzle' => 'Puzzle' ),
        array( 'iconsmind-QIK' => 'QIK' ),
        array( 'iconsmind-QR-Code' => 'QR-Code' ),
        array( 'iconsmind-Queen-2' => 'Queen-2' ),
        array( 'iconsmind-Queen' => 'Queen' ),
        array( 'iconsmind-Quill-2' => 'Quill-2' ),
        array( 'iconsmind-Quill-3' => 'Quill-3' ),
        array( 'iconsmind-Quill' => 'Quill' ),
        array( 'iconsmind-Quotes-2' => 'Quotes-2' ),
        array( 'iconsmind-Quotes' => 'Quotes' ),
        array( 'iconsmind-Radio' => 'Radio' ),
        array( 'iconsmind-Radioactive' => 'Radioactive' ),
        array( 'iconsmind-Rafting' => 'Rafting' ),
        array( 'iconsmind-Rain-Drop' => 'Rain-Drop' ),
        array( 'iconsmind-Rainbow-2' => 'Rainbow-2' ),
        array( 'iconsmind-Rainbow' => 'Rainbow' ),
        array( 'iconsmind-Ram' => 'Ram' ),
        array( 'iconsmind-Razzor-Blade' => 'Razzor-Blade' ),
        array( 'iconsmind-Receipt-2' => 'Receipt-2' ),
        array( 'iconsmind-Receipt-3' => 'Receipt-3' ),
        array( 'iconsmind-Receipt-4' => 'Receipt-4' ),
        array( 'iconsmind-Receipt' => 'Receipt' ),
        array( 'iconsmind-Record2' => 'Record2' ),
        array( 'iconsmind-Record-3' => 'Record-3' ),
        array( 'iconsmind-Record-Music' => 'Record-Music' ),
        array( 'iconsmind-Record' => 'Record' ),
        array( 'iconsmind-Recycling-2' => 'Recycling-2' ),
        array( 'iconsmind-Recycling' => 'Recycling' ),
        array( 'iconsmind-Reddit' => 'Reddit' ),
        array( 'iconsmind-Redhat' => 'Redhat' ),
        array( 'iconsmind-Redirect' => 'Redirect' ),
        array( 'iconsmind-Redo' => 'Redo' ),
        array( 'iconsmind-Reel' => 'Reel' ),
        array( 'iconsmind-Refinery' => 'Refinery' ),
        array( 'iconsmind-Refresh-Window' => 'Refresh-Window' ),
        array( 'iconsmind-Refresh' => 'Refresh' ),
        array( 'iconsmind-Reload-2' => 'Reload-2' ),
        array( 'iconsmind-Reload-3' => 'Reload-3' ),
        array( 'iconsmind-Reload' => 'Reload' ),
        array( 'iconsmind-Remote-Controll' => 'Remote-Controll' ),
        array( 'iconsmind-Remote-Controll2' => 'Remote-Controll2' ),
        array( 'iconsmind-Remove-Bag' => 'Remove-Bag' ),
        array( 'iconsmind-Remove-Basket' => 'Remove-Basket' ),
        array( 'iconsmind-Remove-Cart' => 'Remove-Cart' ),
        array( 'iconsmind-Remove-File' => 'Remove-File' ),
        array( 'iconsmind-Remove-User' => 'Remove-User' ),
        array( 'iconsmind-Remove-Window' => 'Remove-Window' ),
        array( 'iconsmind-Remove' => 'Remove' ),
        array( 'iconsmind-Rename' => 'Rename' ),
        array( 'iconsmind-Repair' => 'Repair' ),
        array( 'iconsmind-Repeat-2' => 'Repeat-2' ),
        array( 'iconsmind-Repeat-3' => 'Repeat-3' ),
        array( 'iconsmind-Repeat-4' => 'Repeat-4' ),
        array( 'iconsmind-Repeat-5' => 'Repeat-5' ),
        array( 'iconsmind-Repeat-6' => 'Repeat-6' ),
        array( 'iconsmind-Repeat-7' => 'Repeat-7' ),
        array( 'iconsmind-Repeat' => 'Repeat' ),
        array( 'iconsmind-Reset' => 'Reset' ),
        array( 'iconsmind-Resize' => 'Resize' ),
        array( 'iconsmind-Restore-Window' => 'Restore-Window' ),
        array( 'iconsmind-Retouching' => 'Retouching' ),
        array( 'iconsmind-Retro-Camera' => 'Retro-Camera' ),
        array( 'iconsmind-Retro' => 'Retro' ),
        array( 'iconsmind-Retweet' => 'Retweet' ),
        array( 'iconsmind-Reverbnation' => 'Reverbnation' ),
        array( 'iconsmind-Rewind' => 'Rewind' ),
        array( 'iconsmind-RGB' => 'RGB' ),
        array( 'iconsmind-Ribbon-2' => 'Ribbon-2' ),
        array( 'iconsmind-Ribbon-3' => 'Ribbon-3' ),
        array( 'iconsmind-Ribbon' => 'Ribbon' ),
        array( 'iconsmind-Right-2' => 'Right-2' ),
        array( 'iconsmind-Right-3' => 'Right-3' ),
        array( 'iconsmind-Right-4' => 'Right-4' ),
        array( 'iconsmind-Right-ToLeft' => 'Right-ToLeft' ),
        array( 'iconsmind-Right' => 'Right' ),
        array( 'iconsmind-Road-2' => 'Road-2' ),
        array( 'iconsmind-Road-3' => 'Road-3' ),
        array( 'iconsmind-Road' => 'Road' ),
        array( 'iconsmind-Robot-2' => 'Robot-2' ),
        array( 'iconsmind-Robot' => 'Robot' ),
        array( 'iconsmind-Rock-andRoll' => 'Rock-andRoll' ),
        array( 'iconsmind-Rocket' => 'Rocket' ),
        array( 'iconsmind-Roller' => 'Roller' ),
        array( 'iconsmind-Roof' => 'Roof' ),
        array( 'iconsmind-Rook' => 'Rook' ),
        array( 'iconsmind-Rotate-Gesture' => 'Rotate-Gesture' ),
        array( 'iconsmind-Rotate-Gesture2' => 'Rotate-Gesture2' ),
        array( 'iconsmind-Rotate-Gesture3' => 'Rotate-Gesture3' ),
        array( 'iconsmind-Rotation-390' => 'Rotation-390' ),
        array( 'iconsmind-Rotation' => 'Rotation' ),
        array( 'iconsmind-Router-2' => 'Router-2' ),
        array( 'iconsmind-Router' => 'Router' ),
        array( 'iconsmind-RSS' => 'RSS' ),
        array( 'iconsmind-Ruler-2' => 'Ruler-2' ),
        array( 'iconsmind-Ruler' => 'Ruler' ),
        array( 'iconsmind-Running-Shoes' => 'Running-Shoes' ),
        array( 'iconsmind-Running' => 'Running' ),
        array( 'iconsmind-Safari' => 'Safari' ),
        array( 'iconsmind-Safe-Box' => 'Safe-Box' ),
        array( 'iconsmind-Safe-Box2' => 'Safe-Box2' ),
        array( 'iconsmind-Safety-PinClose' => 'Safety-PinClose' ),
        array( 'iconsmind-Safety-PinOpen' => 'Safety-PinOpen' ),
        array( 'iconsmind-Sagittarus-2' => 'Sagittarus-2' ),
        array( 'iconsmind-Sagittarus' => 'Sagittarus' ),
        array( 'iconsmind-Sailing-Ship' => 'Sailing-Ship' ),
        array( 'iconsmind-Sand-watch' => 'Sand-watch' ),
        array( 'iconsmind-Sand-watch2' => 'Sand-watch2' ),
        array( 'iconsmind-Santa-Claus' => 'Santa-Claus' ),
        array( 'iconsmind-Santa-Claus2' => 'Santa-Claus2' ),
        array( 'iconsmind-Santa-onSled' => 'Santa-onSled' ),
        array( 'iconsmind-Satelite-2' => 'Satelite-2' ),
        array( 'iconsmind-Satelite' => 'Satelite' ),
        array( 'iconsmind-Save-Window' => 'Save-Window' ),
        array( 'iconsmind-Save' => 'Save' ),
        array( 'iconsmind-Saw' => 'Saw' ),
        array( 'iconsmind-Saxophone' => 'Saxophone' ),
        array( 'iconsmind-Scale' => 'Scale' ),
        array( 'iconsmind-Scarf' => 'Scarf' ),
        array( 'iconsmind-Scissor' => 'Scissor' ),
        array( 'iconsmind-Scooter-Front' => 'Scooter-Front' ),
        array( 'iconsmind-Scooter' => 'Scooter' ),
        array( 'iconsmind-Scorpio-2' => 'Scorpio-2' ),
        array( 'iconsmind-Scorpio' => 'Scorpio' ),
        array( 'iconsmind-Scotland' => 'Scotland' ),
        array( 'iconsmind-Screwdriver' => 'Screwdriver' ),
        array( 'iconsmind-Scroll-Fast' => 'Scroll-Fast' ),
        array( 'iconsmind-Scroll' => 'Scroll' ),
        array( 'iconsmind-Scroller-2' => 'Scroller-2' ),
        array( 'iconsmind-Scroller' => 'Scroller' ),
        array( 'iconsmind-Sea-Dog' => 'Sea-Dog' ),
        array( 'iconsmind-Search-onCloud' => 'Search-onCloud' ),
        array( 'iconsmind-Search-People' => 'Search-People' ),
        array( 'iconsmind-secound' => 'secound' ),
        array( 'iconsmind-secound2' => 'secound2' ),
        array( 'iconsmind-Security-Block' => 'Security-Block' ),
        array( 'iconsmind-Security-Bug' => 'Security-Bug' ),
        array( 'iconsmind-Security-Camera' => 'Security-Camera' ),
        array( 'iconsmind-Security-Check' => 'Security-Check' ),
        array( 'iconsmind-Security-Settings' => 'Security-Settings' ),
        array( 'iconsmind-Security-Smiley' => 'Security-Smiley' ),
        array( 'iconsmind-Securiy-Remove' => 'Securiy-Remove' ),
        array( 'iconsmind-Seed' => 'Seed' ),
        array( 'iconsmind-Selfie' => 'Selfie' ),
        array( 'iconsmind-Serbia' => 'Serbia' ),
        array( 'iconsmind-Server-2' => 'Server-2' ),
        array( 'iconsmind-Server' => 'Server' ),
        array( 'iconsmind-Servers' => 'Servers' ),
        array( 'iconsmind-Settings-Window' => 'Settings-Window' ),
        array( 'iconsmind-Sewing-Machine' => 'Sewing-Machine' ),
        array( 'iconsmind-Sexual' => 'Sexual' ),
        array( 'iconsmind-Share-onCloud' => 'Share-onCloud' ),
        array( 'iconsmind-Share-Window' => 'Share-Window' ),
        array( 'iconsmind-Share' => 'Share' ),
        array( 'iconsmind-Sharethis' => 'Sharethis' ),
        array( 'iconsmind-Shark' => 'Shark' ),
        array( 'iconsmind-Sheep' => 'Sheep' ),
        array( 'iconsmind-Sheriff-Badge' => 'Sheriff-Badge' ),
        array( 'iconsmind-Shield' => 'Shield' ),
        array( 'iconsmind-Ship-2' => 'Ship-2' ),
        array( 'iconsmind-Ship' => 'Ship' ),
        array( 'iconsmind-Shirt' => 'Shirt' ),
        array( 'iconsmind-Shoes-2' => 'Shoes-2' ),
        array( 'iconsmind-Shoes-3' => 'Shoes-3' ),
        array( 'iconsmind-Shoes' => 'Shoes' ),
        array( 'iconsmind-Shop-2' => 'Shop-2' ),
        array( 'iconsmind-Shop-3' => 'Shop-3' ),
        array( 'iconsmind-Shop-4' => 'Shop-4' ),
        array( 'iconsmind-Shop' => 'Shop' ),
        array( 'iconsmind-Shopping-Bag' => 'Shopping-Bag' ),
        array( 'iconsmind-Shopping-Basket' => 'Shopping-Basket' ),
        array( 'iconsmind-Shopping-Cart' => 'Shopping-Cart' ),
        array( 'iconsmind-Short-Pants' => 'Short-Pants' ),
        array( 'iconsmind-Shoutwire' => 'Shoutwire' ),
        array( 'iconsmind-Shovel' => 'Shovel' ),
        array( 'iconsmind-Shuffle-2' => 'Shuffle-2' ),
        array( 'iconsmind-Shuffle-3' => 'Shuffle-3' ),
        array( 'iconsmind-Shuffle-4' => 'Shuffle-4' ),
        array( 'iconsmind-Shuffle' => 'Shuffle' ),
        array( 'iconsmind-Shutter' => 'Shutter' ),
        array( 'iconsmind-Sidebar-Window' => 'Sidebar-Window' ),
        array( 'iconsmind-Signal' => 'Signal' ),
        array( 'iconsmind-Singapore' => 'Singapore' ),
        array( 'iconsmind-Skate-Shoes' => 'Skate-Shoes' ),
        array( 'iconsmind-Skateboard-2' => 'Skateboard-2' ),
        array( 'iconsmind-Skateboard' => 'Skateboard' ),
        array( 'iconsmind-Skeleton' => 'Skeleton' ),
        array( 'iconsmind-Ski' => 'Ski' ),
        array( 'iconsmind-Skirt' => 'Skirt' ),
        array( 'iconsmind-Skrill' => 'Skrill' ),
        array( 'iconsmind-Skull' => 'Skull' ),
        array( 'iconsmind-Skydiving' => 'Skydiving' ),
        array( 'iconsmind-Skype' => 'Skype' ),
        array( 'iconsmind-Sled-withGifts' => 'Sled-withGifts' ),
        array( 'iconsmind-Sled' => 'Sled' ),
        array( 'iconsmind-Sleeping' => 'Sleeping' ),
        array( 'iconsmind-Sleet' => 'Sleet' ),
        array( 'iconsmind-Slippers' => 'Slippers' ),
        array( 'iconsmind-Smart' => 'Smart' ),
        array( 'iconsmind-Smartphone-2' => 'Smartphone-2' ),
        array( 'iconsmind-Smartphone-3' => 'Smartphone-3' ),
        array( 'iconsmind-Smartphone-4' => 'Smartphone-4' ),
        array( 'iconsmind-Smartphone-Secure' => 'Smartphone-Secure' ),
        array( 'iconsmind-Smartphone' => 'Smartphone' ),
        array( 'iconsmind-Smile' => 'Smile' ),
        array( 'iconsmind-Smoking-Area' => 'Smoking-Area' ),
        array( 'iconsmind-Smoking-Pipe' => 'Smoking-Pipe' ),
        array( 'iconsmind-Snake' => 'Snake' ),
        array( 'iconsmind-Snorkel' => 'Snorkel' ),
        array( 'iconsmind-Snow-2' => 'Snow-2' ),
        array( 'iconsmind-Snow-Dome' => 'Snow-Dome' ),
        array( 'iconsmind-Snow-Storm' => 'Snow-Storm' ),
        array( 'iconsmind-Snow' => 'Snow' ),
        array( 'iconsmind-Snowflake-2' => 'Snowflake-2' ),
        array( 'iconsmind-Snowflake-3' => 'Snowflake-3' ),
        array( 'iconsmind-Snowflake-4' => 'Snowflake-4' ),
        array( 'iconsmind-Snowflake' => 'Snowflake' ),
        array( 'iconsmind-Snowman' => 'Snowman' ),
        array( 'iconsmind-Soccer-Ball' => 'Soccer-Ball' ),
        array( 'iconsmind-Soccer-Shoes' => 'Soccer-Shoes' ),
        array( 'iconsmind-Socks' => 'Socks' ),
        array( 'iconsmind-Solar' => 'Solar' ),
        array( 'iconsmind-Sound-Wave' => 'Sound-Wave' ),
        array( 'iconsmind-Sound' => 'Sound' ),
        array( 'iconsmind-Soundcloud' => 'Soundcloud' ),
        array( 'iconsmind-Soup' => 'Soup' ),
        array( 'iconsmind-South-Africa' => 'South-Africa' ),
        array( 'iconsmind-Space-Needle' => 'Space-Needle' ),
        array( 'iconsmind-Spain' => 'Spain' ),
        array( 'iconsmind-Spam-Mail' => 'Spam-Mail' ),
        array( 'iconsmind-Speach-Bubble' => 'Speach-Bubble' ),
        array( 'iconsmind-Speach-Bubble2' => 'Speach-Bubble2' ),
        array( 'iconsmind-Speach-Bubble3' => 'Speach-Bubble3' ),
        array( 'iconsmind-Speach-Bubble4' => 'Speach-Bubble4' ),
        array( 'iconsmind-Speach-Bubble5' => 'Speach-Bubble5' ),
        array( 'iconsmind-Speach-Bubble6' => 'Speach-Bubble6' ),
        array( 'iconsmind-Speach-Bubble7' => 'Speach-Bubble7' ),
        array( 'iconsmind-Speach-Bubble8' => 'Speach-Bubble8' ),
        array( 'iconsmind-Speach-Bubble9' => 'Speach-Bubble9' ),
        array( 'iconsmind-Speach-Bubble10' => 'Speach-Bubble10' ),
        array( 'iconsmind-Speach-Bubble11' => 'Speach-Bubble11' ),
        array( 'iconsmind-Speach-Bubble12' => 'Speach-Bubble12' ),
        array( 'iconsmind-Speach-Bubble13' => 'Speach-Bubble13' ),
        array( 'iconsmind-Speach-BubbleAsking' => 'Speach-BubbleAsking' ),
        array( 'iconsmind-Speach-BubbleComic' => 'Speach-BubbleComic' ),
        array( 'iconsmind-Speach-BubbleComic2' => 'Speach-BubbleComic2' ),
        array( 'iconsmind-Speach-BubbleComic3' => 'Speach-BubbleComic3' ),
        array( 'iconsmind-Speach-BubbleComic4' => 'Speach-BubbleComic4' ),
        array( 'iconsmind-Speach-BubbleDialog' => 'Speach-BubbleDialog' ),
        array( 'iconsmind-Speach-Bubbles' => 'Speach-Bubbles' ),
        array( 'iconsmind-Speak-2' => 'Speak-2' ),
        array( 'iconsmind-Speak' => 'Speak' ),
        array( 'iconsmind-Speaker-2' => 'Speaker-2' ),
        array( 'iconsmind-Speaker' => 'Speaker' ),
        array( 'iconsmind-Spell-Check' => 'Spell-Check' ),
        array( 'iconsmind-Spell-CheckABC' => 'Spell-CheckABC' ),
        array( 'iconsmind-Spermium' => 'Spermium' ),
        array( 'iconsmind-Spider' => 'Spider' ),
        array( 'iconsmind-Spiderweb' => 'Spiderweb' ),
        array( 'iconsmind-Split-FourSquareWindow' => 'Split-FourSquareWindow' ),
        array( 'iconsmind-Split-Horizontal' => 'Split-Horizontal' ),
        array( 'iconsmind-Split-Horizontal2Window' => 'Split-Horizontal2Window' ),
        array( 'iconsmind-Split-Vertical' => 'Split-Vertical' ),
        array( 'iconsmind-Split-Vertical2' => 'Split-Vertical2' ),
        array( 'iconsmind-Split-Window' => 'Split-Window' ),
        array( 'iconsmind-Spoder' => 'Spoder' ),
        array( 'iconsmind-Spoon' => 'Spoon' ),
        array( 'iconsmind-Sport-Mode' => 'Sport-Mode' ),
        array( 'iconsmind-Sports-Clothings1' => 'Sports-Clothings1' ),
        array( 'iconsmind-Sports-Clothings2' => 'Sports-Clothings2' ),
        array( 'iconsmind-Sports-Shirt' => 'Sports-Shirt' ),
        array( 'iconsmind-Spot' => 'Spot' ),
        array( 'iconsmind-Spray' => 'Spray' ),
        array( 'iconsmind-Spread' => 'Spread' ),
        array( 'iconsmind-Spring' => 'Spring' ),
        array( 'iconsmind-Spurl' => 'Spurl' ),
        array( 'iconsmind-Spy' => 'Spy' ),
        array( 'iconsmind-Squirrel' => 'Squirrel' ),
        array( 'iconsmind-SSL' => 'SSL' ),
        array( 'iconsmind-St-BasilsCathedral' => 'St-BasilsCathedral' ),
        array( 'iconsmind-St-PaulsCathedral' => 'St-PaulsCathedral' ),
        array( 'iconsmind-Stamp-2' => 'Stamp-2' ),
        array( 'iconsmind-Stamp' => 'Stamp' ),
        array( 'iconsmind-Stapler' => 'Stapler' ),
        array( 'iconsmind-Star-Track' => 'Star-Track' ),
        array( 'iconsmind-Star' => 'Star' ),
        array( 'iconsmind-Starfish' => 'Starfish' ),
        array( 'iconsmind-Start2' => 'Start2' ),
        array( 'iconsmind-Start-3' => 'Start-3' ),
        array( 'iconsmind-Start-ways' => 'Start-ways' ),
        array( 'iconsmind-Start' => 'Start' ),
        array( 'iconsmind-Statistic' => 'Statistic' ),
        array( 'iconsmind-Stethoscope' => 'Stethoscope' ),
        array( 'iconsmind-stop--2' => 'stop--2' ),
        array( 'iconsmind-Stop-Music' => 'Stop-Music' ),
        array( 'iconsmind-Stop' => 'Stop' ),
        array( 'iconsmind-Stopwatch-2' => 'Stopwatch-2' ),
        array( 'iconsmind-Stopwatch' => 'Stopwatch' ),
        array( 'iconsmind-Storm' => 'Storm' ),
        array( 'iconsmind-Street-View' => 'Street-View' ),
        array( 'iconsmind-Street-View2' => 'Street-View2' ),
        array( 'iconsmind-Strikethrough-Text' => 'Strikethrough-Text' ),
        array( 'iconsmind-Stroller' => 'Stroller' ),
        array( 'iconsmind-Structure' => 'Structure' ),
        array( 'iconsmind-Student-Female' => 'Student-Female' ),
        array( 'iconsmind-Student-Hat' => 'Student-Hat' ),
        array( 'iconsmind-Student-Hat2' => 'Student-Hat2' ),
        array( 'iconsmind-Student-Male' => 'Student-Male' ),
        array( 'iconsmind-Student-MaleFemale' => 'Student-MaleFemale' ),
        array( 'iconsmind-Students' => 'Students' ),
        array( 'iconsmind-Studio-Flash' => 'Studio-Flash' ),
        array( 'iconsmind-Studio-Lightbox' => 'Studio-Lightbox' ),
        array( 'iconsmind-Stumbleupon' => 'Stumbleupon' ),
        array( 'iconsmind-Suit' => 'Suit' ),
        array( 'iconsmind-Suitcase' => 'Suitcase' ),
        array( 'iconsmind-Sum-2' => 'Sum-2' ),
        array( 'iconsmind-Sum' => 'Sum' ),
        array( 'iconsmind-Summer' => 'Summer' ),
        array( 'iconsmind-Sun-CloudyRain' => 'Sun-CloudyRain' ),
        array( 'iconsmind-Sun' => 'Sun' ),
        array( 'iconsmind-Sunglasses-2' => 'Sunglasses-2' ),
        array( 'iconsmind-Sunglasses-3' => 'Sunglasses-3' ),
        array( 'iconsmind-Sunglasses-Smiley' => 'Sunglasses-Smiley' ),
        array( 'iconsmind-Sunglasses-Smiley2' => 'Sunglasses-Smiley2' ),
        array( 'iconsmind-Sunglasses-W' => 'Sunglasses-W' ),
        array( 'iconsmind-Sunglasses-W2' => 'Sunglasses-W2' ),
        array( 'iconsmind-Sunglasses-W3' => 'Sunglasses-W3' ),
        array( 'iconsmind-Sunglasses' => 'Sunglasses' ),
        array( 'iconsmind-Sunrise' => 'Sunrise' ),
        array( 'iconsmind-Sunset' => 'Sunset' ),
        array( 'iconsmind-Superman' => 'Superman' ),
        array( 'iconsmind-Support' => 'Support' ),
        array( 'iconsmind-Surprise' => 'Surprise' ),
        array( 'iconsmind-Sushi' => 'Sushi' ),
        array( 'iconsmind-Sweden' => 'Sweden' ),
        array( 'iconsmind-Swimming-Short' => 'Swimming-Short' ),
        array( 'iconsmind-Swimming' => 'Swimming' ),
        array( 'iconsmind-Swimmwear' => 'Swimmwear' ),
        array( 'iconsmind-Switch' => 'Switch' ),
        array( 'iconsmind-Switzerland' => 'Switzerland' ),
        array( 'iconsmind-Sync-Cloud' => 'Sync-Cloud' ),
        array( 'iconsmind-Sync' => 'Sync' ),
        array( 'iconsmind-Synchronize-2' => 'Synchronize-2' ),
        array( 'iconsmind-Synchronize' => 'Synchronize' ),
        array( 'iconsmind-T-Shirt' => 'T-Shirt' ),
        array( 'iconsmind-Tablet-2' => 'Tablet-2' ),
        array( 'iconsmind-Tablet-3' => 'Tablet-3' ),
        array( 'iconsmind-Tablet-Orientation' => 'Tablet-Orientation' ),
        array( 'iconsmind-Tablet-Phone' => 'Tablet-Phone' ),
        array( 'iconsmind-Tablet-Secure' => 'Tablet-Secure' ),
        array( 'iconsmind-Tablet-Vertical' => 'Tablet-Vertical' ),
        array( 'iconsmind-Tablet' => 'Tablet' ),
        array( 'iconsmind-Tactic' => 'Tactic' ),
        array( 'iconsmind-Tag-2' => 'Tag-2' ),
        array( 'iconsmind-Tag-3' => 'Tag-3' ),
        array( 'iconsmind-Tag-4' => 'Tag-4' ),
        array( 'iconsmind-Tag-5' => 'Tag-5' ),
        array( 'iconsmind-Tag' => 'Tag' ),
        array( 'iconsmind-Taj-Mahal' => 'Taj-Mahal' ),
        array( 'iconsmind-Talk-Man' => 'Talk-Man' ),
        array( 'iconsmind-Tap' => 'Tap' ),
        array( 'iconsmind-Target-Market' => 'Target-Market' ),
        array( 'iconsmind-Target' => 'Target' ),
        array( 'iconsmind-Taurus-2' => 'Taurus-2' ),
        array( 'iconsmind-Taurus' => 'Taurus' ),
        array( 'iconsmind-Taxi-2' => 'Taxi-2' ),
        array( 'iconsmind-Taxi-Sign' => 'Taxi-Sign' ),
        array( 'iconsmind-Taxi' => 'Taxi' ),
        array( 'iconsmind-Teacher' => 'Teacher' ),
        array( 'iconsmind-Teapot' => 'Teapot' ),
        array( 'iconsmind-Technorati' => 'Technorati' ),
        array( 'iconsmind-Teddy-Bear' => 'Teddy-Bear' ),
        array( 'iconsmind-Tee-Mug' => 'Tee-Mug' ),
        array( 'iconsmind-Telephone-2' => 'Telephone-2' ),
        array( 'iconsmind-Telephone' => 'Telephone' ),
        array( 'iconsmind-Telescope' => 'Telescope' ),
        array( 'iconsmind-Temperature-2' => 'Temperature-2' ),
        array( 'iconsmind-Temperature-3' => 'Temperature-3' ),
        array( 'iconsmind-Temperature' => 'Temperature' ),
        array( 'iconsmind-Temple' => 'Temple' ),
        array( 'iconsmind-Tennis-Ball' => 'Tennis-Ball' ),
        array( 'iconsmind-Tennis' => 'Tennis' ),
        array( 'iconsmind-Tent' => 'Tent' ),
        array( 'iconsmind-Test-Tube' => 'Test-Tube' ),
        array( 'iconsmind-Test-Tube2' => 'Test-Tube2' ),
        array( 'iconsmind-Testimonal' => 'Testimonal' ),
        array( 'iconsmind-Text-Box' => 'Text-Box' ),
        array( 'iconsmind-Text-Effect' => 'Text-Effect' ),
        array( 'iconsmind-Text-HighlightColor' => 'Text-HighlightColor' ),
        array( 'iconsmind-Text-Paragraph' => 'Text-Paragraph' ),
        array( 'iconsmind-Thailand' => 'Thailand' ),
        array( 'iconsmind-The-WhiteHouse' => 'The-WhiteHouse' ),
        array( 'iconsmind-This-SideUp' => 'This-SideUp' ),
        array( 'iconsmind-Thread' => 'Thread' ),
        array( 'iconsmind-Three-ArrowFork' => 'Three-ArrowFork' ),
        array( 'iconsmind-Three-Fingers' => 'Three-Fingers' ),
        array( 'iconsmind-Three-FingersDrag' => 'Three-FingersDrag' ),
        array( 'iconsmind-Three-FingersDrag2' => 'Three-FingersDrag2' ),
        array( 'iconsmind-Three-FingersTouch' => 'Three-FingersTouch' ),
        array( 'iconsmind-Thumb' => 'Thumb' ),
        array( 'iconsmind-Thumbs-DownSmiley' => 'Thumbs-DownSmiley' ),
        array( 'iconsmind-Thumbs-UpSmiley' => 'Thumbs-UpSmiley' ),
        array( 'iconsmind-Thunder' => 'Thunder' ),
        array( 'iconsmind-Thunderstorm' => 'Thunderstorm' ),
        array( 'iconsmind-Ticket' => 'Ticket' ),
        array( 'iconsmind-Tie-2' => 'Tie-2' ),
        array( 'iconsmind-Tie-3' => 'Tie-3' ),
        array( 'iconsmind-Tie-4' => 'Tie-4' ),
        array( 'iconsmind-Tie' => 'Tie' ),
        array( 'iconsmind-Tiger' => 'Tiger' ),
        array( 'iconsmind-Time-Backup' => 'Time-Backup' ),
        array( 'iconsmind-Time-Bomb' => 'Time-Bomb' ),
        array( 'iconsmind-Time-Clock' => 'Time-Clock' ),
        array( 'iconsmind-Time-Fire' => 'Time-Fire' ),
        array( 'iconsmind-Time-Machine' => 'Time-Machine' ),
        array( 'iconsmind-Time-Window' => 'Time-Window' ),
        array( 'iconsmind-Timer-2' => 'Timer-2' ),
        array( 'iconsmind-Timer' => 'Timer' ),
        array( 'iconsmind-To-Bottom' => 'To-Bottom' ),
        array( 'iconsmind-To-Bottom2' => 'To-Bottom2' ),
        array( 'iconsmind-To-Left' => 'To-Left' ),
        array( 'iconsmind-To-Right' => 'To-Right' ),
        array( 'iconsmind-To-Top' => 'To-Top' ),
        array( 'iconsmind-To-Top2' => 'To-Top2' ),
        array( 'iconsmind-Token-' => 'Token-' ),
        array( 'iconsmind-Tomato' => 'Tomato' ),
        array( 'iconsmind-Tongue' => 'Tongue' ),
        array( 'iconsmind-Tooth-2' => 'Tooth-2' ),
        array( 'iconsmind-Tooth' => 'Tooth' ),
        array( 'iconsmind-Top-ToBottom' => 'Top-ToBottom' ),
        array( 'iconsmind-Touch-Window' => 'Touch-Window' ),
        array( 'iconsmind-Tourch' => 'Tourch' ),
        array( 'iconsmind-Tower-2' => 'Tower-2' ),
        array( 'iconsmind-Tower-Bridge' => 'Tower-Bridge' ),
        array( 'iconsmind-Tower' => 'Tower' ),
        array( 'iconsmind-Trace' => 'Trace' ),
        array( 'iconsmind-Tractor' => 'Tractor' ),
        array( 'iconsmind-traffic-Light' => 'traffic-Light' ),
        array( 'iconsmind-Traffic-Light2' => 'Traffic-Light2' ),
        array( 'iconsmind-Train-2' => 'Train-2' ),
        array( 'iconsmind-Train' => 'Train' ),
        array( 'iconsmind-Tram' => 'Tram' ),
        array( 'iconsmind-Transform-2' => 'Transform-2' ),
        array( 'iconsmind-Transform-3' => 'Transform-3' ),
        array( 'iconsmind-Transform-4' => 'Transform-4' ),
        array( 'iconsmind-Transform' => 'Transform' ),
        array( 'iconsmind-Trash-withMen' => 'Trash-withMen' ),
        array( 'iconsmind-Tree-2' => 'Tree-2' ),
        array( 'iconsmind-Tree-3' => 'Tree-3' ),
        array( 'iconsmind-Tree-4' => 'Tree-4' ),
        array( 'iconsmind-Tree-5' => 'Tree-5' ),
        array( 'iconsmind-Tree' => 'Tree' ),
        array( 'iconsmind-Trekking' => 'Trekking' ),
        array( 'iconsmind-Triangle-ArrowDown' => 'Triangle-ArrowDown' ),
        array( 'iconsmind-Triangle-ArrowLeft' => 'Triangle-ArrowLeft' ),
        array( 'iconsmind-Triangle-ArrowRight' => 'Triangle-ArrowRight' ),
        array( 'iconsmind-Triangle-ArrowUp' => 'Triangle-ArrowUp' ),
        array( 'iconsmind-Tripod-2' => 'Tripod-2' ),
        array( 'iconsmind-Tripod-andVideo' => 'Tripod-andVideo' ),
        array( 'iconsmind-Tripod-withCamera' => 'Tripod-withCamera' ),
        array( 'iconsmind-Tripod-withGopro' => 'Tripod-withGopro' ),
        array( 'iconsmind-Trophy-2' => 'Trophy-2' ),
        array( 'iconsmind-Trophy' => 'Trophy' ),
        array( 'iconsmind-Truck' => 'Truck' ),
        array( 'iconsmind-Trumpet' => 'Trumpet' ),
        array( 'iconsmind-Tumblr' => 'Tumblr' ),
        array( 'iconsmind-Turkey' => 'Turkey' ),
        array( 'iconsmind-Turn-Down' => 'Turn-Down' ),
        array( 'iconsmind-Turn-Down2' => 'Turn-Down2' ),
        array( 'iconsmind-Turn-DownFromLeft' => 'Turn-DownFromLeft' ),
        array( 'iconsmind-Turn-DownFromRight' => 'Turn-DownFromRight' ),
        array( 'iconsmind-Turn-Left' => 'Turn-Left' ),
        array( 'iconsmind-Turn-Left3' => 'Turn-Left3' ),
        array( 'iconsmind-Turn-Right' => 'Turn-Right' ),
        array( 'iconsmind-Turn-Right3' => 'Turn-Right3' ),
        array( 'iconsmind-Turn-Up' => 'Turn-Up' ),
        array( 'iconsmind-Turn-Up2' => 'Turn-Up2' ),
        array( 'iconsmind-Turtle' => 'Turtle' ),
        array( 'iconsmind-Tuxedo' => 'Tuxedo' ),
        array( 'iconsmind-TV' => 'TV' ),
        array( 'iconsmind-Twister' => 'Twister' ),
        array( 'iconsmind-Twitter-2' => 'Twitter-2' ),
        array( 'iconsmind-Twitter' => 'Twitter' ),
        array( 'iconsmind-Two-Fingers' => 'Two-Fingers' ),
        array( 'iconsmind-Two-FingersDrag' => 'Two-FingersDrag' ),
        array( 'iconsmind-Two-FingersDrag2' => 'Two-FingersDrag2' ),
        array( 'iconsmind-Two-FingersScroll' => 'Two-FingersScroll' ),
        array( 'iconsmind-Two-FingersTouch' => 'Two-FingersTouch' ),
        array( 'iconsmind-Two-Windows' => 'Two-Windows' ),
        array( 'iconsmind-Type-Pass' => 'Type-Pass' ),
        array( 'iconsmind-Ukraine' => 'Ukraine' ),
        array( 'iconsmind-Umbrela' => 'Umbrela' ),
        array( 'iconsmind-Umbrella-2' => 'Umbrella-2' ),
        array( 'iconsmind-Umbrella-3' => 'Umbrella-3' ),
        array( 'iconsmind-Under-LineText' => 'Under-LineText' ),
        array( 'iconsmind-Undo' => 'Undo' ),
        array( 'iconsmind-United-Kingdom' => 'United-Kingdom' ),
        array( 'iconsmind-United-States' => 'United-States' ),
        array( 'iconsmind-University-2' => 'University-2' ),
        array( 'iconsmind-University' => 'University' ),
        array( 'iconsmind-Unlike-2' => 'Unlike-2' ),
        array( 'iconsmind-Unlike' => 'Unlike' ),
        array( 'iconsmind-Unlock-2' => 'Unlock-2' ),
        array( 'iconsmind-Unlock-3' => 'Unlock-3' ),
        array( 'iconsmind-Unlock' => 'Unlock' ),
        array( 'iconsmind-Up--Down' => 'Up--Down' ),
        array( 'iconsmind-Up--Down3' => 'Up--Down3' ),
        array( 'iconsmind-Up-2' => 'Up-2' ),
        array( 'iconsmind-Up-3' => 'Up-3' ),
        array( 'iconsmind-Up-4' => 'Up-4' ),
        array( 'iconsmind-Up' => 'Up' ),
        array( 'iconsmind-Upgrade' => 'Upgrade' ),
        array( 'iconsmind-Upload-2' => 'Upload-2' ),
        array( 'iconsmind-Upload-toCloud' => 'Upload-toCloud' ),
        array( 'iconsmind-Upload-Window' => 'Upload-Window' ),
        array( 'iconsmind-Upload' => 'Upload' ),
        array( 'iconsmind-Uppercase-Text' => 'Uppercase-Text' ),
        array( 'iconsmind-Upward' => 'Upward' ),
        array( 'iconsmind-URL-Window' => 'URL-Window' ),
        array( 'iconsmind-Usb-2' => 'Usb-2' ),
        array( 'iconsmind-Usb-Cable' => 'Usb-Cable' ),
        array( 'iconsmind-Usb' => 'Usb' ),
        array( 'iconsmind-User' => 'User' ),
        array( 'iconsmind-Ustream' => 'Ustream' ),
        array( 'iconsmind-Vase' => 'Vase' ),
        array( 'iconsmind-Vector-2' => 'Vector-2' ),
        array( 'iconsmind-Vector-3' => 'Vector-3' ),
        array( 'iconsmind-Vector-4' => 'Vector-4' ),
        array( 'iconsmind-Vector-5' => 'Vector-5' ),
        array( 'iconsmind-Vector' => 'Vector' ),
        array( 'iconsmind-Venn-Diagram' => 'Venn-Diagram' ),
        array( 'iconsmind-Vest-2' => 'Vest-2' ),
        array( 'iconsmind-Vest' => 'Vest' ),
        array( 'iconsmind-Viddler' => 'Viddler' ),
        array( 'iconsmind-Video-2' => 'Video-2' ),
        array( 'iconsmind-Video-3' => 'Video-3' ),
        array( 'iconsmind-Video-4' => 'Video-4' ),
        array( 'iconsmind-Video-5' => 'Video-5' ),
        array( 'iconsmind-Video-6' => 'Video-6' ),
        array( 'iconsmind-Video-GameController' => 'Video-GameController' ),
        array( 'iconsmind-Video-Len' => 'Video-Len' ),
        array( 'iconsmind-Video-Len2' => 'Video-Len2' ),
        array( 'iconsmind-Video-Photographer' => 'Video-Photographer' ),
        array( 'iconsmind-Video-Tripod' => 'Video-Tripod' ),
        array( 'iconsmind-Video' => 'Video' ),
        array( 'iconsmind-Vietnam' => 'Vietnam' ),
        array( 'iconsmind-View-Height' => 'View-Height' ),
        array( 'iconsmind-View-Width' => 'View-Width' ),
        array( 'iconsmind-Vimeo' => 'Vimeo' ),
        array( 'iconsmind-Virgo-2' => 'Virgo-2' ),
        array( 'iconsmind-Virgo' => 'Virgo' ),
        array( 'iconsmind-Virus-2' => 'Virus-2' ),
        array( 'iconsmind-Virus-3' => 'Virus-3' ),
        array( 'iconsmind-Virus' => 'Virus' ),
        array( 'iconsmind-Visa' => 'Visa' ),
        array( 'iconsmind-Voice' => 'Voice' ),
        array( 'iconsmind-Voicemail' => 'Voicemail' ),
        array( 'iconsmind-Volleyball' => 'Volleyball' ),
        array( 'iconsmind-Volume-Down' => 'Volume-Down' ),
        array( 'iconsmind-Volume-Up' => 'Volume-Up' ),
        array( 'iconsmind-VPN' => 'VPN' ),
        array( 'iconsmind-Wacom-Tablet' => 'Wacom-Tablet' ),
        array( 'iconsmind-Waiter' => 'Waiter' ),
        array( 'iconsmind-Walkie-Talkie' => 'Walkie-Talkie' ),
        array( 'iconsmind-Wallet-2' => 'Wallet-2' ),
        array( 'iconsmind-Wallet-3' => 'Wallet-3' ),
        array( 'iconsmind-Wallet' => 'Wallet' ),
        array( 'iconsmind-Warehouse' => 'Warehouse' ),
        array( 'iconsmind-Warning-Window' => 'Warning-Window' ),
        array( 'iconsmind-Watch-2' => 'Watch-2' ),
        array( 'iconsmind-Watch-3' => 'Watch-3' ),
        array( 'iconsmind-Watch' => 'Watch' ),
        array( 'iconsmind-Wave-2' => 'Wave-2' ),
        array( 'iconsmind-Wave' => 'Wave' ),
        array( 'iconsmind-Webcam' => 'Webcam' ),
        array( 'iconsmind-weight-Lift' => 'weight-Lift' ),
        array( 'iconsmind-Wheelbarrow' => 'Wheelbarrow' ),
        array( 'iconsmind-Wheelchair' => 'Wheelchair' ),
        array( 'iconsmind-Width-Window' => 'Width-Window' ),
        array( 'iconsmind-Wifi-2' => 'Wifi-2' ),
        array( 'iconsmind-Wifi-Keyboard' => 'Wifi-Keyboard' ),
        array( 'iconsmind-Wifi' => 'Wifi' ),
        array( 'iconsmind-Wind-Turbine' => 'Wind-Turbine' ),
        array( 'iconsmind-Windmill' => 'Windmill' ),
        array( 'iconsmind-Window-2' => 'Window-2' ),
        array( 'iconsmind-Window' => 'Window' ),
        array( 'iconsmind-Windows-2' => 'Windows-2' ),
        array( 'iconsmind-Windows-Microsoft' => 'Windows-Microsoft' ),
        array( 'iconsmind-Windows' => 'Windows' ),
        array( 'iconsmind-Windsock' => 'Windsock' ),
        array( 'iconsmind-Windy' => 'Windy' ),
        array( 'iconsmind-Wine-Bottle' => 'Wine-Bottle' ),
        array( 'iconsmind-Wine-Glass' => 'Wine-Glass' ),
        array( 'iconsmind-Wink' => 'Wink' ),
        array( 'iconsmind-Winter-2' => 'Winter-2' ),
        array( 'iconsmind-Winter' => 'Winter' ),
        array( 'iconsmind-Wireless' => 'Wireless' ),
        array( 'iconsmind-Witch-Hat' => 'Witch-Hat' ),
        array( 'iconsmind-Witch' => 'Witch' ),
        array( 'iconsmind-Wizard' => 'Wizard' ),
        array( 'iconsmind-Wolf' => 'Wolf' ),
        array( 'iconsmind-Woman-Sign' => 'Woman-Sign' ),
        array( 'iconsmind-WomanMan' => 'WomanMan' ),
        array( 'iconsmind-Womans-Underwear' => 'Womans-Underwear' ),
        array( 'iconsmind-Womans-Underwear2' => 'Womans-Underwear2' ),
        array( 'iconsmind-Women' => 'Women' ),
        array( 'iconsmind-Wonder-Woman' => 'Wonder-Woman' ),
        array( 'iconsmind-Wordpress' => 'Wordpress' ),
        array( 'iconsmind-Worker-Clothes' => 'Worker-Clothes' ),
        array( 'iconsmind-Worker' => 'Worker' ),
        array( 'iconsmind-Wrap-Text' => 'Wrap-Text' ),
        array( 'iconsmind-Wreath' => 'Wreath' ),
        array( 'iconsmind-Wrench' => 'Wrench' ),
        array( 'iconsmind-X-Box' => 'X-Box' ),
        array( 'iconsmind-X-ray' => 'X-ray' ),
        array( 'iconsmind-Xanga' => 'Xanga' ),
        array( 'iconsmind-Xing' => 'Xing' ),
        array( 'iconsmind-Yacht' => 'Yacht' ),
        array( 'iconsmind-Yahoo-Buzz' => 'Yahoo-Buzz' ),
        array( 'iconsmind-Yahoo' => 'Yahoo' ),
        array( 'iconsmind-Yelp' => 'Yelp' ),
        array( 'iconsmind-Yes' => 'Yes' ),
        array( 'iconsmind-Ying-Yang' => 'Ying-Yang' ),
        array( 'iconsmind-Youtube' => 'Youtube' ),
        array( 'iconsmind-Z-A' => 'Z-A' ),
        array( 'iconsmind-Zebra' => 'Zebra' ),
        array( 'iconsmind-Zombie' => 'Zombie' ),
        array( 'iconsmind-Zoom-Gesture' => 'Zoom-Gesture' ),
        array( 'iconsmind-Zootool' => 'Zootool' ),
    );

    return array_merge( $icons, $iconsmind_icons );
}

add_filter( 'vc_iconpicker-type-iconsmind', 'cariera_iconpicker_type_iconsmind' );





/**
 * Getting strings as a bool
 *
 * @since  1.3.0
 */
function cariera_string_to_bool( $value ) {
    return ( is_bool( $value ) && $value ) || in_array( $value, array( '1', 'true', 'yes' ) ) ? true : false;
}





/**
 * Getting partitions
 *
 * @since  1.3.0
 */
function cariera_partition( $list, $p ) {
    $listlen = count( $list );
    $partlen = floor( $listlen / $p );
    $partrem = $listlen % $p;
    $partition = array();
    $mark = 0;
    for ($px = 0; $px < $p; $px++) {
        $incr = ($px < $partrem) ? $partlen + 1 : $partlen;
        $partition[$px] = array_slice( $list, $mark, $incr );
        $mark += $incr;
    }
    return $partition;
}





/**
 * Get Product Categories
 *
 * @since  1.3.2
 */
function cariera_get_categories( $taxonomy = 'category' ) {
    $output     = array();
    $categories = get_terms( $taxonomy, 'hide_empty=0' );
    if ( ! is_wp_error( $categories ) && $categories ) {
        foreach ( $categories as $category ) {
            if ( $category ) {
                $output[] = array(
                    'value' => $category->slug,
                    'label' => $category->name,
                );
            }
        }
    }

    return $output;
}





/**
 * Count Posts based on their status
 *
 * @since  1.3.4
 */
function cariera_count_user_posts_by_status( $post_author=null, $post_type=array(), $post_status=array() ) {
    global $wpdb;

    if(empty($post_author)) {
        return 0;
    }

    $post_status = (array) $post_status;
    $post_type = (array) $post_type;

    $sql = $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->posts WHERE post_author = %d AND ", $post_author );

    //Post status
    if(!empty($post_status)){
        $argtype = array_fill(0, count($post_status), '%s');
        $where = "(post_status=".implode( " OR post_status=", $argtype).') AND ';
        $sql .= $wpdb->prepare($where,$post_status);
    }

    //Post type
    if(!empty($post_type)){
        $argtype = array_fill(0, count($post_type), '%s');
        $where = "(post_type=".implode( " OR post_type=", $argtype).') AND ';
        $sql .= $wpdb->prepare($where,$post_type);
    }

    $sql .='1=1';
    $count = $wpdb->get_var($sql);

    return $count;
}





/**
 * Get data in Database
 *
 * @since  1.3.4
 */
if( !function_exists('cariera_get_data_from_db') ){
    function cariera_get_data_from_db($table, $data, $condition){
        global $wpdb;

        $dbprefix = $wpdb->prefix;

        $table = $dbprefix.$table;
        if($wpdb->get_var("SHOW TABLES LIKE '$table'") == $table) {
            $query  = "";
            $query  = "SELECT $data from $table WHERE $condition ORDER BY main_id DESC";
            $result = $wpdb->get_results( $query);
            return $result;
        }
        
        return;
    }
}





/**
 * Insert data in Database
 *
 * @since  1.3.4
 */
if( !function_exists('cariera_insert_data_in_db') ) {
    function cariera_insert_data_in_db($table, $dataArray) {
        global $wpdb;
        
        $dbprefix   = $wpdb->prefix;
        $table      = $dbprefix.$table;
        $result     = $wpdb->insert( $table, $dataArray, $format = null );

        if(!empty($result) && $result > 0){
            return true;
        } else{
            return false;
        }
    }
}





/**
 * Update data in Database
 *
 * @since  1.3.4
 */
if( !function_exists('cariera_update_data_in_db') ){
    function cariera_update_data_in_db($table, $data, $where){
        global $wpdb;
        
        $dbprefix   = $wpdb->prefix;
        $table      = $dbprefix.$table;

        $result = $wpdb->update( $table, $data, $where, $format = null, $where_format = null );
        if(!empty($result) && $result > 0){
            return true;
        } else {
            return false;
        }
    }
}





/**
 * Get days of the Month
 *
 * @since  1.3.4
 */
if(!function_exists('cariera_get_days_of_month')){
    function cariera_get_days_of_month($month, $year) {
        
        $num = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $dates_month = array();

        for ($i = 1; $i <= $num; $i++) {
            $mktime = mktime(0, 0, 0, $month, $i, $year);
            $date = date("Y-m-d", $mktime);
            $date = strtotime($date);
            $dates_month[$i] = $date;
        }

        return $dates_month;
    }
}





/**
 * Hide WP Admin Bar
 *
 * @since  1.3.7
 */
function cariera_disable_admin_bar() {
   if ( !current_user_can('administrator') ) {
     show_admin_bar(false); 
   }
}

add_action( 'after_setup_theme', 'cariera_disable_admin_bar' );





/**
 * Remove WPJM Admin notices
 *
 * @since  1.3.8
 */
function cariera_remove_wpjm_notices() {
    if ( class_exists('WP_Job_Manager_Helper') ) {
        //remove_action( 'plugin_action_links', array( WP_Job_Manager_Helper::instance(), 'plugin_links' ), 10, 2 );
        remove_action( 'admin_notices', array( WP_Job_Manager_Helper::instance(), 'licence_error_notices' ) );
    }
}

add_action( 'admin_init', 'cariera_remove_wpjm_notices' );





/**
 * Get Currency Symbol
 *
 * @since 1.0.0
 */
if ( !function_exists('cariera_currency_symbol') ) {
    function cariera_currency_symbol( $currency = '' ) {
        if ( ! $currency ) {
            $currency = get_option('cariera_currency_setting');
        }

        switch ( $currency ) {
            case 'BHD' :
                $currency_symbol = esc_html__('.د.ب', 'cariera');
                break;
            case 'AED' :
                $currency_symbol = esc_html__('د.إ', 'cariera');
                break;
            case 'AUD' :
            case 'ARS' :
            case 'CAD' :
            case 'CLP' :
            case 'COP' :
            case 'HKD' :
            case 'MXN' :
            case 'NZD' :
            case 'SGD' :
            case 'USD' :
                $currency_symbol = esc_html__('&#36;', 'cariera');
                break;
            case 'BDT':
                $currency_symbol = esc_html__('&#2547;&nbsp;', 'cariera');
                break;
            case 'LKR':
                $currency_symbol = esc_html__('&#3515;&#3540;&nbsp;', 'cariera');
                break;
            case 'BGN' :
                $currency_symbol = esc_html__('&#1083;&#1074;.', 'cariera');
                break;
            case 'BRL' :
                $currency_symbol = esc_html__('&#82;&#36;', 'cariera');
                break;
            case 'CHF' :
                $currency_symbol = esc_html__('&#67;&#72;&#70;', 'cariera');
                break;
            case 'CNY' :
            case 'JPY' :
            case 'RMB' :
                $currency_symbol = esc_html__('&yen;', 'cariera');
                break;
            case 'CZK' :
                $currency_symbol = esc_html__('&#75;&#269;', 'cariera');
                break;
            case 'DKK' :
                $currency_symbol = esc_html__('DKK', 'cariera');
                break;
            case 'DOP' :
                $currency_symbol = esc_html__('RD&#36;', 'cariera');
                break;
            case 'EGP' :
                $currency_symbol = esc_html__('EGP', 'cariera');
                break;
            case 'EUR' :
                $currency_symbol = esc_html__('&euro;', 'cariera');
                break;
            case 'GBP' :
                $currency_symbol = esc_html__('&pound;', 'cariera');
                break;
            case 'HRK' :
                $currency_symbol = esc_html__('Kn', 'cariera');
                break;
            case 'HUF' :
                $currency_symbol = esc_html__('&#70;&#116;', 'cariera');
                break;
            case 'IDR' :
                $currency_symbol = esc_html__('Rp', 'cariera');
                break;
            case 'ILS' :
                $currency_symbol = esc_html__('&#8362;', 'cariera');
                break;
            case 'INR' :
                $currency_symbol = esc_html__('Rs.', 'cariera');
                break;
            case 'ISK' :
                $currency_symbol = esc_html__('Kr.', 'cariera');
                break;
            case 'KIP' :
                $currency_symbol = esc_html__('&#8365;', 'cariera');
                break;
            case 'KRW' :
                $currency_symbol = esc_html__('&#8361;', 'cariera');
                break;
            case 'MYR' :
                $currency_symbol = esc_html__('&#82;&#77;', 'cariera');
                break;
            case 'NGN' :
                $currency_symbol = esc_html__('&#8358;', 'cariera');
                break;
            case 'NOK' :
                $currency_symbol = esc_html__('&#107;&#114;', 'cariera');
                break;
            case 'NPR' :
                $currency_symbol = esc_html__('Rs.', 'cariera');
                break;
            case 'PHP' :
                $currency_symbol = esc_html__('&#8369;', 'cariera');
                break;
            case 'PLN' :
                $currency_symbol = esc_html__('&#122;&#322;', 'cariera');
                break;
            case 'PYG' :
                $currency_symbol = esc_html__('&#8370;', 'cariera');
                break;
            case 'RON' :
                $currency_symbol = esc_html__('lei', 'cariera');
                break;
            case 'RUB' :
                $currency_symbol = esc_html__('&#1088;&#1091;&#1073;.', 'cariera');
                break;
            case 'SEK' :
                $currency_symbol = esc_html__('&#107;&#114;', 'cariera');
                break;
            case 'THB' :
                $currency_symbol = esc_html__('&#3647;', 'cariera');
                break;
            case 'TRY' :
                $currency_symbol = esc_html__('&#8378;', 'cariera');
                break;
            case 'TWD' :
                $currency_symbol = esc_html__('&#78;&#84;&#36;', 'cariera');
                break;
            case 'UAH' :
                $currency_symbol = esc_html__('&#8372;', 'cariera');
                break;
            case 'VND' :
                $currency_symbol = esc_html__('&#8363;', 'cariera');
                break;
            case 'ZAR' :
                $currency_symbol = esc_html__('&#82;', 'cariera');
                break;
            default :
                $currency_symbol = esc_html__('', 'cariera');
                break;
        }

        return apply_filters( 'woocommerce_currency_symbol', $currency_symbol, $currency );
    }
}





/**
 * Cariera get option function to avoid site error when another theme has been activated
 *
 * @since  1.4.3
 */
if ( !function_exists('cariera_get_option') ) {
    function cariera_get_option( $name ) {
        global $cariera_customize;

        $value = false;

        if ( class_exists( 'Kirki' ) ) {
            $value = Kirki::get_option( 'cariera', $name );
        } elseif ( ! empty( $cariera_customize ) ) {
            $value = $cariera_customize->get_option( $name );
        }

        return apply_filters( 'cariera_get_option', $value, $name );
    }
}






/**
 * Get Contact Form 7 forms
 *
 * @since  1.4.3
 */
if ( ! function_exists( 'cariera_get_forms' ) ) {
    function cariera_get_forms() {
        $forms  = array( 0 => esc_html__( 'Please select a form', 'cariera' ) );

        if (function_exists('wpcf7')) {
            $_forms = get_posts(
                array(
                    'numberposts' => -1,
                    'post_type'   => 'wpcf7_contact_form',
                )
            );

            if ( ! empty( $_forms ) ) {

                foreach ( $_forms as $_form ) {
                    $forms[ $_form->ID ] = $_form->post_title;
                }
            }
        }

        return $forms;
    }
}





/**
 * Job, Resume & Company listings map shortcode
 *
 * @since  1.4.3
 */
if ( !function_exists( 'cariera_job_resume_map' ) ) {
	function cariera_job_resume_map( $atts ) {
        
        extract(shortcode_atts(array(
			'type'         => 'job_listing',
            'map_height'   => '450px',
            'class'        => '',
		), $atts));
		
		
        $html = do_shortcode('[cariera-map type="' . $type . '" class="' . $class . '" height="' . $map_height . '"]');

        return $html;
	}
}

add_shortcode( 'job_resume_map', 'cariera_job_resume_map' );





/**
 * Gecoding addresses
 *
 * @since  1.4.3
 */
function cariera_geocode($address){
 
    // url encode the address
    $address = urlencode($address);

    // Cariera Google API Key
    $api_key = cariera_get_option( 'cariera_gmap_api_key' );

    // WPJM Google API Key if Cariera Google API Key doesn't exist
    if( empty($api_key) ){
        $api_key = get_option( 'job_manager_google_maps_api_key' );
    }

    // Country Restrictions
    $limit_country = cariera_get_option( 'cariera_map_restriction');
    
    if($limit_country) {
        $url = "https://maps.google.com/maps/api/geocode/json?address={$address}&key={$api_key}&components=country:".$limit_country;
    } else {
        $url = "https://maps.google.com/maps/api/geocode/json?address={$address}&key={$api_key}";
    }

    // JSON Response
    $resp_json = wp_remote_get($url);

    $file = 'wp-content/geocode.txt';    
 
    $resp_json = wp_remote_get($url);
    $resp = json_decode( wp_remote_retrieve_body( $resp_json ), true );


    if( $resp['status'] == 'OK' ) {
 
        // get the important data
        $lat                = $resp['results'][0]['geometry']['location']['lat'];
        $long               = $resp['results'][0]['geometry']['location']['lng'];
        $formatted_address  = $resp['results'][0]['formatted_address'];
         
        // verify if data is complete
        if($lat && $long && $formatted_address){
         
            // put the data in the array
            $data_arr = array();            
             
            array_push(
                $data_arr, 
                $lat, 
                $long, 
                $formatted_address
            );
             
            return $data_arr;
        } else {
            return false;
        }
    } else {
        return false;
    }
}





/**
 * Get nearby listings based on the location
 *
 * @since  1.4.3
 */
function cariera_get_nearby_listings( $lat, $lng, $distance, $radius_type ) {
    global $wpdb;

    if( $radius_type == 'km' ) {
        $ratio = 6371;
    } else {
        $ratio = 3959;
    }

    $post_ids = 
            $wpdb->get_results(
                $wpdb->prepare( "
            SELECT DISTINCT
                    geolocation_lat.post_id,
                    geolocation_lat.meta_key,
                    geolocation_lat.meta_value as jobLat,
                    geolocation_long.meta_value as jobLong,
                    ( %d * acos( cos( radians( %f ) ) * cos( radians( geolocation_lat.meta_value ) ) * cos( radians( geolocation_long.meta_value ) - radians( %f ) ) + sin( radians( %f ) ) * sin( radians( geolocation_lat.meta_value ) ) ) ) AS distance 
            
                FROM 
                    $wpdb->postmeta AS geolocation_lat
                    LEFT JOIN $wpdb->postmeta as geolocation_long ON geolocation_lat.post_id = geolocation_long.post_id
                    WHERE geolocation_lat.meta_key = 'geolocation_lat' AND geolocation_long.meta_key = 'geolocation_long'
                    HAVING distance < %d
            ", 
            $ratio, 
            $lat, 
            $lng, 
            $lat, 
            $distance)
        ,ARRAY_A);

    return $post_ids;
}





/**
 * Sort by column
 *
 * @since  1.4.3
 */
function cariera_array_sort_by_column(&$arr, $col, $dir = SORT_ASC) {
    $sort_col = array();
    foreach ($arr as $key=> $row) {
        $sort_col[$key] = $row[$col];
    }

    array_multisort($sort_col, $dir, $arr);
}





/**
 * Job Search form shortcode
 *
 * @since  1.4.5
 */
if ( !function_exists( 'cariera_job_search_form' ) ) {
	function cariera_job_search_form( $atts, $content = null ) {
		 extract(shortcode_atts(array(
            'search_style'  => 'stlye-1',
		    'location'      => '',
            'region'        => '',
		    'categories'    => '',
        ), $atts));
        

		// Location field
		if( !empty($location) ) {
			$location = '<div class="search-location"><input type="text" id="search_location" name="search_location" placeholder="' . esc_html__("Location", "cariera") . '"><div class="geolocation"><i class="geolocate"></i></div></div>';
		}
        
        
        // Regions Field
        if ( class_exists('Astoundify_Job_Manager_Regions') ) {
            if( !empty($region) ) {

                ob_start(); ?>

                    <div class="search-region">
                        <?php 
                        wp_dropdown_categories( apply_filters( 'job_manager_regions_dropdown_args', array(
                            'show_option_all'   => esc_html__( 'All Regions', 'cariera' ),
                            'hierarchical'      => true,
                            'orderby'           => 'name',
                            'taxonomy'          => 'job_listing_region',
                            'name'              => 'search_region',
                            'class'             => 'search_region',
                            'hide_empty'        => 0,
                            'selected'          => isset( $atts[ 'selected_region' ] ) ? $atts[ 'selected_region' ] : ''
                        ) ) ); ?>
                    </div>

                <?php
                $region = ob_get_clean();
            }
        } else {
            $region = '';
        }

        
		// Categories dropdown
		if( !empty($categories) ) {
			ob_start(); ?>

                <div class="search-categories">
                    
                    <?php
                    cariera_job_manager_dropdown_category( array( 
                        'taxonomy'          => 'job_listing_category',
                        'hierarchical'      => 1, 
                        'show_option_all'   => esc_html__( 'Any category', 'cariera' ), 
                        'name'              => 'search_category',
                        'id'                => 'search_category',
                        'orderby'           => 'name', 
                        'selected'          => '', 
                        'multiple'          => false 
                    ) );
                    ?>
                </div>

            <?php
			$categories = ob_get_clean();
        }
        
        
        $search_result = '<div class="search-results"><div class="search-loader"><span></span></div><div class="job-listings"></div></div>';
        
		// Form
		$output = '<form method="GET" action="' . get_permalink(get_option('job_manager_jobs_page_id')) . '" class="job-search-form ' . $search_style . '">
			<div class="search-keywords"><input type="text" id="search_keywords" name="search_keywords" placeholder="' . esc_html__("Keywords", "cariera") . '" autocomplete="off">' . $search_result . '</div>' . $location . $region . $categories . '<div class="search-submit"><input type="submit" class="btn btn-main btn-effect" value="'. esc_html__("Search", "cariera") . '"></div>
		</form>';

		return $output;
	}
}

add_shortcode( 'search_form', 'cariera_job_search_form' );





/**
 * Gets a number of posts and displays them as options
 * 
 * @since 1.4.8
 */
function cariera_get_post_options( $query_args ) {

	$args = wp_parse_args( $query_args, array(
		'post_type'   => 'post',
		'numberposts' => -1,
	) );

	$posts              = get_posts( $args );
	$post_options       = array();
	$post_options[0]    = esc_html__( '--Choose page--', 'cariera' );
	if ( $posts ) {
		foreach ( $posts as $post ) {
          $post_options[ $post->ID ] = $post->post_title;
		}
	}

	return $post_options;
}





/**
 * Get Pages
 *
 * @since  1.4.8
 */
function cariera_get_pages_options() {
	return cariera_get_post_options( array( 'post_type' => 'page' ) );
}





/**
 * Generate a random key, used for security reasons
 *
 * @since  1.5.0
 */
if ( !function_exists( 'cariera_random_key' ) ) {
    function cariera_random_key( $length = 8 ) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $return = '';
        for ($i = 0; $i < $length; $i++) {
            $return .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $return;
    }
}