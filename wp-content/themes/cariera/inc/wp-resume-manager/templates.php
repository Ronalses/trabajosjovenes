<?php
/**
*
* @package Cariera
*
* @since 1.3.1
* 
* ========================
* ALL WP RESUME MANAGER TEMPLATE HOOKS
* ========================
*     
**/





/* 
=====================================================
    SINGLE RESUME PAGE
=====================================================
*/

/*
 * Candidate Social Media
 *
 * @since 1.3.1
 */
function cariera_candidate_social_accounts() {
    if( ! empty( cariera_get_the_candidate_fb() || cariera_get_the_candidate_twitter() || cariera_get_the_candidate_linkedin() || cariera_get_the_candidate_instagram() || cariera_get_the_candidate_youtube() ) ) {
        echo '<div class="candidate-social">';
            cariera_candidate_fb_output();
            cariera_candidate_twitter_output();
            cariera_candidate_linkedin_output();
            cariera_candidate_instagram_output();        
            cariera_candidate_youtube_output();
        echo '</div>';
    }
}

add_action( 'cariera_candidate_socials', 'cariera_candidate_social_accounts' );





/*
 * Adding Resume description to Single Resume Page
 *
 * @since 1.4.6
 */
if ( ! function_exists( 'cariera_candidate_description' ) ) {
    function cariera_candidate_description() {
        if( ! empty( get_the_content() ) ) {  ?>
            <!-- Candidate Description -->
            <div id="candidate-description" class="candidate-description">
                <h5><?php esc_html_e('About the Candidate', 'cariera'); ?></h5>
                <?php echo apply_filters( 'the_resume_description', get_the_content() ); ?>
            </div>
        <?php }
    }
}

add_action( 'single_resume_content', 'cariera_candidate_description', 10 );





/*
 * Adding Candidate Education to Single Resume Page
 *
 * @since 1.4.6
 */
if ( ! function_exists( 'cariera_candidate_education' ) ) {
    function cariera_candidate_education() {
        global $post;

        if ( $items = get_post_meta( $post->ID, '_candidate_education', true ) ) { ?>
            <!-- Start of Candidate Education Section -->
            <div id="candidate-qualification" class="candidate-education">
                <h5><?php esc_html_e( 'Education', 'cariera'); ?></h5>

                <?php foreach( $items as $item ) { ?>
                    <div class="education-item">                                            
                        <small class="time"><?php echo esc_html( $item['date'] ); ?></small>
                        <div class="education-title">
                            <strong class="location"><?php echo esc_html( $item['location'] ); ?></strong>
                            <span class="qualification"><?php echo esc_html( $item['qualification'] ); ?></span>
                        </div>

                        <!-- Start of Education Body -->
                        <div class="education-body">
                            <p><?php echo wpautop( wptexturize( $item['notes'] ) ); ?></p>
                        </div>
                        <!-- End of Education Body -->
                    </div>
                <?php } ?>
            </div>
            <!-- End of Candidate Education Section -->
        <?php }
    }
}

add_action( 'single_resume_content', 'cariera_candidate_education', 20 );





/*
 * Adding Candidate Experience to Single Resume Page
 *
 * @since 1.4.6
 */
if ( ! function_exists( 'cariera_candidate_experience' ) ) {
    function cariera_candidate_experience() {
        global $post;

        if ( $items = get_post_meta( $post->ID, '_candidate_experience', true ) ) { ?>
            <!-- Start of Candidate Experience Section -->
            <div id="candidate-experience" class="candidate-experience">
                <h5><?php esc_html_e( 'Experience', 'cariera'); ?></h5>

                <?php foreach( $items as $item ) { ?>

                    <div class="experience-item">
                        <small class="time"><?php echo esc_html( $item['date'] ); ?></small>
                        <div class="experience-title">
                            <strong class="employer"><?php echo esc_html( $item['employer'] ); ?></strong>
                            <span class="position"><?php echo esc_html( $item['job_title'] ); ?></span>
                        </div>


                        <!-- Start of Education Body -->
                        <div class="experience-body">
                            <p><?php echo wpautop( wptexturize( $item['notes'] ) ); ?></p>
                        </div>
                        <!-- End of Education Body -->
                    </div>

                <?php } ?>
            </div>
            <!-- Start of Candidate Experience Section -->
        <?php }
    }
}

add_action( 'single_resume_content', 'cariera_candidate_experience', 30 );





/*
 * Adding Candidate Skills to Single Resume Page
 *
 * @since 1.4.6
 */
if ( ! function_exists( 'cariera_candidate_skill' ) ) {
    function cariera_candidate_skill() {
        global $post;

        if ( ( $skills = wp_get_object_terms( $post->ID, 'resume_skill', array( 'fields' => 'names' ) ) ) && is_array( $skills ) ) { ?>
            <!-- Start of Candidate Skills Section -->
            <div id="candidate-skills" class="candidate-skills">
                <h5><?php esc_html_e( 'Skills', 'cariera'); ?></h5>

                <div class="skills">
                    <?php echo '<span>' . implode( '</span><span>', $skills ) . '</span>'; ?>
                </div>
            </div>
            <!-- Start of Candidate Skills Section -->
        <?php }
    }
}

add_action( 'single_resume_content', 'cariera_candidate_skill', 40 );





/*
 * Adding Candidate Video to Single Resume Page
 *
 * @since 1.4.6
 */
if ( ! function_exists( 'cariera_candidate_video' ) ) {
    function cariera_candidate_video() {
        if( ! empty( get_the_candidate_video() ) ) { ?>
            <div class="candidate-video">
                <h5><?php esc_html_e( 'Candidates Video', 'cariera' ); ?></h5>
                <?php echo apply_filters( 'cariera_the_candidate_video', the_candidate_video() ); ?>
            </div>
        <?php }
    }
}

add_action( 'single_resume_content', 'cariera_candidate_video', 50 );





/*
 * Adding Share buttons to Single Resume Page 
 *
 * @since 1.4.6
 */
if ( !function_exists('cariera_single_resume_share') ) {
    function cariera_single_resume_share() {
        if ( cariera_get_option( 'cariera_resume_share' ) ) {
            // check if function exists
            if ( function_exists ( 'cariera_share_media' ) ) {
                echo cariera_share_media();
            }
        }
    }
}

add_action( 'single_resume_content', 'cariera_single_resume_share', 60 );



/* 
=====================================================
    RESUME SUBMISSION HTML MARKUP
=====================================================
*/

/*
 * Resume Submission Flow
 *
 * @since 1.3.2
 */
function cariera_resume_submission_flow() {
    // temporary variables
    $is_packages_enabled = false;

    // get page IDs
    $current_page_id        = get_queried_object_id();
    $resume_submission_page = intval( get_option( 'resume_manager_submit_resume_form_page_id', false ) );

    // get resume packages
    if (function_exists('wc_get_products')) {
        $resume_packages        = wc_get_products( ['type' => 'resume_package'] );
        $resume_subscriptions   = wc_get_products( ['type' => 'resume_package_subscription'] );
        $is_packages_enabled    = class_exists( 'WC_Paid_Listings' ) && ( !empty($resume_packages) || !empty($resume_subscriptions) );
    }

    // display submission flow
    if ( !empty($resume_submission_page) && ($resume_submission_page == $current_page_id) ) { ?>
        <div class="submission-flow resume-submission-flow">
            <ul>
                <?php if ( get_option('resume_manager_paid_listings_flow') == 'before' && $is_packages_enabled ) { ?>
                    <li class="choose-package"><?php echo esc_html__( 'Choose Package', 'cariera' ); ?></li>
                <?php } ?>
                <li class="listing-details"><?php echo esc_html__( 'Resume Details', 'cariera' ); ?></li>
                <li class="preview-listing"><?php echo esc_html__( 'Preview Resume', 'cariera' ); ?></li>
                <?php if ( get_option('resume_manager_paid_listings_flow') != 'before' && $is_packages_enabled ) { ?>
                    <li class="choose-package"><?php echo esc_html__( 'Choose Package', 'cariera' ); ?></li>
                <?php } ?>
            </ul>
        </div>
    <?php
    }
}

add_action( 'cariera_page_content_start', 'cariera_resume_submission_flow' );
add_action( 'cariera_dashboard_content_start', 'cariera_resume_submission_flow' );





/*
 * Resume submission fields
 *
 * @since 1.4.0
 */
function cariera_submit_resume_fields_start() {
    echo '<div class="submit-job-box submit_resume-info">';
        echo '<h3 class="title">' . esc_html__( 'Candidate Details', 'cariera' ) . '</h3>';
        echo '<div class="form-fields">';
}

add_action( 'submit_resume_form_resume_fields_start', 'cariera_submit_resume_fields_start' );


function cariera_submit_resume_fields_end() {
    echo '</div></div>';
}

add_action( 'submit_resume_form_resume_fields_end', 'cariera_submit_resume_fields_end' );





/*
 * Company selection 
 *
 * @since 1.4.0
 */
function cariera_submit_resume_form_button_text( $text ) {
    return esc_html__( 'Preview Resume', 'cariera' ) ;
}

add_filter( 'submit_resume_form_submit_button_text', 'cariera_submit_resume_form_button_text' );





/**
 * Resume Class
 *
 * @since  1.4.5
 */

// Echo the class
if ( ! function_exists( 'cariera_resume_class' ) ) {
    function cariera_resume_class( $class = '', $post_id = null ) {
        echo 'class="' . esc_attr( join( ' ', cariera_get_resume_class( $class, $post_id ) ) ) . '"';
    }
}



// Get Company Class
if ( ! function_exists( 'cariera_get_resume_class' ) ) {
    function cariera_get_resume_class( $class = '', $post_id = null ) {
        $post = get_post( $post_id );

        if ( empty( $post ) || 'resume' !== $post->post_type ) {
            return [];
        }

        $classes    = [];
        $classes[]  = 'resume';

        if ( ! empty( $class ) ) {
            if ( ! is_array( $class ) ) {
                $class = preg_split( '#\s+#', $class );
            }
            $classes = array_merge( $classes, $class );
        }

        if ( is_resume_featured( $post ) ) {
            $classes[] = 'resume_featured';
        }

        return get_post_class( $classes, $post->ID );
    }
}