<?php
/**
*
* @package Cariera
*
* @since    1.0.0
* @version  1.5.1
* 
* ========================
* ALL WP JOB MANAGER FUNCTIONS
* ========================
*     
**/





// Prevent from enqueueing wpjm frontend.css
add_filter( 'job_manager_enqueue_frontend_style', '__return_false', 30 );

// Remove Job Search Button
add_filter( 'job_manager_job_filters_show_submit_button', '__return_false' );

// Remove "Application Deadline Output"
if ( class_exists('WP_Job_Manager_Application_Deadline') ) {
    remove_action( 'single_job_listing_meta_end', array( $job_manager_application_deadline, 'display_the_deadline' ) );
}





/**
 * Bind default `job_content_start` to the theme
 *
 * @since 1.3.8.1
 */
function cariera_job_content_start() {
    return do_action('job_content_start');
}

add_action( 'single_job_listing_start', 'cariera_job_content_start' );





/**
 * Get Currency Symbol
 *
 * @since 1.0.0
 */
if ( !function_exists('cariera_currency_symbol') ) {
    function cariera_currency_symbol( $currency = '' ) {
        if ( ! $currency ) {
            $currency = get_option('cariera_currency_setting');
        }

        switch ( $currency ) {
            case 'BHD' :
                $currency_symbol = esc_html__('.د.ب', 'cariera');
                break;
            case 'AED' :
                $currency_symbol = esc_html__('د.إ', 'cariera');
                break;
            case 'AUD' :
            case 'ARS' :
            case 'CAD' :
            case 'CLP' :
            case 'COP' :
            case 'HKD' :
            case 'MXN' :
            case 'NZD' :
            case 'SGD' :
            case 'USD' :
                $currency_symbol = esc_html__('&#36;', 'cariera');
                break;
            case 'BDT':
                $currency_symbol = esc_html__('&#2547;&nbsp;', 'cariera');
                break;
            case 'LKR':
                $currency_symbol = esc_html__('&#3515;&#3540;&nbsp;', 'cariera');
                break;
            case 'BGN' :
                $currency_symbol = esc_html__('&#1083;&#1074;.', 'cariera');
                break;
            case 'BRL' :
                $currency_symbol = esc_html__('&#82;&#36;', 'cariera');
                break;
            case 'CHF' :
                $currency_symbol = esc_html__('&#67;&#72;&#70;', 'cariera');
                break;
            case 'CNY' :
            case 'JPY' :
            case 'RMB' :
                $currency_symbol = esc_html__('&yen;', 'cariera');
                break;
            case 'CZK' :
                $currency_symbol = esc_html__('&#75;&#269;', 'cariera');
                break;
            case 'DKK' :
                $currency_symbol = esc_html__('DKK', 'cariera');
                break;
            case 'DOP' :
                $currency_symbol = esc_html__('RD&#36;', 'cariera');
                break;
            case 'EGP' :
                $currency_symbol = esc_html__('EGP', 'cariera');
                break;
            case 'EUR' :
                $currency_symbol = esc_html__('&euro;', 'cariera');
                break;
            case 'GBP' :
                $currency_symbol = esc_html__('&pound;', 'cariera');
                break;
            case 'HRK' :
                $currency_symbol = esc_html__('Kn', 'cariera');
                break;
            case 'HUF' :
                $currency_symbol = esc_html__('&#70;&#116;', 'cariera');
                break;
            case 'IDR' :
                $currency_symbol = esc_html__('Rp', 'cariera');
                break;
            case 'ILS' :
                $currency_symbol = esc_html__('&#8362;', 'cariera');
                break;
            case 'INR' :
                $currency_symbol = esc_html__('Rs.', 'cariera');
                break;
            case 'ISK' :
                $currency_symbol = esc_html__('Kr.', 'cariera');
                break;
            case 'KIP' :
                $currency_symbol = esc_html__('&#8365;', 'cariera');
                break;
            case 'KRW' :
                $currency_symbol = esc_html__('&#8361;', 'cariera');
                break;
            case 'MYR' :
                $currency_symbol = esc_html__('&#82;&#77;', 'cariera');
                break;
            case 'NGN' :
                $currency_symbol = esc_html__('&#8358;', 'cariera');
                break;
            case 'NOK' :
                $currency_symbol = esc_html__('&#107;&#114;', 'cariera');
                break;
            case 'NPR' :
                $currency_symbol = esc_html__('Rs.', 'cariera');
                break;
            case 'PHP' :
                $currency_symbol = esc_html__('&#8369;', 'cariera');
                break;
            case 'PLN' :
                $currency_symbol = esc_html__('&#122;&#322;', 'cariera');
                break;
            case 'PYG' :
                $currency_symbol = esc_html__('&#8370;', 'cariera');
                break;
            case 'RON' :
                $currency_symbol = esc_html__('lei', 'cariera');
                break;
            case 'RUB' :
                $currency_symbol = esc_html__('&#1088;&#1091;&#1073;.', 'cariera');
                break;
            case 'SEK' :
                $currency_symbol = esc_html__('&#107;&#114;', 'cariera');
                break;
            case 'THB' :
                $currency_symbol = esc_html__('&#3647;', 'cariera');
                break;
            case 'TRY' :
                $currency_symbol = esc_html__('&#8378;', 'cariera');
                break;
            case 'TWD' :
                $currency_symbol = esc_html__('&#78;&#84;&#36;', 'cariera');
                break;
            case 'UAH' :
                $currency_symbol = esc_html__('&#8372;', 'cariera');
                break;
            case 'VND' :
                $currency_symbol = esc_html__('&#8363;', 'cariera');
                break;
            case 'ZAR' :
                $currency_symbol = esc_html__('&#82;', 'cariera');
                break;
            default :
                $currency_symbol = esc_html__('', 'cariera');
                break;
        }

        return apply_filters( 'woocommerce_currency_symbol', $currency_symbol, $currency );
    }
}





/**
 * Newly Job Posted
 *
 * @since 1.1.0
 */
if(!function_exists('cariera_newly_posted')) {
	function cariera_newly_posted() {
		global $post;
		$now = date('U'); 
        $published = get_the_time('U');
		$new = false;
        
		// set to 48 hours in seconds 
		if( $now-$published  <= 2*24*60*60 ) $new = true;
        
		return $new;
	}
}




/*
 * Rating class function
 *
 * @since 1.1.0
 */
if ( !function_exists('cariera_get_rating_class') ) {
    function cariera_get_rating_class($average) {
        if(!$average) {
                $class="no-stars";
        } else {
            switch ($average) {
                case $average >= 1 && $average < 1.5:
                    $class="one-stars";
                    break;
                case $average >= 1.5 && $average < 2:
                    $class="one-and-half-stars";
                    break;
                case $average >= 2 && $average < 2.5:
                    $class="two-stars";
                    break;
                case $average >= 2.5 && $average < 3:
                    $class="two-and-half-stars";
                    break;
                case $average >= 3 && $average < 3.5:
                    $class="three-stars";
                    break;
                case $average >= 3.5 && $average < 4:
                    $class="three-and-half-stars";
                    break;
                case $average >= 4 && $average < 4.5:
                    $class="four-stars";
                    break;
                case $average >= 4.5 && $average < 5:
                    $class="four-and-half-stars";
                    break;
                case $average >= 5:
                    $class="five-stars";
                    break;

                default:
                    $class="no-stars";
                    break;
            }
        }
        return $class;
    }
}





/*
 * Job Alert Login URL
 *
 * @since 1.2.3
 */
function cariera_job_alert_login() {
    
    $login_registration = get_option('cariera_login_register_layout');
                
    if ( $login_registration == 'popup' ) {
        $login_registration_page = get_option('woocommerce_myaccount_page_id');
    } else {
        $login_registration_page = get_option('cariera_login_register_page');
    }
            
    return get_permalink( $login_registration_page );
}

add_filter( 'job_manager_alerts_login_url', 'cariera_job_alert_login' );





/*
 * Show Number of Job Applications
 *
 * @since 1.2.5
 */
if ( class_exists( 'WP_Job_Manager_Applications' ) ) {
    if ( !function_exists('cariera_job_applications') ) {
        function cariera_job_applications() {
            global $post;
            $count = get_job_application_count( $post->ID );

            echo '<span>' . $count . ' ' . esc_html__( 'Application(s)', 'cariera' ) . '</span>';
        }
    }
}





/*
 * Changing the email for candidates & companies in CF7
 *
 * @since 1.3.0
 */
if ( ! function_exists( 'cariera_wpjm_wpcf7_notification_email' ) ) {
    function cariera_wpjm_wpcf7_notification_email( $components, $cf7, $three = null ) {
        $forms = apply_filters( 'cariera_wpjm_wpcf7_notification_email_forms', [
            'company' => [
                'contact' => get_option('cariera_single_company_contact_form')
            ],
            'resume' => [
                'contact' => get_option('resume_manager_single_resume_contact_form')
            ]
        ]);

        $submission = WPCF7_Submission::get_instance();
        $unit_tag   = $submission->get_meta( 'unit_tag' );

        if ( ! preg_match( '/^wpcf7-f(\d+)-p(\d+)-o(\d+)$/', $unit_tag, $matches ) ) {
            return $components;   
        }

        $post_id  = (int) $matches[2];
        $post     = get_post( $post_id );

        
        // Prevent issues when the form is not submitted via a resume or company page
        if ( ! isset( $forms[ $post->post_type ] ) ) {
            return $components;
        }

        if ( ! array_search( $cf7->id(), $forms[ $post->post_type ] ) ) {
            return $components;
        }

        // // Bail if this is the second mail
        if ( isset( $three ) && 'mail_2' == $three->name() ) {
            return $components;
        }

        switch ( $post->post_type ) {
            case 'company':
                $recipient = $post->_company_email ? $post->_company_email : '';
                break;

            case 'resume':
                $recipient = $post->_candidate_email ? $post->_candidate_email : '';
                break;

            default:
                $recipient = '';
                break;
        }

        //if we couldn't find the email by now, get it from the listing owner/author
        if ( empty( $recipient ) ) {

            //just get the email of the listing author
            $owner_ID = $post->post_author;

            //retrieve the owner user data to get the email
            $owner_info = get_userdata( $owner_ID );

            if ( false !== $owner_info ) {
                $recipient = $owner_info->user_email;
            }
        }

        $components['recipient'] = $recipient;

        return $components;
    }
}

add_filter( 'wpcf7_mail_components', 'cariera_wpjm_wpcf7_notification_email', 10, 3 );





/*
 * Job Quickview AJAX function
 *
 * @since 1.3.1
 */
function cariera_load_quickview_content_callback() {
    if( !isset($_POST['id']) ){
        die('0');
    }

    $job_id = absint( (int)($_POST['id']) );

    global $post;
    $post = get_post( $job_id );

    $classes = 'cariera-quickview-wrapper job-listing';
    ob_start();
    ?>

    <div id="job-id-<?php echo esc_attr( get_the_ID() );?>" class="<?php echo esc_attr($classes); ?>">
        <div class="row">

            <!-- Job summary -->
            <div class="col-md-7 col-sm-12 col-xs-12 job-summary">
                
                <div class="single-job-listing">
                    <?php if ( get_option( 'job_manager_hide_expired_content', 1 ) && 'expired' === $post->post_status ) : ?>
                        <div class="job-manager-info"><?php _e( 'This listing has expired.', 'cariera' ); ?></div>
                    <?php else : ?>
                        <?php do_action( 'single_job_listing_start' ); ?>

                        <div class="job-description">
                            <?php echo get_post_field('post_content', $job_id); ?>
                        </div>

                        <?php if ( candidates_can_apply() ) : ?>
                            <?php get_job_manager_template( 'job-application.php' ); ?>
                        <?php endif; ?>

                        <?php do_action( 'single_job_listing_end' ); ?>
                    <?php endif; ?>
                </div>
                
            </div>
            
            <!-- Job map -->
            <div class="col-md-5 col-sm-12 col-xs-12 job-map-wrapper">
                <div id="job-map" data-longitude="<?php echo esc_attr( $post->geolocation_long ); ?>" data-latitude="<?php echo esc_attr( $post->geolocation_lat ); ?>"></div>
            </div>
            
        </div>

    </div>

    <?php
    $return = ob_get_clean();
    wp_reset_postdata();

    die($return);
}

/* Register ajax */
add_action('wp_ajax_load_quickjob_content', 'cariera_load_quickview_content_callback' );
add_action('wp_ajax_nopriv_load_quickjob_content', 'cariera_load_quickview_content_callback' );





/*
 * AJAX Job Search Suggestions
 *
 * @since 1.3.1
 */
function cariera_search_jobs() {
    check_ajax_referer( '_cariera_nonce', 'nonce' );

    $search_query = new WP_Query( array(
        's'             => sanitize_text_field( $_REQUEST['term'] ),
        'post_type'     => 'job_listing',
        'post_status'   => 'publish',
    ) );
    
    global $post;
    $response = array();

    if ( $search_query->get_posts() ) {
        foreach ( $search_query->get_posts() as $post ) {
            setup_postdata($post);
        
            $response[] = sprintf(
                '<li>' .
                '<a class="search-item" href="%s">' .
                '<img class="item-thumb" src="%s">' .
                '<div class="item-details">' .
                '<span class="title">%s</span>' .
                '<span class="location">%s</span>' .
                '</div>' .
                '</a>' .
                '</li>',
                esc_url( get_permalink($post->ID) ),
                get_the_post_thumbnail_url(),
                $post->post_title,
                get_post_meta( $post->ID, '_job_location', true )
            );
        }
    }
    
    if ( empty( $response ) ) {
        $response[] = sprintf( '<li>%s</li>', esc_html__( 'Nothing found', 'cariera' ) );
    }
    
    $output = sprintf( '<ul>%s</ul>', implode( ' ', $response ) );
    
    wp_send_json_success( $output );
    
    die();
}

/* Register ajax */
add_action( 'wp_ajax_cariera_search_jobs', 'cariera_search_jobs' );
add_action( 'wp_ajax_nopriv_cariera_search_jobs', 'cariera_search_jobs' );





/*
 * Company Logos
 *
 * @since   1.3.2
 * @version 1.5.0
 */
if ( ! function_exists( 'cariera_the_company_logo' ) ) {
    function cariera_the_company_logo( $args = [] ) {
        $defaults = apply_filters( 'cariera_company_logo_size', [
            'size'    => 'thumbnail',
            'default' => null,
            'post'    => null
        ] );
        
        $args = wp_parse_args( $defaults, $args );
        extract( $args );

        the_company_logo( $size, $default, $post );
    }
}





/*
 * Output the job's min & max rate if there is any
 *
 * @since 1.4.1
 */
function cariera_job_rate() {
    global $post;

    $currency_position  = get_option( 'cariera_currency_position', 'before' );
    $rate_min           = get_post_meta( $post->ID, '_rate_min', true );
    
    if ( $rate_min ) {
        $rate_max = get_post_meta( $post->ID, '_rate_max', true );

        // Currency Symbol Before
        if( $currency_position == 'before' ) { 
            echo cariera_currency_symbol(); 
        }
        echo esc_html( $rate_min );
        // Currency Symbol After
        if( $currency_position == 'after' ) { 
            echo cariera_currency_symbol(); 
        }

        // MAX Rate if there is any
        if( !empty($rate_max) ) { 
            echo esc_html(' - ');
            
            // Currency Symbol Before
            if( $currency_position == 'before' ) { 
                echo cariera_currency_symbol(); 
            }
            echo esc_html( $rate_max );
            // Currency Symbol After
            if( $currency_position == 'after' ) { 
                echo cariera_currency_symbol(); 
            }
        } 
        esc_html_e( '/hour','cariera' );
    }
}





/*
 * Output the job's min & max salary if there is any
 *
 * @since 1.4.1
 */
function cariera_job_salary() {
    global $post;
    
    $currency_position  = get_option( 'cariera_currency_position', 'before' );
    $salary_min         = get_post_meta( $post->ID, '_salary_min', true ); 
    
    if ( $salary_min ) { 
        $salary_max = get_post_meta( $post->ID, '_salary_max', true );

        // Currency Symbol Before
        if( $currency_position == 'before' ) { 
            echo cariera_currency_symbol(); 
        }
        echo esc_html( $salary_min );
        // Currency Symbol After
        if( $currency_position == 'after' ) { 
            echo cariera_currency_symbol(); 
        }

        // MAX Salary if there is any
        if( !empty($salary_max) ) {
            echo esc_html(' - ');
            
            // Currency Symbol Before
            if( $currency_position == 'before' ) { 
                echo cariera_currency_symbol(); 
            }
            echo esc_html( $salary_max );
            // Currency Symbol After
            if( $currency_position == 'after' ) { 
                echo cariera_currency_symbol(); 
            }                            
        }
    }
}





/*
 * Output the job's min & max salary if there is any
 *
 * @since 1.4.1
 */
function cariera_customize_editor_toolbar( $args ) {
    $args['tinymce']['toolbar1'] = 'formatselect,|,bold,italic,underline,|,bullist,numlist,|,link,unlink,|,undo,redo';
    return $args;
}

add_filter( 'submit_job_form_wp_editor_args', 'cariera_customize_editor_toolbar' );





/*
 * Add og:image tag for jobs
 *
 * @since 1.4.6
 */
function cariera_job_og_image() {
    if( is_singular('job_listing') ) {
        echo '<meta property="og:image" content="' . get_the_post_thumbnail_url( get_the_ID(), 'full' )   . '" />';
    }
}

add_action( 'wp_head', 'cariera_job_og_image' );