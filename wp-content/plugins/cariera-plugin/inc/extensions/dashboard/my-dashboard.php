<?php
/**
*
* @package Cariera
*
* @since 1.3.4
* 
* ========================
* MY DASHBOARD FUNTIONS
* ========================
*     
**/





/**
 * Listing Packages shown in the [cariera_dashboard] when the WC Paid listing is active
 *
 * @since  1.3.4
 */

if ( !function_exists('cariera_dashboard_listing_packages') ) {
    function cariera_dashboard_listing_packages() { 
        
        if ( !class_exists( 'WP_Job_Manager' ) || !class_exists( 'WC_Paid_Listings' ) ) {
            return;
        } ?>

        <ul class="listing-packages">                                
            <?php
            $job_packages       = wc_paid_listings_get_user_packages( get_current_user_id(), 'job_listing' ); 
            $resume_packages    = wc_paid_listings_get_user_packages( get_current_user_id(), 'resume' );
            $packages           = sizeof($job_packages) + sizeof($resume_packages);


            if ( $packages > 0 ) {

                // Showing all the Job Packages
                foreach ( $job_packages as $job_package ) {
                    $job_package = wc_paid_listings_get_package( $job_package ); ?>

                    <li class="package">
                        <i class="list-icon icon-star"></i>
                        <h6 class="package-title"><?php echo $job_package->get_title(); ?></h6>

                        <p><?php printf( esc_html__( 'You have %s job listings left that you can post.', 'cariera' ), $job_package->get_limit() ? absint( $job_package->get_limit() - $job_package->get_count() ) : esc_html__( 'Unlimited', 'cariera' ) ); ?></p>

                        <p><?php printf( esc_html__( 'Job listing duration: %s', 'cariera' ), $job_package->get_duration() ? sprintf( _n( '%d day', '%d days', $job_package->get_duration(), 'cariera' ), $job_package->get_duration() ) : '-' ); ?></p>
                    </li>
                <?php }


                // Showing all the Resume Packages
                foreach ( $resume_packages as $resume_package ) {
                    $resume_package = wc_paid_listings_get_package( $resume_package ); ?>

                    <li class="package">
                        <i class="list-icon icon-star"></i>
                        <h6 class="package-title"><?php echo $resume_package->get_title(); ?></h6>

                        <p><?php printf( esc_html__( 'You have %s resumes left that you can post.', 'cariera' ), $resume_package->get_limit() ? absint( $resume_package->get_limit() - $resume_package->get_count() ) : esc_html__( 'Unlimited', 'cariera' ) ); ?></p>

                        <p><?php printf( esc_html__( 'Resume listing duration: %s', 'cariera' ), $resume_package->get_duration() ? sprintf( _n( '%d day', '%d days', $resume_package->get_duration(), 'cariera' ), $resume_package->get_duration() ) : '-' ); ?></p>
                    </li>
                <?php }

            } else { ?>
                <li><?php esc_html_e( 'No packages have been bought or all packages have been used.', 'cariera' ); ?></li>
            <?php } ?>
        </ul>
        
    <?php }
}





/**
 * My Account shortcode
 * Usage: [cariera_dashboard]
 *
 * @since  1.3.4
 */

if ( !function_exists('cariera_dashboard') ) {
    function cariera_dashboard() {
        global $wp_roles;
        $current_user = wp_get_current_user();
        $user_id      = $current_user->ID;
        
        if ( !is_user_logged_in() ) { ?>
            <p><?php esc_html_e( 'You need to be signed in to access your dashboard.', 'cariera'); ?></p>

            <?php
            $login_registration = get_option('cariera_login_register_layout');

            if ( $login_registration == 'popup' ) { ?>
                <a href="#login-register-popup" class="btn btn-main btn-effect popup-with-zoom-anim">
            <?php } else {
                $login_registration_page     = get_option('cariera_login_register_page');
                $login_registration_page_url = get_permalink( $login_registration_page );?>

                <a href="<?php echo esc_url( $login_registration_page_url ); ?>" class="btn btn-main btn-effect">
            <?php }
                esc_html_e( 'Sign in', 'cariera' ); ?>
            </a>
        <?php } else { 

            if ( in_array( 'administrator', (array) $current_user->roles ) ) {
                $listing_name = esc_html__( 'Listings', 'cariera');                    
                
                // Jobs
                $active_jobs            = cariera_count_user_posts_by_status( $current_user->ID, 'job_listing', 'publish' );
                $pending_jobs           = cariera_count_user_posts_by_status( $current_user->ID, 'job_listing', 'pending' );
                $expired_jobs           = cariera_count_user_posts_by_status( $current_user->ID, 'job_listing', 'expired' );
                // Resumes
                $active_resumes         = cariera_count_user_posts_by_status( $current_user->ID, 'resume', 'publish' );
                $pending_resumes        = cariera_count_user_posts_by_status( $current_user->ID, 'resume', 'pending' );
                $expired_resumes        = cariera_count_user_posts_by_status( $current_user->ID, 'resume', 'expired' );
                // All listings together
                $active_listings        = $active_jobs + $active_resumes;
                $pending_listings       = $pending_jobs + $pending_resumes;
                $expired_listings       = $expired_jobs + $expired_resumes;
            } elseif ( in_array( 'employer', (array) $current_user->roles ) ) {
                $listing_name = esc_html__( 'Listings', 'cariera');
                
                $active_listings        = cariera_count_user_posts_by_status( $current_user->ID, 'job_listing', 'publish' );
                $pending_listings       = cariera_count_user_posts_by_status( $current_user->ID, 'job_listing', 'pending' );
                $expired_listings       = cariera_count_user_posts_by_status( $current_user->ID, 'job_listing', 'expired' );
            } elseif ( in_array( 'candidate', (array) $current_user->roles ) ) {
                $listing_name = esc_html__( 'Resumes', 'cariera');
                
                $active_listings        = cariera_count_user_posts_by_status( $current_user->ID, 'resume', 'publish' );
                $pending_listings       = cariera_count_user_posts_by_status( $current_user->ID, 'resume', 'pending' );
                $expired_listings       = cariera_count_user_posts_by_status( $current_user->ID, 'resume', 'expired' );
            } else {
                return;
            } ?>
            
            
            
            <!-- Start of Stats -->
            <div class="row">

                <!-- Stat Item -->
                <div class="col-lg-3 col-md-6 dashboard-widget published-listings">
                    <div class="card-statistics style-1">
                        <div class="statistics-content">
                            <h4><?php echo esc_html($active_listings); ?></h4>
                            <span><?php printf( esc_html__( 'Published %s', 'cariera' ), $listing_name ); ?></span>
                        </div>
                        <div class="statistics-icon">
                            <i class="icon-check"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Stat Item -->
                <div class="col-lg-3 col-md-6 dashboard-widget pending-listings">
                    <div class="card-statistics style-2">
                        <div class="statistics-content">
                            <h4><?php echo esc_html($pending_listings); ?></h4>
                            <span><?php printf( esc_html__( 'Pending %s', 'cariera' ), $listing_name ); ?></span>
                        </div>
                        <div class="statistics-icon">
                            <i class="icon-pencil"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Stat Item -->
                <div class="col-lg-3 col-md-6 dashboard-widget expired-listings">
                    <div class="card-statistics style-3">
                        <div class="statistics-content">
                            <h4><?php echo esc_html($expired_listings); ?></h4>
                            <span><?php printf( esc_html__( 'Expired %s', 'cariera' ), $listing_name ); ?></span>
                        </div>
                        <div class="statistics-icon">
                            <i class="icon-clock"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Stat Item -->
                <div class="col-lg-3 col-md-6 dashboard-widget monthly-views-stats">
                    <div class="card-statistics style-4">
                        <div class="statistics-content">
                            <h4><?php echo esc_html( '0' ); ?></h4>
                            <span><?php esc_html_e( 'Monthly Views', 'cariera' ); ?></span>
                        </div>
                        <div class="statistics-icon">
                            <i class="icon-eye"></i>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Stats -->
            


            <!-- Start of Charts & Packages -->
            <div class="row mt50">
                
                <?php if ( cariera_get_option('cariera_dashboard_views_statistics') ) { ?>
                    
                    <!-- Monthly Views -->
                    <div class="col-lg-6 col-md-12 dashboard-content-views">
                        <div class="dashboard-card-box">
                            <h4 class="title"><?php esc_html_e( 'Monthly Views', 'cariera' ); ?></h4>

                            <div class="dashboard-card-box-inner">
                                <div class="canvas-loader"><span></span></div>
                                <canvas id="views-chart"></canvas>
                            </div>                        
                        </div>
                    </div>
                <?php } ?> 
                
                <?php if ( class_exists( 'WooCommerce' ) && class_exists('WC_Paid_Listings') ) { ?>
                    <!-- Listing Packages -->
                    <div class="col-lg-6 col-md-12 dashboard-content-packages">
                        <div class="dashboard-card-box">
                            <h4 class="title"><?php esc_html_e( 'Active Packages', 'cariera' ); ?></h4>

                            <div class="dashboard-card-box-inner">
                                <?php echo cariera_dashboard_listing_packages(); ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            
            </div>
            <!-- End of Charts & Packages -->
        <?php }
    }
}

add_shortcode( 'cariera_dashboard', 'cariera_dashboard' );