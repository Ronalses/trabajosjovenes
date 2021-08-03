<?php

/**
*
* @package Cariera
*
* @since 1.4.4
* 
* ========================
* CARIERA COMPANY MANAGER TEMPLATES
* ========================
*     
**/



if ( ! defined( 'ABSPATH' ) ) {
	exit;
}





/**
 * Displays or retrieves the current company name with optional content.
 *
 * @since 1.4.4
 */
if ( ! function_exists( 'cariera_company_name' ) ) {
    function cariera_company_name( $before = '', $after = '', $echo = true, $post = null ) {

        $company_name = cariera_get_company_name( $post );

        if ( 0 === strlen( $company_name ) ) {
            return null;
        }

        $company_name = esc_attr( wp_strip_all_tags( $company_name ) );
        $company_name = $before . $company_name . $after;

        if ( $echo ) {
            echo wp_kses_post( $company_name );
        } else {
            return $company_name;
        }
    }
}




/**
 * Gets the company name.
 *
 * @since 1.4.4
 */
if ( ! function_exists( 'cariera_get_company_name' ) ) {
    function cariera_get_company_name( $post = null ) {

        $post = get_post( $post );
        if ( ! $post || 'company' !== $post->post_type ) {
            return '';
        }

        return apply_filters( 'cariera_the_company_name', $post->_company_name, $post );
    }
}









/**
 * Add post classes to companies
 *
 * @since 1.4.5
 */
if ( ! function_exists( 'cariera_company_add_post_class' ) ) {
    function cariera_company_add_post_class( $classes, $class, $post_id ) {
        $post = get_post( $post_id );

        if ( empty( $post ) || 'company' !== $post->post_type ) {
            return $classes;
        }

        $classes[] = 'company';

        if ( cariera_is_company_featured( $post ) ) {
            $classes[] = 'company_featured';
        }

        return $classes;
    }
}

add_action( 'post_class', 'cariera_company_add_post_class', 10, 3 );











/**
 * Displays some content when no results are found
 *
 * @since  1.3.0
 */
if ( ! function_exists( 'cariera_output_company_no_results' ) ) {
    function cariera_output_company_no_results() {
        get_company_template( 'content-no-companies-found.php' );
    }
}

add_action( 'cariera_company_no_results', 'cariera_output_company_no_results');





/**
 * Get company pagination for [companies] shortcode.
 *
 * @since 1.3.0
 */
if ( ! function_exists( 'cariera_get_company_pagination' ) ) {
    function cariera_get_company_pagination( $max_num_pages, $current_page = 1 ) {
        ob_start();
        
        get_company_template(
            'company-pagination.php',
            array(
                'max_num_pages' => $max_num_pages,
                'current_page'  => absint( $current_page ),
            )
        );
        
        return ob_get_clean();
    }
}





/**
 * Get the Company Permalinks
 *
 * @since  1.3.0
 */
if ( ! function_exists( 'cariera_get_the_company_permalink' ) ) {
    function cariera_get_the_company_permalink( $post = null ) {
        $post = get_post( $post );
        $link = get_permalink( $post );

        return apply_filters( 'cariera_the_company_permalink', $link, $post );
    }
}

// Output the permalink
if ( ! function_exists( 'cariera_the_company_permalink' ) ) {
    function cariera_the_company_permalink( $post = null ) {
        echo esc_url( cariera_get_the_company_permalink( $post ) );
    }
}





/**
 * Get the category of the company
 *
 * @since  1.3.0
 */
if ( ! function_exists( 'cariera_get_the_company_category' ) ) {
    function cariera_get_the_company_category( $post = null ) {        
        $post = get_post( $post );
        if ( $post->post_type !== 'company' ) {
            return '';
        }

        if ( ! get_option( 'cariera_company_category' ) ) {
            return '';
        }

        $categories = wp_get_object_terms( $post->ID, 'company_category' );

        if ( is_wp_error( $categories ) ) {
            return '';
        }

        return apply_filters( 'cariera_the_company_category_output', $categories, $post );
    }
}

// Output the category of the company
if ( ! function_exists( 'cariera_the_company_category_output' ) ) {
    function cariera_the_company_category_output( $post = null ) {
        $categories = cariera_get_the_company_category( $post );
        
        if( ! empty( $categories )) {
            echo '<ul class="categories">';
            foreach( $categories as $category ) {
                echo '<li><a href="' . esc_url( get_term_link( $category ) ) . '">' . esc_html( $category->name ) . '</a></li>';
            }
            echo '</ul>';
        }
    }
}



// Get an array of categories of the company
function cariera_get_the_company_category_array( $post = null ) {
	$post = get_post( $post );
	if ( $post->post_type !== 'company' ) {
        return '';
    }

	if ( ! get_option( 'cariera_company_category' ) ) {
        return '';
    }

	$categories = wp_get_object_terms( $post->ID, 'company_category', array( 'fields' => 'names' ) );

	if ( is_wp_error( $categories ) ) {
		return '';
	}

	return implode( ', ', $categories );
}

// Output the array category of the company
function cariera_the_company_category_array( $post = null ) {
	echo cariera_get_the_company_category_array( $post );
}





/**
 * Get the team size of the company
 *
 * @since  1.3.0
 */
function cariera_get_the_company_team_size( $post = null ) {        
    $post = get_post( $post );
    if ( $post->post_type !== 'company' ) {
        return '';
    }

    if ( ! get_option( 'cariera_company_team_size' ) ) {
        return '';
    }

    $teams = wp_get_object_terms( $post->ID, 'company_team_size', array( 'fields' => 'names' ) );

    if ( is_wp_error( $teams ) ) {
        return '';
    }

    return implode( ', ', $teams );
}

// Output
function cariera_the_company_team_size_output( $post = null ) {
    echo cariera_get_the_company_team_size($post);
}





/**
 * Returns the registration fields used when an account is required.
 *
 * @since 1.4.4
 */
function cariera_get_registration_fields() {
	$account_required                  = cariera_user_requires_account();

	$registration_fields = array();
	if ( cariera_enable_registration() ) {
		$registration_fields['create_account_username'] = array(
			'type'     => 'text',
			'label'    => esc_html__( 'Username', 'cariera' ),
			'required' => $account_required,
			'value'    => isset( $_POST['create_account_username'] ) ? sanitize_text_field( wp_unslash( $_POST['create_account_username'] ) ) : '',
		);
		$registration_fields['create_account_password'] = array(
			'type'         => 'password',
			'label'        => esc_html__( 'Password', 'cariera' ),
			'autocomplete' => false,
			'required'     => $account_required,
		);
		$password_hint = wpjm_get_password_rules_hint();
		if ( $password_hint ) {
			$registration_fields['create_account_password']['description'] = $password_hint;
		}
		$registration_fields['create_account_password_verify'] = array(
			'type'         => 'password',
			'label'        => esc_html__( 'Verify Password', 'cariera' ),
			'autocomplete' => false,
			'required'     => $account_required,
		);
	}

	// Filters the fields used at registration.
	return apply_filters( 'cariera_get_registration_fields', $registration_fields );
}





/**
 * Get the website of the company
 *
 * @since  1.3.0
 */
if ( ! function_exists( 'cariera_get_the_company_website_link' ) ) {
    function cariera_get_the_company_website_link( $post = null ) {
        $post = get_post( $post );
        if ( $post->post_type !== 'company' ) {
            return;
        }

        $website = $post->_company_website;

        if ( $website && ! strstr( $website, 'http:' ) && ! strstr( $website, 'https:' ) ) {
            $website = 'http://' . $website;
        }

        return apply_filters( 'the_company_website_link', $website, $post );
    }
}




/**
 * Get the email of the company
 *
 * @since  1.3.0
 */
if ( ! function_exists( 'cariera_get_the_company_email' ) ) {
    function cariera_get_the_company_email( $post = null ) {
        $post = get_post( $post );
        if ( $post->post_type !== 'company' ) {
            return;
        }

        return apply_filters( 'cariera_the_company_email', $post->_company_email, $post );
    }
}





/**
 * Get the phone number of the company
 *
 * @since  1.3.0
 */
if ( ! function_exists( 'cariera_get_the_company_phone' ) ) {
    function cariera_get_the_company_phone( $post = null , $post_type = 'company' ) {
        $post = get_post( $post );
        if ( $post->post_type !== 'company' ) {
            return;
        }

        return apply_filters( 'cariera_the_company_phone', $post->_company_phone, $post );
    }
}





/**
 * Get the company job listings
 *
 * @since   1.3.0
 * @version 1.5.1
 */
if ( ! function_exists( 'cariera_get_the_company_job_listing' ) ) {
    function cariera_get_the_company_job_listing( $post = null ) {
        if( ! $post ) {
            global $post;
        }

        return get_posts( [
            'post_type'     => 'job_listing', 
            'meta_key'      => '_company_manager_id',
            'meta_value'    => $post->ID, 
            'nopaging'      => true 
        ] );
    }
}





/**
 * Get the company open position
 *
 * @since  1.3.0
 */
if ( ! function_exists( 'cariera_get_the_company_job_listing_count' ) ) {
    function cariera_get_the_company_job_listing_count( $post = null ) {
        $posts = cariera_get_the_company_job_listing( $post );
        
        return count( $posts );
    }
}





/**
 * Get the company location
 *
 * @since  1.3.0
 */
if ( ! function_exists( 'cariera_get_the_company_location' ) ) {
    function cariera_get_the_company_location( $post = null ) {
        $post = get_post( $post );
        if ( $post->post_type !== 'company' ) {
            return;
        }

        return apply_filters( 'cariera_the_company_location', $post->_company_location, $post );
    }
}

// Output
if ( ! function_exists( 'cariera_the_company_location_output' ) ) {
    function cariera_the_company_location_output( $map_link = true, $post = null ) {
        $location = cariera_get_the_company_location( $post );

        if ( $location ) {
            if ( $map_link )
                echo apply_filters( 'cariera_the_company_location_map_link', '<a class="google_map_link company-location" href="http://maps.google.com/maps?q=' . urlencode( $location ) . '&zoom=14&size=512x512&maptype=roadmap&sensor=false" target="_blank">' . $location . '</a>', $location, $post );
            else
                echo '<span class="company-location">' . $location . '</span>';
        }
    }
}





/**
 * Company created since
 *
 * @since  1.3.0
 */
if ( ! function_exists( 'cariera_get_company_since' ) ) {
    function cariera_get_company_since( $post = null ) {
        $post = get_post( $post );
        if ( $post->post_type !== 'company' ) {
            return;
        }

        return apply_filters( 'cariera_get_company_since', $post->_company_since, $post );
    }
}





/**
 * Company Social
 *
 * @since  1.3.0
 */

// Facebook
if ( ! function_exists( 'cariera_get_the_company_fb' ) ) {
    function cariera_get_the_company_fb( $post = null ) {
        $post = get_post( $post );
        if ( $post->post_type !== 'company' ) {
            return;
        }

        if ( $post->_company_facebook && ! strstr( $post->_company_facebook, 'http:' ) && ! strstr( $post->_company_facebook, 'https:' ) ) {
            $post->_company_facebook = 'https://' . $post->_company_facebook;
        }

        return apply_filters( 'cariera_company_fb_output', $post->_company_facebook, $post );
    }
}

if ( ! function_exists( 'cariera_company_fb_output' ) ) {
    function cariera_company_fb_output( $post = null ) {
        if ( ! empty( cariera_get_the_company_fb( $post ) ) ) {
            echo '<a href="' . esc_url( cariera_get_the_company_fb( $post ) ) . '" class="company-facebook"><i class="fab fa-facebook-f"></i></a>';
        }
    }
}

// Twitter
if ( ! function_exists( 'cariera_get_the_company_twitter' ) ) {
    function cariera_get_the_company_twitter( $post = null ) {
        $post = get_post( $post );
        if ( $post->post_type !== 'company' ) {
            return;
        }

        if ( $post->_company_twitter && ! strstr( $post->_company_twitter, 'http:' ) && ! strstr( $post->_company_twitter, 'https:' ) ) {
            $post->_company_twitter = 'https://' . $post->_company_twitter;
        }

        return apply_filters( 'cariera_company_twitter_output', $post->_company_twitter, $post );
    }
}

if ( ! function_exists( 'cariera_company_twitter_output' ) ) {
    function cariera_company_twitter_output( $post = null ) {
        if ( !empty( cariera_get_the_company_twitter( $post ) ) ) {
            echo '<a href="' . esc_url( cariera_get_the_company_twitter( $post ) ) . '" class="company-twitter"><i class="fab fa-twitter"></i></a>';
        }
    }
}

// Linkedin
if ( ! function_exists( 'cariera_get_the_company_linkedin' ) ) {
    function cariera_get_the_company_linkedin( $post = null ) {
        $post = get_post( $post );
        if ( $post->post_type !== 'company' ) {
            return;
        }

        if ( $post->_company_linkedin && ! strstr( $post->_company_linkedin, 'http:' ) && ! strstr( $post->_company_linkedin, 'https:' ) ) {
            $post->_company_linkedin = 'https://' . $post->_company_linkedin;
        }
        
        
        return apply_filters( 'cariera_company_linkedin_output', $post->_company_linkedin, $post );
    }
}

if ( ! function_exists( 'cariera_company_linkedin_output' ) ) {
    function cariera_company_linkedin_output( $post = null ) {
        if ( ! empty( cariera_get_the_company_linkedin( $post ) ) ) {
            echo '<a href="' . esc_url( cariera_get_the_company_linkedin( $post ) ) . '" class="company-linkedin"><i class="fab fa-linkedin-in"></i></a>';
        }
    }
}





/**
 * Company Video
 *
 * @since  1.3.0
 */
if ( ! function_exists( 'cariera_get_the_company_video' ) ) {
    function cariera_get_the_company_video( $post = null ) {
        $post = get_post( $post );
        if ( ! $post || 'company' !== $post->post_type ) {
            return null;
        }
        return apply_filters( 'cariera_the_company_video', $post->_company_video, $post );
    }
}

// Output
if ( ! function_exists( 'cariera_the_company_video_output' ) ) {
    function cariera_the_company_video_output( $post = null ) {
        $video_embed = false;
        $video       = cariera_get_the_company_video( $post );
        $filetype    = wp_check_filetype( $video );

        if ( ! empty( $video ) ) {
            // FV WordPress Flowplayer Support for advanced video formats.
            if ( shortcode_exists( 'flowplayer' ) ) {
                $video_embed = '[flowplayer src="' . esc_url( $video ) . '"]';
            } elseif ( ! empty( $filetype['ext'] ) ) {
                $video_embed = wp_video_shortcode( array( 'src' => $video ) );
            } else {
                $video_embed = wp_oembed_get( $video );
            }
        }

        $video_embed = apply_filters( 'the_company_video_embed', $video_embed, $post );

        if ( $video_embed ) {
            echo '<div class="company-video">' . $video_embed . '</div>'; // WPCS: XSS ok.
        }
    }
}





/*
==================================================================================
        SINGLE COMPANY PAGE
==================================================================================
*/

/**
 * Single Company Info Header
 *
 * @since  1.3.0
 */
if ( ! function_exists( 'cariera_company_info' ) ) {
    function cariera_company_info() {
        ?>
        <div class="company-photo">
            <?php cariera_the_company_logo(); ?>
        </div>

        <div class="company-details">
            <h1 class="company-name"><?php echo apply_filters( 'cariera_company_name', get_the_title() ); ?></h1>
            
            <?php do_action( 'cariera_single_company_contact_start' ); ?>
        
            <?php if( !empty( cariera_get_the_company_website_link() )) {  ?>
                <div class="company-website">
                    <i class="icon-globe"></i>
                    <a href="<?php echo esc_url( cariera_get_the_company_website_link()); ?>" target="_blank">
                        <?php echo cariera_get_the_company_website_link(); ?>
                    </a>
                </div>
            <?php } ?>
            
            <?php if( !empty( cariera_get_the_company_phone() )) {  ?>
                <div class="company-phone">
                    <i class="icon-phone"></i>
                    <a href="tel:<?php echo esc_attr( cariera_get_the_company_phone() ); ?>"><?php echo cariera_get_the_company_phone(); ?></a>
                </div>
            <?php } ?>
            
            <?php if( !empty( cariera_get_the_company_email() )) {  ?>
                <div class="company-email">
                    <i class="icon-envelope"></i>
                    <a href="mailto:<?php echo esc_attr( cariera_get_the_company_email()); ?>">
                        <?php echo cariera_get_the_company_email(); ?>
                    </a>
                </div>
            <?php } ?>
            
            <?php do_action( 'cariera_single_company_contact_end' ); ?>        
        </div>
        <?php
    }
}

add_action( 'cariera_single_company_header_info', 'cariera_company_info', 10 );





/**
 * Single Company Extra Header Info
 *
 * @since  1.3.0
 */
if ( ! function_exists( 'cariera_company_extra_info_start' ) ) {
    function cariera_company_extra_info_start() {
        echo '<div class="company-extra-info">';
    }
}

add_action( 'cariera_single_company_header_info', 'cariera_company_extra_info_start', 10 );


if ( ! function_exists( 'cariera_company_extra_info_end' ) ) {
    function cariera_company_extra_info_end() {
        echo '</div>';
    }
}

add_action( 'cariera_single_company_header_info', 'cariera_company_extra_info_end', 13 );





/**
 * Single Company Social Media
 *
 * @since  1.3.0
 */
if ( ! function_exists( 'cariera_company_socail_network' ) ) {
    function cariera_company_socail_network() {
        global $post;

        echo '<div class="company-social">';
            // /if( !empty( cariera_get_the_company_fb() ) || !empty( cariera_get_the_company_twitter() ) || !empty( cariera_get_the_company_linkedin() ) ) {
                cariera_company_fb_output();
                cariera_company_twitter_output();
                cariera_company_linkedin_output();
            // /}
            do_action( 'cariera_company_bookmarks' );
        echo '</div>';
    }
}

add_action( 'cariera_single_company_header_info', 'cariera_company_socail_network', 11 );





/**
 * Company Contact Us
 *
 * @since   1.3.0
 * @version 1.5.0
 */
if ( ! function_exists( 'cariera_company_contact_form' ) ) {
    function cariera_company_contact_form() {
        get_job_manager_template_part( 'company', 'contact', 'wp-job-manager-companies' );
    }
}

add_action( 'cariera_single_company_header_info', 'cariera_company_contact_form', 12 );





/**
 * Single Company Description
 *
 * @since   1.3.0
 * @version 1.5.0
 */
if ( ! function_exists( 'cariera_company_description' ) ) {
    function cariera_company_description() {
        if ( empty( get_the_content() ) ) { 
            return;
        } ?>

        <div id="company-description" class="company-description">
            <h5><?php esc_html_e( 'About the Company', 'cariera' ); ?></h5>
            <?php
            the_content();
            wp_link_pages( array(
                'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'cariera' ),
                'after'  => '</div>',
            ) );

            do_action( 'cariera_the_company_description' ); ?>
        </div>
    <?php
    }
}

add_action( 'cariera_single_company_listing', 'cariera_company_description', 10 ); 





/**
 * Single Company Video
 *
 * @since  1.3.0
 */
if ( ! function_exists( 'cariera_company_video' ) ) {
    function cariera_company_video() {
        cariera_the_company_video_output();
    }
}

add_action( 'cariera_single_company_listing', 'cariera_company_video', 11 ); 





/**
 * Single Company Job Listings
 *
 * @since   1.3.0
 * @version 1.5.0
 */
if ( ! function_exists( 'cariera_company_job_listing' ) ) {
    function cariera_company_job_listing() {
        global $post;

        $posts = cariera_get_the_company_job_listing( $post );

        if ( ! get_option( 'cariera_single_company_active_jobs' ) ) {
            return;
        }
        
        if ( count( $posts ) > 0 ) { ?>
            <div id="company-job-listings" class="company-job-listings">
                <h5><?php esc_html_e( 'Job Positions', 'cariera' ); ?></h5>

                <ul class="job_listings job-listings-main job_list row">
                    <?php
                    foreach ( $posts as $post ) {
                        setup_postdata( $post );
                        get_job_manager_template_part( 'job-templates/content', 'job_listing_list1' );
                    } ?>
                </ul>
            </div>
            <?php wp_reset_postdata();
        }
        
    }
}

add_action( 'cariera_single_company_listing', 'cariera_company_job_listing', 13 );





/*
 * Company Submission Flow
 *
 * @since 1.4.4
 */
function cariera_company_submission_flow() {

    // get page IDs
    $current_page_id         = get_queried_object_id();
    $company_submission_page = intval( get_option( 'cariera_submit_company_page', false ) );

    // display submission flow
    if ( !empty($company_submission_page) && ($company_submission_page == $current_page_id) ) { ?>

        <div class="submission-flow company-submission-flow">
            <ul>
                <li class="listing-details"><?php echo esc_html__('Company Details', 'cariera'); ?></li>
                <li class="preview-listing"><?php echo esc_html__('Preview Company', 'cariera'); ?></li>
            </ul>
        </div>

    <?php
    }
}

add_action( 'cariera_page_content_start', 'cariera_company_submission_flow' );
add_action( 'cariera_dashboard_content_start', 'cariera_company_submission_flow', 11 );





/*
 * Adding Share buttons to Single Job Listing 
 *
 * @since 1.4.6
 */
if ( !function_exists('cariera_single_company_share') ) {
    function cariera_single_company_share() {
        if ( cariera_get_option( 'cariera_company_share' ) ) {
            // check if function exists
            if ( function_exists ( 'cariera_share_media' ) ) {
                echo cariera_share_media();
            }
        }
    }
}

add_action( 'cariera_single_company_listing', 'cariera_single_company_share', 12 );