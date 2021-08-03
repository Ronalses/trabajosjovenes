<?php

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}




vc_map( array(
    'name'               => esc_html__( 'Listing Categories Grid', 'cariera' ),
    'description'        => esc_html__( 'Dispays list of job or resume categories in a grid layout.', 'cariera' ),
    'base'               => 'listing_categories_grid',
    "category"           => 'Job Manager',
    "params"             => array(
        array(
            "type"              => "dropdown",
            "class"             => "",
            "heading"           => esc_html__("Listing", 'cariera'),
            "param_name"        => "listing",
            "value"             => array(
                'Job Listing'      => 'job_listing',
                'Resume'      => 'resume',
            ),
            'save_always'       => true,
        ),
        array(
            "type"              => "dropdown",
            "class"             => "",
            "heading"           => esc_html__( 'Category List Layout', 'cariera'),
            "param_name"        => "category_layout",
            "value"             =>
            array(
                'Layout 1'      => 'layout1',
                'Layout 2'      => 'layout2',
                'Layout 3'      => 'layout3',
            ),
            'save_always'       => true,
        ),
        array(
            "type"              => "dropdown",
            "class"             => "",
            "heading"           => esc_html__( 'Category Icon', 'cariera' ),
            "param_name"        => "category_icon",
            "value"             =>
            array(
                'Hide'      => 'hide',
                'Show'      => 'show',
            ),
            'save_always'       => true,
            'dependency'        => array(
                'element'   => 'category_layout',
                'value'     => ['layout1', 'layout2']
            ),
        ),
        array(
            "type"              => "dropdown",
            "class"             => "",
            "heading"           => esc_html__( 'Category Background', 'cariera' ),
            "param_name"        => "category_background",
            "value"             => array(
                'Hide'      => 'hide',
                'Show'      => 'show',
            ),
            'save_always'       => true,
            'dependency'        => array(
                'element'   => 'category_layout',
                'value'     => ['layout1']
            ),
        ),
        array(
            "type"              => "dropdown",
            "class"             => "",
            "heading"           => esc_html__( 'Jobs Counter', 'cariera' ),
            "param_name"        => "job_counter",
            "value" => array(
                'Yes'       => 'yes',
                'No'        => 'no',
            ),
            'save_always'       => true,
            "description"       => esc_html__( 'Show number of jobs inside of category', 'cariera' ),
            'dependency'        => array(
                'element'   => 'listing',
                'value'     => array('job_listing')
            ),
        ),
        array(
            "type"              => "dropdown",
            "class"             => "",
            "heading"           => esc_html__( 'Resume Counter', 'cariera' ),
            "param_name"        => "resume_counter",
            "value" => array(
                'Yes'       => 'yes',
                'No'        => 'no',
            ),
            'save_always'       => true,
            "description"       => esc_html__( 'Show number of jobs inside of category', 'cariera' ),
            'dependency'        => array(
                'element'   => 'listing',
                'value'     => array('resume')
            ),
        ),
        array(
            "type"              => "dropdown",
            "class"             => "",
            "heading"           => esc_html__("Hide empty", 'cariera'),
            "param_name"        => "hide_empty",
            "value" => array(
                'Hide'      => '1',
                'Show'      => '0',
            ),
            'save_always'       => true,
            "description"       => esc_html__( 'Hides categories that doesn\'t have any listings', 'cariera'),
        ),
        array(
            'type'              => 'dropdown',
            'heading'           => esc_html__('Order by', 'cariera'),
            'param_name'        => 'orderby',
            'value'             => array(
                'Name'      => 'naem',
                'ID'        => 'ID',
                'Count'     => 'count',
                'Slug'      => 'slug',
                'None'      => 'none',
            ),
        ),
        array(
            'type'              => 'dropdown',
            'heading'           => esc_html__('Order', 'cariera'),
            'param_name'        => 'order',
            'value'             => array(
                'Descending' => 'DESC',
                'Ascending'  => 'ASC'
            ),
        ),
        array(
            'type'              => 'textfield',
            'heading'           => esc_html__( 'Total items', 'cariera' ),
            'param_name'        => 'items',
            'value'             => 10, // default value
            'description'       => esc_html__( 'Set max limit for items (limited to 1000).', 'cariera' ),
        ),
    )
) );




/*
Shortcode logic how it should be rendered
*/
if ( !function_exists( 'listing_categories_grid' ) ) {
    function listing_categories_grid( $atts, $content = null ) {

        extract(shortcode_atts(array(
            'listing'               => 'job_listing',
            'category_layout'       => 'layout1',
            'category_icon'         => 'hide',
            'category_background'   => 'hide',
            'job_counter'           => 'yes',
            'resume_counter'        => 'yes',
            'hide_empty'            => 0,
            'orderby'               => 'count',
            'order'                 => 'DESC',
            'items'                 => '99',
        ), $atts));


        $output = '';

        $categories = get_terms( array(
            'taxonomy'   => $listing . '_category',
            'orderby'    => $orderby,
            'order'      => $order,
            'hide_empty' => $hide_empty,
            'number'     => $items,
        ) );
        

        if ( !is_wp_error( $categories ) ) {
            $output .= '<div class="listing-category-wrapper">';
            $chunks = cariera_partition($categories, 1);

        
            // Listing Layout
            foreach( $chunks as $chunk ) {

                // Category Grid Layout 1
                if ( $category_layout == 'layout1' ) {
                    $output .= '<div class="listing-categories ' . $listing .  '-categories grid-layout1">';
                        foreach ( $chunk as $term ) {
                            $t_id = $term->term_id;
                            $term_meta = get_option( "taxonomy_$t_id" );

                            $bg_img    = isset($term_meta['background_image']) ? $term_meta['background_image'] : '';
                            $img_icon  = isset($term_meta['image_icon']) ? $term_meta['image_icon'] : '';
                            $font_icon = isset($term_meta['font_icon']) ? $term_meta['font_icon'] : '';

                            $output .= '<div class="listing-category">';
                            
                            // Background Options
                            if ( $category_background == 'show' ) {
                                if ( !empty($bg_img) ) {
                                    $output .= '<a href="' . get_term_link( $term ) . '" class="listing-category-bg" style="background-image: url(' . esc_attr($bg_img) . ')">';
                                } else {
                                    $output .= '<a href="' . get_term_link( $term ) . '">';
                                }
                            } else {
                                $output .= '<a href="' . get_term_link( $term ) . '">';
                            }
                            
                            // Category Icon
                            if ( $category_icon == 'show' ) {
                                if ( !empty($img_icon) ) {
                                    $output .= '<img src="' . esc_attr($img_icon) . '" class="category-icon" />';
                                } elseif ( !empty($font_icon) ) { 
                                    $output .= ' <i class="' . esc_attr($font_icon) . '"></i>';
                                }
                            }

                            $output .= '<h4 class="title">' . $term->name . '</h4>';

                            if( $listing == 'job_listing' ) {
                                if( $job_counter == 'yes' ) {
                                    $output .= '<span class="positions">(' . $term->count . esc_html__(" open positions", "cariera") . ')</span>';
                                }
                            } else {
                                if( $resume_counter == 'yes' ) {
                                    $output .= '<span class="positions">(' . $term->count . esc_html__( ' Resumes', 'cariera' ) . ')</span>';
                                }
                            }

                            $output .= '</a></div>';
                        }
                    $output .= '</div>';
                }


                // Category Grid Layout 2
                elseif ( $category_layout == 'layout2' ) {
                    $output .= '<div class="listing-categories ' . $listing .  '-categories grid-layout2">';
                        foreach ( $chunk as $term ) {
                            $t_id = $term->term_id;
                            $term_meta = get_option( "taxonomy_$t_id" );

                            $img_icon  = isset($term_meta['image_icon']) ? $term_meta['image_icon'] : '';
                            $font_icon = isset($term_meta['font_icon']) ? $term_meta['font_icon'] : '';

                            $output .= '<div class="listing-category">';
                            
                            $output .= '<a href="' . get_term_link( $term ) . '">';
                            
                            
                            // Category Icon
                            if ( $category_icon == 'show' ) {
                                if ( !empty($img_icon) ) {
                                    $output .= '<img src="' . esc_attr($img_icon) . '" class="category-icon" />';
                                } elseif ( !empty($font_icon) ) { 
                                    $output .= ' <i class="' . esc_attr($font_icon) . '"></i>';
                                }
                            }

                            $output .= '<h4 class="title">' . $term->name . '</h4>';

                            if( $listing == 'job_listing' ) {
                                if( $job_counter == 'yes' ) {
                                    $output .= '<span class="positions">(' . $term->count . esc_html__( ' open positions', 'cariera' ) . ')</span>';
                                }
                            } else {
                                if( $resume_counter == 'yes' ) {
                                    $output .= '<span class="positions">(' . $term->count . esc_html__( ' Resumes', 'cariera' ) . ')</span>';
                                }
                            }

                            $output .= '</a></div>';
                        }
                    $output .= '</div>';
                }


                // Category Grid Layout 3
                elseif ( $category_layout == 'layout3' ) {
                    $output .= '<div class="listing-categories ' . $listing .  '-categories grid-layout3">';
                        foreach ( $chunk as $term ) {
                            $t_id = $term->term_id;
                            $term_meta = get_option( "taxonomy_$t_id" );

                            $img_icon  = isset($term_meta['image_icon']) ? $term_meta['image_icon'] : '';
                            $font_icon = isset($term_meta['font_icon']) ? $term_meta['font_icon'] : '';

                            $output .= '<div class="listing-category">';
                            
                            $output .= '<a href="' . get_term_link( $term ) . '">';
                            
                            // Category Icon
                            if ( !empty($img_icon) ) {
                                $output .= '<img src="' . esc_attr($img_icon) . '" class="category-icon" />';
                            } elseif ( !empty($font_icon) ) { 
                                $output .= ' <i class="' . esc_attr($font_icon) . '"></i>';
                            }

                            $output .= '<h4 class="title">' . $term->name . '</h4>';

                            if( $listing == 'job_listing' ) {
                                if( $job_counter == 'yes' ) {
                                    $output .= '<span class="positions">(' . $term->count . esc_html__( ' open positions', 'cariera' ) . ')</span>';
                                }
                            } else {
                                if( $resume_counter == 'yes' ) {
                                    $output .= '<span class="positions">(' . $term->count . esc_html__( ' Resumes', 'cariera' ) . ')</span>';
                                }
                            }

                            $output .= '</a></div>';
                        }
                    $output .= '</div>';

                }

            }

            $output .= '</div>';
        }

        echo $output;

    }
}

add_shortcode( 'listing_categories_grid', 'listing_categories_grid' );