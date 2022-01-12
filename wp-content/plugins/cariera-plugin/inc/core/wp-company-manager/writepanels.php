<?php
/**
*
* @package Cariera
*
* @since    1.3.0
* @version  1.5.0
* 
* ========================
* CARIERA COMPANY MANAGER - WRITEPANELS
* ========================
*     
**/



// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! class_exists( 'WP_Job_Manager_Writepanels' ) ) {
    include( JOB_MANAGER_PLUGIN_DIR . '/includes/admin/class-wp-job-manager-writepanels.php' );
}



/**
 * Cariera_Company_Writepanels Class
 */
class Cariera_Company_Manager_Writepanels extends WP_Job_Manager_Writepanels {

    public function __construct() {
        add_action( 'add_meta_boxes', [ $this, 'add_meta_boxes' ] );
        add_action( 'save_post', [ $this, 'save_post' ], 1, 2 );
        add_action( 'cariera_save_company', [ $this, 'save_company_data' ], 1, 2 );
    }

    
    
    /**
     * Company Fields
     *
     * @since  1.3.0
     */
    public static function company_fields() {
        
        $fields = apply_filters( 'cariera_company_manager_fields', [
            '_company_tagline'  => [
                'label'         => esc_html__( 'Tagline', 'cariera' ),
                'placeholder'   => esc_html__( 'Brief description about the company', 'cariera' )
            ],
            '_company_location' => [
                'label'         => esc_html__( 'Location', 'cariera' ),
                'placeholder'   => esc_html__( 'e.g. "London"', 'cariera' ),
                'description'   => ''
            ],
            '_company_email'    => [
                'label'         => esc_html__( 'Email', 'cariera' ),
                'placeholder'   => esc_html__( 'you@yourdomain.com', 'cariera' ),
                'description'   => ''
            ],
            '_company_website'  => [
                'label'         => esc_html__( 'Website', 'cariera' ),
                'placeholder'   => ''
            ],
            '_company_phone'    => [
                'label'         => esc_html__( 'Phone', 'cariera' ),
                'placeholder'   => esc_html__( 'Enter the company\'s phone number', 'cariera' ),
                'description'   => ''
            ],
            '_company_facebook' => [
                'label'         => esc_html__( 'Facebook', 'cariera' ),
                'placeholder'   => esc_html__( 'company facebook page link', 'cariera' ),
            ],
            '_company_twitter'  => [
                'label'         => esc_html__( 'Twitter', 'cariera' ),
                'placeholder'   => esc_html__( 'company twitter page link', 'cariera' ),
            ],
            '_company_linkedin' => [
                'label'         => esc_html__( 'LinkedIn', 'cariera' ),
                'placeholder'   => esc_html__( 'company linkedin page link', 'cariera' ),
            ],
            '_company_video'    => [
                'label'         => esc_html__( 'Video', 'cariera' ),
                'placeholder'   => esc_html__( 'URL to the company video', 'cariera' ),
                'type'          => 'file'
            ],
            '_company_since'    => [
                'label'         => esc_html__( 'Since', 'cariera' ),
                'placeholder'   => esc_html__( 'company established', 'cariera' ),
            ],
            '_company_header_image' => [
                'label'         => esc_html__( 'Company Header Image', 'cariera' ),
                'type'          => 'file',
                'description'   => ''
            ],
            '_featured'         => [
                'label'         => esc_html__( 'Feature this Company?', 'cariera' ),
                'type'          => 'checkbox',
                'description'   => esc_html__( 'Featured companies will be styled differently.', 'cariera' ),
            ],
            '_company_author'   => [
                'label'         => esc_html__( 'Posted by', 'cariera' ),
                'type'          => 'author'
            ]
        ] );

        return $fields;
    }

    
    
    /**
     * Add meta box
     *
     * @since  1.3.0
     */
    public function add_meta_boxes() {
        add_meta_box( 'company_data', esc_html__( 'Company Data', 'cariera' ), [ $this, 'company_data' ], 'company', 'normal', 'high' );
    }



    
    
    /**
     * Company Data
     *
     * @since  1.3.0
     */    
    public function company_data( $post ) {
        global $post, $thepostid;

        $thepostid = $post->ID;

        echo '<div class="wp_company_manager_meta_data wp_job_manager_meta_data">';

        wp_nonce_field( 'save_meta_data', 'company_manager_nonce' );

        do_action( 'cariera_company_manager_company_data_start', $thepostid );

        foreach ( $this->company_fields() as $key => $field ) {
            $type = ! empty( $field['type'] ) ? $field['type'] : 'text';
            
            // Fix not saving fields
            if ( ! isset( $field['value'] ) && metadata_exists( 'post', $thepostid, $key ) ) {
				$field['value'] = get_post_meta( $thepostid, $key, true );
			}

			if ( ! isset( $field['value'] ) && isset( $field['default'] ) ) {
				$field['value'] = $field['default'];
			} elseif ( ! isset( $field['value'] ) ) {
				$field['value'] = '';
			}
            
            if( has_action( 'company_manager_input_' . $type ) ) {
                do_action( 'company_manager_input_' . $type, $key, $field );
            } elseif( method_exists( $this, 'input_' . $type ) ) {
                call_user_func( array( $this, 'input_' . $type ), $key, $field );
            }
        }

        do_action( 'cariera_company_data_end', $thepostid );

        echo '</div>';
    }

    
    
    /**
     * Triggered on Save Post
     *
     * @since  1.3.0
     */    
    public function save_post( $post_id, $post ) {
        if ( empty( $post_id ) || empty( $post ) || empty( $_POST ) ) {
			return;
		}
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( is_int( wp_is_post_revision( $post ) ) ) {
			return;
		}
		if ( is_int( wp_is_post_autosave( $post ) ) ) {
			return;
		}
        if ( empty( $_POST['company_manager_nonce'] ) || ! wp_verify_nonce( $_POST['company_manager_nonce'], 'save_meta_data' ) ) { 
            return;
        }
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
        if ( 'company' !== $post->post_type ) {
			return;
		}

        do_action( 'cariera_save_company', $post_id, $post );
    }

    
    
    /**
     * Save Company Meta
     *
     * @since   1.3.0
     * @version 1.5.0
     */    
    public function save_company_data( $post_id, $post ) {
        global $wpdb;

        // These need to exist
        add_post_meta( $post_id, '_featured', 0, true );

        foreach ( $this->company_fields() as $key => $field ) {

            // Expirey date
            if ( '_company_founded' === $key ) {
                if ( ! empty( $_POST[ $key ] ) ) {
                    update_post_meta( $post_id, $key, date( 'Y-m-d', strtotime( sanitize_text_field( $_POST[ $key ] ) ) ) );
                } else {
                    update_post_meta( $post_id, $key, '' );
                }
            }

            elseif ( '_company_location' === $key ) {
                if ( update_post_meta( $post_id, $key, sanitize_text_field( $_POST[ $key ] ) ) ) {
                    do_action( 'cariera_company_manager_company_location_edited', $post_id, sanitize_text_field( $_POST[ $key ] ) );
                } elseif ( apply_filters( 'cariera_company_manager_geolocation_enabled', true ) && ! WP_Job_Manager_Geocode::has_location_data( $post_id ) ) {
                    WP_Job_Manager_Geocode::generate_location_data( $post_id, sanitize_text_field( $_POST[ $key ] ) );
                }
                continue;
            }

            elseif( '_company_author' === $key ) {
                $wpdb->update( $wpdb->posts, array( 'post_author' => $_POST[ $key ] > 0 ? absint( $_POST[ $key ] ) : 0 ), array( 'ID' => $post_id ) );
            }

            // Everything else
            else {
                $type = !empty( $field['type'] ) ? $field['type'] : '';

                switch ( $type ) {
                    case 'textarea' :    
                    case 'wp_editor' :
                    case 'wp-editor' :
                        update_post_meta( $post_id, $key, wp_kses_post( stripslashes( $_POST[ $key ] ) ) );
                    break;
                    case 'checkbox' :
                        if ( isset( $_POST[ $key ] ) ) {
                            update_post_meta( $post_id, $key, 1 );
                        } else {
                            update_post_meta( $post_id, $key, 0 );
                        }
                    break;
                    default :
                        if ( is_array( $_POST[ $key ] ) ) {
                            update_post_meta( $post_id, $key, array_filter( array_map( 'sanitize_text_field', $_POST[ $key ] ) ) );
                        } else {
                            update_post_meta( $post_id, $key, sanitize_text_field( $_POST[ $key ] ) );
                        }
                    break;
                }
            }
        }
    }
}