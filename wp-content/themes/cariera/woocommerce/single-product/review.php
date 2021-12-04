<?php
/**
 * Review Comments Template
 *
 * Closing li is left out on purpose!.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/review.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.6.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>


<!-- Start of Review -->
<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
    
    <!-- Commenter Image -->
    <div class="avatar pull-left commenter">
        <?php
		/**
		 * @hooked woocommerce_review_display_gravatar - 10
		 */
		do_action( 'woocommerce_review_before', $comment );
		?>
    </div>

    <div class="media-body comment-body">
        <!-- Comment Wrapper -->
        <div class="comment-content-wrapper">
            <div class="media-heading clearfix">
                
                <!-- Rating -->
                <?php do_action( 'woocommerce_review_before_comment_meta', $comment ); ?>
                
                
                <?php
                /**
                 * @hooked woocommerce_review_display_meta - 10
                 * @hooked WC_Structured_Data::generate_review_data() - 20
                 */
                do_action( 'woocommerce_review_meta', $comment );
                do_action( 'woocommerce_review_before_comment_text', $comment ); ?>

                
                <!-- Comment -->
                <?php
                /**
                 * The woocommerce_review_comment_text hook
                 *
                 * @hooked woocommerce_review_display_comment_text - 10
                 */
                do_action( 'woocommerce_review_comment_text', $comment );
                do_action( 'woocommerce_review_after_comment_text', $comment ); ?>

                
            </div>
        </div>
        <!-- End of Comment Wrapper -->
    </div>