<?php
/**
*
* @package Cariera
*
* @since 1.0.0
* 
* ========================
* TEMPLATE FOR DISPLAYING ALL PAGES
* ========================
*     
**/


get_header(); 


while ( have_posts() ) : the_post();
    get_template_part( 'templates/content/content', 'page' );
endwhile; // End of the loop. 


get_footer();