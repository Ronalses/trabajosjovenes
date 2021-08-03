<?php
/**
*
* @package Cariera
*
* @since    1.3.4
* @version  1.5.1
* 
* ========================
* Template Name: User Dashboard
* ========================
*    
**/


wp_enqueue_style( 'cariera-dashboard', get_template_directory_uri() . '/assets/dist/css/dashboard.css' );


// If user is not logged
if ( !is_user_logged_in() ) {
    
    get_header();
    
    while ( have_posts() ) : the_post();
        get_template_part( 'templates/content/content', 'page' );
    endwhile;
    
    get_footer();
    
// else user is logged
} else { 
    
    get_header();
    $current_user   = wp_get_current_user();
    $user_id        = get_current_user_id();
    $roles          = $current_user->roles;
    $role           = array_shift( $roles );

    wp_enqueue_script('cariera-dashboard'); ?>
    

    <!-- ===== Start of Dashboard ===== -->
    <div id="dashboard" class="<?php echo esc_attr($role); ?>-dashboard">
        
        <!-- =============== Start of Dashboard Navigation =============== -->
        <a href="#" class="dashboard-mobile-nav">
            <i class="fas fa-bars"></i><?php esc_html_e( 'Dashboard Navigation', 'cariera' ); ?>
        </a>
        
        <div class="dashboard-nav">
            <?php do_action( 'cariera_dashboard_nav_inner_start' ); ?>
            <div class="dashboard-nav-inner">
                <?php do_action( 'cariera_dashboard_menu' ); ?>
            </div>
        </div>
        <!-- =============== End of Dashboard Navigation =============== -->

        
        
        <!-- =============== Start of Dashboard Content =============== -->        
        <div class="dashboard-content" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <?php do_action('cariera_dashboard_content_start'); ?>
            
            <?php 
            while ( have_posts() ) : the_post();
                the_content();
            endwhile; // End of the loop. ?>
            
            <?php do_action('cariera_dashboard_content_end'); ?>                    
        </div>        
        <!-- =============== End of Dashboard Content =============== -->

    </div>
    <!-- ===== End of Dashboard ===== -->

    <?php get_footer('empty');
} ?>