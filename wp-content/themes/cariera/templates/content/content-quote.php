<?php
/**
*
* @package Cariera
*
* @since 1.0.0
* 
* ========================
* QUOTE POST FORMAT
* ========================
*     
**/



// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>


<article id="post-<?php the_ID(); ?>" <?php post_class('blog-post blog-post-quote'); ?>>  
    
    <?php
    $quote_content = get_post_meta($post->ID, 'cariera_blog_quote_content', TRUE);
    $quote_author  = get_post_meta($post->ID, 'cariera_blog_quote_author', TRUE);
    $quote_source  = get_post_meta($post->ID, 'cariera_blog_quote_source', TRUE);
    $allowed_tags = wp_kses_allowed_html( 'post' ); 

    if (!empty($quote_content) && !empty($quote_author) ) : ?>
        <!-- Blog Post Quote -->
        <figure class="post-quote mb40">
            <span class="icon"></span>
            <blockquote>

                <h4><?php echo esc_html( $quote_content ); ?></h4>

                <?php if(!empty($quote_source)) { ?>
                    <a href="<?php echo esc_url( $quote_source ); ?>">
                <?php } ?>
                        <h6 class="pt20"><?php echo esc_html('- '); echo wp_kses($quote_author,$allowed_tags); ?></h6>
                <?php if(!empty($quote_source)) { ?>
                    </a> 
                <?php } ?>

            </blockquote>
        </figure>
    <?php endif; ?>


    <!-- Blog Post Description -->
    <div class="blog-desc">

        <!-- Post Title -->
        <h3 class="blog-post-title">
            <a href="<?php the_permalink(); ?>">
                <?php the_title(); ?>
            </a>
        </h3>

        <?php echo cariera_posted_meta(); ?>
        
        <div class="blog-post-exerpt">
            <?php the_excerpt(); ?>
        </div>

        <a href="<?php the_permalink(); ?>" class="btn btn-main btn-effect mt20">
            <?php esc_html_e('Read More','cariera') ?>
        </a>

    </div> <!-- .blog-desc -->
</article>