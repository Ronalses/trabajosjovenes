<?php
/**
*
* @package Cariera
*
* @since 1.1.0
* 
* ========================
* RELATED JOBS TEMPLATE
* ========================
*     
**/



// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


global $post;

$tags = get_the_terms( $post->ID, 'job_listing_category' );

if ( ! $tags || is_wp_error( $tags ) || ! is_array( $tags ) ) {
	return;
}

$tags = wp_list_pluck( $tags, 'term_id' );

$related_args = array(
	'post_type'         => 'job_listing',
	'orderby'           => 'rand',
	'posts_per_page'    => 6,
	'post_status'       => 'publish',
	'post__not_in'      => array( $post->ID ),
	'tax_query'         => array(
		array(
			'taxonomy' => 'job_listing_category',
			'field'    => 'id',
			'terms'    => $tags
		)
	)
);

$wp_query = new WP_Query($related_args );

if ( ! $wp_query->have_posts() ) {
	return;
}

$randID = rand(1, 99); ?>



<section class="related-jobs pb80">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h4 class="title nomargin pb30"><?php esc_html_e( 'Related Jobs', 'cariera'); ?></h4>

                <!-- Start of Slider -->
                <div class="related-jobs-slider">
                    
                    <?php while( $wp_query->have_posts() ) : $wp_query->the_post(); 
                    $id = get_the_id(); ?>
                    
                        <!-- Start of Slide item -->
                        <div class="single-related-job">
                            <a href="<?php the_permalink(); ?>">

                                <!-- Job Info Wrapper -->
                                <div class="job-info-wrapper">
                                    <div class="logo-wrapper"><?php cariera_the_company_logo(); ?></div>

                                    <div class="job-info">
                                        <h5 class="title"><?php the_title(); ?></h5>

                                        <ul>
                                            <li class="location"><i class="icon-location-pin"></i><?php the_job_location( false ); ?></li>
                                            
                                            <?php if( get_post_meta( $post->ID, '_salary_min', true ) ) { ?>
                                                <li class="salary"><i class="far fa-money-bill-alt"></i><?php cariera_job_salary(); ?></li>
                                            <?php } ?>

                                            <?php if( empty(get_post_meta( $post->ID, '_salary_min', true )) ) {
                                                if( get_post_meta( $post->ID, '_rate_min', true ) ) { ?>
                                                    <li class="rate"><i class="far fa-money-bill-alt"></i><?php cariera_job_rate(); ?></li>
                                                <?php }
                                            } ?>
                                        </ul>
                                    </div>
                                </div>
                                
                                <!-- Job Extras -->
                                <div class="job-extras">
                                    <?php
                                    $job_types = [];
                                    $types     = wpjm_get_the_job_types();

                                    if ( ! empty( $types ) ) { 
                                        foreach ( $types as $type ) {
                                            $job_types[] = $type->name;
                                        }
                                    } ?>
                                    
                                    <div class="job-type-icon"></div>
                                    <span class="job-types"><?php echo esc_html( implode( ', ', $job_types ) ); ?></span>
                                </div>

                            </a>
                        </div>
                        <!-- End of Slide item -->
                    
                    <?php endwhile; ?>

                </div>
            </div>
        </div>
    </div>
</section>

<?php wp_reset_query(); ?>