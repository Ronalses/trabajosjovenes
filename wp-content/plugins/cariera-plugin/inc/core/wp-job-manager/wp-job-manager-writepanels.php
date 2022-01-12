<?php

/**
*
* @package Cariera
*
* @since 1.4.4
* 
* ========================
* WP JOB MANAGER - EXTENDED WRITEPANELS
* ========================
*     
**/


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WP_Job_Manager_Writepanels' ) ) {
    include( JOB_MANAGER_PLUGIN_DIR . '/includes/admin/class-wp-job-manager-writepanels.php' );
}



class Cariera_WP_Job_Manager_Writepanels extends WP_Job_Manager_Writepanels {
    
    private static $instance = null;

    public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}


	
    /**
	 * Displays Company select fields
	 *
	 * @since 1.4.4
	 */
	public static function input_company_select( $key, $field ) {
        if ( ! empty( $field['name'] ) ) {
			$name = $field['name'];
		} else {
			$name = $key;
		} ?>

        <p class="form-field">
			<label for="<?php echo esc_attr( $key ); ?>">
				<?php echo esc_html( wp_strip_all_tags( $field['label'] ) ); ?>:
				<?php if ( ! empty( $field['description'] ) ) : ?>
					<span class="tips" data-tip="<?php echo esc_attr( $field['description'] ); ?>">[?]</span>
				<?php endif; ?>
			</label>
			<select name="<?php echo esc_attr( $name ); ?>" id="<?php echo esc_attr( $key ); ?>">
				<option value=""><?php esc_html_e( 'No Company Selected', 'cariera' ); ?></option>
                <?php           
                $args = [
                    'post_type'      => 'company',
                    'hide_empty'     => false,
                    'posts_per_page' => -1,
                    'post_status'    => ['publish', 'pending']
				];

                $companies = get_posts( $args );

                foreach( $companies as $company ) {
                    if( !empty( $company->ID ) ) { ?>
                        <option value="<?php echo esc_attr( $company->ID ); ?>" <?php if ( isset( $field['value'] ) ) { selected( $field['value'], $company->ID ); } ?>><?php echo esc_html( $company->post_title );   if( $company->post_status == 'pending') { echo '  (' . esc_html( $company->post_status ) . ')'; } ?></option>
                    <?php }
                } ?>
			</select>
		</p>

    <?php
    }
    
}

Cariera_WP_Job_Manager_Writepanels::instance();