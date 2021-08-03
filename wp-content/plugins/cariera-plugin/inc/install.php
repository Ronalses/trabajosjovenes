<?php
/**
*
* @package Cariera
*
* @since    1.4.8
* @version  1.4.8
* 
* ========================
* PLUGIN INSTALL
* ========================
*     
**/



// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}



class Cariera_Install {


    public $version = '1.1';


    /**
	 * Construct
	 *
	 * @since  1.0.0
	 */    
    public function __construct() {		
        register_activation_hook( CARIERA_CORE, [ $this, 'install' ] );
        register_deactivation_hook( CARIERA_CORE, [ $this, 'uninstall' ] );
    }





    /**
	 * Construct
	 *
	 * @since  1.4.8
	 */   
    public function install() {
        $this->create_table_views();
        $this->create_table_external_apply();
        $this->create_table_notifications();
        $this->update_db_version();
        $this->schedule_cron_jobs();
    }





    /**
	 * Run when plugin gets deactivated
	 *
	 * @since  1.5.0
	 */
    public function uninstall() {
        $this->unschedule_events();
    }





    /**
	 * Scheduled events to clear the db tables
	 *
	 * @since   1.5.0
	 */   
    public function schedule_cron_jobs() {        
        // Check for expired promotions
        if ( ! wp_next_scheduled( 'cariera_check_expired_promotions' ) ) {
			wp_schedule_event( time(), 'hourly', 'cariera_check_expired_promotions' );
        }

        // Delete Notifications
        if ( ! wp_next_scheduled( 'cariera_delete_notifications' ) ) {
            wp_schedule_event( time(), 'monthly', 'cariera_delete_notifications' );
        }
    }
    




    /**
	 * Unscheduled events to avoid issues after plugin deactivation
	 *
	 * @since  1.5.0
	 */   
    public function unschedule_events() {
		wp_clear_scheduled_hook( 'cariera_check_expired_promotions' );
		wp_clear_scheduled_hook( 'cariera_delete_notifications' );
	}





    /**
     * Creating the Database table for the views
     *
     * @since   1.3.4
     * @version 1.4.8
     */
    public function create_table_views() {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'cariera_listing_stats_views';
        $collate    = '';
        if ( $wpdb->has_cap( 'collation' ) ) {
            if ( ! empty( $wpdb->charset ) ) {
                $collate .= "DEFAULT CHARACTER SET $wpdb->charset";
            }
            if ( ! empty( $wpdb->collate ) ) {
                $collate .= " COLLATE $wpdb->collate";
            }
        }
        
        $sql = "
            CREATE TABLE $table_name (
                main_id bigint(20) NOT NULL auto_increment,
                user_id varchar(255) default NULL,
                listing_id varchar(255) default NULL,
                listing_title varchar(255) default NULL,
                post_type varchar(255) default NULL,
                action_type varchar(255) default NULL,
                month LONGTEXT default NULL,
                count varchar(255) default NULL,
                PRIMARY KEY  (`main_id`)
            ) $collate;
        ";
        
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }





    /**
     * Creating the Database table for the external redirection
     *
     * @since   1.3.8
     * @version 1.4.8
     */
    public function create_table_external_apply() {    
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'cariera_job_external_redirection';
        $collate    = '';
        if ( $wpdb->has_cap( 'collation' ) ) {
            if ( ! empty( $wpdb->charset ) ) {
                $collate .= "DEFAULT CHARACTER SET $wpdb->charset";
            }
            if ( ! empty( $wpdb->collate ) ) {
                $collate .= " COLLATE $wpdb->collate";
            }
        }
        
        $sql = "
            CREATE TABLE $table_name (
                main_id bigint(20) NOT NULL auto_increment,
                listing_id varchar(255) default NULL,
                listing_title varchar(255) default NULL,
                count varchar(255) default NULL,
                PRIMARY KEY  (`main_id`)
            ) $collate;
        ";
        
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }





    /**
	 * Create Notifications Database Table
	 *
	 * @since  1.5.0
	 */   
    public function create_table_notifications() {
        global $wpdb;

        $table_name = $wpdb->prefix . 'cariera_notifications';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "
            CREATE TABLE $table_name (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                owner_id bigint(20) NOT NULL,
                user_id bigint(20) NOT NULL,
                post_id bigint(20) NOT NULL,
                action varchar(255) NOT NULL,
                meta longtext NULL,
                active boolean DEFAULT 1 NOT NULL,
                created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
                PRIMARY KEY (id)
            ) $charset_collate;
        ";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }





    /**
     * Updating Database Version if something changes to update the Tables
     *
     * @since  1.4.8
     */
    public function update_db_version() {
        add_option( 'cariera_db_version', $this->version );
    }

}

new Cariera_Install();