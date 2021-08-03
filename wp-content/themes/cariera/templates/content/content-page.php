<?php
/**
*
* @package Cariera
*
* @since 1.0.0
* 
* ========================
* STANDARD PAGE TEMPLATE
* ========================
*    
**/



// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


$page_layout = get_post_meta( $post->ID, 'cariera_page_layout', true );
$page_class  = ( $page_layout != 'sidebar' ) ? 'col-md-12' : 'col-md-8 col-sm-12';
$sidebar     = get_post_meta( $post->ID, 'cariera_select_page_sidebar', true );




if ( get_post_meta( get_the_ID(), 'cariera_show_page_title', 'true' ) != 'hide' ) { ?>

    <!-- ===== Start of Page Header ===== -->
    <?php $page_header = get_post_meta($post->ID, 'cariera_page_header_bg', true);

    if ( !empty($page_header) ) :
        $image = wp_get_attachment_url($page_header); ?>

        <section class="page-header page-header-bg" style="background: url(<?php echo esc_attr( $image ); ?>);">
    <?php else : ?>
        <section class="page-header">
    <?php endif; ?>

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
            <article id="post-<?php the_ID(); ?>" <?php post_class( esc_attr($page_class) ); ?>>
                
                <?php do_action('cariera_page_content_start'); ?>
                
                <?php                
                the_content(); 
            
                wp_link_pages( array(
                    'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'cariera' ),
                    'after'  => '</div>',
                ) );
                ?>                
                
                <?php if ( comments_open() || get_comments_number() ) : // If comments are open or we have at least one comment, load up the comment template.
                    comments_template();
                endif; ?>
                
                <?php do_action('cariera_page_content_end'); ?>

            </article>

            <?php if( $page_layout == 'sidebar' ) { ?>
                <!-- Sidebar -->
                <div class="col-md-4 col-xs-12 cariera-page-extra-sidebar">
                    <?php if ( is_active_sidebar( $sidebar ) ) { ?>
                        <div class="sidebar">
                            <?php dynamic_sidebar($sidebar); ?>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>

        </div>
    </div>
</main>
<!-- ===== End of Main Wrapper ===== -->