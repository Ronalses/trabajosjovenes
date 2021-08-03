<?php

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}




vc_map( array(
    'name'               => esc_html__( 'Listing Categories List', 'cariera' ),
    'description'        => esc_html__( 'Dispays list of job or resume categories in a list layout.', 'cariera' ),
    'base'               => 'listing_categories_list',
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
            ),
            'save_always'       => true,
        ),
        array(
            "type"              => "dropdown",
            "class"             => "",
            "heading"           => esc_html__("Columns", 'cariera'),
            "param_name"        => "columns",
            "value" => array(
                '4'         => '4',
                '3'         => '3',
                '2'         => '2',
            ),
            'save_always'       => true,
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
if ( !function_exists( 'listing_categories_list' ) ) {
    function listing_categories_list( $atts, $content = null ) {

        extract(shortcode_atts(array(
            'listing'               => 'job_listing',
            'category_layout'       => 'layout1',
            'columns'               => '2',
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
            $output .= '<div class="row">';
            $chunks = cariera_partition($categories, $columns);

            switch ($columns) {
                case 2:
                    $column_class = 'col-md-6 col-xs-12';
                break;

                case 3:
                    $column_class = 'col-md-4 col-xs-12';
                break;

                case 4:
                    $column_class = 'col-md-3 col-xs-12';
                break;
            }


            // Listing Layout
            foreach( $chunks as $chunk ) {
                $output .= '<div class="' . $column_class . '">';
                
                // Category List Version 1
                if ( $category_layout == 'layout1' ) {
                    $output .= '<ul class="listing-categories ' . $listing .  '-categories list-layout1">';
                        foreach ( $chunk as $term ) {
                            $output .= '<li><a href="' . get_term_link( $term ) . '"><h4 class="title">' . $term->name;
                            $output .= '</h4>';

                            if( $listing == 'job_listing' ) {
                                if( $job_counter == 'yes' ) {
                                    $output .= '<span class="positions">(' . $term->count . esc_html__(" open positions", "cariera") . ')</span>';
                                }
                            } else {
                                if( $resume_counter == 'yes' ) {
                                    $output .= '<span class="positions">(' . $term->count . esc_html__( ' Resumes', 'cariera' ) . ')</span>';
                                }
                            }

                            $output .= '</a></li>';
                        }
                    $output .= '</ul>';
                }


                // Category List Version 2
                else {
                    $output .= '<ul class="listing-categories ' . $listing .  '-categories list-layout2">';
                        foreach ( $chunk as $term ) {
                            $t_id       = $term->term_id;
                            $term_meta  = get_option( "taxonomy_$t_id" );
                            $bg_img     = isset($term_meta['background_image']) ? $term_meta['background_image'] : '';
                            
                            $output .= '<li><a href="' . get_term_link( $term ) . '">';
                            
                            if ( !empty($bg_img) ) {
                                $output .= '<div class="category-img" style="background-image: url(' . esc_attr($bg_img) . ');"></div>';
                            } else {
                                $output .= '<div class="category-img"></div>';
                            }
                            
                            $output .= '<div class="category-info"><h4 class="title">' . $term->name . '</h4>';

                            if( $listing == 'job_listing' ) {
                                if( $job_counter == 'yes' ) {
                                    $output .= '<span class="positions">(' . $term->count . esc_html__(" open positions", "cariera") . ')</span>';
                                }
                            } else {
                                if( $resume_counter == 'yes' ) {
                                    $output .= '<span class="positions">(' . $term->count . esc_html__( ' Resumes', 'cariera' ) . ')</span>';
                                }
                            }
                            
                            $output .= '<span class="cat-description">' . $term->description . '</span>';
                            
                            $output .= '</div><div class="clearfix"></div></a></li>';
                        }
                    $output .= '</ul>';
                }
                
                $output .= '</div>';
            }

            $output .= '</div></div>';
        }

        echo $output;

    }
}

add_shortcode( 'listing_categories_list', 'listing_categories_list' );