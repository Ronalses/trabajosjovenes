<?php
/**
*
* @package Cariera
*
* @since    1.0.0
* @version  1.5.1
* 
* ========================
* TEMPLATE FOR DISPLAYING THE COMMENTS
* ========================
*     
**/


if ( post_password_required() ) {
    return;
} ?>


<section class="col-md-12 comments">
    <?php if ( have_comments() ) { ?>
        <h4><?php comments_number( 'no comments', '1 comment', '% comments' ); ?></h4>

        <?php cariera_get_comment_navigation(); ?>

        <ul class="comment-list">
            <?php
                wp_list_comments( [
                    'style'         => 'ul',
                    'short_ping'    => true,
                    'callback'      => 'cariera_comment',
                ] );
            ?>
        </ul><!-- .comment-list -->

        <?php cariera_get_comment_navigation();
    } //Check for have_comment().
    
    
    // Message if comments are closed.
    if ( ! comments_open() && get_comments_number() ) { ?>
        <h6 class="no-comments"><?php esc_html_e( 'Comments are closed.', 'cariera' ); ?></h6>
    <?php }
    

    comment_form(); ?>
</section>