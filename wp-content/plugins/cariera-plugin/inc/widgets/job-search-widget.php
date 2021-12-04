<?php

/**
*
* @package Cariera
*
* @since 1.4.0
* 
* ========================
* Job Search Custom Widget
* ========================
*     
**/



class Cariera_Widget_Job_Search extends WP_Widget {

    // class constructor
	public function __construct() {
        $widget_options = array(
			'classname' => 'job-search-widget',
			'description' => esc_html__( 'Job search form.', 'cariera' )
		);
        
		parent::__construct( 'job-search-widget', esc_html__( 'Custom: Job Search Widget', 'cariera' ), $widget_options, 'cariera' );
    }
	
    // output the widget content on the front-end
    public function widget( $args, $instance ) {
        extract($args);
        echo $before_widget;

        wp_enqueue_script( 'wp-job-manager-ajax-filters' );
        ?>

        
                
        <form class="job_filters">
            <div class="search_jobs">

                <div class="search_keywords">
                    <?php 
                    if ( ! empty( $_GET['search_keywords'] ) ) {
                        $keywords = sanitize_text_field( $_GET['search_keywords'] );
                    } else {
                        $keywords = '';
                    } ?>
                    <label for="search_keywords"><?php esc_html_e( 'Keywords', 'cariera' ); ?></label>
                    <input type="text" name="search_keywords" id="search_keywords" placeholder="<?php esc_attr_e( 'Keywords', 'cariera' ); ?>" value="<?php echo esc_attr( $keywords ); ?>" />
                </div>


                <div class="search_location">
                    <?php 
					if ( ! empty( $_GET['search_location'] ) ) {
						$location = sanitize_text_field( $_GET['search_location'] );
					} else {
						$location = '';
					} ?>
                    <label for="search_location"><?php esc_html_e( 'Location', 'cariera' ); ?></label>
                    <input type="text" name="search_location" id="search_location" placeholder="<?php esc_attr_e( 'Location', 'cariera' ); ?>" value="<?php echo esc_attr( $location ); ?>" />
                    <div class="geolocation"><i class="geolocate"></i></div>
                </div>
                
                
                <?php do_action( 'cariera_wpjm_job_filters_search_radius' ); ?>

                
                <?php if ( ! is_tax( 'job_listing_category' ) && get_terms( array( 'taxonomy' => 'job_listing_category' ) ) ) { 
                    $show_category_multiselect = get_option( 'job_manager_enable_default_category_multiselect', false ); 

                    if ( !empty( $_GET['search_category'] ) ) {
                        $selected_category = sanitize_text_field( $_GET['search_category'] );
                    } else {
                        $selected_category = "";
                    } ?>

                    <div class="search_categories">
                        <label for="search_categories"><?php esc_html_e( 'Category', 'cariera' ); ?></label>
                        <?php if ( $show_category_multiselect ) : ?>
                            <?php job_manager_dropdown_categories( array( 
                                'taxonomy'      => 'job_listing_category', 
                                'hierarchical'  => 1, 
                                'name'          => 'search_categories', 
                                'orderby'       => 'name', 
                                'selected'      => $selected_category, 
                                'hide_empty'    => false, 
                                'show_count'    => 0,
                                'class'         => 'cariera-select2-search'
                            ) ); ?>
                        <?php else : ?>
                            <?php job_manager_dropdown_categories( array( 
                                'taxonomy'          => 'job_listing_category', 
                                'hierarchical'      => 1, 
                                'show_option_all'   => esc_html__( 'Any category', 'cariera' ), 
                                'name'              => 'search_categories', 
                                'orderby'           => 'name', 
                                'selected'          => $selected_category, 
                                'multiple'          => false, 
                                'hide_empty'        => false, 
                                'show_count'        => 0,
                                'class'             => 'cariera-select2-search'
                            ) ); ?>
                        <?php endif; ?>
                    </div>
                <?php } ?>
                
                <?php do_action( 'cariera_wpjm_sidebar_job_filters_search_jobs_end' ); ?>

                <?php if ( get_option( 'job_manager_enable_types' ) ) { ?>
                    <div class="search-job-types">
                        <?php if ( ! is_tax( 'job_listing_type' ) ) { ?>
                            <label><?php esc_html_e( 'Job Type','cariera' ); ?></label>
                        <?php }
                        
                        $selected_job_types = implode( ',', array_values( get_job_listing_types( 'id=>slug' ) ) );

                        get_job_manager_template( 'job-filter-job-types.php', array( 
                            'job_types'          => '', 
                            'atts'               => '',
                            'selected_job_types' => is_array( $selected_job_types ) ? $selected_job_types : array_filter( array_map( 'trim', explode( ',', $selected_job_types ) ) ), 
                        )); ?>
                    </div>
                <?php } ?>
                
            </div>

            <div class="showing_jobs"></div>
        </form>


        <?php
        echo $after_widget;
    }

    // output the option form field in admin Widgets screen
    public function form( $instance ) {}

    // save options
    public function update( $new_instance, $old_instance ) {}

}

register_widget( 'Cariera_Widget_Job_Search' );