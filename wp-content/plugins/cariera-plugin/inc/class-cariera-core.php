<?php
/**
*
* @package Cariera
*
* @since 1.4.3
* 
* ========================
* Cariera_Core CLASS
* ========================
*     
**/



// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}





/**
 * Handles core plugin hooks and action setup.
 *
 * @since 1.4.3
 */
class Cariera_Core {

    /**
	 * The single instance of the class.
	 *
	 * @since  1.4.3
	 */
    private static $_instance = null;
    




    /**
	 * Main Cariera Core Instance.
	 *
	 * Ensures only one instance of Cariera Core is loaded or can be loaded.
	 *
	 * @since  1.4.3
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}





    /**
	 * Constructor function.
	 * 
	 * @since 1.4.3
	 */
    public function __construct() {
        
        // Includes
        include_once CARIERA_PATH . '/inc/core/admin.php';
        include_once CARIERA_PATH . '/inc/core/cpt.php';
        include_once CARIERA_PATH . '/inc/core/emails.php';
        include_once CARIERA_PATH . '/inc/core/fields.php';
        include_once CARIERA_PATH . '/inc/core/search.php';
        include_once CARIERA_PATH . '/inc/core/settings.php';
        include_once CARIERA_PATH . '/inc/core/notifications.php';


        // Init Classes
        $this->cpt              = new Cariera_Core_CPT();
        $this->fields           = new Cariera_Core_Fields();
        $this->search           = new Cariera_Core_Search();
        $this->settings         = new Cariera_Core_Settings();
		$this->notifications    = new Cariera_Core_Notifications();
		$this->admin            = Cariera_Core_Admin::instance();
        $this->emails           = Cariera_Core_Emails::instance();


        // // Load Frontend JS
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ], 10 );

        // Load Admin CSS & JS
        add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ], 10, 1 );
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_styles' ], 10, 1 );

        // Actions
        add_action( 'widgets_init', [ $this, 'widgets_init' ] );
        add_action( 'init', [ $this, 'wpbakery_custom_elements' ] );
        add_action( 'admin_notices', [ $this, 'admin_notices' ] );

        // AJAX Dropzone
		add_action( 'wp_ajax_handle_uploaded_media', [ $this, 'uploaded_dropzone_media' ] );
		add_action( 'wp_ajax_nopriv_handle_uploaded_media', [ $this, 'uploaded_dropzone_media' ] );
		add_action( 'wp_ajax_handle_deleted_media',  [ $this, 'deleted_dropzone_media' ] );
        add_action( 'wp_ajax_nopriv_handle_deleted_media',  [ $this, 'deleted_dropzone_media' ] );
        
        // Custom Cron Schedules
		add_filter( 'cron_schedules', [ $this, 'cron_schedules' ] );
    }




    /**
     * Registering Widgets
     *
     * @since  1.2.2
     */
    public function widgets_init() {
        include_once( CARIERA_PATH . '/inc/widgets/social-media-widget.php' );
        include_once( CARIERA_PATH . '/inc/widgets/recent-posts-widget.php' );
        include_once( CARIERA_PATH . '/inc/widgets/job-search-widget.php' );
        include_once( CARIERA_PATH . '/inc/widgets/resume-search-widget.php' );
        include_once( CARIERA_PATH . '/inc/widgets/company-search-widget.php' );
    }

    



    /**
     * VC Shortcodes
     *
     * @since  1.2.2
     */
    public function wpbakery_custom_elements() {
        if ( class_exists('WPBakeryShortCode') ) {
            include_once( CARIERA_PATH . '/inc/vc-addons/buttons/addon.php' );
            include_once( CARIERA_PATH . '/inc/vc-addons/blog-slider/addon.php' );
            include_once( CARIERA_PATH . '/inc/vc-addons/blog-posts/addon.php' );
            include_once( CARIERA_PATH . '/inc/vc-addons/counterup/addon.php' );
            include_once( CARIERA_PATH . '/inc/vc-addons/counterup/counters.php' );
            include_once( CARIERA_PATH . '/inc/vc-addons/testimonial/addon.php' );
            include_once( CARIERA_PATH . '/inc/vc-addons/logo/addon.php' );
            include_once( CARIERA_PATH . '/inc/vc-addons/pricing-tables/addon.php' );
            include_once( CARIERA_PATH . '/inc/vc-addons/gmaps/addon.php' );
            include_once( CARIERA_PATH . '/inc/vc-addons/icon-box/addon.php' );
            include_once( CARIERA_PATH . '/inc/vc-addons/newsletter/addon.php' );
            include_once( CARIERA_PATH . '/inc/vc-addons/video-modal/addon.php' );
            
            if( class_exists('WP_Job_Manager') ) {
                include_once( CARIERA_PATH . '/inc/vc-addons/job/addon.php' );
                include_once( CARIERA_PATH . '/inc/vc-addons/job-categories/slider.php' );
                include_once( CARIERA_PATH . '/inc/vc-addons/job-spotlight/addon.php' );
                include_once( CARIERA_PATH . '/inc/vc-addons/job-search/addon.php' );
                include_once( CARIERA_PATH . '/inc/vc-addons/job-search-box/addon.php' );
                include_once( CARIERA_PATH . '/inc/vc-addons/job-resume-map/addon.php' );
                include_once( CARIERA_PATH . '/inc/vc-addons/job-resume-search/addon.php' );
                include_once( CARIERA_PATH . '/inc/vc-addons/company/addon.php' );
                include_once( CARIERA_PATH . '/inc/vc-addons/company-carousel/addon.php' );                
                include_once( CARIERA_PATH . '/inc/vc-addons/listing-categories/list.php' );
                include_once( CARIERA_PATH . '/inc/vc-addons/listing-categories/grid.php' );
            }
            if( class_exists( 'WP_Resume_Manager' ) ) {
                include_once( CARIERA_PATH . '/inc/vc-addons/resume/addon.php' );
                include_once( CARIERA_PATH . '/inc/vc-addons/resume-carousel/addon.php' );
                include_once( CARIERA_PATH . '/inc/vc-addons/resume-search/addon.php' );
            }
        }
    }





    /**
	 * Load Frontend Javascript.
	 * 
	 * @since   1.4.3
	 */
    public function enqueue_scripts() {        
        // Main JS File of the core plugin
        wp_enqueue_script( 'cariera-core-main', CARIERA_URL . '/assets/dist/js/frontend.js', [ 'jquery' ], false, true );
        
        $ajax_url = admin_url( 'admin-ajax.php', 'relative' );
        $translations = [
            'ajax_url'          => esc_url( $ajax_url ),
            'nonce'             => wp_create_nonce( '_cariera_core_nonce' ),
            'is_rtl'            => is_rtl() ? 1 : 0,
            'upload_ajax'       => admin_url( 'admin-ajax.php?action=handle_uploaded_media' ),
            'delete_ajax'       => admin_url( 'admin-ajax.php?action=handle_deleted_media' ),
            'max_file_size'     => apply_filters( 'cariera_file_max_size', size_format( wp_max_upload_size() ) ),
            'map_provider'	    => cariera_get_option( 'cariera_map_provider' ),
        ];

        wp_localize_script( 'cariera-core-main', 'cariera_core_settings', $translations );
        
        
        // Resume AJAX Search
        if( class_exists('WP_Job_Manager') && class_exists( 'WP_Resume_Manager' ) ) {
            wp_dequeue_script( 'wp-resume-manager-ajax-filters' );
            wp_deregister_script( 'wp-resume-manager-ajax-filters' );
            wp_register_script( 'wp-resume-manager-ajax-filters', CARIERA_URL . '/assets/dist/js/resumes-ajax-filters.js', [ 'jquery', 'jquery-deserialize' ], '', true );
            wp_localize_script( 'wp-resume-manager-ajax-filters', 'resume_manager_ajax_filters', [
                'ajax_url'                  => $ajax_url,
                'currency'		      		=> cariera_currency_symbol(),
                'showing_all'		      	=> esc_html__( 'Showing all resumes', 'cariera' )
            ]);
        }



        //Map Providers
        $map_provider   = cariera_get_option( 'cariera_map_provider');
        $gmap_api_key   = cariera_get_option( 'cariera_gmap_api_key' );

        if( $map_provider == 'google' ) {
            if( $gmap_api_key ) {
                wp_enqueue_script( 'google-maps', 'https://maps.googleapis.com/maps/api/js?key=' . $gmap_api_key . '&amp;libraries=places', [ 'jquery' ], false, true );
            }
        }

        wp_enqueue_script( 'cariera-maps', CARIERA_URL . '/assets/dist/js/maps.js', [ 'jquery' ], false, true  );
        wp_localize_script( 'cariera-maps', 'cariera_maps', [
            'map_provider'			=> cariera_get_option( 'cariera_map_provider' ),
            'autolocation'		    => cariera_get_option( 'cariera_job_location_autocomplete' ) == 1 ? true : false,
            'country'   	        => cariera_get_option( 'cariera_map_restriction'),
            'centerPoint'			=> cariera_get_option( 'cariera_map_center'),
            'mapbox_access_token'	=> cariera_get_option( 'cariera_mapbox_access_token' ),
            'map_type'		        => cariera_get_option( 'cariera_maps_type'),
        ] );
    }





    /**
	 * Load Admin Javascript.
	 *
	 * @since   1.4.3
     * @version 1.5.1
	 */
	public function admin_enqueue_scripts ( $hook = '' ) {
        $map_provider   = cariera_get_option( 'cariera_map_provider');
        $gmap_api_key   = cariera_get_option( 'cariera_gmap_api_key' );

        wp_enqueue_script( 'cariera-core-admin', CARIERA_URL . '/assets/dist/js/admin.js' );
        wp_localize_script( 'cariera-core-admin', 'cariera_core_admin', [
            'ajax_url'     => esc_url( admin_url( 'admin-ajax.php', 'relative' ) ),
            'map_provider' => $map_provider,
        ]);


        if ( $map_provider == 'google' ) {
            if ( $gmap_api_key ) {
                wp_enqueue_script( 'google-maps', 'https://maps.google.com/maps/api/js?key=' . $gmap_api_key . '&libraries=places' );
            }
        }
	}





    /**
	 * Load Admin CSS.
	 * 
	 * @since   1.4.3
	 */
    public function admin_enqueue_styles ( $hook = '' ) {
		wp_enqueue_style( 'cariera-core-admin', CARIERA_URL . '/assets/dist/css/admin.css' );
    }
    




    /**
	 * Upload Media function for dropzone
     * 
     * @since 1.4.7
	 */
	public function uploaded_dropzone_media() {
	    status_header(200);

	    $upload_dir 	= wp_upload_dir();
	    $upload_path 	= $upload_dir['path'] . DIRECTORY_SEPARATOR;
	    //$num_files 		= count($_FILES['file']['tmp_name']);

	    $newupload = 0;

	    if ( !empty($_FILES) ) {
	        $files = $_FILES;
	        foreach($files as $file) {
	            $newfile = array (
					'name' 		=> $file['name'],
					'type' 		=> $file['type'],
					'tmp_name' 	=> $file['tmp_name'],
					'error' 	=> $file['error'],
					'size' 		=> $file['size']
	            );

	            $_FILES = ['upload' => $newfile];
	            foreach($_FILES as $file => $array) {
	                $newupload = media_handle_upload( $file, 0 );
	            }
	        }
	    }

	    echo $newupload;    
	    wp_die();
	}





	/**
	 * Delete Media function for dropzone
     * 
     * @since 1.4.7
	 */
	function deleted_dropzone_media() {
	    if( isset($_REQUEST['media_id']) ) {
	        $post_id = absint( $_REQUEST['media_id'] );
	        $status = wp_delete_attachment($post_id, true);
	        if( $status ) {
	            echo json_encode(array('status' => 'OK'));
			} else {
				echo json_encode(array('status' => 'FAILED'));
			}
		}
		
	    wp_die();
    }
    




    /**
	 * Admin Notices
     * 
     * @since 1.4.8
	 */
    public function admin_notices() {
        $wpjm_gmaps_api_key = get_option('job_manager_google_maps_api_key');
        
		if ( empty($wpjm_gmaps_api_key) ) { ?>
            <div class="error notice">
                <p><?php esc_html_e( 'Please add an unrestricted Google Maps API key in the "Job Listings->Settings" in order to be able to geocode your listings and show them in the maps.', 'cariera' ); ?> <a href="https://wpjobmanager.com/document/geolocation-with-googles-maps-api/" target="_blank"><?php esc_html_e( 'Learn More', 'cariera' ); ?></a></p>
            </div>
        <?php 
        }
    }
    




    /**
	 * Add schedule to use for cron job. Should not be called externally.
     * 
     * @since 1.5.0
	 */
	public function cron_schedules($schedules) {
	    if( !isset($schedules["5min"]) ) {
	        $schedules["5min"] = [
	            'interval' 	=> 5*60,
				'display' 	=> esc_html__( 'Once every 5 minutes', 'cariera' ),
			];
		}
		
	    if( !isset($schedules["30min"]) ) {
	        $schedules["30min"] = [
	            'interval' 	=> 30*60,
				'display'  	=> esc_html__( 'Once every 30 minutes', 'cariera' ),
			];
		}

		if( !isset($schedules["monthly"]) ) {
			$schedules['monthly'] = [
				'interval' 	=> 2635200,
				'display' 	=> esc_html__( 'Once monthly', 'cariera' ),
			];
		}
		
	    return $schedules;
	}

}