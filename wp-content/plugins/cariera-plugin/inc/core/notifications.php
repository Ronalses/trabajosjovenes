<?php
/**
*
* @package Cariera
*
* @since    1.5.0
* @version  1.5.1
* 
* ========================
* CARIERA CORE NOTIFICATIONS
* ========================
*     
**/



// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}



class Cariera_Core_Notifications {



    /**
	 * Returns instance of the class
	 *
	 * @since 1.5.0
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}





    /**
	 * Constructor
	 *
	 * @since 1.5.0
	 */
    public function __construct() {
        // Mark Notifications as read
        add_action( 'wp_ajax_cariera_notification_marked_read', [ $this, 'mark_read' ] );

        // Delete Notifications
        add_action( 'cariera_delete_notifications', [ $this, 'delete_notifications' ] );        

        // Notifications
        add_action( 'transition_post_status', [ $this, 'listing_post_status' ], 10, 3 );
        add_action( 'new_job_application', [ $this, 'application_notification' ], 10 );
        add_action( 'cariera_listing_promotion_started', [ $this, 'listing_promoted_notification' ], 10 );
        add_action( 'cariera_listing_promotion_ended', [ $this, 'promotion_expired_notification' ], 10 );
    }





    /**
	 * Insert data to database
	 *
	 * @since 1.5.0
	 */
    public function insert( $args ) {
        global $wpdb;
        
        // Get Current User
        $user = get_user_by( 'id', get_current_user_id() );
		if ( $user ) {	
			if ( empty( $args['user_id'] ) ) {
                $args['user_id'] = $user->ID;
            }
		} else {
			if ( empty( $args['user_id'] ) ) {
				$args['user_id'] = 0;
            }
        }
        

        // Duplication check
        $exists = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT id FROM {$wpdb->prefix}cariera_notifications
					WHERE action = %s
						AND owner_id    = %d
						AND user_id     = %d
						AND post_id     = %d
						AND active      = 1
				;",
				$args['action'],
				$args['owner_id'],
				$args['user_id'],
				$args['post_id']
			)
        );
        
        // Return if the insert already exists
        if ( $exists ) {
            return;
        }

        // Insert into the database
        $wpdb->insert( $wpdb->prefix . 'cariera_notifications', [
            'action'    	=> $args['action'],     // action name
            'owner_id'      => $args['owner_id'],   // The ID of the owner
            'user_id'   	=> $args['user_id'],    // The ID of the user that did the action
            'post_id' 		=> $args['post_id'],    // Post ID
            'active'        => '1',			
        ] );

    }





    /**
	 * Active notifications that haven't been read
	 *
	 * @since 1.5.0
	 */
    public function active( $user_id = null ) {
        global $wpdb;

        if ( is_null( $user_id ) ) {
            $user_id = get_current_user_id();
        }

        // check if user exists
        if ( get_userdata( $user_id ) === false ) {
            return;
        }

        $results = $wpdb->get_var("
            SELECT COUNT(*)
            FROM {$wpdb->prefix}cariera_notifications
            WHERE owner_id = {$user_id}
            AND active = 1
        ");

        return $results;
    }





    /**
	 * Latest notifications
	 *
	 * @since 1.5.0
	 */
    public function latest( $user_id = null, $num = 10, $active = false ) {
        global $wpdb;

        if ( is_null( $user_id ) ) {
            $user_id = get_current_user_id();
        }

        // check if user exists
        if ( get_userdata( $user_id ) === false ) {
            return [];
        }

        $active_sql = $active ? 'AND active = 1' : '';

        $sql = "
            SELECT *
            FROM {$wpdb->prefix}cariera_notifications
            WHERE owner_id = {$user_id}
            $active_sql
            ORDER BY created_at DESC
            LIMIT $num
        ";

        $results = $wpdb->get_results( $sql, OBJECT );

        return $results;
    }





    /**
	 * Mark active notifications as read
	 *
	 * @since 1.5.0
	 */
    public function mark_read() {
        global $wpdb;

        $user_id = get_current_user_id();
        
        // check if user exists
        if ( get_userdata( $user_id ) === false ) {
            return;
        }

        $wpdb->query(
            $wpdb->prepare("
                    UPDATE {$wpdb->prefix}cariera_notifications 
                    SET active = 0 
                    WHERE owner_id = %d
                ",
                $user_id
            )
        );

        wp_send_json_success([
            'success' => true,
        ]);

        die();
    }






    /**
	 * Delete old notifications
	 *
	 * @since 1.5.0
	 */
    public function delete_notifications() {
        global $wpdb;

        $wpdb->query("DELETE FROM {$wpdb->prefix}cariera_notifications WHERE active = 0");
    }





    /**
	 * Create the notifications output
	 *
	 * @since   1.5.0
     * @version 1.5.1
	 */
    public function output() {

        $results = $this->latest();

        if ( !$results ) {
            return;
        }


        ob_start();

        echo '<ul class="cariera-notifications">';
        foreach ( $results as $result ) {
            $post       = get_post( $result->post_id );
            $post_title = get_the_title( $result->post_id );
            $post_url	= get_permalink( $result->post_id );

            if ( !empty($result->user_id) ) {
                $user       = get_user_by( 'id', $result->user_id );
                // $user_name  = !empty( $user->user_firstname ) ? $user->user_firstname : $user->user_login;
            }

            $user_url   = get_author_posts_url( $result->user_id );
            $active     = $result->active ? 'notification-active' : '';
            $time       = date_i18n( get_option('date_format'), strtotime( $result->created_at ) );
                    
            switch ($result->action) {

                // When a listing has been been created and is automatically approved
                case 'listing_created': ?>
                    <li class="<?php echo esc_attr($active); ?>">
                        <a href="<?php echo esc_url($post_url); ?>">
                            <div class="notification-icon">
                                <i class="icon-layers"></i>
                            </div>
                            <div class="notification-content">
                                <span class="action"><?php printf( esc_html__( 'Listing %s has been published.', 'cariera' ), '<strong>' . $post_title . '</strong>' ); ?></span>
                                <span class="time"><?php echo esc_html($time); ?></span>
                            </div>
                        </a>
                    </li>
                    <?php
                    break;

                // When a listing has been submitted and it's pending for approval
                case 'listing_pending': ?>
                    <li class="<?php echo esc_attr($active); ?>">
                        <a href="#">
                            <div class="notification-icon">
                                <i class="icon-layers"></i>
                            </div>
                            <div class="notification-content">
                                <span class="action"><?php printf( esc_html__( 'Your listing %s is pending for approval.', 'cariera' ), '<strong>' . $post_title . '</strong>' ); ?></span>
                                <span class="time"><?php echo esc_html($time); ?></span>
                            </div>
                        </a>
                    </li>
                    <?php
                    break;

                // When a listing has been submitted and it's pending for payment approval
                case 'listing_pending_payment': ?>
                    <li class="<?php echo esc_attr($active); ?>">
                        <a href="<?php echo esc_url($post_url); ?>">
                            <div class="notification-icon">
                                <i class="icon-layers"></i>
                            </div>
                            <div class="notification-content">
                                <span class="action"><?php printf( esc_html__( 'Your listing %s has been created, payment approval might be required.', 'cariera' ), '<strong>' . $post_title . '</strong>' ); ?></span>
                                <span class="time"><?php echo esc_html($time); ?></span>
                            </div>
                        </a>
                    </li>
                    <?php
                    break;

                // When a listing get's approved
                case 'listing_approved': ?>
                    <li class="<?php echo esc_attr($active); ?>">
                        <a href="<?php echo esc_url($post_url); ?>">
                            <div class="notification-icon">
                                <i class="icon-check"></i>
                            </div>
                            <div class="notification-content">
                                <span class="action"><?php printf( esc_html__( 'Your listing %s has been approved.', 'cariera' ), '<strong>' . $post_title . '</strong>' ); ?></span>
                                <span class="time"><?php echo esc_html($time); ?></span>
                            </div>
                        </a>
                    </li>
                    <?php
                    break;

                // When a listing get's deleted by admin
                case 'listing_expired': ?>
                    <li class="<?php echo esc_attr($active); ?>">
                        <a href="#">
                            <div class="notification-icon">
                                <i class="icon-clock"></i>
                            </div>
                            <div class="notification-content">
                                <span class="action"><?php printf( esc_html__( 'Your listing %s has expired.', 'cariera' ), '<strong>' . $post_title . '</strong>' ); ?></span>
                                <span class="time"><?php echo esc_html($time); ?></span>
                            </div>
                        </a>
                    </li>
                    <?php
                    break;

                // When a listing get's relisted
                case 'listing_relisted': ?>
                    <li class="<?php echo esc_attr($active); ?>">
                        <a href="<?php echo esc_url($post_url); ?>">
                            <div class="notification-icon">
                                <i class="icon-reload"></i>
                            </div>
                            <div class="notification-content">
                                <span class="action"><?php printf( esc_html__( 'Your listing %s has been relisted.', 'cariera' ), '<strong>' . $post_title . '</strong>' ); ?></span>
                                <span class="time"><?php echo esc_html($time); ?></span>
                            </div>
                        </a>
                    </li>
                    <?php
                    break;

                // When a listing get's deleted by admin
                case 'listing_deleted': ?>
                    <li class="<?php echo esc_attr($active); ?>">
                        <a href="#">
                            <div class="notification-icon">
                                <i class="icon-trash"></i>
                            </div>
                            <div class="notification-content">
                                <span class="action"><?php printf( esc_html__( 'Your listing %s has been deleted.', 'cariera' ), '<strong>' . $post_title . '</strong>' ); ?></span>
                                <span class="time"><?php echo esc_html($time); ?></span>
                            </div>
                        </a>
                    </li>
                    <?php
                    break;

                // When someone applies to a job
                case 'job_application': 
                    $job_id          = $post->post_parent;
                    $job_title       = get_the_title($job_id);
                    $application_url = add_query_arg( array( 'action' => 'show_applications', 'job_id' => $job_id ), get_permalink(get_option( 'job_manager_job_dashboard_page_id' )) ); ?>

                    <li class="<?php echo esc_attr($active); ?>">
                        <a href="<?php echo esc_url($application_url); ?>">
                            <div class="notification-icon">
                                <i class="icon-pencil"></i>
                            </div>
                            <div class="notification-content">
                                <span class="action"><?php printf( esc_html__( '%s applied to your job %s.', 'cariera' ), '<strong>' . $post_title . '</strong>', '<strong>' . $job_title . '</strong>'); ?></span>
                                <span class="time"><?php echo esc_html($time); ?></span>
                            </div>
                        </a>
                    </li>
                    <?php
                    break;

                // When listing gets promoted
                case 'listing_promoted': ?>
                    <li class="<?php echo esc_attr($active); ?>">
                        <a href="<?php echo esc_url($post_url); ?>">
                            <div class="notification-icon">
                                <i class="icon-energy"></i>
                            </div>
                            <div class="notification-content">
                                <span class="action"><?php printf( esc_html__( 'Your listing %s has been promoted.', 'cariera' ), '<strong>' . $post_title . '</strong>' ); ?></span>
                                <span class="time"><?php echo esc_html($time); ?></span>
                            </div>
                        </a>
                    </li>
                    <?php
                    break;

                // When promotion expires
                case 'promotion_expired': ?>
                    <li class="<?php echo esc_attr($active); ?>">
                        <a href="<?php echo esc_url($post_url); ?>">
                            <div class="notification-icon">
                                <i class="icon-clock"></i>
                            </div>
                            <div class="notification-content">
                                <span class="action"><?php printf( esc_html__( 'Your promotion for %s has expired.', 'cariera' ), '<strong>' . $post_title . '</strong>' ); ?></span>
                                <span class="time"><?php echo esc_html($time); ?></span>
                            </div>
                        </a>
                    </li>
                    <?php
                    break;

            }

        }
        echo '</ul>';

        return ob_get_clean();
    }





    /**
	 * Listing Statuses
	 *
	 * @since   1.5.0
     * @version 1.5.1
	 */
    public function listing_post_status( $new_status, $old_status, $post  ) {
        if ( ! get_option('cariera_notifications') ) {
            return;
        }

        $post_types = [ 'job_listing', 'company', 'resume' ];

        // Return if the "post type" is not in array
        if ( !in_array( get_post_type( $post->ID ), $post_types ) ) {
            return;
        }


        $action = '';

        // Notification action based on the listing's post status
        if ( 'preview' === $old_status && 'publish' == $new_status ) {
            $action = 'listing_created';
        } elseif ( 'preview' === $old_status && 'pending_payment' == $new_status ) {
			$action = 'listing_pending_payment';
        } elseif ( ('preview' === $old_status || 'pending_payment' === $old_status || 'expired' === $old_status ) && 'pending' == $new_status ) {
            $action = 'listing_pending';
        } elseif ( ('pending' === $old_status || 'pending_payment' === $old_status) && 'publish' == $new_status ) {
            $action = 'listing_approved';
        } elseif ( 'trash' === $new_status ) {
            $action = 'listing_deleted';
        } elseif ( 'expired' === $new_status ) {
            $action = 'listing_expired';
        } elseif ( 'expired' === $old_status && 'publish' == $new_status ) {
            $action = 'listing_relisted';
        } else {
            $action = '';
        }

        if ( wp_is_post_revision( $post->ID ) ) {
            return;
        }

        if ( $action == '' ) {
            return;
        }


        // Inser the taken action into the database as a notification
        $this->insert([
            'action'    => $action,
            'owner_id'  => get_post_field( 'post_author', $post->ID ),
            'user_id'   => '',
            'post_id'   => $post->ID,
        ]);
    }





    /**
	 * Add Application Notification
	 *
	 * @since 1.5.0
	 */
    public function application_notification( $post_id ) {
        if ( ! get_option('cariera_notifications') ) {
            return;
        }

        $this->insert([
            'action'    => 'job_application',
            'owner_id'  => get_post_field( 'post_author', $post_id ),
            'user_id'   => get_current_user_id(),
            'post_id'   => $post_id,
        ]);
    }
    




    /**
	 * Add Promotion Notification
	 *
	 * @since 1.5.0
	 */
    public function listing_promoted_notification( $post_id ) {
        if ( ! get_option('cariera_notifications') ) {
            return;
        }

        $this->insert([
            'action'    => 'listing_promoted',
            'owner_id'  => get_post_field( 'post_author', $post_id ),
            'user_id'   => '',
            'post_id'   => $post_id,
        ]);
    }





    /**
	 * Add Promotion Notification
	 *
	 * @since 1.0.0
	 */
    public function promotion_expired_notification( $post_id ) {
        if ( ! get_option('cariera_notifications') ) {
            return;
        }

        $this->insert([
            'action'    => 'promotion_expired',
            'owner_id'  => get_post_field( 'post_author', $post_id ),
            'user_id'   => '',
            'post_id'   => $post_id,
        ]);
    }

}