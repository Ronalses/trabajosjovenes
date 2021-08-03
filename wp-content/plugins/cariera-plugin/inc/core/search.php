<?php

/**
*
* @package Cariera
*
* @since 1.4.3
* 
* ========================
* CARIERA CORE SEARCH
* ========================
*     
**/



// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}



class Cariera_Core_Search {

    /**
     * The single instance of WordPress_Plugin_Template_Settings.
     * 
     * @since   1.4.3
     */
    private static $_instance = null;


    /**
	 * Constructor function.
	 * 
	 * @since 1.4.3
	 */
    public function __construct() {
        // WP Job Manager
        add_action( 'job_manager_job_filters_search_jobs_end', [ $this, 'wpjm_advanced_search_start' ], 1 );
        add_action( 'job_manager_job_filters_search_jobs_end', [ $this, 'wpjm_advanced_search_end' ], 10 );

        add_action( 'cariera_wpjm_job_filters_search_radius', [ $this, 'search_by_radius_fields' ], 2 );
		add_filter( 'job_manager_get_listings', [ $this,'wpjm_search_by_radius_query' ], 10, 2 );

        add_action( 'job_manager_job_filters_search_jobs_end', [ $this, 'wpjm_search_by_salary_fields' ], 2 );
        add_action( 'cariera_wpjm_sidebar_job_filters_search_jobs_end', [ $this, 'wpjm_search_by_salary_fields' ], 2 );
        add_filter( 'job_manager_get_listings', [ $this, 'wpjm_search_by_salary_query' ], 10, 2 );

        add_action( 'job_manager_job_filters_search_jobs_end', [ $this, 'wpjm_search_by_rate_fields' ], 2 );
        add_action( 'cariera_wpjm_sidebar_job_filters_search_jobs_end', [ $this, 'wpjm_search_by_rate_fields' ], 2 );
        add_filter( 'job_manager_get_listings', [ $this, 'wpjm_search_by_rate_query' ], 10, 2 );


        // WP Resume Manager
        add_action( 'cariera_wprm_job_filters_search_radius', [ $this, 'search_by_radius_fields' ], 2 );
        add_filter( 'resume_manager_get_resumes', [ $this, 'wprm_search_by_radius_query' ], 10, 2 );        

        add_action( 'resume_manager_resume_filters_search_resumes_end', [ $this, 'wprm_search_skills_field' ] );
        add_action( 'cariera_wprm_sidebar_job_filters_search_jobs_end', [ $this, 'wprm_search_skills_field' ] );
        add_filter( 'resume_manager_get_resumes', [ $this, 'wprm_search_skills_query' ], 10, 2 );

        add_action( 'resume_manager_resume_filters_search_resumes_end', [ $this, 'wprm_search_rate_field' ] );        
        add_action( 'cariera_wprm_sidebar_job_filters_search_jobs_end', [ $this, 'wprm_search_rate_field' ] );
        add_filter( 'resume_manager_get_resumes', [ $this, 'wprm_search_rate_query' ], 10, 2 );
    }





    /*
    =====================================================
        WP JOB MANAGER
    =====================================================
    */

    /*
    * Extra job search fields wrapper
    *
    * @since 1.3.6
    */
    public function wpjm_advanced_search_start() {
        if( !get_option('cariera_enable_filter_salary') && !get_option('cariera_enable_filter_rate') ) {
            return;
        }
        
        echo '<div class="advanced-search-btn"><a href="#" id="advance-search">' . esc_html__( 'Advanced Search', 'cariera' ) . '</a></div>';
        echo '<div class="advanced-search-filters">';
    }

    function wpjm_advanced_search_end() {
        if( !get_option('cariera_enable_filter_salary') && !get_option('cariera_enable_filter_rate') ) {
            return;
        }
        
        echo '</div>';
    }





    /*
    * Custom search by salary field for the Job search
    *
    * @since 1.3.6
    */
    public function wpjm_search_by_salary_fields() {
        if( get_option('cariera_enable_filter_salary') ) { ?>
            <div class="search_salary_min">
                <label for="search_salary_min"><?php _e( 'Minimum Salary', 'cariera' ); ?></label>
                <input type="text" class="job-manager-filter" name="search_salary_min" placeholder="<?php esc_attr_e( 'Search Salary Min', 'cariera' ); ?>">
            </div>

            <div class="search_salary_max">
                <label for="search_salary_max"><?php _e( 'Maximum Salary', 'cariera' ); ?></label>
                <input type="text" class="job-manager-filter" name="search_salary_max" placeholder="<?php esc_attr_e( 'Search Salary Max', 'cariera' ); ?>">
            </div>
        <?php }
    }



    // Modifying the job search query
    public function wpjm_search_by_salary_query( $query_args, $args ) {
        if ( isset( $_POST['form_data'] ) ) {
            parse_str( $_POST['form_data'], $form_data );
            
            // If this is set, we are filtering by salary min
            if ( ! empty( $form_data['search_salary_min'] ) ) {
                $salary_min = sanitize_text_field( $form_data['search_salary_min'] );
                
                $query_args['meta_query'][] = array(
                    'key'       => '_salary_min',
                    'value'     => $salary_min,
                    'compare'   => '>=',
                    'type'      => 'NUMERIC',
                );
                
                // This will show the 'reset' link
                add_filter( 'job_manager_get_listings_custom_filter', '__return_true' );
            }
            
            
            // If this is set, we are filtering by salary max
            if ( ! empty( $form_data['search_salary_max']  ) ) {
                $salary_max = sanitize_text_field( $form_data['search_salary_max'] );
                
                $query_args['meta_query'][] = array(
                    'key'       => '_salary_max',
                    'value'     => $salary_max,
                    'compare'   => '<=',
                    'type'      => 'NUMERIC',
                );
                
                // This will show the 'reset' link
                add_filter( 'job_manager_get_listings_custom_filter', '__return_true' );
            }
            
        }

        return $query_args;
    }





    /*
    * Custom search by rate field for the Job search
    *
    * @since 1.3.6
    */
    public function wpjm_search_by_rate_fields() {        
        if( get_option('cariera_enable_filter_rate') ) { ?>
            <div class="search_rate_min">
                <label for="search_rate_min"><?php _e( 'Minimum Rate', 'cariera' ); ?></label>
                <input type="text" class="job-manager-filter" name="search_rate_min" placeholder="<?php esc_attr_e( 'Search Rate Min', 'cariera' ); ?>">
            </div>

            <div class="search_rate_max">
                <label for="search_rate_max"><?php _e( 'Maximum Rate', 'cariera' ); ?></label>
                <input type="text" class="job-manager-filter" name="search_rate_max" placeholder="<?php esc_attr_e( 'Search Rate Max', 'cariera' ); ?>">
            </div>
        <?php }
    }



    // Modifying the job search query
    public function wpjm_search_by_rate_query( $query_args, $args ) {
        if ( isset( $_POST['form_data'] ) ) {
            parse_str( $_POST['form_data'], $form_data );
            
            // If this is set, we are filtering by salary min
            if ( ! empty( $form_data['search_rate_min'] ) ) {
                $rate_min = sanitize_text_field( $form_data['search_rate_min'] );
                
                $query_args['meta_query'][] = array(
                    'key'       => '_rate_min',
                    'value'     => $rate_min,
                    'compare'   => '>=',
                    'type'      => 'NUMERIC',
                );
                
                // This will show the 'reset' link
                add_filter( 'job_manager_get_listings_custom_filter', '__return_true' );
            }
            
            
            // If this is set, we are filtering by salary max
            if ( ! empty( $form_data['search_rate_max']  ) ) {
                $rate_max = sanitize_text_field( $form_data['search_rate_max'] );
                
                $query_args['meta_query'][] = array(
                    'key'       => '_rate_max',
                    'value'     => $rate_max,
                    'compare'   => '<=',
                    'type'      => 'NUMERIC',
                );
                
                // This will show the 'reset' link
                add_filter( 'job_manager_get_listings_custom_filter', '__return_true' );
            }
        }
        
        return $query_args;
    }





    /*
    * Custom search by location radius for the Job search
    *
    * @since 1.4.3
    */
    public function search_by_radius_fields() {
        $search_radius = cariera_get_option( 'cariera_search_radius' );
        if ( $search_radius ) { ?>
            
            <div class="search_radius" >
                <div class="range-slider">
                    <input name="search_radius" id="search_radius" class="distance-radius" type="range" min="1" max="<?php echo cariera_get_option( 'cariera_max_radius_search_value' ); ?>" step="1" value="<?php cariera_get_option( 'cariera_radius_unit' ); ?>" data-title="<?php echo esc_html__( 'Radius around selected location.', 'cariera' ) ?>">
                </div>
            </div>
        <?php }
    }



    // Modifying the job search query
    public function wpjm_search_by_radius_query( $query_args, $args ) {
		if ( isset( $_POST['form_data'] ) ) {
			parse_str( $_POST['form_data'], $form_data );

			// If this is set, we are filtering by radius
			if( isset( $form_data['search_radius']) && !empty( $form_data['search_radius'] ) ) {
				
				if ( ! empty( $form_data['search_radius'] ) ) {
					$address        = $form_data['search_location'];
					$radius         = $form_data['search_radius'];
                    $radius_type    = cariera_get_option( 'cariera_radius_unit' );
                    
					if( !empty($address) ) {
						$latlng         = cariera_geocode($address);
						$nearbyposts    = cariera_get_nearby_listings($latlng[0], $latlng[1], $radius, $radius_type ); 
						cariera_array_sort_by_column( $nearbyposts,'distance' );

						$ids = array_unique(array_column($nearbyposts, 'post_id'));
						if ( !empty($ids) ) {
							$query_args['post__in'] = $ids;
							unset( $query_args['meta_query'][0]); 
						}
					}

					// This will show the 'reset' link
					add_filter( 'job_manager_get_listings_custom_filter', '__return_true' );
				}
			} else {
				add_filter( 'job_manager_get_listings_custom_filter', '__return_true' );
			}
		}

		return $query_args;
	}




    
    /*
    =====================================================
        WP RESUME MANAGER
    =====================================================
    */

    /*
    * Custom search by skills field for the Resume search
    *
    * @since 1.3.6
    */
    public function wprm_search_skills_field() {
        
        if ( get_option( 'resume_manager_enable_skills' ) ) {
            
            if ( ! empty( $_GET['search_skills'] ) ) {
                $selected_skills = sanitize_text_field( $_GET['search_skills'] );
            } else {
                $selected_skills  = '';
            }
            
            if ( ! is_tax( 'resume_skill' ) && get_terms( 'resume_skill' ) ) { ?>
                <div class="search_skills resume-filter">
                    <label for="search_skills"><?php esc_html_e( 'Filter by Skills', 'cariera' ); ?></label>
                    <?php job_manager_dropdown_categories( array( 'taxonomy' => 'resume_skill', 'hierarchical' => 1, 'name' => 'search_skills', 'orderby' => 'name', 'selected' => $selected_skills, 'hide_empty' => false, 'class' => 'cariera-select2', 'id'=>'search_skills', 'placeholder'=> esc_html__( 'Choose a skill', 'cariera' ) ) ); ?>					
                </div>
            <?php }
        }
    }



    // Modifying the resume search query
    public function wprm_search_skills_query( $query_args, $args ) {
        if ( isset( $_POST['form_data'] ) ) {
            parse_str( $_POST['form_data'], $form_data );

            // If this is set, we are filtering by salary
            if( isset( $form_data['search_skills'])) {
                if ( ! empty( $form_data['search_skills'] ) ) {
                        
                    $field    = is_numeric( $form_data['search_skills'][0] ) ? 'term_id' : 'slug';
                    $operator = 'all' === sizeof( $form_data['search_skills'] ) > 1 ? 'AND' : 'IN';
        
                    $query_args['tax_query'][] = array(
                        'taxonomy'         => 'resume_skill',
                        'field'            => $field,
                        'terms'            => array_values( $form_data['search_skills'] ),
                        'include_children' => $operator !== 'AND' ,
                        'operator'         => $operator
                    );
                    
                    // This will show the 'reset' link
                    add_filter( 'resume_manager_get_resumes_custom_filter', '__return_true' );                    
                }
            }
        }
        return $query_args;

    }





    /*
    * Custom search by rate field for the Resume search
    *
    * @since 1.3.6
    */
    public function wprm_search_rate_field() { ?>
        <div class="search_by_rate resume-filter">
            <label for="search_by_rate"><?php _e( 'Minimum Rate', 'cariera' ); ?></label>
            <input type="text" name="search_by_rate" id="search_by_rate"  placeholder="<?php esc_attr_e( 'Search by minimum rate', 'cariera'); ?>">
        </div>
    <?php
    }



    // Modifying the resume search query
    public function wprm_search_rate_query( $query_args, $args ) {
        if ( isset( $_POST['form_data'] ) ) {
            parse_str( $_POST['form_data'], $form_data );

            if ( ! empty( $form_data['search_by_rate'] ) ) {
                $rate = sanitize_text_field( $form_data['search_by_rate'] );

                $query_args['meta_query'][] = array(
                    'key'     => '_rate',
                    'value'   => $rate,
                    'compare' => '>=',
                    'type'    => 'NUMERIC'
                );

                // This will show the 'reset' link
                add_filter( 'resume_manager_get_resumes_custom_filter', '__return_true' );
            }
            
        }
        
        return $query_args;
    }





    /*
    * Custom search by location radius for the Resume search
    *
    * @since 1.4.3
    */
    // Modifying the job search query
    public function wprm_search_by_radius_query( $query_args, $args ) {
		if ( isset( $_POST['form_data'] ) ) {
			parse_str( $_POST['form_data'], $form_data );

			// If this is set, we are filtering by radius
			if( isset( $form_data['search_radius']) && !empty( $form_data['search_radius'] ) ) {
				
				if ( ! empty( $form_data['search_radius'] ) ) {
					$address        = $form_data['search_location'];
					$radius         = $form_data['search_radius'];
                    $radius_type    = cariera_get_option( 'cariera_radius_unit' );
                    
					if( !empty($address) ) {
						$latlng         = cariera_geocode($address);
						$nearbyposts    = cariera_get_nearby_listings($latlng[0], $latlng[1], $radius, $radius_type ); 
						cariera_array_sort_by_column( $nearbyposts,'distance' );

						$ids = array_unique(array_column($nearbyposts, 'post_id'));
						if ( !empty($ids) ) {
							$query_args['post__in'] = $ids;
							unset( $query_args['meta_query'][0]); 
						}
					}

					// This will show the 'reset' link
					add_filter( 'resume_manager_get_resumes_custom_filter', '__return_true' );
				}
			} else {
				add_filter( 'resume_manager_get_resumes_custom_filter', '__return_true' );
			}
		}

		return $query_args;
	}


}