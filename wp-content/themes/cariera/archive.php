<?php
/**
*
* @package Cariera
*
* @since 1.0.0
* 
* ========================
* ARCHIVE TEMPLATE FILE
* ========================
*     
**/



get_header();


$blog_layout = cariera_get_option( 'cariera_blog_layout' );

if ( $blog_layout == 'fullwidth') {
    $layout = 'col-md-12';
} else {
    $layout = 'col-md-8 col-xs-12';
}


if (cariera_get_option( 'cariera_blog_page_header', 'true')) { ?>
    <!-- ===== Start of Page Header ===== -->
    <section class="page-header">
        <div class="container">
            <div class="row">

                <!-- Start of Page Title -->
                <div class="col-md-12 text-center">
                    <h1 class="title"><?php echo cariera_get_the_title(); ?></h1>
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
            <div class="<?php echo esc_attr($layout) ?>">
                <?php
                if( have_posts() ) {                
                    while( have_posts() ): the_post();
                        get_template_part( 'templates/content/content', get_post_format() );
                    endwhile;
                    
                    cariera_paging_nav();
                
                } else {
                    get_template_part( 'templates/content/content', 'none' );
                } ?>
            </div>
            
            <?php 
            if ( $blog_layout != 'fullwidth' ) {
                get_sidebar();
            } ?>
        </div>
    </div>
</main>
<!-- ===== End of Main Wrapper ===== -->



<?php get_footer(); ?>