<?php
/**
*
* @package Cariera
*
* @since 1.0.0
* 
* ========================
* SINGLE POST CONTENT
* ========================
*     
**/



// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$blog_layout = cariera_get_option( 'cariera_blog_layout' );

if ( 'left-sidebar' == $blog_layout) {
    $layout = 'col-md-8 col-md-push-4';
}
elseif ( 'right-sidebar' == $blog_layout) {
    $layout = 'col-md-8';
}
else {
    $layout = 'col-md-12';
} ?>



<?php if (cariera_get_option('cariera_blog_page_header', 'true')) { ?>
    <!-- ===== Start of Page Header ===== -->
    <section class="page-header">
        <div class="container">
            <div class="row">

                <!-- Start of Page Title -->
                <div class="col-md-12 text-center">
                    <h1 class="title"><?php echo cariera_get_the_title(); ?></h1>
                    <?php if(function_exists('cariera_breadcrumbs')) { 
                        echo cariera_breadcrumbs();
                    } ?>
                </div>
                <!-- End of Page Title -->

            </div>
        </div>
    </section>
    <!-- ===== End of Page Header ===== -->
<?php } ?>



<!-- ===== Start of Main Wrapper ===== -->
<main class="ptb80">
    <div class="container">
        <div class="row">
            <div class="<?php echo esc_attr($layout); ?> col-xs-12">

                <article id="post-<?php the_ID(); ?>" <?php post_class('blog-post-content'); ?>>
                    
                    <?php cariera_single_post_thumb(); ?>
                    
                    <!-- Blog Post Content -->
                    <div class="blog-desc">
                        <?php 
                        $page_header = cariera_get_option('cariera_blog_page_header');
                        if ( $page_header == false) : ?>
                            <!-- Post Title -->
                            <h3 class="blog-title"><?php the_title(); ?></h3>
                        <?php endif; ?>

                        <!-- Post Meta Info -->
                        <?php
                        echo cariera_posted_meta();

                        the_content();
                        
                        wp_link_pages();
                        
                        // get sharing options
                        if ( cariera_get_option( 'cariera_post_share' ) ) {
                            if ( function_exists ( 'cariera_share_media' ) ) {
                                echo cariera_share_media();
                            }
                        } ?>                        
                    </div>
                </article>

                <?php
                // Show Post Nav only if there are more posts than 1.
                if ( true == cariera_get_option( 'cariera_blog_post_nav' ) ) :
                    cariera_get_post_navigation();
                endif;

                // If comments are open or we have at least one comment, load up the comment template.
                if ( comments_open() || get_comments_number() ) :
                    comments_template();
                endif;
                ?>

            </div>

            <?php get_sidebar(); ?>
        </div>
    </div> <!-- .container -->
</main>
<!-- ===== End of Main Wrapper ===== -->