<?php

/**
*
* @package Cariera
*
* @since 1.4.4
* 
* ========================
* CARIERA COMPANY MANAGER - WPJM INTEGRATION
* ========================
*     
**/


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}



class Cariera_Company_Manager_WPJM {


    private $values = [];
    private $submit_instance = null;

    
    /**
	 * Constructor.
	 * 
	 * @since 1.4.4
	 */
	public function __construct() {
        
        // // Backend Fields & Integration
        add_filter( 'job_manager_job_listing_data_fields', [ $this, 'job_admin_fields' ], 9999 );

        // Frontend Fields & Integration
        add_action( 'submit_job_form_company_fields_start', [ $this, 'company_selection' ], 99 );
        add_filter( 'submit_job_form_fields', [ $this, 'company_fields' ] );
        add_action( 'submit_job_form_company_fields_end', [ $this, 'add_company_fields' ] );
        add_action( 'job_manager_update_job_data', [ $this, 'update_job_form_fields' ], 99, 2 );
        add_filter( 'submit_job_form_validate_fields', [ $this, 'validate_fields' ], 99, 3 );

        // Schema data for new Company Handling
        add_filter( 'wpjm_get_job_listing_structured_data', [ $this, 'job_listing_company_name_structured_data' ], 10, 2 );

        // Metakey Migration
        if( !get_option( 'cariera_company_metakey_migration_init' ) ) {
            add_action( 'admin_init', [ $this, 'company_metakey_migration' ] );
        }
    }





    /**
	 * Check if the Cariera Company Manager integrations is enabled
	 * 
	 * @since 1.4.4
	 */
	function integration_enabled() {
		return get_option( 'cariera_company_manager_integration', false );
    }





    /**
	 * Removing default wpjm company meta fields
	 * 
	 * @since 1.4.4
	 */
	function job_admin_fields( $fields ) {

		if ( ! $this->integration_enabled() ) {
			return $fields;
		}

        if ( isset( $fields['_company_name'] ) ) {
			unset( $fields['_company_name'] );
		}

		if ( isset( $fields['_company_website'] ) ) {
			unset( $fields['_company_website'] );
		}

		if ( isset( $fields['_company_tagline'] ) ) {
			unset( $fields['_company_tagline'] );
		}

		if ( isset( $fields['_company_twitter'] ) ) {
			unset( $fields['_company_twitter'] );
		}

		if ( isset( $fields['_company_video'] ) ) {
			unset( $fields['_company_video'] );
        }
        
        $fields['_company_manager_id'] = [
			'label'    => esc_html__( 'Company', 'cariera' ),
			'type'     => 'company_select',
			'priority' => 0.1,
			'options'  => [],
		];

		return $fields;
	}





    /*
	* Company Selection 
	*
	* @since 1.4.0
	*/
	function company_selection() {

		if ( ! $this->integration_enabled() || isset( $_GET['action'] ) ) {
			return;
        }
        
        wp_enqueue_script( 'cariera-company-manager-submission' );
		
		if ( get_option('cariera_user_specific_company') ) {
			$user_id         = get_current_user_id();
			$user_companies  = cariera_get_user_companies($user_id);
			$status_class    = $user_companies ? ['has-companies'] : ['no-companies'];
		} else {
			$status_class = ['has-companies'];
		}

		$add_new_company  = get_option( 'cariera_add_new_company' );
		$submission_limit = get_option( 'cariera_company_submission_limit' );
		$total_companies  = cariera_count_user_companies();
		$checked 		  = ( $add_new_company && ( $total_companies < $submission_limit || ! $submission_limit ) ) ? '' : esc_attr('checked');
		
		if( $add_new_company && ( $total_companies < $submission_limit || ! $submission_limit ) ) { 
			$status_class[]	= '';
		} else {
			$status_class[]	= 'disable-add-company';
		}
		
		echo '<div id="company-selection" class="' . esc_attr( join( ' ', $status_class ) ) . '">';
		
			if( $add_new_company && ( $total_companies < $submission_limit || ! $submission_limit ) ) {
				echo '<div class="fieldset new-company">';
					echo '<input type="radio" name="company_submission" id="new-company" value="new_company" class="company-selection-radio" checked>';
					echo '<label for="new-company">';
						echo '<span class="icon"><i class="icon-plus"></i></span>';
						echo '<span class="text">' . esc_html__( 'New Company', 'cariera' ) . '</span>';
					echo '</label>';
				echo '</div>';
			}

			echo '<div class="fieldset existing-company">';
				echo '<input type="radio" name="company_submission" id="existing-company" value="existing_company" class="company-selection-radio" ' . $checked . '>';
				echo '<label for="existing-company">';
					echo '<span class="icon"><i class="far fa-building"></i></span>';
					echo '<span class="text">' . esc_html__( 'Existing Company', 'cariera' ) . '</span>';
				echo '</label>';
			echo '</div>';
		echo '</div>';

		echo '<fieldset class="no-companies-message hidden">';
			echo '<p class="job-manager-error">';
				echo esc_html__( 'You either have not logged in or you don\'t have any companies with this account.', 'cariera' );
			echo '</p>';
		echo '</fieldset>';
    }
    




    /**
     * Removing default company fields
     * 
     * @since 1.4.4
     */
    function company_fields( $fields ) {

        if ( ! $this->integration_enabled() ) {
            return $fields;
        }

        if ( isset( $fields['company'] ) ) {
            unset( $fields['company'] );
        }

        $fields['company']['company_manager_id'] = array(
            'label'       => esc_html__( 'Select Company', 'cariera' ),
            'type'        => 'company-select',
            'required'    => false,
            'description' => '',
            'priority'    => '0.1',
            'default'     => -1,
            'options'     => array(),
        );
    
        return $fields;
    }





    /**
	 * Getting all the company fields
	 * 
	 * @since 1.3.0
	 */
    function submit_company_form_fields() {
        
        $fields = Cariera_Company_Manager_Form_Submit_Company::get_company_fields();

        return apply_filters( 'cariera_submit_job_form_company_fields' , $fields );
    }





    /**
	 * Adding company fields
	 * 
	 * @since 1.3.0
	 */
    function add_company_fields() {

        if ( ! $this->integration_enabled() || isset( $_GET['action'] ) ) {
			return;
		}

        $company_fields  = $this->submit_company_form_fields();

        $job_id     = ! empty( $_REQUEST['job_id'] ) ? absint( $_REQUEST['job_id'] ) : 0;
        $company_id = 0;

        if ( ! job_manager_user_can_edit_job( $job_id ) ) {
            $job_id = 0;
        }

        if( $job_id ) {
            $company_id = get_post_meta( $job_id, '_company_manager_id', true );
            if( ! empty( $company_id ) ) {
                $company = get_post( $company_id );
            }
        }

        foreach ( $company_fields as $key => $field ) {
            if( $company_id ) {
                if ( ! isset( $field['value'] ) ) {
                    if( 'company_name' === $key ) {
                        $field['value'] = $company->post_title;
                    } elseif ( 'company_content' === $key ) {
                        $field['value'] = $company->post_content;
                    } elseif ( ! empty( $field['taxonomy'] ) ) {
                        $field['value'] = wp_get_object_terms( $company->ID, $field['taxonomy'], array( 'fields' => 'ids' ) );
                    } else {
                        $field['value'] = get_post_meta( $company->ID, '_' . $key, true );
                    }
                }
            } ?>
            <fieldset class="fieldset-<?php echo esc_attr( $key ); ?> cariera-company-manager-fieldset">
                <label for="<?php echo esc_attr( $key ); ?>"><?php echo wp_kses_post( $field['label'] ) . wp_kses_post( apply_filters( 'submit_job_form_required_label', $field['required'] ? '' : ' <small>' . esc_html__( '(optional)', 'cariera' ) . '</small>', $field ) ); ?></label>
                <div class="field <?php echo esc_attr( $field['required'] ? 'required-field' : '' ); ?>">
                    <?php get_job_manager_template( 'form-fields/' . $field['type'] . '-field.php', array( 'key' => $key, 'field' => $field ) ); ?>
                </div>
            </fieldset>
        <?php }

    }
    




    /**
     * Updating custom fields
     *
     * @since  1.3.0
     */
    function update_job_form_fields( $job_id, $values ) {

        if ( ! $this->integration_enabled() ) {
			return;
        }
        
        // Post the values
		if( isset($_POST['company_submission']) && $_POST['company_submission'] === 'new_company' ) {

            $values = $this->get_posted_values();

            if( !empty( $values ) ) {
                $post_id    = get_post_meta( $job_id, '_company_manager_id', true );
                $company_id = !empty( $post_id ) ? $post_id : 0;

                if ( $company_id == 0 ) {
                    $company_id = $this->get_submit_form()->save_company( $values['company_fields']['company_name'], $values['company_fields']['company_content'], get_option( 'cariera_company_submission_requires_approval' ) ? 'pending' : 'publish', $values );
                    $this->get_submit_form()->update_company_data( $values );
                } else {
                    $company_id = $this->get_submit_form()->save_company( $values['company_fields']['company_name'], $values['company_fields']['company_content'], get_option( 'cariera_company_submission_requires_approval' ) ? 'pending' : 'publish', $values, $company_id );
                    $this->get_submit_form()->update_company_data( $values );
                }
            }

            update_post_meta( $job_id, '_company_manager_id', $company_id );

        } else {
            if( !empty( $_POST['company_manager_id'] ) ) {
                $company_id = absint( $_POST['company_manager_id'] );

                update_post_meta( $job_id, '_company_manager_id', $company_id );                
            }
        }

    }





    /**
     * Validate Fields
     *
     * @since  1.4.7
     */
    function validate_fields( $valid, $fields, $values ) {

		if( ! isset( $_POST['company_manager_id'] ) || is_wp_error( $valid ) || ! $valid ) {
			return $valid;
		}

		if( isset( $_POST['company_submission'] ) && $_POST['company_submission'] === 'new_company' ) {
			try {
				return $this->get_submit_form()->validate_fields( $this->get_posted_values() );
			} catch ( Exception $e ) {
				return new WP_Error( 'thrown-error', $e->getMessage() );
			}
        }

		return $valid;
    }





    /**
	 * Init the "Cariera_Company_Manager_Form_Submit_Company" class
	 * 
	 * @since 1.3.0
	 */
    function get_submit_form() {
		if( ! $this->submit_instance ) {
			$this->submit_instance = Cariera_Company_Manager_Form_Submit_Company::instance();
		}

		return $this->submit_instance;
    }
    




    /**
	 * Get posted company values
	 * 
	 * @since 1.4.7
	 */
    function get_posted_values() {

		if( empty( $this->values ) ) {
			// Init fields
			$this->get_submit_form()->init_fields();
			// Get posted values
			$this->values = $this->get_submit_form()->job_submit_get_posted_fields();
		}

		return $this->values;
    }





    /**
	 * Schema data for the new pulled company
	 * 
	 * @since 1.4.7
	 */
    function job_listing_company_name_structured_data( $data, $post ) {

        if ( ! $this->integration_enabled() ) {
			return;
        }

        if( get_post_type( $post ) == 'job_listing' ) {
            $company_id = get_post_meta( $post->ID, '_company_manager_id', true );

            if( isset( $data['hiringOrganization'] ) && !empty( $company_id ) ) {
                $company_name = get_the_title( $company_id );
                $data['hiringOrganization']['name'] = $company_name;
            }
        }
    }





    /**
	 * "_company_name" meta migration to "_company_manager_id"
	 * 
	 * @since 1.4.7
	 */
    function company_metakey_migration() {
        // Get all job posts
        $job_listing = get_posts([ 'numberposts' => -1, 'post_type' => 'job_listing' ]);

        // Stop the migration if there are no job listings
        if( ! $this->integration_enabled() || empty($job_listing) ) {
            return;
        }

        foreach( $job_listing as $job ) {
            setup_postdata($job);

            // Get company_name meta
            $meta = get_post_meta( $job->ID, '_company_name', true );

            if( ! empty( $meta ) ) {
                $company    = get_page_by_title( $meta, OBJECT, 'company' );
                $company_id = $company->ID;
    
                // Update new Meta
                update_post_meta( $job->ID, '_company_manager_id', $company_id );
    
                // Delete old Meta
                //delete_post_meta( $job->ID, '_company_name' );
            }
        }

        if ( !empty($job_listing) ) {
            update_option( 'cariera_company_metakey_migration_init', 1 );
        }
    }

    
}