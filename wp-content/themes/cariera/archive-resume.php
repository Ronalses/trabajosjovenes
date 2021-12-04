<?php
/**
*
* @package Cariera
*
* @since 1.0.0
* 
* ========================
* ARCHIVE TEMPLATE FILE FOR RESUMES
* ========================
*     
**/


get_header(); ?>


<!-- ===== Start of Page Header ===== -->
<section class="page-header">
    <div class="container">
        <div class="row">

            <!-- Start of Page Title -->
            <div class="col-md-12 text-center">
                <?php $count_resumes = wp_count_posts( 'resume', 'readable' ); ?>
				<h1 class="title"><?php printf( esc_html__( 'We have %s resumes in our database', 'cariera' ), $count_resumes->publish ); ?></h1>
            </div>
            <!-- End of Page Title -->

        </div>
    </div>
</section>
<!-- ===== End of Page Header ===== -->



<?php
$google_api = cariera_get_option('cariera_gmap_api_key');
$map        = cariera_get_option( 'cariera_resume_search_map' );

if ( !empty($google_api) && $map == 'true' ) {
    echo do_shortcode('[cariera-map type="resume" class="resume_page"]'); 
} ?>



<!-- ===== Start of Main Wrapper ===== -->
<main class="ptb80">
    <div class="container">
        <div class="col-md-12">
            
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <?php echo do_shortcode('[resumes]'); ?>
            </article>

        </div>
        
        <?php //get_sidebar(); ?>
    </div>
</main>
<!-- ===== End of Main Wrapper ===== -->


<?php get_footer(); ?>