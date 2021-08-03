<?php

/**
*
* @package Cariera
*
* @since 1.4.3
* 
* ========================
* CARIERA CORE FIELDS
* ========================
*     
**/



// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}



class Cariera_Core_Fields {

    /**
     * The single instance of WordPress_Plugin_Template_Settings.
     * 
     * @since 1.4.3
     */
    private static $_instance = null;


    /**
	 * Constructor function.
	 * 
	 * @since 1.4.3
	 */
    public function __construct() {
        
        // WP Job Manager
        add_filter( 'submit_job_form_fields', [ $this, 'frontend_wpjm_extra_fields' ] );
        add_action( 'job_manager_update_job_data', [ $this, 'wpjm_update_job_data' ], 10, 2 );
        add_filter( 'job_manager_job_listing_data_fields', [ $this, 'admin_wpjm_extra_fields' ] );
        
        // WP Resume Manager
        add_filter( 'submit_resume_form_fields', [ $this, 'frontend_wprm_fields' ] );
        add_action( 'resume_manager_update_resume_data', [ $this, 'wprm_update_data' ], 10, 2 );
        add_filter( 'resume_manager_resume_fields', [ $this, 'admin_wprm_fields' ] );
        
        // Category Taxonomy
        add_action( 'job_listing_category_add_form_fields', [ $this, 'wpjm_category_add_new_meta_field' ], 10, 2 );
        add_action( 'resume_category_add_form_fields', [ $this, 'wpjm_category_add_new_meta_field' ], 10, 2 );

        add_action( 'job_listing_category_edit_form_fields', [ $this, 'wpjm_category_edit_meta_field' ], 10, 2 );
        add_action( 'resume_category_edit_form_fields', [ $this, 'wpjm_category_edit_meta_field' ], 10, 2 );

        add_action( 'edited_job_listing_category', [ $this, 'save_taxonomy_custom_meta' ], 10, 2 );  
        add_action( 'create_job_listing_category', [ $this, 'save_taxonomy_custom_meta' ], 10, 2 );
        add_action( 'edited_resume_category', [ $this, 'save_taxonomy_custom_meta' ], 10, 2 );  
        add_action( 'create_resume_category', [ $this, 'save_taxonomy_custom_meta' ], 10, 2 );

        // Salary Schema Data
        add_filter( 'wpjm_get_job_listing_structured_data', [ $this, 'salary_field_structured_data' ], 10, 2 );
    }





    /*
    =====================================================
        WP JOB MANAGER
    =====================================================
    */

    /**
     * Adding Extra Job Fields - Front-End.
     *
     * @since 1.0.0
     */
    public function frontend_wpjm_extra_fields( $fields ) {
        
        $fields['job']['apply_link'] = array(
            'label'       => esc_html__( 'External "Apply for Job" link', 'cariera' ),
            'type'        => 'text',
            'required'    => false,
            'placeholder' => esc_html__( 'http://', 'cariera' ),
            'priority'    => 6
        );
        
        if( get_option('cariera_job_manager_enable_qualification') ) {
            $fields['job']['job_listing_qualification'] = array(
                'label'       => esc_html__( 'Job Qualification', 'cariera' ),
                'type'        => 'term-multiselect',
                'taxonomy'    => 'job_listing_qualification',
                'required'    => false,
                'placeholder' => esc_html__( 'Choose a job qualification', 'cariera' ),
                'priority'    => 7
            );
        }

        if( get_option('cariera_job_manager_enable_career_level') ) {
            $fields['job']['job_listing_career_level'] = array(
                'label'       => esc_html__( 'Job Career Level', 'cariera' ),
                'type'        => 'term-select',
                'taxonomy'    => 'job_listing_career_level',
                'required'    => false,
                'default'     => '',
                'placeholder' => esc_html__( 'Choose a career level', 'cariera' ),
                'priority'    => 8
            );
        }

        if( get_option('cariera_job_manager_enable_experience') ) {
            $fields['job']['job_listing_experience'] = array(
                'label'       => esc_html__( 'Job Experience', 'cariera' ),
                'type'        => 'term-select',
                'taxonomy'    => 'job_listing_experience',
                'required'    => false,
                'default'     => '',
                'placeholder' => esc_html__( 'Choose a job experience', 'cariera' ),
                'priority'    => 9
            );
        }

        // If true Enable Rate fields
        if( get_option('cariera_enable_filter_rate') ) {
            $fields['job']['rate_min'] = array(
                'label'       => esc_html__( 'Minimum rate/h', 'cariera' ),
                'type'        => 'text',
                'required'    => false,
                'placeholder' => esc_html__( 'e.g. 20', 'cariera' ),
                'priority'    => 10
            );  
            $fields['job']['rate_max'] = array(
                'label'       => esc_html__( 'Maximum rate/h', 'cariera' ),
                'type'        => 'text',
                'required'    => false,
                'placeholder' => esc_html__( 'e.g. 50', 'cariera' ),
                'priority'    => 10.1
            );
        }

        // If true Enable Salary fields
        if( get_option('cariera_enable_filter_salary') ) {
            $fields['job']['salary_min'] = array(
                'label'       => esc_html__( 'Minimum Salary', 'cariera' ),
                'type'        => 'text',
                'required'    => false,
                'placeholder' => esc_html__( 'e.g. 20000', 'cariera' ),
                'priority'    => 11
            );  
            $fields['job']['salary_max'] = array(
                'label'       => esc_html__( 'Maximum Salary', 'cariera' ),
                'type'        => 'text',
                'required'    => false,
                'placeholder' => esc_html__( 'e.g. 50000', 'cariera' ),
                'priority'    => 11.1
            );
        }
        
        $fields['job']['hours'] = array(
            'label'       => esc_html__( 'Hours per week', 'cariera' ),
            'type'        => 'text',
            'required'    => false,
            'placeholder' => esc_html__( 'e.g. 72', 'cariera' ),
            'priority'    => 12
        );
        $fields['job']['header_image'] = array(
            'label'       => esc_html__( 'Featured Image', 'cariera' ),
            'type'        => 'file',
            'required'    => false,
            'description' => esc_html__( 'The featured image size should be at least 1600x200px', 'cariera' ),
            'priority'    => 13,
            'ajax'        => true,
            'multiple'    => false,
            'allowed_mime_types' => array(
                'jpg'  => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'gif'  => 'image/gif',
                'png'  => 'image/png'
            )
        );

        return $fields;
    }





    /**
     * Save the extra frontend fields.
     *
     * @since 1.0.0
     */
    public function wpjm_update_job_data( $job_id, $values ) {
        if( isset( $values[ 'job' ][ 'rate_min' ]) ) {
            update_post_meta( $job_id, '_rate_min', $values[ 'job' ][ 'rate_min' ] );
        }
        if( isset( $values[ 'job' ][ 'rate_max' ]) ) {
            update_post_meta( $job_id, '_rate_max', $values[ 'job' ][ 'rate_max' ] );
        }
        if( isset( $values[ 'job' ][ 'salary_min' ]) ) {
            update_post_meta( $job_id, '_salary_min', $values[ 'job' ][ 'salary_min' ] );
        }
        if( isset( $values[ 'job' ][ 'salary_max' ]) ) {
            update_post_meta( $job_id, '_salary_max', $values[ 'job' ][ 'salary_max' ] );
        }
        if( isset( $values[ 'job' ][ 'hours' ]) ) {
            update_post_meta( $job_id, '_hours', $values[ 'job' ][ 'hours' ] );
        }
        if( isset( $values[ 'job' ][ 'apply_link' ]) ) {
            update_post_meta( $job_id, '_apply_link', $values[ 'job' ][ 'apply_link' ] );
        }
        if( isset( $values[ 'job' ][ 'header_image' ]) ) {
            update_post_meta( $job_id, 'cariera_job_page_header', $values[ 'job' ][ 'header_image' ] );
        }
    }





    /**
     * Adding Extra Job Fields - Back-End.
     *
     * @since 1.0.0
     */
    public function admin_wpjm_extra_fields( $fields ) {
        
        $fields['_hours'] = array(
            'label'       => esc_html__( 'Hours per week', 'cariera' ),
            'type'        => 'text',
            'priority'    => 11,
            'placeholder' => 'e.g. 72',
            'description' => ''
        );
        
        // If true Enable Rate fields
        if( get_option('cariera_enable_filter_rate') ) {
            $fields['_rate_min'] = array(
                'label'       => esc_html__( 'Rate/h (minimum)', 'cariera' ),
                'type'        => 'text',
                'priority'    => 12,
                'placeholder' => 'e.g. 20',
                'description' => esc_html__('Put just a number','cariera'),
            );    
            $fields['_rate_max'] = array(
                'label'       => esc_html__( 'Rate/h (maximum) ', 'cariera' ),
                'type'        => 'text',
                'priority'    => 12,
                'placeholder' => esc_html__('e.g. 20','cariera'),
                'description' => esc_html__('Put just a number - you can leave it empty and set only minimum rate value ','cariera'),
            );
        }
        
        // If true Enable Salary fields
        if( get_option('cariera_enable_filter_salary') ) {
            $fields['_salary_min'] = array(
                'label'       => esc_html__( 'Salary min', 'cariera' ),
                'type'        => 'text',
                'priority'    => 12,
                'placeholder' => esc_html__('e.g. 20.000','cariera'),
                'description' => esc_html__('Enter the min Salary of the Job','cariera'),
            );   
            $fields['_salary_max'] = array(
                'label'       => esc_html__( 'Salary max', 'cariera' ),
                'type'        => 'text',
                'priority'    => 12,
                'placeholder' => esc_html__('e.g. 50.000','cariera'),
                'description' => esc_html__('Maximum of salary range you can offer - you can leave it empty and set only minimum salary ','cariera'),
            );
        }
        
        $fields['_apply_link'] = array(
            'label'       => esc_html__( 'External "Apply for Job" link', 'cariera' ),
            'type'        => 'text',
            'priority'    => 5,
            'placeholder' => esc_html__('http://','cariera'),
            'description' => esc_html__('If the job applying is done on external page, here\'s the place to put link to that page - it will be used instead of standard Apply form','cariera'),
        );

        return $fields;
    }


    


    /*
    =====================================================
        WP RESUME MANAGER
    =====================================================
    */

    /**
     * Adding custom fields for resumes - Front-End
     *
     * @since 1.0.0
     */
    public function frontend_wprm_fields( $fields ) {
        $fields['resume_fields']['candidate_rate'] = array(
            'label'       => esc_html__( 'Rate per Hour', 'cariera' ),
            'type'        => 'text',
            'required'    => false,
            'placeholder' => esc_html__('e.g. 20', 'cariera'),
            'priority'    => 9
        );
        
        if( get_option('cariera_resume_manager_enable_education') ) {
            $fields['resume_fields']['candidate_education_level'] = array(
                'label'       => esc_html__( 'Education Level', 'cariera' ),
                'type'        => 'term-select',
                'taxonomy'    => 'resume_education_level',
                'required'    => false,
                'default'     => '',
                'placeholder' => esc_html__( 'Bachelor degree', 'cariera' ),
                'priority'    => 9
            );
        }
        
        $fields['resume_fields']['candidate_languages'] = array(
            'label'       => esc_html__( 'Languages', 'cariera' ),
            'type'        => 'text',
            'required'    => false,
            'placeholder' => esc_html__('English, German, Chinese', 'cariera'),
            'priority'    => 9
        );
        
        if( get_option('cariera_resume_manager_enable_experience') ) {
            $fields['resume_fields']['candidate_experience_years'] = array(
                'label'       => esc_html__( 'Experience', 'cariera' ),
                'type'        => 'term-select',
                'taxonomy'    => 'resume_experience',
                'required'    => false,
                'default'     => '',
                'placeholder' => esc_html__( '3 years', 'cariera' ),
                'priority'    => 9
            );
        }
        
        $fields['resume_fields']['candidate_featured_image'] = array(
            'label'       => esc_html__( 'Featured Image', 'cariera' ),
            'type'        => 'file',
            'required'    => false,
            'description' => esc_html__( 'The featured image size should be max 1920x400px', 'cariera' ),
            'priority'    => 6,
            'ajax'        => true,
            'multiple'    => false,
            'allowed_mime_types' => array(
                'jpg'  => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'gif'  => 'image/gif',
                'png'  => 'image/png'
            )
        );
        
        $fields['resume_fields']['candidate_facebook'] = array(
            'label'       => esc_html__( 'Facebook', 'cariera' ),
            'type'        => 'text',
            'placeholder' => esc_html__( 'Your Facebook page link', 'cariera'),
            'priority'    => 9.4,
            'required'    => false
        );
        
        $fields['resume_fields']['candidate_twitter'] = array(
            'label'       => esc_html__( 'Twitter', 'cariera' ),
            'type'        => 'text',
            'placeholder' => esc_html__( 'Your Twitter page link', 'cariera'),
            'priority'    => 9.5,
            'required'    => false
        );
        
        $fields['resume_fields']['candidate_linkedin'] = array(
            'label'       => esc_html__( 'LinkedIn', 'cariera' ),
            'type'        => 'text',
            'placeholder' => esc_html__( 'Your LinkedIn page link', 'cariera'),
            'priority'    => 9.7,
            'required'    => false
        );
        
        $fields['resume_fields']['candidate_instagram'] = array(
            'label'       => esc_html__( 'Instagram', 'cariera' ),
            'type'        => 'text',
            'placeholder' => esc_html__( 'Your Instagram page link', 'cariera'),
            'priority'    => 9.8,
            'required'    => false
        );
        
        $fields['resume_fields']['candidate_youtube'] = array(
            'label'       => esc_html__( 'Youtube', 'cariera' ),
            'type'        => 'text',
            'placeholder' => esc_html__( 'Your Youtube page link', 'cariera'),
            'priority'    => 9.9,
            'required'    => false
        );
        
        return $fields;
    }





    /**
     * Update frontend fields.
     *
     * @since 1.3.0
     */
    public function wprm_update_data( $resume_id, $values ) {
        if( isset( $values[ 'resume_fields' ][ 'candidate_rate' ]) ) {
            update_post_meta( $resume_id, '_rate', $values[ 'resume_fields' ][ 'candidate_rate' ] );
        }
        if( isset( $values[ 'resume_fields' ][ 'candidate_languages' ]) ) {
            update_post_meta( $resume_id, '_languages', $values[ 'resume_fields' ][ 'candidate_languages' ] );
        }
        if( isset( $values[ 'resume_fields' ][ 'candidate_featured_image' ]) ) {
            update_post_meta( $resume_id, '_featured_image', $values[ 'resume_fields' ][ 'candidate_featured_image' ] );
        }
        if( isset( $values[ 'resume_fields' ][ 'candidate_facebook' ]) ) {
            update_post_meta( $resume_id, '_facebook', $values[ 'resume_fields' ][ 'candidate_facebook' ] );
        }
        if( isset( $values[ 'resume_fields' ][ 'candidate_twitter' ]) ) {
            update_post_meta( $resume_id, '_twitter', $values[ 'resume_fields' ][ 'candidate_twitter' ] );
        }
        if( isset( $values[ 'resume_fields' ][ 'candidate_linkedin' ]) ) {
            update_post_meta( $resume_id, '_linkedin', $values[ 'resume_fields' ][ 'candidate_linkedin' ] );
        }
        if( isset( $values[ 'resume_fields' ][ 'candidate_instagram' ]) ) {
            update_post_meta( $resume_id, '_instagram', $values[ 'resume_fields' ][ 'candidate_instagram' ] );
        }
        if( isset( $values[ 'resume_fields' ][ 'candidate_youtube' ]) ) {
            update_post_meta( $resume_id, '_youtube', $values[ 'resume_fields' ][ 'candidate_youtube' ] );   
        }
    }

    



    /**
     * Adding custom fields for resumes - Back-End
     *
     * @since 1.0.0
     */
    public function admin_wprm_fields( $fields ) {
        $fields['_rate'] = array(
            'label'       => esc_html__( 'Rate per Hour', 'cariera' ),
            'type'        => 'text',
            'placeholder' => esc_html__('e.g. 20', 'cariera'),
            'description' => ''
        );
        
        $fields['_languages'] = array(
            'label'       => esc_html__( 'Languages', 'cariera' ),
            'type'        => 'text',
            'placeholder' => esc_html__('English, German, Chinese', 'cariera'),
            'description' => ''
        );
        
        $fields['_facebook'] = array(
            'label'       => esc_html__( 'Facebook', 'cariera' ),
            'type'        => 'text',
            'placeholder' => esc_html__( 'Your Facebook page link', 'cariera')
        );
        
        $fields['_twitter'] = array(
            'label'       => esc_html__( 'Twitter', 'cariera' ),
            'type'        => 'text',
            'placeholder' => esc_html__( 'Your Twitter page link', 'cariera')
        );
        
        $fields['_linkedin'] = array(
            'label'       => esc_html__( 'LinkedIn', 'cariera' ),
            'type'        => 'text',
            'placeholder' => esc_html__( 'Your LinkedIn page link', 'cariera')
        );
        
        $fields['_instagram'] = array(
            'label'       => esc_html__( 'Instagram', 'cariera' ),
            'type'        => 'text',
            'placeholder' => esc_html__( 'Your Instagram page link', 'cariera')
        );
        
        $fields['_youtube'] = array(
            'label'       => esc_html__( 'Youtube', 'cariera' ),
            'type'        => 'text',
            'placeholder' => esc_html__( 'Your Youtube page link', 'cariera')
        );
        
        $fields['_featured_image'] = array(
            'label'       => esc_html__( 'Featured Image', 'cariera' ),
            'type'        => 'file',
            'description' => '',
            'multiple'    => false,
            'allowed_mime_types' => array(
                'jpg'  => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'gif'  => 'image/gif',
                'png'  => 'image/png'
            )
        );  
            
        return $fields;
    }





    /*
    =====================================================
        TAXONOMY META FIELDS
    =====================================================
    */

    /*
    * Custom Icon field for Job & Resume Categories taxonomy
    *
    * @since 1.2.0
    */
    public function wpjm_category_add_new_meta_field() {
        wp_enqueue_media();
        // this will add the custom meta field to the add new term page
        ?>

        <div class="form-field">
            <label for="term_meta[background_image]"><?php esc_html_e( 'Category Background Image', 'cariera' ); ?></label>
            <input type="text" name="term_meta[background_image]" id="term_meta[background_image]" value="" style="margin-bottom: 10px;">
            <a type="button" class="button cariera-upload-btn"><?php esc_html_e('upload image', 'cariera'); ?></a>
            <a href="#" class="button cariera_remove_image_button"><?php esc_html_e('Remove image', 'cariera'); ?></a>
            <p class="description"><?php esc_html_e( 'Upload or select a background image.', 'cariera' ); ?></p>
        </div>

        <div class="form-field">
            <label for="term_meta[image_icon]"><?php esc_html_e( 'Custom Image Icon', 'cariera' ); ?></label>
            <input type="text" name="term_meta[image_icon]" id="term_meta[image_icon]" value="" style="margin-bottom: 10px;">
            <a type="button" class="button cariera-upload-btn"><?php esc_html_e('upload image', 'cariera'); ?></a>
            <a href="#" class="button cariera_remove_image_button"><?php esc_html_e('Remove image', 'cariera'); ?></a>
            <p class="description"><?php esc_html_e( 'Upload or select a custom image icon.', 'cariera' ); ?></p>
        </div>

        <div class="form-field">
            <label for="term_meta[font_icon]"><?php esc_html_e( 'Category Font Icon', 'cariera' ); ?></label>
            <select class="cariera-icon-select" name="term_meta[font_icon]" id="term_meta[font_icon]" id="">
                <?php
                // Fontawesome icons 
                $fa_icons = cariera_fontawesome_icons_list();
                foreach ( $fa_icons as $key => $value ) {
                    echo '<option value="' . $key . '">' . $value . '</option>';
                }
        
                // Simpleline icons
                $sl_icons = cariera_simpleline_icons_list();
                foreach ( $sl_icons as $key => $value ) {
                    echo '<option value="icon-' . $key . '">' . $value . '</option>';
                }
        
                // Iconsmind icons
                if ( get_option('cariera_font_iconsmind') ) {
                    $im_icons = cariera_iconsmind_list();
                    foreach ( $im_icons as $key ) {
                        echo '<option value="iconsmind-' . $key . '">' . $key . '</option>';
                    }
                } ?>
            </select>
            <p class="description"><?php esc_html_e( 'Icon will be displayed in categories grid view', 'cariera' ); ?></p>
        </div>

    <?php
    }





    /*
    * Edit Term Page
    *
    * @since 1.2.0
    */
    public function wpjm_category_edit_meta_field($term) {
        wp_enqueue_media();
        
        // put the term ID into a variable
        $t_id = $term->term_id;

        // retrieve the existing value(s) for this meta field. This returns an array
        $term_meta = get_option( "taxonomy_$t_id" ); 
        ?>

        <tr class="form-field">
            <th scope="row" valign="top"><label for="term_meta[background_image]"><?php esc_html_e( 'Category Background Image', 'cariera' ); ?></label></th>
            <td>
                <input type="text" name="term_meta[background_image]" id="term_meta[background_image]" value="<?php echo !empty( $term_meta['background_image'] ) ? esc_attr( $term_meta['background_image'] ) : ''; ?>" style="margin-bottom: 10px;">
                <a type="button" class="button cariera-upload-btn"><?php esc_html_e( 'Upload image', 'cariera'); ?></a>
                <a href="#" class="button cariera_remove_image_button"><?php esc_html_e( 'Remove image', 'cariera'); ?></a>
                <p class="description"><?php esc_html_e( 'Upload or change the categories background image.', 'cariera' ); ?></p>
            </td>
        </tr>

        <tr class="form-field">
            <th scope="row" valign="top"><label for="term_meta[image_icon]"><?php esc_html_e( 'Custom Image Icon', 'cariera' ); ?></label></th>
            <td>
                <input type="text" name="term_meta[image_icon]" id="term_meta[image_icon]" value="<?php echo !empty( $term_meta['image_icon'] ) ? esc_attr( $term_meta['image_icon'] ) : ''; ?>" style="margin-bottom: 10px;">
                <a type="button" class="button cariera-upload-btn"><?php esc_html_e( 'Upload image', 'cariera'); ?></a>
                <a href="#" class="button cariera_remove_image_button"><?php esc_html_e( 'Remove image', 'cariera'); ?></a>
                <p class="description"><?php esc_html_e( 'Upload or change the categories icon image.', 'cariera' ); ?></p>
            </td>
        </tr>

        <tr class="form-field">
            <th scope="row" valign="top"><label for="term_meta[font_icon]"><?php esc_html_e( 'Category Font Icon', 'cariera' ); ?></label></th>
            <td>
                <select class="cariera-icon-select" name="term_meta[font_icon]" id="term_meta[font_icon]">
                    <?php 
                        $fa_icons = cariera_fontawesome_icons_list();
                        foreach ( $fa_icons as $key => $value ) {
                            echo '<option value="' . $key . '" ';
                            if( isset($term_meta['font_icon']) && $term_meta['font_icon'] == $key ) { 
                                echo ' selected="selected"';
                            }
                            echo '>' . $value . '</option>';
                        }
        
                        
                        $sl_icons = cariera_simpleline_icons_list();
                        foreach ( $sl_icons as $key => $value ) {
                            echo '<option value="icon-' . $key . '" ';
                            if( isset($term_meta['font_icon']) && $term_meta['font_icon'] == 'icon-' . $key ) { 
                                echo ' selected="selected"';
                            }
                            echo '>' . $value . '</option>';
                        }

                        if ( get_option('cariera_font_iconsmind') ) {
                            $im_icons = cariera_iconsmind_list();
                            foreach ($im_icons as $key ) {
                                echo '<option value="iconsmind-' . $key . '" ';
                                if( isset($term_meta['font_icon']) && $term_meta['font_icon'] == 'iconsmind-' . $key ) { 
                                    echo ' selected="selected"';
                                }
                                echo '>' . $key . '</option>';
                            }
                        }
                    ?>
                </select>
                <p class="description"><?php esc_html_e( 'Icon will be displayed in categories grid view', 'cariera' ); ?></p>
            </td>
        </tr>

    <?php
    }





    // Save extra taxonomy fields callback function.
    public function save_taxonomy_custom_meta( $term_id ) {
        if ( isset( $_POST['term_meta'] ) ) {
            $t_id = $term_id;
            $term_meta = get_option( "taxonomy_$t_id" );
            $cat_keys = array_keys( $_POST['term_meta'] );
            foreach ( $cat_keys as $key ) {
                if ( isset ( $_POST['term_meta'][$key] ) ) {
                    $term_meta[$key] = $_POST['term_meta'][$key];
                }
            }
            // Save the option array.
            update_option( "taxonomy_$t_id", $term_meta );
        }
    }





    /*
    =====================================================
        SCHEMA DATA
    =====================================================
    */

    /*
    * Adding Salary Field to Structured Data (schema.org markup)
    *
    * @since 1.4.6
    */
    function salary_field_structured_data( $data, $post ) {
    
        if( $post && $post->ID ) {
            $salary = get_post_meta( $post->ID, '_job_salary', true );
            
            // Here you can add values that would be considered "not a salary" to skip output for
            $no_salary_values = array( 'Not Disclosed', 'N/A', 'TBD' );
            
            // Don't add anything if empty value, or value equals something above in no salary values
            if( empty( $salary) || in_array( strtolower( $salary ), array_map( 'strtolower' , $no_salary_values ) ) ){
                return $data;
            }
            
            // Determine float value, stripping all non-alphanumeric characters
            $salary_float_val = (float) filter_var( $salary, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
            
            if( ! empty( $salary_float_val ) ) {
                // @see https://schema.org/JobPosting
                // Simple value:
                // $data['baseSalary'] = $salary_float_val;
                
                // Or using Google's Structured Data format
                // @see https://developers.google.com/search/docs/data-types/job-posting
                // This is the format Google really wants it in, so you should customize this yourself
                // to match your setup and configuration
                $data['baseSalary'] = array(
                    '@type'     => 'MonetaryAmount',
                    'currency'  => 'USD',
                    'value'     => array(
                        '@type'     => 'QuantitativeValue',
                        'value'     => $salary_float_val,
                        // HOUR, DAY, WEEK, MONTH, or YEAR
                        'unitText'  => 'YEAR'
                    )
                );
            }
        }
    
        return $data;
    }


}