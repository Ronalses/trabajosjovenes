<?php
/**
*
* @package Cariera
*
* @since 1.0
* 
* ========================
* Template Name: Home Page - Search Banner
* ========================
*    
**/


get_header(); ?>



<!-- ===== Start of Main Search Section ===== -->
<section class="home-search overlay-black">
    <div class="container justify-content-center align-self-center">
        <div class="row">
            <div class="col-md-12">

                <h2 class="title">
                    <?php 
                    $intro_text = cariera_get_option( 'home_page_text' );
                    
                    echo esc_html( $intro_text ); ?>
                </h2>

                <!-- Start of Form -->
                <?php echo do_shortcode('[search_form location="yes"]'); ?>
                <!-- End of Form -->
                
                <?php if( cariera_get_option('home_job_counter') ) : ?>
                    <!-- Job Counter Extra Info -->
                    <div class="extra-info">
                        <?php $count_jobs = wp_count_posts( 'job_listing', 'readable' ); ?>
                        
                        <?php if ( cariera_wp_job_manager_is_activated() ) { ?>
                            <span><?php printf( esc_html__( 'We have %s job offers for you!', 'cariera' ), '<strong>' . esc_html( $count_jobs->publish ) . '</strong>' ) ?></span>
                        <?php } else {
                            echo '<small>' . esc_html__( 'There is no Job count because WP Job Manager Plugin is not installed.', 'cariera') . '</small>';
                        } ?> 
                    </div>
                <?php endif; ?>
        
            </div>
        </div>
    </div>
</section>
<!-- ===== End of Main Search Section ===== -->



<?php while ( have_posts() ) : the_post(); ?>
    <!-- ===== Start of Main Wrapper ===== -->
    <main>
        <div class="container">
            <article <?php post_class(); ?>>
                <?php the_content(); ?>
            </article>
        </div>
    </main>
    <!-- ===== End of Main Wrapper ===== -->
<?php endwhile; // end of the loop.


get_footer(); ?>