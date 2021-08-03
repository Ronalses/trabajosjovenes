<?php
/**
*
* @package Cariera
*
* @since 1.0.0
* 
* ========================
* TAXONOMY FOR COMPANY CATEGORY
* ========================
*     
**/


$taxonomy = get_taxonomy( get_queried_object()->taxonomy );

get_header(); ?>


<!-- ===== Start of Page Header ===== -->
<section class="page-header company-taxonomy-header">
    <div class="container">
        <div class="row">

            <!-- Start of Job Title -->
            <div class="col-md-12 text-center">
                <h1 class="title"><?php if( $taxonomy ) : echo esc_attr( $taxonomy->labels->singular_name ); echo ":"; endif; ?> <?php single_term_title(); ?></h1>
            </div>
            <!-- End of Job Title -->

        </div>
    </div>
</section>
<!-- ===== End of Page Header ===== -->


<!-- ===== Start of Main Wrapper ===== -->
<main class="ptb80">
    <div class="container">
        <?php echo do_shortcode('[companies show_filters="false" categories=' . get_query_var( 'company_category' ) . ']'); ?>
        
    </div>
</main>
<!-- ===== End of Main Wrapper ===== -->


<?php get_footer();