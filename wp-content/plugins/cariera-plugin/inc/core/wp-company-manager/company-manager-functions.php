<?php

/**
*
* @package Cariera
*
* @since 1.3.0
* 
* ========================
* CARIERA COMPANY MANAGER FUNTIONS
* ========================
*     
**/



if ( ! defined( 'ABSPATH' ) ) {
	exit;
}



/**
 * Queries companies with certain criteria and returns them.
 *
 * @since  1.3.0
 */
if ( ! function_exists( 'cariera_get_companies' ) ) {
    function cariera_get_companies( $args = [] ) {
        global $wpdb, $cariera_company_keyword;
        
        $args = wp_parse_args(
			$args, 
			[
				'search_keywords'   => '',
				'search_location'   => '',
				'search_categories' => [],
				'offset'            => '',
				'posts_per_page'    => '-1',
				'orderby'           => 'date',
				'order'             => 'DESC',
				'featured'          => null,
				'fields'            => 'all',
			]
		);
        
        // Query args
        $query_args = [
			'post_type'              => 'company',
			'post_status'            => 'publish',
			'ignore_sticky_posts'    => 1,
			'offset'                 => absint( $args['offset'] ),
			'posts_per_page'         => intval( $args['posts_per_page'] ),
			'orderby'                => $args['orderby'],
			'order'                  => $args['order'],
			'tax_query'              => [],
			'meta_query'             => [],
			'update_post_term_cache' => false,
			'update_post_meta_cache' => false,
			'cache_results'          => false,
			'fields'                 => $args['fields'],
		];
        
        if ( $args['posts_per_page'] < 0 ) {
			$query_args['no_found_rows'] = true;
		}
        
        if ( ! empty( $args['search_location'] ) ) {
			$location_meta_keys = [ 'geolocation_formatted_address', '_company_location', 'geolocation_state_long' ];
			$location_search    = [ 'relation' => 'OR' ];
			foreach ( $location_meta_keys as $meta_key ) {
				$location_search[] = [
					'key'     => $meta_key,
					'value'   => $args['search_location'],
					'compare' => 'like',
				];
			}
			$query_args['meta_query'][] = $location_search;
		}
        
        if ( ! is_null( $args['featured'] ) ) {
			$query_args['meta_query'][] = [
				'key'     => '_featured',
				'value'   => '1',
				'compare' => $args['featured'] ? '=' : '!=',
			];
		}
        
        if ( ! empty( $args['search_categories'] ) ) {
			$field                     = is_numeric( $args['search_categories'][0] ) ? 'term_id' : 'slug';
			$operator                  = 'all' === get_option( 'job_manager_category_filter_type', 'all' ) && count( $args['search_categories'] ) > 1 ? 'AND' : 'IN';
			$query_args['tax_query'][] = [
				'taxonomy'         => 'company_category',
				'field'            => $field,
				'terms'            => array_values( $args['search_categories'] ),
				'include_children' => 'AND' !== $operator,
				'operator'         => $operator,
			];
		}
        
        if ( 'featured' === $args['orderby'] ) {
			$query_args['orderby'] = [
				'menu_order' => 'ASC',
				'date'       => 'DESC',
				'ID'         => 'DESC',
			];
		}
        
        if ( 'rand_featured' === $args['orderby'] ) {
			$query_args['orderby'] = [
				'menu_order' => 'ASC',
				'rand'       => 'ASC',
			];
		}
		
		
       
		if ( $cariera_company_keyword = sanitize_text_field( $args['search_keywords'] ) ) {
			$query_args['_keyword'] = $cariera_company_keyword; // Does nothing but needed for unique hash
			add_filter( 'posts_clauses', 'cariera_get_company_keyword_search' );
		}
        
        $query_args = apply_filters( 'cariera_get_companies', $query_args, $args );
        
        if ( empty( $query_args['meta_query'] ) ) {
			unset( $query_args['meta_query'] );
		}

		if ( empty( $query_args['tax_query'] ) ) {
			unset( $query_args['tax_query'] );
		}
        
        // Filter args.
		$query_args = apply_filters( 'cariera_get_companies_query_args', $query_args, $args );
        
        //Generate hash
        $to_hash              = wp_json_encode( $query_args );
        $query_args_hash      = 'jm_' . md5( $to_hash ) . WP_Job_Manager_Cache_Helper::get_transient_version( 'cariera_get_company_listings' );
        
        do_action( 'before_get_companies', $query_args, $args );
        
		$cached_query = true;
		if ( false === ( $result = get_transient( $query_args_hash ) ) ) {
			$cached_query = false;
			$result = new WP_Query( $query_args );
			set_transient( $query_args_hash, $result, DAY_IN_SECONDS );
		}
		if ( $cached_query ) {
			// random order is cached so shuffle them
			if ( 'rand_featured' === $args['orderby'] ) {
				usort( $result->posts, 'cariera_companies_shuffle_featured_post_results_helper' );
			} elseif ( 'rand' === $args['orderby'] ) {
				shuffle( $result->posts );
			}
		}
        
        do_action( 'after_get_companies', $query_args, $args );

		remove_filter( 'posts_clauses', 'cariera_get_company_keyword_search' );

		return $result;
    }
}





/**
 * Helper function to maintain featured status when shuffling results.
 *
 * @since 1.5.0
 */
if ( ! function_exists( 'cariera_companies_shuffle_featured_post_results_helper' ) ) {
	function cariera_companies_shuffle_featured_post_results_helper( $a, $b ) {
		if ( -1 === $a->menu_order || -1 === $b->menu_order ) {
			// Left is featured
			if ( 0 === $b->menu_order ) {
				return -1;
			}
			// Right is featured
			if ( 0 === $a->menu_order ) {
				return 1;
			}
		}
		return rand( -1, 1 );
	}
}





/**
 * Search based on Company keywords
 *
 * @since  1.3.0
 */
// if ( ! function_exists( 'cariera_get_company_keyword_search' ) ) {
//     function cariera_get_company_keyword_search( $search ) {
//         global $wpdb, $cariera_company_keyword;

//         // Searchable Meta Keys: set to empty to search all meta keys
//         $searchable_meta_keys = array(
//             '_company_tagline',
//             '_company_location',
//             '_company_website',
//             '_company_email',
//             '_company_phone',
//             '_company_twitter',
//             '_company_facebook',
//         );

//         $searchable_meta_keys = apply_filters( 'cariera_company_searchable_meta_keys', $searchable_meta_keys );

//         // Set Search DB Conditions
//         $conditions   = array();

//         // Search Post Meta
//         if( apply_filters( 'cariera_company_search_post_meta', true ) ) {

//             // Only selected meta keys
//             if( $searchable_meta_keys ) {
//                 $conditions[] = "{$wpdb->posts}.ID IN ( SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key IN ( '" . implode( "','", array_map( 'esc_sql', $searchable_meta_keys ) ) . "' ) AND meta_value LIKE '%" . esc_sql( $cariera_company_keyword ) . "%' )";
//             } else {
//                 // No meta keys defined, search all post meta value
//                 $conditions[] = "{$wpdb->posts}.ID IN ( SELECT post_id FROM {$wpdb->postmeta} WHERE meta_value LIKE '%" . esc_sql( $cariera_company_keyword ) . "%' )";
//             }
//         }

//         // Search taxonomy
//         $conditions[] = "{$wpdb->posts}.ID IN ( SELECT object_id FROM {$wpdb->term_relationships} AS tr LEFT JOIN {$wpdb->term_taxonomy} AS tt ON tr.term_taxonomy_id = tt.term_taxonomy_id LEFT JOIN {$wpdb->terms} AS t ON tt.term_id = t.term_id WHERE t.name LIKE '%" . esc_sql( $cariera_company_keyword ) . "%' )";


//         $conditions = apply_filters( 'cariera_company_search_conditions', $conditions, $cariera_company_keyword );
//         if ( empty( $conditions ) ) {
//             return $search;
//         }

//         $conditions_str = implode( ' OR ', $conditions );

//         if ( ! empty( $search ) ) {
//             $search = preg_replace( '/^ AND /', '', $search );
//             $search = " AND ( {$search} OR ( {$conditions_str} ) )";
//         } else {
//             $search = " AND ( {$conditions_str} )";
//         }

//         return $search;
//     }
// }



if ( ! function_exists( 'cariera_get_company_keyword_search' ) ) {
	function cariera_get_company_keyword_search( $args ) {
		global $wpdb, $cariera_company_keyword;

		// Meta searching - Query matching ids to avoid more joins
		$post_ids = $wpdb->get_col( "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_value LIKE '%" . esc_sql( $cariera_company_keyword ) . "%'" );

		// Term searching
		$post_ids = array_merge( $post_ids, $wpdb->get_col( "SELECT object_id FROM {$wpdb->term_relationships} AS tr LEFT JOIN {$wpdb->terms} AS t ON tr.term_taxonomy_id = t.term_id WHERE t.name LIKE '" . esc_sql( $cariera_company_keyword ) . "%'" ) );

		// Title and content searching
		$conditions   = [];
		$conditions[] = "{$wpdb->posts}.post_title LIKE '%" . esc_sql( $cariera_company_keyword ) . "%'";
		$conditions[] = "{$wpdb->posts}.post_content RLIKE '[[:<:]]" . esc_sql( $cariera_company_keyword ) . "[[:>:]]'";

		if ( $post_ids ) {
			$conditions[] = "{$wpdb->posts}.ID IN (" . esc_sql( implode( ',', array_unique( $post_ids ) ) ) . ')';
		}

		$args['where'] .= ' AND ( ' . implode( ' OR ', $conditions ) . ' ) ';

		return $args;
	}
}





if ( ! function_exists( 'cariera_order_featured_company' ) ) {
	function cariera_order_featured_company( $args ) {
		global $wpdb;

		$args['orderby'] = "$wpdb->postmeta.meta_value+0 DESC, $wpdb->posts.post_title ASC";

		return $args;
	}
}








/**
 * Get current company page URL.
 *
 * @since  1.3.0
 */
if ( ! function_exists( 'cariera_get_current_company_page_url' ) ) {
    function cariera_get_current_company_page_url() {
        if ( defined( 'COMPANIES_IS_ON_FRONT' ) ) {
            $link = home_url( '/' );
        } elseif( cariera_is_company_taxonomy() ) {
            $queried_object = get_queried_object();
            $link = get_term_link( $queried_object->slug, $queried_object->taxonomy );
        } else {
            $link = get_permalink( cariera_get_company_page_id( 'companies' ) );
        }

        return $link;
    }
}






/**
 * Company Class
 *
 * @since  1.3.0
 */

// Output the class
function cariera_company_class( $class = '', $post_id = null ) {
	echo 'class="' . esc_attr( join( ' ', cariera_get_company_class( $class, $post_id ) ) ) . '"';
}



// Get Company Class
function cariera_get_company_class( $class = '', $post_id = null ) {
	$post = get_post( $post_id );

	if ( empty( $post ) || 'company' !== $post->post_type ) {
		return [];
	}

	$classes = [];

	if ( ! empty( $class ) ) {
		if ( ! is_array( $class ) ) {
			$class = preg_split( '#\s+#', $class );
		}
		$classes = array_merge( $classes, $class );
	}

	return get_post_class( $classes, $post->ID );
}





/**
 * Check if company is featured
 *
 * @since  1.3.0
 */
function cariera_is_company_featured( $post = null ) {
	$post = get_post( $post );

	return $post->_featured ? true : false;
}





/**
 * Get the company status
 *
 * @since  1.4.4
 */
function cariera_company_status( $post = null ) {
	$post   = get_post( $post );
	$status = $post->post_status;

	if ( $status == 'publish' ) {
		$status = esc_html__( 'Published', 'cariera' );
	} elseif ( $status == 'expired' ) {
		$status = esc_html__( 'Expired', 'cariera' );
	} elseif ( $status == 'pending' ) {
		$status = esc_html__( 'Pending Review', 'cariera' );
	} elseif ( $status == 'hidden' ) {
		$status = esc_html__( 'Hidden', 'cariera' );
	} elseif ( $status == 'preview' ) {
		$status = esc_html__( 'Preview', 'cariera' );
	} else {
		$status = esc_html__( 'Inactive', 'cariera' );
	}

	return apply_filters( 'cariera_the_company_status', $status, $post );
}





/**
 * Check if it is a Company Taxonomy
 *
 * @since  1.3.0
 */
function cariera_is_company_taxonomy() {
	return is_tax( get_object_taxonomies( 'company' ) );
}





/**
 * Gets and includes template files.
 *
 * @since 1.3.0
 */
function get_company_template( $template_name, $args = array(), $template_path = 'wp-job-manager-companies', $default_path = '' ) {
	if ( $args && is_array( $args ) ) {
		// Please, forgive us.
		extract( $args ); // phpcs:ignore WordPress.Functions.DontExtract.extract_extract
	}
	include locate_company_template( $template_name, $template_path, $default_path );
}





/**
 * Locates a template and return the path for inclusion.
 *
 * @since 1.3.0
 */
function locate_company_template( $template_name, $template_path = 'wp-job-manager-companies', $default_path = '' ) {
	// Look within passed path within the theme - this is priority.
	$template = locate_template(
		array(
			trailingslashit( $template_path ) . $template_name,
			$template_name,
		)
	);

	// Return what we found.
	return apply_filters( 'company_locate_template', $template, $template_name, $template_path );
}





/**
 * Get the number of Companies a user has submitted
 *
 * @since 1.4.4
 */
function cariera_count_user_companies( $user_id = 0 ) {
	global $wpdb;

	if ( ! $user_id ) {
		$user_id = get_current_user_id();
	}

	return $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(ID) FROM {$wpdb->posts} WHERE post_author = %d AND post_type = 'company' AND post_status IN ( 'publish', 'pending', 'expired', 'hidden' );", $user_id ) );
}





/**
 * Get Job Listing Post Type Label
 *
 * @since 1.4.4
 */
if ( ! function_exists( 'cariera_get_job_post_label' ) ) {
	function cariera_get_job_post_label( $plural = false ) {

		$job_object = get_post_type_object( 'job_listing' );

		if( ! $plural ) {
			$post_label = is_object( $job_object ) ? $job_object->labels->singular_name : esc_html__( 'Job', 'cariera' );
			if ( ! $post_label ) {
				$post_label = esc_html__( 'Job', 'cariera' );
			}
		} else {
			$post_label = is_object( $job_object ) ? $job_object->labels->name : esc_html__( 'Jobs', 'cariera' );
			if ( ! $post_label ) {
				$post_label = esc_html__( 'Jobs', 'cariera' );
			}
		}

		return $post_label;
	}
}





/**
 * True if an the user can post a company. By default, you must be logged in.
 *
 * @since 1.4.4
 */
function cariera_user_can_post_company() {
	$can_post = true;

	if ( ! is_user_logged_in() ) {
		if ( cariera_user_requires_account() && ! cariera_enable_registration() ) {
			$can_post = false;
		}
	}

	return apply_filters( 'cariera_user_can_post_company', $can_post );
}




/**
 * True if an the user can edit a company.
 *
 * @since 1.4.4
 */
function cariera_user_can_edit_company( $company_id ) {
	$can_edit = true;

	if ( ! $company_id || ! is_user_logged_in() ) {
		$can_edit = false;
		if ( $company_id
			&& ! cariera_company_manager_user_requires_account()
			&& isset( $_COOKIE[ 'wp-job-manager-submitting-company-key-' . $company_id ] )
			&& $_COOKIE[ 'wp-job-manager-submitting-company-key-' . $company_id ] === get_post_meta( $company_id, '_submitting_key', true )
		) {
			$can_edit = true;
		}
	} else {

		$company = get_post( $company_id );

		if ( ! $company || ( absint( $company->post_author ) !== get_current_user_id() && ! current_user_can( 'edit_post', $company_id ) ) ) {
			$can_edit = false;
		}
	}

	return apply_filters( 'cariera_user_can_edit_company', $can_edit, $company_id );
}





/**
 * True if an account is required to post.
 *
 * @since 1.4.4
 */
function cariera_user_requires_account() {
	return apply_filters( 'cariera_company_user_requires_account', get_option( 'cariera_company_user_requires_account' ) == 1 ? true : false );
}





/**
 * True if registration is enabled.
 *
 * @since 1.4.4
 */
function cariera_enable_registration() {
	return apply_filters( 'cariera_enable_registration', get_option( 'cariera_enable_company_registration' ) == 1 ? true : false );
}





/**
 * True if an account is required to post.
 *
 * @since 1.4.4
 */
function cariera_company_manager_user_requires_account() {
	return apply_filters( 'cariera_company_user_requires_account', get_option( 'cariera_company_user_requires_account' ) == 1 ? true : false );
}





/**
 * Whether to create attachments for files that are uploaded with a Company.
 *
 * @since 1.4.4
 */
function cariera_company_attach_uploaded_files() {
	return apply_filters( 'cariera_company_attach_uploaded_files', false );
}





/**
 * Get all Company Taxonomies
 *
 * @since  1.3.0
 */

function cariera_get_all_company_taxonomies() {
    $taxonomies = array();

    $taxonomy_objects = get_object_taxonomies( 'company', 'objects' );
    foreach ( $taxonomy_objects as $taxonomy_object ) {
        $taxonomies[] = array(
            'taxonomy'  => $taxonomy_object->name,
            'name'      => $taxonomy_object->label,
        );
    }

    return $taxonomies;
}





/**
 * Get Singular "Company" Label
 *
 * @since 1.4.4
 */
function cariera_get_company_manager_singular_label( $lowercase = false ){

	$singular = get_option( 'cariera_company_manager_cpt_singular_label', 'Company' );
	// In case user saves with empty value
	if ( empty( $singular ) ) {
		$singular = 'Company';
	}

	$singular = esc_html__( $singular, 'cariera' );

	return apply_filters( 'cariera_company_manager_get_singular_label', $lowercase ? strtolower( $singular ) : $singular );
}





/**
 * Get Plural "Company" Label
 *
 * @since 1.4.4
 */
function cariera_get_company_manager_plural_label( $lowercase = false ){

	$plural = get_option( 'cariera_company_manager_cpt_plural_label', 'Companies' );
	// In case user saves with empty value
	if ( empty( $plural ) ) {
		$plural = 'Companies';
	}

	$plural = esc_html__( $plural, 'cariera' );

	return apply_filters( 'cariera_company_manager_get_plural_label', $lowercase ? strtolower( $plural ) : $plural );
}





/**
 * Gets the company id.
 *
 * @since 1.4.7
 */
function cariera_get_the_company( $post = null ) {
    $post = get_post( $post );

    if ( ! $post || 'job_listing' !== $post->post_type ) {
        return '';
    }

    $company_id = get_post_meta( $post->ID, '_company_manager_id', true );

    return apply_filters( 'cariera_get_the_company', $company_id, $post );
}





/**
 * Get the Company Name from the job's company_manager_id and filter it.
 *
 * @since 1.4.7
 */
function cariera_get_the_company_name( $company_name, $post ) {
	$company_id = cariera_get_the_company( $post );

	if( !empty($company_id) ) {
		$company_name = get_the_title( $company_id );
	} else {
		$company_name = '';
	}

	return $company_name;
}

add_filter( 'the_company_name', 'cariera_get_the_company_name', 10, 2 );





/**
 * Gets the company website from the job's company_manager_id and filter it.
 *
 * @since 1.4.7
 */
function cariera_get_the_company_website_filter( $website, $post ) {
	$company_id = cariera_get_the_company( $post );

	return get_post_meta( $company_id, '_company_website', true );
}

add_filter( 'the_company_website', 'cariera_get_the_company_website_filter', 10, 2 );





/**
 * Gets the company twitter from the job's company_manager_id and filter it.
 *
 * @since 1.4.7
 */
function cariera_get_the_company_twitter_filter( $twitter, $post ) {
	$company_id = cariera_get_the_company( $post );

	return get_post_meta( $company_id, '_company_twitter', true );
}

add_filter( 'the_company_twitter', 'cariera_get_the_company_twitter_filter', 10, 2 );





/**
 * Gets the company tagline from the job's company_manager_id and filter it.
 *
 * @since 1.4.7
 */
function cariera_get_the_company_tagline_filter( $tagline, $post ) {
	$company_id = cariera_get_the_company( $post );

	return get_post_meta( $company_id, '_company_tagline', true );
}

add_filter( 'the_company_tagline', 'cariera_get_the_company_tagline_filter', 10, 2 );





/**
 * Gets the company video from the job's company_manager_id and filter it.
 *
 * @since 1.4.7
 */
function cariera_get_the_company_video_filter( $video, $post ) {
	$company_id = cariera_get_the_company( $post );

	return get_post_meta( $company_id, '_company_video', true );
}

add_filter( 'the_company_video', 'cariera_get_the_company_video_filter', 10, 2 );





/**
 * True if an the user can browse companies.
 *
 * @since 1.4.7
 */
function cariera_user_can_browse_companies() {
	$can_browse = true;
	$caps       = array_filter( array_map( 'trim', array_map( 'strtolower', explode( ',', get_option( 'cariera_company_manager_browse_company_capability' ) ) ) ) );

	if ( $caps ) {
		$can_browse = false;
		foreach ( $caps as $cap ) {
			if ( current_user_can( $cap ) ) {
				$can_browse = true;
				break;
			}
		}
	}

	return apply_filters( 'cariera_company_manager_user_can_browse_companies', $can_browse );
}





/**
 * True if an the user can view a company.
 *
 * @since 1.4.7
 */
function cariera_user_can_view_company( $company_id ) {
	$can_view = true;
	$company   = get_post( $company_id );

	// Allow previews
	if ( $company->post_status === 'preview' ) {
		return true;
	}

	$caps = array_filter( array_map( 'trim', array_map( 'strtolower', explode( ',', get_option( 'cariera_company_manager_view_company_capability' ) ) ) ) );

	if ( $caps ) {
		$can_view = false;
		foreach ( $caps as $cap ) {
			if ( current_user_can( $cap ) ) {
				$can_view = true;
				break;
			}
		}
	}

	if ( $company->post_status === 'expired' ) {
		$can_view = false;
	}

	if ( $company->post_author > 0 && $company->post_author == get_current_user_id() ) {
		$can_view = true;
	}

	if ( ( $key = get_post_meta( $company_id, 'share_link_key', true ) ) && ! empty( $_GET['key'] ) && $key == $_GET['key'] ) {
		$can_view = true;
	}

	return apply_filters( 'cariera_company_manager_user_can_view_company', $can_view, $company_id );
}





/**
 * Check if the option to discourage company search indexing is enabled.
 *
 * @since 1.4.7
 */
function cariera_discourage_company_search_indexing() {
	// Allows overriding the option to discourage search indexing.
	return apply_filters( 'cariera_company_manager_discourage_company_search_indexing', 1 == get_option( 'cariera_company_manager_discourage_company_search_indexing' ) );
}





/*
==================================================================================
    COMPANY SELECTION FUNCTIONS
==================================================================================
*/

/**
 * Get Companies based on the User ID
 *
 * @since  1.4.0
 */
 function cariera_get_user_companies( $user_id = 0 ) {
    if ( $user_id == 0 ) {
        return null;
    }

    $current_user = isset($_GET['user_id']) ? absint( $_GET['user_id'] ) : (isset($user_id) ? $user_id : get_current_user_id());
    $user_company_ids = []; //array to store all of user company term IDs

    // query `job_listing_company` taxonomy terms based on meta
    $args = array(
        'post_type'      => 'company',
        'hide_empty'     => false,
        'author'         => $current_user,
        'posts_per_page' => -1,
    );

    $companies = get_posts( $args ); 

    // narrow the result to only term IDs
    foreach( $companies as $company ) {
        if( !empty($company->ID ) ) {
            array_push( $user_company_ids, $company->ID );
        }
    }

    // return user-specific company term(s) IDs
    return $user_company_ids;
}





/**
 * Modifying the Company Select fields
 *
 * @since  1.4.0
 */
function cariera_modify_company_select_field($args) {

    if ( !current_user_can('administrator') ) {
        // Check if limitation is enabled
        if ( get_option('cariera_user_specific_company') ) {

            // Force returning no values
            $nullify = '99999999999';

            // Check if user is loggedin
            if ( is_user_logged_in() ) {
                $user_id         = get_current_user_id();
                $user_companies  = cariera_get_user_companies($user_id);
                $args['include'] = $user_companies ? $user_companies : $nullify;
            } else { 
                $args['include'] = $nullify;
            }
        } else {
            return $args;
        }
    }

    return $args;
}

add_filter( 'cariera_company_select_field_wp_dropdown_args', 'cariera_modify_company_select_field' );





/**
 * Gets post statuses used for companies.
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'cariera_get_company_post_statuses' ) ) {
	function cariera_get_company_post_statuses() {
		return apply_filters(
			'cariera_company_post_statuses',
			[
				'draft'           => _x( 'Draft', 'post status', 'cariera' ),
				//'expired'         => _x( 'Expired', 'post status', 'cariera' ),
				//'hidden'          => _x( 'Hidden', 'post status', 'cariera' ),
				'preview'         => _x( 'Preview', 'post status', 'cariera' ),
				'pending'         => _x( 'Pending approval', 'post status', 'cariera' ),
				//'pending_payment' => _x( 'Pending payment', 'post status', 'cariera' ),
				'publish'         => _x( 'Active', 'post status', 'cariera' ),
			]
		);
	}
}