        
<?php
/**
*
* @package Cariera
*
* @since 1.3.0
* 
* ========================
* ARCHIVE TEMPLATE FILE FOR COMPANY LISTINGS
* ========================
*     
**/



get_header(); ?>


<!-- ===== Start of Page Header ===== -->
<section class="page-header">
    <div class="container">
        <div class="row">

            <!-- Start of Page Title -->
            <div class="col-md-12 text-center">
                <?php $count = wp_count_posts( 'company' )->publish; ?>
                
                <h1 class="title">
                    <?php echo apply_filters( 'cariera_company_listing_count', wp_kses_post( sprintf( _n( 'We have %s Company in our database', 'We have %s Companies in our database', $count, 'cariera' ), $count ) ) ); ?>
                </h1>
            </div>
            <!-- End of Page Title -->

        </div>
    </div>
</section>
<!-- ===== End of Page Header ===== -->



<?php
$google_api = cariera_get_option('cariera_gmap_api_key');
$map        = cariera_get_option( 'cariera_company_search_map' );

if ( !empty($google_api) && $map == 'true' ) {
    echo do_shortcode('[cariera-map type="company" class="companies_page"]'); 
} ?>



<!-- ===== Start of Main Wrapper ===== -->
<main class="ptb80">
    <div class="container">
        <div class="col-md-12">
            <?php
            do_action( 'cariera_before_company_loop' );

            echo do_shortcode('[companies]');

            do_action( 'cariera_after_company_loop' );
            ?>
        </div>
    </div>
</main>
<!-- ===== End of Main Wrapper ===== -->


<?php get_footer(); ?>