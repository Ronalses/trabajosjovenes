<?php
/**
*
* @package Cariera
*
* @since    1.3.4
* @version  1.5.0
* 
* ========================
* DASHBOARD FUNCTIONS
* ========================
*     
**/





/**
 * Dashboard Navigation - Profile Box
 *
 * @since  1.4.0
 */

function cariera_dashboard_profile() { ?>

    <div class="dashboard-profile-box">
        <?php
        $current_user       = wp_get_current_user();
        $user_id            = get_current_user_id();
        $user_img           = get_avatar( get_the_author_meta( 'ID', $user_id ), 80 );
        ?>

        <span class="avatar-img">
            <div class="login-status"></div>
            <?php echo wp_kses_post( $user_img ); ?>
        </span>
        <span class="fullname">
            <?php echo esc_html( $current_user->first_name ) . ' ' . esc_html( $current_user->last_name ); ?>
        </span>
        <span class="user-role">
            <?php echo esc_html( $current_user->roles[0] ); ?>
        </span>

    </div>

<?php }

add_action( 'cariera_dashboard_nav_inner_start', 'cariera_dashboard_profile', 10 );





/**
 * Dashboard Main Menu
 *
 * @since  1.3.4
 */

function cariera_dashboard_main_menu() { 
    global $post; 

    $user                   = wp_get_current_user();

    // Pages for the Dashboard Main Menu
    $dashboard_page         = get_option( 'cariera_dashboard_page' );
    $employer_dashboard     = get_option( 'job_manager_job_dashboard_page_id' );
    $company_dashboard      = get_option( 'cariera_company_dashboard_page' );
    $candidate_dashboard    = get_option( 'resume_manager_candidate_dashboard_page_id' );
    $job_alerts             = get_option( 'job_manager_alerts_page_id' );
    $resume_alerts          = get_option( 'job_manager_resume_alerts_page_id' );
    $bookmarks              = get_option( 'cariera_bookmarks_page' );
    $applied_jobs           = get_option( 'cariera_past_applications_page' );
    $listing_reports        = get_option( 'cariera_listing_reports_page' );
    
    if ( cariera_wc_is_activated() ) {
        $orders = wc_get_endpoint_url('orders', '', wc_get_page_permalink('myaccount'));
    } ?>

    <ul class="dashboard-nav-main" data-submenu-title="<?php esc_html_e( 'Main', 'cariera' ); ?>">    
        
        <?php if( cariera_get_option('cariera_dashboard_page_enable') ) { ?>
            <li class="dashboard-menu-item_dashboard <?php if( $post->ID == $dashboard_page ) { echo esc_attr('active'); } ?>">
                <a href="<?php echo esc_url(get_permalink($dashboard_page)); ?>">
                    <i class="icon-settings"></i><?php esc_html_e( 'Dashboard', 'cariera' ); ?>
                </a>
            </li>
        <?php } ?>
        
        
        <?php 
        // Employer Dashboard Link
        if( cariera_wp_job_manager_is_activated() ) {
            if ( in_array( 'employer', (array) $user->roles ) || in_array( 'administrator', (array) $user->roles ) ) { ?>
                <li class="dashboard-menu-item_jobs <?php if( $post->ID == $employer_dashboard ) { echo esc_attr('active'); } ?>">
                    <a href="<?php echo esc_url(get_permalink($employer_dashboard)); ?>">
                        <i class="icon-briefcase"></i><?php esc_html_e( 'My Jobs', 'cariera' ); ?>
                    </a>
                </li>
        <?php }
        }


        // Company Dashboard Link
        if( cariera_wp_job_manager_is_activated() && cariera_wp_company_manager_is_activated() ) {
            if ( in_array( 'employer', (array) $user->roles ) || in_array( 'administrator', (array) $user->roles ) ) { ?>
                <li class="dashboard-menu-item_companies <?php if( $post->ID == $company_dashboard ) { echo esc_attr('active'); } ?>">
                    <a href="<?php echo esc_url(get_permalink($company_dashboard)); ?>">
                        <i class="far fa-building"></i><?php esc_html_e( 'My Companies', 'cariera' ); ?>
                    </a>
                </li>
        <?php }
        }
        
    
        // Candidate Dashboard Link
        if( cariera_wp_job_manager_is_activated() && cariera_wp_resume_manager_is_activated() ) {
            if ( in_array( 'candidate', (array) $user->roles ) || in_array( 'administrator', (array) $user->roles ) ) { ?>
                <li class="dashboard-menu-item_resumes <?php if( $post->ID == $candidate_dashboard ) { echo esc_attr('active'); } ?>">
                    <a href="<?php echo esc_url(get_permalink($candidate_dashboard)); ?>">
                        <i class="icon-layers"></i><?php esc_html_e( 'My Resumes', 'cariera' ); ?>
                    </a>
                </li>
            <?php }
        }
        
    
        // Job Alerts Link
        if( cariera_wp_job_manager_is_activated() && class_exists('WP_Job_Manager_Alerts') ) {
            if ( in_array( 'candidate', (array) $user->roles ) || in_array( 'administrator', (array) $user->roles ) ) {
                if( cariera_get_option('cariera_dashboard_job_alerts_page_enable') ) { ?>
                    <li class="dashboard-menu-item_job-alerts <?php if( $post->ID == $job_alerts ) { echo esc_attr('active'); } ?>">
                        <a href="<?php echo esc_url(get_permalink($job_alerts)); ?>">
                            <i class="icon-bell"></i><?php esc_html_e( 'Job Alerts', 'cariera' ); ?>
                        </a>
                    </li>
                <?php }
            }
        }


        // Resume Alerts Link
        if( cariera_wp_job_manager_is_activated() && class_exists('WP_Job_Manager_Resume_Alerts') ) {
            if ( in_array( 'employer', (array) $user->roles ) || in_array( 'administrator', (array) $user->roles ) ) { ?>
                <li class="dashboard-menu-item_resume-alerts <?php if( $post->ID == $resume_alerts ) { echo esc_attr('active'); } ?>">
                    <a href="<?php echo esc_url(get_permalink($resume_alerts)); ?>">
                        <i class="icon-bell"></i><?php esc_html_e( 'Resume Alerts', 'cariera' ); ?>
                    </a>
                </li>
            <?php }
        }
        
    
        // Bookmarks Link
        if( cariera_wp_job_manager_is_activated() && class_exists('WP_Job_Manager_Bookmarks') ) {
            if( cariera_get_option('cariera_dashboard_bookmark_page_enable') ) { ?>
                <li class="dashboard-menu-item_bookmarks <?php if( $post->ID == $bookmarks ) { echo esc_attr('active'); } ?>">
                    <a href="<?php echo esc_url(get_permalink($bookmarks)); ?>">
                        <i class="icon-heart"></i><?php esc_html_e( 'My Bookmarks', 'cariera' ); ?>
                    </a>
                </li>
            <?php }
        }
    
    
        // Applied Jobs Link
        if( cariera_wp_job_manager_is_activated() && class_exists('WP_Job_Manager_Applications') ) {
            if ( in_array( 'candidate', (array) $user->roles ) || in_array( 'administrator', (array) $user->roles ) ) {
                if( cariera_get_option('cariera_dashboard_applied_jobs_page_enable') ) { ?>
                    <li class="dashboard-menu-item_applied-jobs <?php if( $post->ID == $applied_jobs ) { echo esc_attr('active'); } ?>">
                        <a href="<?php echo esc_url(get_permalink($applied_jobs)); ?>">
                            <i class="icon-pencil"></i><?php esc_html_e( 'Applied Jobs', 'cariera' ); ?>
                        </a>
                    </li>
                <?php }
            }
        }
    
        
        // Reports
        if( cariera_wp_job_manager_is_activated() ) {
            if( cariera_get_option('cariera_dashboard_listing_reports_page_enable') ) { ?>
                <li class="dashboard-menu-item_listing-reports <?php if( $post->ID == $listing_reports ) { echo esc_attr('active'); } ?>">
                    <a href="<?php echo esc_url(get_permalink($listing_reports)); ?>">
                        <i class="icon-chart"></i><?php esc_html_e( 'Listing Reports', 'cariera' ); ?>
                    </a>
                </li>
            <?php }
        }
    

        // Orders Link 
        if( cariera_wc_is_activated() ) {
            if( cariera_get_option('cariera_dashboard_orders_page_enable') ) { ?>
                <li class="dashboard-menu-item_orders <?php if( is_wc_endpoint_url('orders') ) { echo esc_attr('active'); } ?>">
                    <a href="<?php echo esc_url($orders); ?>">
                        <i class="icon-credit-card"></i><?php esc_html_e( 'Orders', 'cariera'); ?>
                    </a>            
                </li>
            <?php }
        } ?>
        
        <?php do_action( 'cariera_dashboard_main_nav_end' ); ?>
    </ul>


    <?php
    // Extra Dashboard Menu for Employers
    if ( in_array( 'employer', (array) $user->roles ) ) {
        wp_nav_menu( array(
            'theme_location'    => 'employer-dash',
            'container'         => false,
            'menu_class'        => 'dashboard-nav-employer-extra',
            'walker'            => new Cariera_Mega_Menu_Walker,
            'fallback_cb'       => '__return_false'
        ) );
    }
    
    // Extra Dashboard Menu for Candidates
    if ( in_array( 'candidate', (array) $user->roles ) ) {
        wp_nav_menu( array(
            'theme_location'    => 'candidate-dash',
            'container'         => false,
            'menu_class'        => 'dashboard-nav-candidate-extra',
            'walker'            => new Cariera_Mega_Menu_Walker,
            'fallback_cb'       => '__return_false'
        ));
    }
    ?>

<?php }

add_action( 'cariera_dashboard_menu', 'cariera_dashboard_main_menu', 10 );





/**
 * Dashboard Listing Menu
 *
 * @since  1.3.4
 */

function cariera_dashboard_listing_menu() {
    global $post; 

    $user = wp_get_current_user();

    // Pages for the Dashboard Listing Menu
    $post_job       = get_option('job_manager_submit_job_form_page_id');
    $post_company   = get_option('cariera_submit_company_page');
    $post_resume    = get_option('resume_manager_submit_resume_form_page_id');
    ?>

    <ul class="dashboard-nav-listing" data-submenu-title="<?php esc_html_e( 'Listing', 'cariera' ); ?>">
        
        <?php 
        // Post Job Link
        if ( cariera_wp_job_manager_is_activated() ) {
            if ( in_array( 'employer', (array) $user->roles ) || in_array( 'administrator', (array) $user->roles ) ) { ?>
                <li class="dashboard-menu-item_post-job <?php if( $post->ID == $post_job ) { echo esc_attr('active'); } ?>">
                    <a href="<?php echo esc_url(get_permalink($post_job)); ?>">
                        <i class="icon-plus"></i><?php esc_html_e( 'Post Job', 'cariera'); ?>
                    </a>
                </li>
            <?php }
        }

        // Submit Company Link
        if ( cariera_wp_job_manager_is_activated() && cariera_wp_company_manager_is_activated() ) {
            if ( in_array( 'employer', (array) $user->roles ) || in_array( 'administrator', (array) $user->roles ) ) { ?>
                <li class="dashboard-menu-item_submit-company <?php if( $post->ID == $post_company ) { echo esc_attr('active'); } ?>">
                    <a href="<?php echo esc_url(get_permalink($post_company)); ?>">
                        <i class="icon-plus"></i><?php esc_html_e( 'Submit Company', 'cariera'); ?>
                    </a>
                </li>
            <?php }
        }
    
        // Submit Resume Link
        if ( cariera_wp_job_manager_is_activated() && cariera_wp_resume_manager_is_activated() ) {
            if ( in_array( 'candidate', (array) $user->roles ) || in_array( 'administrator', (array) $user->roles ) ) { ?>
                <li class="dashboard-menu-item_submit-resume <?php if( $post->ID == $post_resume ) { echo esc_attr('active'); } ?>">
                    <a href="<?php echo esc_url(get_permalink($post_resume)); ?>">
                        <i class="icon-plus"></i><?php esc_html_e( 'Submit Resume', 'cariera'); ?>
                    </a>
                </li>
            <?php }
        } ?>
        
        <?php do_action( 'cariera_dashboard_listing_nav_end' ); ?>        
    </ul>

<?php }

add_action( 'cariera_dashboard_menu', 'cariera_dashboard_listing_menu', 11 );





/**
 * Dashboard Account Menu
 *
 * @since  1.3.4
 */

function cariera_dashboard_account_menu() { 
    global $post; 

    // Pages for the Dashboard Listing Menu
    $profile    = get_option('cariera_dashboard_profile_page');
    ?>

    <ul class="dashboard-nav-account" data-submenu-title="<?php esc_html_e( 'Account', 'cariera' ); ?>">
        
        <?php if( cariera_get_option('cariera_dashboard_profile_page_enable') ) { ?>
            <li class="dashboard-menu-item_my-profile <?php if( $post->ID == $profile ) { echo esc_attr('active'); } ?>">
                <a href="<?php echo esc_url(get_permalink($profile)); ?>">
                    <i class="icon-user"></i><?php esc_html_e( 'My Profile', 'cariera'); ?>
                </a>
            </li>
        <?php } ?>
    
        <li>
            <a href="<?php echo wp_logout_url(home_url()); ?>"><i class="icon-power"></i><?php esc_html_e( 'Logout', 'cariera' );?></a>
        </li>
    </ul>

<?php }

add_action( 'cariera_dashboard_menu', 'cariera_dashboard_account_menu', 12 );





/**
 * Dashboard Title Bar
 *
 * @since  1.3.4
 */

function cariera_dashboard_titlebar() {
    global $post;
    $current_user = wp_get_current_user();
    
    if(!empty($current_user->user_firstname)){
        $name = $current_user->user_firstname;
    } else {
        $name =  $current_user->display_name;
    } ?>
    <div class="title-bar">
        <div class="row">
            <div class="col-md-12">
                <?php 
                $dashboard_page = cariera_get_option('cariera_dashboard_page'); 
                
                if( $dashboard_page == $post->ID ) { ?>
                    <h2><?php printf( esc_html__( 'Welcome, %s!', 'cariera'), esc_html($name) ); ?></h2>
                <?php } else {  ?>
                    <h1><?php the_title(); ?></h1>
                <?php } ?>
            </div>
        </div>
    </div>
<?php }

add_action( 'cariera_dashboard_content_start', 'cariera_dashboard_titlebar', 10 );





/**
 * Dashboard Copyright Footer
 *
 * @since  1.3.4
 */

function cariera_dashboard_copyright() { ?>
    <!-- Copyrights -->
    <div class="row">
        <div class="col-md-12">
            <div class="copyrights">
                <?php 
                $copyright = cariera_get_option( 'cariera_copyrights' ); 
                echo wp_kses_post( $copyright );
                ?>
            </div>
        </div>
    </div>
<?php }

add_action( 'cariera_dashboard_content_end', 'cariera_dashboard_copyright', 10 );





/**
 * Remove WooCommerce Nav on User Dashboard Template
 *
 * @since  1.3.5
 */

function cariera_remove_wc_nav_on_dash() {
    if ( is_page_template( 'templates/user-dashboard.php' ) ) {
        remove_action( 'woocommerce_account_navigation', 'woocommerce_account_navigation' );
    }    
}

add_action( 'wp', 'cariera_remove_wc_nav_on_dash' );