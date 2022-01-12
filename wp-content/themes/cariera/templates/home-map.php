<?php
/**
*
* @package Cariera
*
* @since 1.2.0
* 
* ========================
* Template Name: Home Page - Map Background
* ========================
*    
**/


get_header(); ?>



<!-- ===== Start of Main Search Section ===== -->
<section class="home-search-map">
    
    <?php echo do_shortcode('[cariera-map type="job_listing" class="home-map" height="400px"]'); ?>

    <!-- Start of Wrapper -->
    <div class="form-wrapper">
        <div class="container">

            <!-- Start of Form -->
            <div class="job-search-form-wrapper">
                <?php echo do_shortcode('[search_form location="yes"]'); ?>
            </div>
            <!-- End of Form -->

        </div>
    </div>
    <!-- End of Wrapper -->

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