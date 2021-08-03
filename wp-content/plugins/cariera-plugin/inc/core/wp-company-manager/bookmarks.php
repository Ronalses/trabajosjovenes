<?php

/**
*
* @package Cariera
*
* @since 1.4.6
* 
* ========================
* CARIERA COMPANY MANAGER BOOKMARKS COMPATIBILITY
* ========================
*     
**/


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}



class Cariera_Company_Manager_Bookmarks {
	
	/**
	 * Constructor.
	 * 
	 * @since 1.4.6
	 */
	public function __construct() {
		//add_action( 'init', array( $this, 'init' ) );
		add_action( 'cariera_company_bookmarks', array( $this, 'bookmark_trigger' ), 10 );
		add_action( 'cariera_company_bookmarks', 'cariera_bookmark_popup', 11 );
		
		add_action( 'wp', array( $this, 'bookmark_handler' ) );
	}
	




	/**
	 * Init action
	 * 
	 * @since 1.4.6
	 */
	public function init() {
		global $job_manager_bookmarks;
        add_action( 'cariera_company_bookmarks', array( $job_manager_bookmarks, 'bookmark_form' ) );
	}
	






	/**
	* Bookmark button trigger
	*
	* @since 1.4.6
	*/
	function bookmark_trigger() {
		if ( is_user_logged_in() ) {
			echo '<a href="#bookmark-popup-'. esc_attr(get_the_ID()) .'" class="company-bookmark popup-with-zoom-anim"><i class="fas fa-heart"></i></a>';
		} else {
			$login_registration = get_option('cariera_login_register_layout');
			
			if ( $login_registration == 'popup' ) {
				echo '<a href="#login-register-popup" class="company-bookmark popup-with-zoom-anim">';
			} else {
				$login_registration_page 	 = get_option('cariera_login_register_page');
				$login_registration_page_url = get_permalink( $login_registration_page );

				echo '<a href="' . esc_url( $login_registration_page_url ) . '" class="company-bookmark">';
			}
					
			echo '<i class="fas fa-heart"></i>';
			
			echo '</a>';   
		}
	}




	/**
	 * See if a post is bookmarked by ID
	 * @param  int post ID
	 * @return boolean
	 */
	public function is_bookmarked( $post_id ) {
		global $wpdb;
		
		return $wpdb->get_var( $wpdb->prepare( "SELECT id FROM {$wpdb->prefix}job_manager_bookmarks WHERE post_id = %d AND user_id = %d;", $post_id, get_current_user_id() ) ) ? true : false;
	}
	

    public function bookmark_handler() {
		global $wpdb;

		if ( ! is_user_logged_in() ) {
			return;
		}

		$action_data = null;

		if ( ! empty( $_POST['submit_bookmark'] ) ) {
			if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'update_bookmark' ) ) {
				$action_data = array(
					'error_code' => 400,
					'error' 	 => esc_html__( 'Bad request', 'cariera' ),
				);
			} else {
				$post_id = absint( $_POST[ 'bookmark_post_id' ] );
				$note    = wp_kses_post( stripslashes( $_POST[ 'bookmark_notes' ] ) );

				if ( $post_id && in_array( get_post_type( $post_id ), array( 'company' ) ) ) {
					if ( ! $this->is_bookmarked( $post_id ) ) {
						$wpdb->insert(
							"{$wpdb->prefix}job_manager_bookmarks",
							array(
								'user_id'       => get_current_user_id(),
								'post_id'       => $post_id,
								'bookmark_note' => $note,
								'date_created'  => current_time( 'mysql' )
							)
						);
					} else {
						$wpdb->update(
							"{$wpdb->prefix}job_manager_bookmarks",
							array(
								'bookmark_note' => $note
							),
							array(
								'post_id' => $post_id,
								'user_id' => get_current_user_id()
							)
						);
					}

					delete_transient( 'bookmark_count_' . $post_id );
					$action_data = array( 'success' => true, 'note' =>  $note );
				}
			}
		}

		if ( ! empty( $_GET['remove_bookmark'] ) ) {
			if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'remove_bookmark' ) ) {
				$action_data = array(
					'error_code' => 400,
					'error' 	 => esc_html__( 'Bad request', 'cariera' ),
				);
			} else {
				$post_id = absint( $_GET[ 'remove_bookmark' ] );

				$wpdb->delete(
					"{$wpdb->prefix}job_manager_bookmarks",
					array(
						'post_id' => $post_id,
						'user_id' => get_current_user_id()
					)
				);

				delete_transient( 'bookmark_count_' . $post_id );
				$action_data = array( 'success' => true );
			}
		}

		if ( null === $action_data ) {
			return;
		}
		if ( ! empty( $_REQUEST['wpjm-ajax'] ) && ! defined( 'DOING_AJAX' ) ) {
			define( 'DOING_AJAX', true );
		}
		if ( wp_doing_ajax() ) {
			wp_send_json( $action_data, ! empty( $action_data['error_code'] ) ? $action_data['error_code'] : 200 );
		} else {
			wp_redirect( remove_query_arg( array( 'submit_bookmark', 'remove_bookmark', '_wpnonce', 'wpjm-ajax' ) ) );
		}
	}

}