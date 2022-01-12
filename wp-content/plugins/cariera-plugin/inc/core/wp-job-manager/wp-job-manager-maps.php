<?php

/**
*
* @package Cariera
*
* @since 1.2.0
* 
* ========================
* WP JOB MANAGER - MAP FUNCTIONS //@todo rework
* ========================
*     
**/


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}



class Cariera_Maps {
	
	protected $plugin_slug = 'cariera-map';
    
	function __construct() {
		add_shortcode( 'cariera-map', array( $this, 'show_map' ) );
	}
    
    
	public function show_map($atts) {
                
		extract(shortcode_atts(array(
			'class'  => '',
			'type'   => 'job_listing',
            'height' => ''
        ), $atts));

	
		$query_args = array( 
            'post_type'              => $type,
            'post_status'            => 'publish',
            'posts_per_page'		 => -1,
        );
        
        if ( empty($height) ) {
            $map_height = cariera_get_option( 'cariera_map_height' );
        } else {
            $map_height = $height;
        }
		
		$markers = array();
		// The Loop
        $wp_query = new WP_Query( $query_args );
   		
        if ( $wp_query->have_posts() ) {
			$i = 0;
        
			while( $wp_query->have_posts() ) {
				$wp_query->the_post(); 
				
				$lat = $wp_query->post->geolocation_lat;
				$id = $wp_query->post->ID;
					if (!empty($lat)) {
					    
						$title = get_the_title();
						$ibcontet = '';
						ob_start();
                        
						if($type == 'resume') { // Neeeds woork ?>
                            <a href="<?php the_permalink(); ?>">
                                
                                <div class="candidate-img">
                                    <?php the_candidate_photo(); ?>
                                </div>
                
                                <div class="resumes-content">
                                    <h4><?php the_title(); ?></h4>
                                    <h6><?php the_candidate_title(); ?></h6>
                                    <?php $rate = get_post_meta( $id, '_rate_min', true );
                                    if(!empty($rate)) { ?>
                                        <span class="resume-rate">
                                            <i class="far fa-money-bill-alt"></i>
                                            <?php echo cariera_currency_symbol();  echo get_post_meta( $id, '_rate_min', true ); ?> <?php esc_html_e('/ hour','cariera') ?>
                                        </span>
                                    <?php } ?>
                                </div>
                                <div class="clearfix"></div>
                                
                                <?php if ( ( $skills = wp_get_object_terms( $id, 'resume_skill', array( 'fields' => 'names' ) ) ) && is_array( $skills ) ) : ?>
                                    <div class="resume-skills">
                                        <?php echo '<span>' . implode( '</span><span>', $skills ) . '</span>'; ?>
                                    </div>
                                    <div class="clearfix"></div>
                                <?php endif; ?>
                                
                            </a>
						<?php } else { //type == job ?>

                            <a href="<?php the_job_permalink(); ?>">
                                <div class="job-list-content">
                                    <h4><?php the_title(); ?></h4>
                                    
                                     <?php if ( get_option( 'job_manager_enable_types' ) ) :
                                        $types = wpjm_get_the_job_types();
                                        if ( ! empty( $types ) ) : 
                                            foreach ( $types as $type ) : ?>
                                                <span class="job-type <?php echo esc_attr( sanitize_title( $type->slug ) ); ?>"><?php echo esc_html( $type->name ); ?></span>

                                                <?php if( cariera_newly_posted() ) :
                                                    echo '<span class="job-type new-job-tag">' . esc_html__('New','cariera') . '</span>';
                                                endif;
                                            endforeach; 
                                        endif;
                                    endif;

                                    if( cariera_newly_posted() ) :
                                        echo '<span class="job-type new-job-tag">' . esc_html__('New','cariera') . '</span>';
                                    endif; ?>

                                    <div class="job-info">

                                        <span class="cariera-meta-job-company">
                                            <?php the_company_name('<i class="far fa-building"></i>'); ?>
                                        </span>
                                        
                                        <?php 
                                        $rate_min = get_post_meta( $id, '_rate_min', true ); 
                                        if ( $rate_min) { 
                                            $rate_max = get_post_meta( $id, '_rate_max', true );  ?>
                                            <span class="cariera-meta-rate">
                                                <i class="far fa-money-bill-alt"></i>
                                                <?php echo cariera_currency_symbol(); echo esc_html( $rate_min ); if( !empty($rate_max) ) {
                                                    echo '- ' . cariera_currency_symbol() . $rate_max; 
                                                } 
                                                esc_html_e('/ hour', 'cariera'); ?>
                                            </span>
                                        <?php } ?>

                                        <?php 
                                        $salary_min = get_post_meta($id, '_salary_min', true ); 
                                        if ( $salary_min ) {
                                            $salary_max = get_post_meta($id, '_salary_max', true );  ?>
                                            <span class="cariera-meta-salary">
                                                <i class="far fa-money-bill-alt"></i>
                                                <?php echo cariera_currency_symbol(); echo esc_html( $salary_min ); if( !empty($salary_max) ) {
                                                    echo '- ' . cariera_currency_symbol() . $salary_max; 
                                                } ?>
                                            </span>
                                        <?php } ?>

                                    </div>
                                </div>
                            </a>
						<?php }
                        
                        $ibcontet = ob_get_clean();
                        
				    }

                } // End of while
	    
            } // End of if
    	wp_reset_postdata();
        

		$output = '';
		$output .= '<div id="map-container" class="' . esc_attr( $class ) . '">';
		$output .= '	<div id="cariera-map" style="height:' . esc_attr( $map_height ) . '" >
					        <!-- map goes here -->
					    </div>
					</div>';

		return $output;
		
	}
    
    private function cariera_find_matching_location($haystack, $needle) {
	    foreach ($haystack as $index => $a) {
	        if ($a['lat'] == $needle['lat']
	                && $a['lng'] == $needle['lng']
	              ) {
	            return $index;
	        }
	    }
	    return null;
	}

}

new Cariera_Maps();