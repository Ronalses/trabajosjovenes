<?php
/**
*
* @package Cariera
*
* @since 1.3.3
* 
* ========================
* ALL WP JOB MANAGER BOOKMARK RELATED FUNCTIONS
* ========================
*     
**/


/**
 * Remove & add bookmark links
 *
 * @since 1.3.3
 */

global $job_manager_bookmarks;

remove_action( 'single_job_listing_meta_after', array( $job_manager_bookmarks, 'bookmark_form' ) );
remove_action( 'single_resume_start', array( $job_manager_bookmarks, 'bookmark_form' ) );

add_action( 'cariera_bookmark_hook', 'cariera_bookmark_trigger', 10 );
add_action( 'cariera_bookmark_hook', 'cariera_bookmark_popup', 11 );
add_action( 'cariera_bookmark_popup_form', array( $job_manager_bookmarks, 'bookmark_form' ) );





/**
 * Bookmark button trigger
 *
 * @since 1.3.3
 */

function cariera_bookmark_trigger() {
    if ( is_user_logged_in() ) {
        echo '<a href="#bookmark-popup-'. esc_attr(get_the_ID()) .'" class="btn btn-main btn-effect popup-with-zoom-anim">'. esc_html__('Bookmark', 'cariera'). '</a>';
    } else {
        $login_registration = get_option('cariera_login_register_layout');
        
        if ( $login_registration == 'popup' ) {
            echo '<a href="#login-register-popup" class="btn btn-main btn-effect popup-with-zoom-anim">';
        } else {
            $login_registration_page     = get_option('cariera_login_register_page');
            $login_registration_page_url = get_permalink( $login_registration_page );

            echo '<a href="' . esc_url( $login_registration_page_url ) . '" class="btn btn-main btn-effect">';
        }
                
        esc_html_e( 'Login to bookmark', 'cariera' );
        
        echo '</a>';   
    }
}





/**
 * Bookmark Popup
 *
 * @since 1.3.3
 */

function cariera_bookmark_popup() { ?>
 
    <!-- Bookmark Popup -->
    <div id="bookmark-popup-<?php echo esc_attr(get_the_ID());?>" class="small-dialog zoom-anim-dialog mfp-hide">
        <div class="bookmarks-popup">
            <div class="small-dialog-headline">
                <h3 class="title"><?php esc_html_e( 'Bookmark Details', 'cariera' ); ?></h3>
            </div>

            <div class="small-dialog-content text-left">
                <?php do_action('cariera_bookmark_popup_form'); ?>            
            </div>
        </div>
    </div>

<?php }