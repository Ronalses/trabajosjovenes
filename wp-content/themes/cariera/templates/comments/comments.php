<?php
/**
*
* @package Cariera
*
* @since    1.0.0
* @version  1.5.1
* 
* ========================
* COMMENTS TEMPLATE FOR THE CALLBACK FUNCTION
* ========================
*     
**/



function cariera_comment( $comment, $args, $depth ) {
    $GLOBALS['comment'] = $comment;
    switch ( $comment->comment_type ) :
    case 'pingback' :
    case 'trackback' : ?>


    <li class="post pingback">
        <p><?php esc_html_e( 'Pingback:', 'cariera' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( esc_html__( '(Edit)', 'cariera' ), ' ' ); ?></p>
        <?php
        break;
        default :
        $allowed_tags = wp_kses_allowed_html( 'post' ); ?>

    <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
        <div id="comment-<?php comment_ID(); ?>" class="comment clearfix">
            <div class="commenter">
                <?php echo get_avatar( $comment, 70 ); ?>

                <div class="commenter-details">
                    <?php printf( '<h6 class="commenter-name">%s</h6>', get_comment_author_link() ); ?>
                    <span class="date"> <?php printf( esc_html__( '%1$s at %2$s', 'cariera' ), get_comment_date(), get_comment_time() ); ?></span>
                </div>
            </div>

            <div class="comment-content">
                <div class="arrow-comment"></div>
                <?php comment_text(); ?>

                <?php 
                $myclass = 'btn btn-small btn-main btn-effect';
                echo preg_replace( '/comment-reply-link/', 'comment-reply-link ' . $myclass, 
                    get_comment_reply_link( array_merge( $args, [
                        'reply_text'    => wp_kses( __( 'Reply', 'cariera' ), $allowed_tags ),
                        'depth'         => $depth, 
                        'max_depth'     => $args['max_depth']
                    ]
                ) ), 1 ); ?>
            </div>
        </div>
<?php
    break;
    endswitch;
}