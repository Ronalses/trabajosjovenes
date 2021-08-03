<?php
/**
*
* @package Cariera
*
* @since 1.0
* 
* ========================
* FOOTER TEMPLATE
* ========================
*     
**/



// Add Elementor Pro support for Custom Footer
if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'footer' ) ) {

    if ( get_post_meta( get_the_ID(), 'cariera_show_footer', 'true' ) != 'hide' ) {

        $footer_style       = cariera_get_option( 'cariera_footer_style' );
        $footer_info        = cariera_get_option( 'cariera_footer_info' );
        $footer_sidebar_1   = cariera_get_option( 'cariera_footer_sidebar_1' );
        $footer_sidebar_2   = cariera_get_option( 'cariera_footer_sidebar_2' );
        $footer_sidebar_3   = cariera_get_option( 'cariera_footer_sidebar_3' );
        $footer_sidebar_4   = cariera_get_option( 'cariera_footer_sidebar_4' );
        ?>

        <!-- =============== Start of Footer =============== -->
        <footer class="main-footer <?php echo esc_attr($footer_style) ?>">

            <?php if ( true == $footer_info ) {
                if ( get_post_meta( get_the_ID(), 'cariera_show_footer_widgets', 'true' ) != 'hide' ) {
                    if ( is_active_sidebar('footer-widget-area') || is_active_sidebar('footer-widget-area-2') || is_active_sidebar('footer-widget-area-3') || is_active_sidebar('footer-widget-area-4') ) { ?>

                        <!-- ===== Start of Footer Information & Widget Section ===== -->
                        <div class="footer-widgets footer-info">
                            <div class="container">
                                <div class="row">


                                    <?php if( $footer_sidebar_1 != 'disabled' ) : ?>
                                        <!-- Start of Footer Widget Area 1 -->
                                        <div class="<?php echo esc_attr( $footer_sidebar_1 ) ?>">
                                            <?php dynamic_sidebar( 'footer-widget-area' ); ?>
                                        </div>
                                        <!-- End of Footer Widget Area 1 -->
                                    <?php endif; ?>


                                    <?php if( $footer_sidebar_2 != 'disabled' ) : ?>
                                        <!-- Start of Footer Widget Area 2 -->
                                        <div class="<?php echo esc_attr( $footer_sidebar_2 ) ?>">
                                            <?php dynamic_sidebar( 'footer-widget-area-2' ); ?>
                                        </div>
                                        <!-- End of Footer Widget Area 2 -->
                                    <?php endif; ?>


                                    <?php if( $footer_sidebar_3 != 'disabled' ) : ?>
                                        <!-- Start of Footer Widget Area 3 -->
                                        <div class="<?php echo esc_attr( $footer_sidebar_3 ) ?>">
                                            <?php dynamic_sidebar( 'footer-widget-area-3' ); ?>
                                        </div>
                                        <!-- End of Footer Widget Area 3 -->
                                    <?php endif; ?>


                                    <?php if( $footer_sidebar_4 != 'disabled' ) : ?>
                                        <!-- Start of Footer Widget Area 4 -->
                                        <div class="<?php echo esc_attr( $footer_sidebar_4 ) ?>">
                                            <?php dynamic_sidebar( 'footer-widget-area-4' ); ?>
                                        </div>
                                        <!-- End of Footer Widget Area 4 -->
                                    <?php endif; ?>


                                </div>
                            </div>
                        </div>
                        <!-- ===== End of Footer Information & Widget Section ===== -->

                    <?php } // End of Widgets active check?>
                <?php } // End if cariera_show_footer_widgets
            } // End if $footer_info ?> 


            <!-- ===== Start of Footer Copyright Section ===== -->
            <div class="copyright ptb40">
                <div class="container">
                    <div class="row">

                        <!-- Copyright Text -->
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <h6>
                                <?php 
                                    $copyright = cariera_get_option( 'cariera_copyrights' ); 
                                    echo wp_kses_post($copyright);
                                ?>
                            </h6>
                        </div>

                        <!-- Start of Social Media Buttons -->
                        <div class="col-md-6 col-sm-6 col-xs-12">

                            <?php
                                $footericons = cariera_get_option( 'cariera_footer_socials', array() );
                                if ( !empty( $footericons ) ) {
                                    echo '<ul class="social-btns text-right">';
                                    foreach( $footericons as $icon ) {
                                        echo '<li class="list-inline-item">
                                                <a class="social-btn-roll ' . $icon['social_type'] . '" href="' . esc_url($icon['link_url']) . '" target="_blank">
                                                    <div class="social-btn-roll-icons">
                                                        <i class="social-btn-roll-icon fab fa-' . $icon['social_type'] . '"></i>
                                                        <i class="social-btn-roll-icon fab fa-' . $icon['social_type'] . '"></i>
                                                    </div>
                                                </a>
                                            </li>';
                                    }
                                    echo '</ul>';
                                }
                            ?>

                        </div>
                        <!-- End of Social Media Buttons -->
                    
                    </div>
                </div>
            </div>
            <!-- ===== End of Footer Copyright Section ===== -->

        </footer>
        <!-- =============== End of Footer =============== -->
    <?php } // End if cariera_show_footer 
    
} ?>



<!-- ===== Start of Back to Top Button ===== -->
<?php if( cariera_get_option('cariera_back_top','on') == 'on' ) : ?>
    <a href="#" class="back-top"><i class="fas fa-chevron-up"></i></a>
<?php endif; ?>
<!-- ===== End of Back to Top Button ===== -->

</div>
<!-- End of Website wrapper -->

<?php wp_footer(); ?>

</body>
</html>