<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
global $post;

if ( resume_manager_user_can_view_resume( $post->ID ) ) : ?>
        
    <!-- ===== Start of Resume Header ===== -->
    <?php
    $image = get_post_meta($post->ID, '_featured_image', true);

    if( !empty($image) ) { ?>
        <section class="page-header resume-header overlay-gradient" style="background: url(<?php echo esc_attr($image); ?>);">
    <?php } else { ?>
        <section class="page-header resume-header overlay-gradient">
    <?php } ?>

    </section>
    <!-- ===== End of Resume Header ===== -->




    <!-- ===== Start of Main Wrapper ===== -->
    <main>
        <article id="post-<?php the_ID(); ?>" <?php post_class('resume-page'); ?>>
            <div class="container">

                <!-- Start of Candidate main resume -->
                <div class="candidate-main-resume">

                    <!-- Start of Candidate Extro Info -->
                    <div class="candidate-extra-info">

                        <?php
                        if ( get_option( 'resume_manager_enable_categories' ) ) {
                            $categories = wp_get_object_terms( $post->ID, 'resume_category');

                            if ( is_wp_error( $categories ) ) {
                                return '';
                            }

                            echo '<div class="left-side"><ul class="candidate-categories">';
                            foreach ( $categories as $category ) {
                                echo '<li><a href="' . esc_url( get_term_link( $category ) ) . '">' . esc_html( $category->name ) . '</a></li>';
                            }
                            echo '</ul></div>';
                        } ?>

                        <div class="right-side">
                            <div class="location">
                                <i class="icon-location-pin"></i>
                                <?php the_candidate_location( false ); ?>
                            </div>

                            <div class="published-date">
                                <i class="icon-clock"></i>
                                <?php printf( '%s %s', esc_html__( 'Member Since ', 'cariera' ) , get_the_date('Y')); ?>
                            </div>

                            <?php if ( resume_has_file() ) {
                                if ( ( $resume_files = get_resume_files() ) && apply_filters( 'resume_manager_user_can_download_resume_file', true, $post->ID ) ) {
                                    foreach ( $resume_files as $key => $resume_file ) { ?>
                                        <div class="candidate-resume">
                                            <a href="<?php echo esc_url( get_resume_file_download_url( null, $key ) ); ?>"><?php esc_html_e('Download CV', 'cariera') ?></a>
                                        </div>
                                    <?php }
                                }
                            } ?>
                        </div>

                    </div>
                    <!-- Start of Candidate Extro Info -->


                    <!-- Start of Candidate Info Wrapper -->
                    <div class="candidate-info-wrapper">

                        <div class="candidate-photo">
                            <?php the_candidate_photo(); ?>
                        </div>


                        <div class="candidate">
                            <?php 
							$featured = get_post_meta( $post->ID, '_featured', true );
							
							
                            //echo ( $featured == true ) ? '<i class="featured-listing icon-energy" title="' . esc_attr__( 'Featured', 'cariera' )  . '"></i>' : ''; ?>
                            <h1 class="title"><?php the_title(); ?></h1>
							
                            <?php if ( resume_manager_user_can_view_contact_details( $post->ID ) )  {
                                do_action( 'single_resume_contact_start' ); ?>

                                <div class="candidate-links">
                                    <?php foreach( get_resume_links() as $link ) {
                                        $parsed_url = parse_url( $link['url'] );
                                        $host       = isset( $parsed_url['host'] ) ? current( explode( '.', $parsed_url['host'] ) ) : ''; ?>
                                        <span class="links">
                                            <a href="<?php echo esc_url( $link['url'] ); ?>" target="_blank"><i class="fas fa-link"></i> <?php echo esc_html( $link['name'] ); ?></a>
                                        </span>
                                    <?php }

                                    $email = get_post_meta( $post->ID, '_candidate_email', true );
                                    if ( $email ) { ?>
                                        <span class="candidate-email">
                                            <a href="mailto:<?php echo esc_url( $email ); ?>"><i class="icon-envelope"></i><?php echo esc_html($email); ?></a>
                                        </span>
                                    <?php } ?>
                                </div> <!-- .candidate-info -->

                                <?php do_action( 'single_resume_contact_end' );
                            } else {
                                get_job_manager_template_part( 'access-denied', 'contact-details', 'wp-job-manager-resumes', RESUME_MANAGER_PLUGIN_DIR . '/templates/' );
                            } ?>
                            
                            <?php do_action('cariera_candidate_socials'); ?>
                        </div>
                        <!-- Start of Bookmark -->
                        <div class="bookmark-wrapper">
						<?php 
							$cv = get_post_meta( $post->ID, '_candidate_cv', true );
							if($cv)
								echo '<a class="btn btn-secondary btn-effect btn-cv" target="_blank" href="'. esc_url($cv) .'"> Descargar Cv</a>';
						?>
                            <?php get_job_manager_template( 'contact-details.php', array( 'post' => $post ), 'wp-job-manager-resumes', RESUME_MANAGER_PLUGIN_DIR . '/templates/' );
                            do_action('cariera_bookmark_hook'); ?>
                        </div>
                        <!-- End of Bookmark -->

                    </div>
                    <!-- End of Candidate Info Wrapper -->

                </div>
                <!-- End of Candidate main resume -->



                <!-- Start of the Main Candidate Content -->
                <div class="row pb80">

                    <!-- RESUME CONTENT HERE -->
                    <div class="col-md-8 col-xs-12">
                        <?php
                        do_action( 'single_resume_start' );
                        do_action( 'single_resume_content' );
                        do_action( 'single_resume_end' );
                        ?>
                    </div>

                    <?php get_sidebar('single-resume'); ?>
                </div>
                <!-- End of the Main Candidate Content -->

            </div>
        </article>
    </main>
    <!-- ===== End of Main Wrapper ===== -->

<?php else :
    get_job_manager_template_part( 'access-denied', 'single-resume', 'wp-job-manager-resumes', RESUME_MANAGER_PLUGIN_DIR . '/templates/' );
endif;