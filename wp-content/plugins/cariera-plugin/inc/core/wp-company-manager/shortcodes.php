<?php

/**
*
* @package Cariera
*
* @since   1.3.0
* @version 1.5.1
* 
* ========================
* CARIERA COMPANY MANAGER SHORTCODES
* ========================
*     
**/


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}




class Cariera_Company_Manager_Shortcodes {

	private $company_dashboard_message = '';
    
    public function __construct() {
		add_action( 'wp', [ $this, 'shortcode_action_handler' ] );
		add_shortcode( 'company_dashboard', [ $this, 'company_dashboard' ] );		
		add_shortcode( 'submit_company', [ $this, 'submit_company' ] );
        add_shortcode( 'companies', [ $this, 'output_companies' ] );
		add_shortcode( 'cariera_companies_list', [ $this, 'output_companies_list' ] );
		
		// AJAX Actions
		add_action( 'wp_ajax_nopriv_cariera_get_companies', [ $this, 'get_ajax_companies' ] );
		add_action( 'wp_ajax_cariera_get_companies', [ $this, 'get_ajax_companies' ] );
    }
	
	



	/**
     * Handle actions which need to be run before the shortcode e.g. post actions
     *
     * @since  1.4.4
     */
	public function shortcode_action_handler() {
		global $post;

		if ( is_page() && strstr( $post->post_content, '[company_dashboard' ) ) {
			$this->company_dashboard_handler();
		}
	}





	/**
     * Handles actions on company dashboard
     *
     * @since  1.4.4
     */
	public function company_dashboard_handler() {
		if ( ! empty( $_REQUEST['action'] ) && ! empty( $_REQUEST['_wpnonce'] ) && wp_verify_nonce( $_REQUEST['_wpnonce'], 'cariera_my_company_actions' ) ) {

			$action    	= sanitize_title( $_REQUEST['action'] );
			$company_id = absint( $_REQUEST['company_id'] );

			try {
				// Get company
				$company = get_post( $company_id );

				// Check ownership
				if ( ! $company || $company->post_author != get_current_user_id() ) {
					throw new Exception( esc_html__( 'Invalid Company ID', 'cariera' ) );
				}
				
				switch ( $action ) {
					case 'delete' :
						// Trash it
						wp_trash_post( $company_id );

						// Message
						$this->company_dashboard_message = '<div class="job-manager-message">' . sprintf( esc_html__( '%s has been deleted', 'cariera' ), $company->post_title ) . '</div>';
					break;
					case 'hide' :
						if ( $company->post_status === 'publish' ) {
							$update_company = array( 'ID' => $company_id, 'post_status' => 'private' );
							wp_update_post( $update_company );
							$this->company_dashboard_message = '<div class="job-manager-message">' . sprintf( esc_html__( '%s has been hidden', 'cariera' ), $company->post_title ) . '</div>';
						}
					break;
					case 'publish' :
						if ( in_array( $company->post_status, array( 'private', 'hidden' ) ) ) {
							$update_company = array( 'ID' => $company_id, 'post_status' => 'publish' );
							wp_update_post( $update_company );
							$this->company_dashboard_message = '<div class="job-manager-message">' . sprintf( esc_html__( '%s has been published', 'cariera' ), $company->post_title ) . '</div>';
						}
					break;
				}

				do_action( 'cariera_my_company_do_action', $action, $company_id );

			} catch ( Exception $e ) {
				$this->company_dashboard_message = '<div class="job-manager-error">' . $e->getMessage() . '</div>';
			}
		}
	}





	/**
     * Companies Dashboard shortcode
     *
     * @since  1.4.4
     */
	public function company_dashboard( $atts ) {
		global $cariera_company_manager;

		if ( ! is_user_logged_in() ) {
			ob_start();
			get_job_manager_template( 'company-dashboard-login.php', array(), 'wp-job-manager-companies' );
			return ob_get_clean();
		}

		extract( shortcode_atts( array(
			'posts_per_page' => '25',
		), $atts ) );

		// If doing an action, show conditional content if needed....
		if ( ! empty( $_REQUEST['action'] ) ) {
			$action     = sanitize_title( $_REQUEST['action'] );
			$company_id = absint( $_REQUEST['company_id'] );

			switch ( $action ) {
				case 'edit' :
					return $cariera_company_manager->forms->get_form( 'edit-company' );
			}
		}

		// ....If not show the company dashboard
		$args = apply_filters( 'cariera_get_dashboard_companies_args', array(
			'post_type'           => 'company',
			'post_status'         => array( 'publish', 'expired', 'pending', 'hidden', 'private' ),
			'ignore_sticky_posts' => 1,
			'posts_per_page'      => $posts_per_page,
			'offset'              => ( max( 1, get_query_var('paged') ) - 1 ) * $posts_per_page,
			'orderby'             => 'date',
			'order'               => 'desc',
			'author'              => get_current_user_id()
		) );

		$companies = new WP_Query;

		ob_start();

		echo wp_kses_post( $this->company_dashboard_message );

		// Get the flash messages sent by external handlers.
		$messages = self::get_company_dashboard_messages( true );
		foreach ( $messages as $message ) {
			$div_class = 'job-manager-message';
			if ( ! empty( $message['is_error'] ) ) {
				$div_class = 'job-manager-error';
			}
			echo '<div class="' . esc_attr( $div_class ) . '">' . wp_kses_post( $message['message'] ) . '</div>';
		}

		$company_dashboard_columns = apply_filters( 'cariera_company_dashboard_columns', array(
			'company-name'     => esc_html__( 'Name', 'cariera' ),
			'company-location' => esc_html__( 'Location', 'cariera' ),
			'company-category' => esc_html__( 'Category', 'cariera' ),
			'date'             => esc_html__( 'Date Posted', 'cariera' ),
			'company-jobs'     => sprintf( esc_html__( 'Active %s', 'cariera' ), cariera_get_job_post_label( true ) )
		) );

		if ( ! get_option( 'cariera_company_category' ) ) {
			unset( $company_dashboard_columns['company-category'] );
		}

        get_job_manager_template( 'company-dashboard.php', array( 
            'companies'                 => $companies->query( $args ), 
            'max_num_pages'             => $companies->max_num_pages, 
			'company_dashboard_columns' => $company_dashboard_columns 
		), 'wp-job-manager-companies' );

		return ob_get_clean();
	}





	/**
     * Show the company submission form
     *
     * @since  1.4.4
     */
	public function submit_company( $atts = array() ) {
		return $GLOBALS['cariera_company_manager']->forms->get_form( 'submit-company', $atts );
	}





    /**
     * Companies shortcode
     *
     * @since   1.3.0
	 * @version 1.5.0
     */
    public function output_companies( $atts ) {
		global $cariera_company_manager;

		ob_start();
		
		if ( !cariera_user_can_browse_companies() ) {
			get_job_manager_template_part( 'access-denied', 'browse-companies', 'wp-job-manager-companies' );
			return ob_get_clean();
		}

		$atts = shortcode_atts(
			apply_filters( 'cariera_output_companies_defaults',	[
				'companies_layout'          => 'list',
				'companies_list_version'    => '1',
				'companies_grid_version'    => '1',

				'per_page'                  => get_option( 'cariera_companies_per_page' ),
				'orderby'                   => 'featured',
				'order'                     => 'DESC',

				// Filters
				'show_filters'              => true,
				'show_pagination'           => false,
				'show_more'                 => true,

				// Limit what companies are shown based on category, post status, and type.
				'categories'                => '',
				'post_status'               => '',
				'featured'                  => null, // True to show only featured, false to hide featured, leave null to show both.

				// Default values for filters.
				'location'                  => '',
				'keywords'                  => '',
				'selected_category'         => '',
			] ), $atts
		);


		// Companies Layout
        if ( $atts['companies_layout'] == 'list' ) {
            $companies_layout 			= '_list';
            $companies_layout_wrapper 	= 'company_list';
            $companies_version 			= $atts['companies_list_version'];
        } else {
            $companies_layout 			= '_' . $atts['companies_layout'];
            $companies_layout_wrapper 	= 'company_grid';
            $companies_version 			= $atts['companies_grid_version'];
        }


        // String and bool handling.
        $atts['show_filters'] 		= $this->string_to_bool( $atts['show_filters'] );
		$atts['show_more']          = $this->string_to_bool( $atts['show_more'] );
		$atts['show_pagination']    = $this->string_to_bool( $atts['show_pagination'] );
        
        if ( ! is_null( $atts['featured'] ) ) {
			$atts['featured'] = ( is_bool( $atts['featured'] ) && $atts['featured'] ) || in_array( $atts['featured'], array( '1', 'true', 'yes' ) ) ? true : false;
		}
        
        // Array handling.
		$atts['categories']   = is_array( $atts['categories'] ) ? $atts['categories'] : array_filter( array_map( 'trim', explode( ',', $atts['categories'] ) ) );
		$atts['post_status']  = is_array( $atts['post_status'] ) ? $atts['post_status'] : array_filter( array_map( 'trim', explode( ',', $atts['post_status'] ) ) );
        
        // Get keywords and location from querystring if set.
		if ( ! empty( $_GET['search_keywords'] ) ) {
			$atts['keywords'] = sanitize_text_field( $_GET['search_keywords'] );
		}
		if ( ! empty( $_GET['search_location'] ) ) {
			$atts['location'] = sanitize_text_field( $_GET['search_location'] );
		}
		if ( ! empty( $_GET['search_category'] ) ) {
			$atts['selected_category'] = sanitize_text_field( $_GET['search_category'] );
		}
        
        
        if ( $atts['show_filters'] ) {
			get_company_template(
				'company-filters.php',
				[
					'per_page'                  => $atts['per_page'],
					'orderby'                   => $atts['orderby'],
					'order'                     => $atts['order'],
					'categories'                => $atts['categories'],
					'atts'                      => $atts,
					'location'                  => $atts['location'],
					'keywords'                  => $atts['keywords'],
				]
			);
            
            echo '<ul class="company_listings company_listings_main ' . esc_attr($companies_layout_wrapper) . '"></ul>';
			echo '<div class="listing-loader"><div></div></div>';
			
			if ( ! $atts['show_pagination'] && $atts['show_more'] ) {
				echo '<div class="text-center"><a class="load_more_companies btn btn-main btn-effect mt40" href="#" style="display:none;">' . esc_html__( 'Load more companies', 'cariera' ) . '</a></div>';
			}

		} else {
            $companies = cariera_get_companies(
				apply_filters(
					'cariera_output_companies_args',
					array(
						'search_location'   => $atts['location'],
						'search_keywords'   => $atts['keywords'],
						'post_status'       => $atts['post_status'],
						'search_categories' => $atts['categories'],
						'orderby'           => $atts['orderby'],
						'order'             => $atts['order'],
						'posts_per_page'    => $atts['per_page'],
						'featured'          => $atts['featured'],
					)
				)
			);

			if ( $companies->have_posts() ) {
				echo '<ul class="company_listings company_listings_main ' . esc_attr($companies_layout_wrapper) . '">';
				
				while ( $companies->have_posts() ) {
					$companies->the_post();
					get_job_manager_template_part( 'company-templates/content', 'company' . $companies_layout . $companies_version, 'wp-job-manager-companies' );
				}

                echo '</ul>';
                echo '<div class="listing-loader"><div></div></div>';
                
                if ( $companies->found_posts > $atts['per_page'] && $atts['show_more'] ) {
					wp_enqueue_script( 'company-ajax-filters' );

					if ( $atts['show_pagination'] ) {
						echo cariera_get_company_pagination( $companies->max_num_pages );
					} else { ?>
                        <div class="text-center">
                            <a class="load_more_companies btn btn-main btn-effect" href="#"><?php esc_html_e( 'Load more companies', 'cariera' ); ?></a>
                        </div>
                    <?php }
				}
			} else {
				do_action( 'cariera_company_no_results' );
			}
            
			wp_reset_postdata();
        }
        
        $data_attributes_string = '';
        $data_attributes = [
			'company_layout'  => $companies_layout,
			'company_version' => $companies_version,
			'location'        => $atts['location'],
			'keywords'        => $atts['keywords'],
			'show_filters'    => $atts['show_filters'] ? 'true' : 'false',
			'show_pagination' => $atts['show_pagination'] ? 'true' : 'false',
			'per_page'        => $atts['per_page'],
			'orderby'         => $atts['orderby'],
			'order'           => $atts['order'],
			'categories'      => implode( ',', $atts['categories'] ),
		];

		if ( ! is_null( $atts['featured'] ) ) {
			$data_attributes['featured'] = $atts['featured'] ? 'true' : 'false';
		}
		if ( ! empty( $atts['post_status'] ) ) {
			$data_attributes['post_status'] = implode( ',', $atts['post_status'] );
		}
		foreach ( $data_attributes as $key => $value ) {
			$data_attributes_string .= 'data-' . esc_attr( $key ) . '="' . esc_attr( $value ) . '" ';
		}

		$companies_output = apply_filters( 'cariera_companies_output', ob_get_clean() );

		return '<div class="company_listings" ' . $data_attributes_string . '>' . $companies_output . '</div>';        
    }
	



	
	/**
	 * Returns Company Listings for Ajax endpoint.
	 *
	 * @since   1.3.0
	 * @version 1.5.0
	 */
	public function get_ajax_companies() {
		global $wpdb;

		ob_start();

		$search_keywords    = sanitize_text_field( stripslashes( $_POST['search_keywords'] ) );
		$search_location    = sanitize_text_field( stripslashes( $_POST['search_location'] ) );
		$search_categories  = isset( $_POST['search_categories'] ) ? $_POST['search_categories'] : '';
        $companies_layout	= sanitize_text_field( $_POST['company_layout'] );
        $companies_version	= sanitize_text_field( $_POST['company_version'] );

		if ( is_array( $search_categories ) ) {
			$search_categories = array_map( 'sanitize_text_field', array_map( 'stripslashes', $search_categories ) );
		} else {
			$search_categories = [ sanitize_text_field( stripslashes( $search_categories ) ), 0 ];
		}

		$search_categories = array_filter( $search_categories );

		$args = [
			'search_keywords'   => $search_keywords,
			'search_location'   => $search_location,
			'search_categories' => $search_categories,
			'orderby'           => sanitize_text_field( $_POST['orderby'] ),
			'order'             => sanitize_text_field( $_POST['order'] ),
			'offset'            => ( absint( $_POST['page'] ) - 1 ) * absint( $_POST['per_page'] ),
			'posts_per_page'    => absint( $_POST['per_page'] ),
		];

		if ( isset( $_POST['featured'] ) && ( $_POST['featured'] === 'true' || $_POST['featured'] === 'false' ) ) {
			$args['featured'] = $_POST['featured'] === 'true' ? true : false;
		}

		// Get the arguments to use when building the Companies WP Query.
		$companies = cariera_get_companies( apply_filters( 'cariera_get_companies_args', $args ) );
		
		$result                  	= [];
		$result['found_companies'] 	= false;


		if ( $companies->have_posts() ) {
			$result['found_companies'] = true;

			while ( $companies->have_posts() ) {
				$companies->the_post();
				get_job_manager_template_part( 'company-templates/content', 'company' . $companies_layout . $companies_version, 'wp-job-manager-companies' );
			}

		} else {
			get_job_manager_template_part( 'content', 'no-companies-found', 'wp-job-manager-companies' );
		}

		$result['html'] = ob_get_clean();


		// Generate pagination.
		if ( isset( $_POST['show_pagination'] ) && $_POST['show_pagination'] === 'true' ) {
			$result['pagination'] = cariera_get_company_pagination( $companies->max_num_pages, absint( $_REQUEST['page'] ) );
		}

		$result['max_num_pages'] = $companies->max_num_pages;

		/** This filter is documented in includes/class-wp-job-manager-ajax.php (above) */
		wp_send_json( apply_filters( 'cariera_get_companies_result', $result, $companies ) );		
	}
    
    
    
	

    /**
     * Output of the company list shortcode
     *
     * @since  1.3.0
     */    
    public function output_companies_list( $atts ) {
        extract( $atts = shortcode_atts( [
            'show_letters' => true,
            'all_title'    => esc_html__( 'All', 'cariera' )
		], $atts ) );

        $output = '';

        $companies = get_posts( [
            'numberposts'   => -1,
            'post_type'     => 'company',
            'post_status'   => 'publish'
		] );

        $_companies = [];
        foreach ( $companies as $company ) {
            $_companies[ strtoupper( $company->post_title[0] ) ][] = $company;
        }

        $output = '<div class="companies-listing-a-z">';
        $show_letters = $this->string_to_bool( $show_letters );
        if ( $show_letters ) {
            $output .= '<div class="company-letters"><ul>';
            $output .= '<li><a href="#all"  data-target="#all" class="all chosen">' . $all_title . '</a></li>';
            foreach ( range( 'A', 'Z' ) as $letter ) {
                if ( ! isset( $_companies[ $letter ] ) ) {
                    $output .= '<li><span>' . $letter . '</span></li>';
                } else {
                    $output .= '<li><a href="#' . $letter . '"  data-target="#' . $letter . '">' . $letter . '</a></li>';
                }
            }
            $output .= '</ul></div>';
        }

        $output .= '<ul class="companies-overview">';
        foreach ( range( 'A', 'Z' ) as $letter ) {
            if ( ! isset( $_companies[ $letter ] ) ) {
                continue;
            }

            $output .= '<li class="company-group"><div class="company-group-inner">';
            $output .= '<div id="' . $letter . '" class="company-letter">' . $letter . '</div>';
            $output .= '<ul>';

            foreach ( $_companies[ $letter ] as $company ) {
                $output .= '<li class="company-name"><a href="' . get_permalink( $company ) . '">' . esc_attr( $company->post_title ) . '</a></li>';
            }

            $output .= '</ul>';
            $output .= '</div></li>';
        }

        $output .= '</ul>';
        $output .= '</div>';

        return $output;
    }


    
    
    
    /**
     * Gets string as a bool.
     *
     * @since  	1.3.0
	 * @version	1.5.1
     */    
    public function string_to_bool( $value ) {
        return ( is_bool( $value ) && $value ) || in_array( $value, [ '1', 'true', 'yes' ] ) ? true : false;
	}
	




	/**
	 * Add a flash message to display on a company dashboard.
	 *
	 * @since 1.4.7
	 */
	public static function add_company_dashboard_message( $message, $is_error = false ) {
		$company_dashboard_page_id = get_option( 'cariera_company_dashboard_page' );
		if ( ! wp_get_session_token() || ! $company_dashboard_page_id ) {
			// We only handle flash messages when the company dashboard page ID is set and user has valid session token.
			return false;
		}
		$messages_key = self::get_company_dashboard_message_key();
		$messages     = self::get_company_dashboard_messages( false );

		$messages[] = [
			'message'  => $message,
			'is_error' => $is_error,
		];

		set_transient( $messages_key, wp_json_encode( $messages ), HOUR_IN_SECONDS );

		return true;
	}





	/**
	 * Gets the current flash messages for the listing dashboard.
	 *
	 * @since 1.4.7
	 */
	private static function get_company_dashboard_messages( $clear ) {
		$messages_key = self::get_company_dashboard_message_key();
		$messages     = get_transient( $messages_key );

		if ( empty( $messages ) ) {
			$messages = [];
		} else {
			$messages = json_decode( $messages, true );
		}

		if ( $clear ) {
			delete_transient( $messages_key );
		}

		return $messages;
	}





	/**
	 * Get the transient key to use to store listing dashboard messages.
	 *
	 * @since 1.4.7
	 */
	private static function get_company_dashboard_message_key() {
		return 'company_dashboard_messages_' . md5( wp_get_session_token() );
	}

}