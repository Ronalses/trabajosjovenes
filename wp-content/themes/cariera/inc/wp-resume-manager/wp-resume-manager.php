<?php
/**
*
* @package Cariera
*
* @since 1.3.0
* 
* ========================
* ALL WP RESUME MANAGER FUNCTIONS
* ========================
*     
**/





/**
 * Remove WP Resume Manager Default filters & actions
 *
 * @since 1.4.3
 */
function cariera_remove_default_resumes_css() {
    wp_deregister_style( 'wp-job-manager-resume-frontend' );
}

add_action( 'wp_enqueue_scripts', 'cariera_remove_default_resumes_css', 20 );





/**
 * Contact Form for Single Candidate Page
 *
 * @since 1.3.0
 */
if ( ! function_exists( 'cariera_single_candidate_contact_form' ) ) {
    function cariera_single_candidate_contact_form() {
        $form_id = get_option ( 'resume_manager_single_resume_contact_form' );
        
        if ( ! empty( $form_id ) ) {
            $shortcode = sprintf( '[contact-form-7 id="%1$d" title="%2$s"]', $form_id, get_the_title( $form_id ) );
            echo '<div class="contact-form contact-candidate">';
            echo do_shortcode( $shortcode );
            echo '</div>';
        }
    }
}





/**
 * Candidate Socials
 *
 * @since  1.3.1
 */

// Facebook
if ( ! function_exists( 'cariera_get_the_candidate_fb' ) ) {
    function cariera_get_the_candidate_fb( $post = null ) {
        $post = get_post( $post );
        if ( $post->post_type !== 'resume' ) {
            return;
        }

        if ( $post->_facebook && ! strstr( $post->_facebook, 'http:' ) && ! strstr( $post->_facebook, 'https:' ) ) {
            $post->_facebook = 'https://' . $post->_facebook;
        }

        return apply_filters( 'cariera_candidate_fb_output', $post->_facebook, $post );
    }
}

if ( ! function_exists( 'cariera_candidate_fb_output' ) ) {
    function cariera_candidate_fb_output( $post = null ) {
        if ( ! empty( cariera_get_the_candidate_fb( $post ) ) ) {
            echo '<a href="' . esc_url( cariera_get_the_candidate_fb( $post ) ) . '" class="candidate-facebook"><i class="fab fa-facebook-f"></i></a>';
        }
    }
}

// Twitter
if ( ! function_exists( 'cariera_get_the_candidate_twitter' ) ) {
    function cariera_get_the_candidate_twitter( $post = null ) {
        $post = get_post( $post );
        if ( $post->post_type !== 'resume' ) {
            return;
        }

        if ( $post->_twitter && ! strstr( $post->_twitter, 'http:' ) && ! strstr( $post->_twitter, 'https:' ) ) {
            $post->_twitter = 'https://' . $post->_twitter;
        }

        return apply_filters( 'cariera_candidate_twitter_output', $post->_twitter, $post );
    }
}

if ( ! function_exists( 'cariera_candidate_twitter_output' ) ) {
    function cariera_candidate_twitter_output( $post = null ) {
        if ( ! empty( cariera_get_the_candidate_twitter( $post ) ) ) {
            echo '<a href="' . esc_url( cariera_get_the_candidate_twitter( $post ) ) . '" class="candidate-twitter"><i class="fab fa-twitter"></i></a>';
        }
    }
}

// LinkedIn
if ( ! function_exists( 'cariera_get_the_candidate_linkedin' ) ) {
    function cariera_get_the_candidate_linkedin( $post = null ) {
        $post = get_post( $post );
        if ( $post->post_type !== 'resume' ) {
            return;
        }

        if ( $post->_linkedin && ! strstr( $post->_linkedin, 'http:' ) && ! strstr( $post->_linkedin, 'https:' ) ) {
            $post->_linkedin = 'https://' . $post->_linkedin;
        }

        return apply_filters( 'cariera_candidate_linkedin_output', $post->_linkedin, $post );
    }
}

if ( ! function_exists( 'cariera_candidate_linkedin_output' ) ) {
    function cariera_candidate_linkedin_output( $post = null ) {
        if ( ! empty( cariera_get_the_candidate_linkedin( $post ) ) ) {
            echo '<a href="' . esc_url( cariera_get_the_candidate_linkedin( $post ) ) . '" class="candidate-linkedin"><i class="fab fa-linkedin-in"></i></a>';
        }
    }
}

// Instagram
if ( ! function_exists( 'cariera_get_the_candidate_instagram' ) ) {
    function cariera_get_the_candidate_instagram( $post = null ) {
        $post = get_post( $post );
        if ( $post->post_type !== 'resume' ) {
            return;
        }

        if ( $post->_instagram && ! strstr( $post->_instagram, 'http:' ) && ! strstr( $post->_instagram, 'https:' ) ) {
            $post->_instagram = 'https://' . $post->_instagram;
        }

        return apply_filters( 'cariera_candidate_instagram_output', $post->_instagram, $post );
    }
}

if ( ! function_exists( 'cariera_candidate_instagram_output' ) ) {
    function cariera_candidate_instagram_output( $post = null ) {
        if ( ! empty( cariera_get_the_candidate_instagram( $post ) ) ) {
            echo '<a href="' . esc_url( cariera_get_the_candidate_instagram( $post ) ) . '" class="candidate-instagram"><i class="fab fa-instagram"></i></a>';
        }
    }
}

// Youtube
if ( ! function_exists( 'cariera_get_the_candidate_youtube' ) ) {
    function cariera_get_the_candidate_youtube( $post = null ) {
        $post = get_post( $post );
        if ( $post->post_type !== 'resume' ) {
            return;
        }

        if ( $post->_youtube && ! strstr( $post->_youtube, 'http:' ) && ! strstr( $post->_youtube, 'https:' ) ) {
            $post->_youtube = 'https://' . $post->_youtube;
        }

        return apply_filters( 'cariera_candidate_youtube_output', $post->_youtube, $post );
    }
}

if ( ! function_exists( 'cariera_candidate_youtube_output' ) ) {
    function cariera_candidate_youtube_output( $post = null ) {
        if ( ! empty( cariera_get_the_candidate_youtube( $post ) ) ) {
            echo '<a href="' . esc_url( cariera_get_the_candidate_youtube( $post ) ) . '" class="candidate-youtube"><i class="fab fa-youtube"></i></a>';
        }
    }
}





/*
 * Output the resume's rate if there is any
 *
 * @since 1.4.1
 */
function cariera_resume_rate() {
    global $post;
    
    $currency_position  = get_option( 'cariera_currency_position', 'before' );
    $rate               = get_post_meta( $post->ID, '_rate', true );
    
    if( !empty($rate) ) {
        // Currency Symbol Before
        if( $currency_position == 'before' ) { 
            echo cariera_currency_symbol(); 
        }
        echo esc_html( $rate );
        // Currency Symbol After
        if( $currency_position == 'after' ) { 
            echo cariera_currency_symbol(); 
        }
        esc_html_e( '/hour', 'cariera' );                          
    }
}





/*
 * Hide Contact button for resume author
 *
 * @since   1.4.7
 * @version 1.4.8
 */
function cariera_hide_contact_button_for_resume_author( $can_view, $resume_id ) {
    $contact = get_option('cariera_resume_manager_contact_owner');
    $resume  = get_post( $resume_id );
    
	if( $resume && isset( $resume->post_author ) && !empty( $resume->post_author ) && $contact ) {
		if ( $resume->post_author == get_current_user_id() ) {
            return false;
		}
    }
	
	return $can_view;
}

add_filter( 'resume_manager_user_can_view_contact_details', 'cariera_hide_contact_button_for_resume_author', 10, 2 );