<?php

/**
*
* @package Cariera
*
* @since 1.4.6
* 
* ========================
* CARIERA CORE CPT
* ========================
*     
**/



// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}



class Cariera_Core_CPT {

    /**
     * The single instance of WordPress_Plugin_Template_Settings.
     * 
     * @since 1.4.6
     */
    private static $_instance = null;


    /**
	 * Constructor function.
	 * 
	 * @since 1.4.6
	 */
    public function __construct() {
        add_action( 'init', [ $this, 'register_job_listing_taxonomies' ] );
        add_action( 'init', [ $this, 'register_resume_taxonomies' ] );
    }





    /*
    =====================================================
        WP JOB MANAGER
    =====================================================
    */

    public function register_job_listing_taxonomies() {
        if ( ! post_type_exists( 'job_listing' ) ) {
            return;
        }

        $admin_capability = 'manage_job_listings';

        // Taxonomies
        $taxonomies_args = apply_filters( 'cariera_job_listing_taxonomies_list', array(
            'job_listing_career_level'  => array(
                'singular'                  => esc_html__( 'Job Career Level', 'cariera' ),
                'plural'                    => esc_html__( 'Job Career level', 'cariera' ),
                'slug'                      => esc_html_x( 'job-career-level', 'Job career level permalink - resave permalinks after changing this', 'cariera' ),
                'enable'                    => get_option( 'cariera_job_manager_enable_career_level', true )
            ),
            'job_listing_experience'    => array(
                'singular'                  => esc_html__( 'Job Experience', 'cariera' ),
                'plural'                    => esc_html__( 'Job Experience', 'cariera' ),
                'slug'                      => esc_html_x( 'job-experience', 'Job experience permalink - resave permalinks after changing this', 'cariera' ),
                'enable'                    => get_option( 'cariera_job_manager_enable_experience', true )
            ),
            'job_listing_qualification' => array(
                'singular'                  => esc_html__( 'Job Qualification', 'cariera' ),
                'plural'                    => esc_html__( 'Job Qualification', 'cariera' ),
                'slug'                      => esc_html_x( 'job-qualification', 'Job qualification permalink - resave permalinks after changing this', 'cariera' ),
                'enable'                    => get_option( 'cariera_job_manager_enable_qualification', true )
            )
        ) );

        foreach ( $taxonomies_args as $taxonomy_name => $taxonomy_args ) {
            if( $taxonomy_args['enable'] ) {
                $singular  = $taxonomy_args['singular'];
                $plural    = $taxonomy_args['plural'];
                $slug      = $taxonomy_args['slug'];

                $args = apply_filters( 'register_taxonomy_{$taxonomy_name}_args', array(
                        'hierarchical'      => true,
                        'update_count_callback' => '_update_post_term_count',
                        'label'             => $plural,
                        'labels'            => array(
                            'name'              => $plural,
                            'singular_name'     => $singular,
                            'menu_name'         => ucwords( $plural ),
                            'search_items'      => sprintf( esc_html__( 'Search %s', 'cariera' ), $plural ),
                            'all_items'         => sprintf( esc_html__( 'All %s', 'cariera' ), $plural ),
                            'parent_item'       => sprintf( esc_html__( 'Parent %s', 'cariera' ), $singular ),
                            'parent_item_colon' => sprintf( esc_html__( 'Parent %s:', 'cariera' ), $singular ),
                            'edit_item'         => sprintf( esc_html__( 'Edit %s', 'cariera' ), $singular ),
                            'update_item'       => sprintf( esc_html__( 'Update %s', 'cariera' ), $singular ),
                            'add_new_item'      => sprintf( esc_html__( 'Add New %s', 'cariera' ), $singular ),
                            'new_item_name'     => sprintf( esc_html__( 'New %s Name', 'cariera' ),  $singular )
                        ),
                        'show_ui'               => true,
                        'show_in_rest'          => true,
                        'show_tagcloud'         => false,
                        'public'                => true,
                        'capabilities'          => array(
                            'manage_terms'      => $admin_capability,
                            'edit_terms'        => $admin_capability,
                            'delete_terms'      => $admin_capability,
                            'assign_terms'      => $admin_capability,
                        ),
                        'rewrite'           => array(
                            'slug'          => $slug,
                            'with_front'    => false,
                            'hierarchical'  => true
                        )
                    )
                );

                register_taxonomy( $taxonomy_name, 'job_listing', $args );
            }
        }
    }





    /*
    =====================================================
        WP RESUME MANAGER
    =====================================================
    */

    public function register_resume_taxonomies() {
        if ( ! post_type_exists( 'resume' ) ) {
            return;
        }

        $admin_capability = 'manage_resumes';

        // Taxonomies
        $taxonomies_args = apply_filters( 'cariera_resume_taxonomies_list', array(
            'resume_education_level'  => array(
                'singular'                  => esc_html__( 'Candidate Education', 'cariera' ),
                'plural'                    => esc_html__( 'Candidate Education', 'cariera' ),
                'slug'                      => esc_html_x( 'resume-education', 'Candidate education permalink - resave permalinks after changing this', 'cariera' ),
                'enable'                    => get_option( 'cariera_resume_manager_enable_education', true )
            ),
            'resume_experience'    => array(
                'singular'                  => esc_html__( 'Candidate Experience', 'cariera' ),
                'plural'                    => esc_html__( 'Candidate Experience', 'cariera' ),
                'slug'                      => esc_html_x( 'resume-experience', 'Candidate experience permalink - resave permalinks after changing this', 'cariera' ),
                'enable'                    => get_option( 'cariera_resume_manager_enable_experience', true )
            ),
        ) );

        foreach ( $taxonomies_args as $taxonomy_name => $taxonomy_args ) {
            if( $taxonomy_args['enable'] ) {
                $singular  = $taxonomy_args['singular'];
                $plural    = $taxonomy_args['plural'];
                $slug      = $taxonomy_args['slug'];

                $args = apply_filters( 'register_taxonomy_{$taxonomy_name}_args', array(
                        'hierarchical'      => true,
                        'update_count_callback' => '_update_post_term_count',
                        'label'             => $plural,
                        'labels'            => array(
                            'name'              => $plural,
                            'singular_name'     => $singular,
                            'menu_name'         => ucwords( $plural ),
                            'search_items'      => sprintf( esc_html__( 'Search %s', 'cariera' ), $plural ),
                            'all_items'         => sprintf( esc_html__( 'All %s', 'cariera' ), $plural ),
                            'parent_item'       => sprintf( esc_html__( 'Parent %s', 'cariera' ), $singular ),
                            'parent_item_colon' => sprintf( esc_html__( 'Parent %s:', 'cariera' ), $singular ),
                            'edit_item'         => sprintf( esc_html__( 'Edit %s', 'cariera' ), $singular ),
                            'update_item'       => sprintf( esc_html__( 'Update %s', 'cariera' ), $singular ),
                            'add_new_item'      => sprintf( esc_html__( 'Add New %s', 'cariera' ), $singular ),
                            'new_item_name'     => sprintf( esc_html__( 'New %s Name', 'cariera' ),  $singular )
                        ),
                        'show_ui'               => true,
                        'show_tagcloud'         => false,
                        'public'                => true,
                        'capabilities'          => array(
                            'manage_terms'      => $admin_capability,
                            'edit_terms'        => $admin_capability,
                            'delete_terms'      => $admin_capability,
                            'assign_terms'      => $admin_capability,
                        ),
                        'rewrite'           => array(
                            'slug'          => $slug,
                            'with_front'    => false,
                            'hierarchical'  => true
                        )
                    )
                );

                register_taxonomy( $taxonomy_name, 'resume', $args );
            }
        }
    }

}