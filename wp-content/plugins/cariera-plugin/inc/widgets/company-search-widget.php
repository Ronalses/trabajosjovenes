<?php

/**
*
* @package Cariera
*
* @since 1.4.0
* 
* ========================
* Company Search Custom Widget
* ========================
*     
**/



class Cariera_Widget_Company_Search extends WP_Widget {

    // class constructor
	public function __construct() {
        $widget_options = array(
			'classname'     => 'company-search-widget',
			'description'   => esc_html__( 'Company search form.', 'cariera' )
		);
        
		parent::__construct( 'company-search-widget', esc_html__( 'Custom: Company Search Widget', 'cariera' ), $widget_options, 'cariera' );
    }
	
    // output the widget content on the front-end
    public function widget( $args, $instance ) {
        extract($args);
        echo $before_widget;

        wp_enqueue_script( 'company-ajax-filters' );
        ?>

        
                
        <form class="company_filters">
            <div class="search_companies">

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
                

                <?php if( get_option('cariera_company_category') ) { ?>
                    <div class="search_categories">
                        <label for="search_categories"><?php echo esc_html__( 'Categories', 'cariera' ); ?></label>

                        <?php wp_dropdown_categories( array( 
                            'taxonomy'          => 'company_category',
                            'hierarchical'      => 1,
                            'show_option_all'   => esc_html__( 'Any category', 'cariera' ),
                            'name'              => 'search_categories',
                            'class'             => 'cariera-select2-search',
                            'orderby'           => 'name',
                            'multiple'          => false,
                        ) ); ?>
                    </div>
                <?php } ?>
                
            </div>
        </form>


        <?php
        echo $after_widget;
    }

    // output the option form field in admin Widgets screen
    public function form( $instance ) {}

    // save options
    public function update( $new_instance, $old_instance ) {}

}

register_widget( 'Cariera_Widget_Company_Search' );