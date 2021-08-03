<?php

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}


        
        
$box_jobs_categories = array('None' => ' ');

$job_listing_categories = get_terms('job_listing_category', 'orderby=count&hide_empty=0');

if (is_array($job_listing_categories) && !empty($job_listing_categories)) {
    foreach ($job_listing_categories as $job_listing_category) {
        $box_jobs_categories[$job_listing_category->name] = esc_attr($job_listing_category->term_id);
    }
}


vc_map( array(
    'name'               => esc_html__( 'Job Categories Slider', 'cariera' ),
    'description'        => esc_html__( 'Dispays a slider with a list of job categories', 'cariera' ),
    'base'               => 'job_categories_slider',
    "category"           => 'Job Manager',
    "params"             => array(
        array(
            "type"              => "dropdown",
            "class"             => "",
            "heading"           => esc_html__("Category Box Style", 'cariera'),
            "param_name"        => "category_style",
            "value"             =>
            array(
                'Dark'       => 'dark',
                'Light'      => 'light',
            ),
            'save_always'       => true,
        ),
        array(
            "type"              => "dropdown",
            "class"             => "",
            "heading"           => esc_html__("Category Icon", 'cariera'),
            "param_name"        => "category_icon",
            "value"             =>
            array(
                'Hide'      => 'hide',
                'Show'      => 'show',
            ),
            'save_always'       => true,
        ),
         array(
            'type'          => 'textfield',
            'heading'       => esc_html__('Visible categories', 'cariera'),
            'param_name'    => 'columns',
            'value'         => '5', // default value
            'description'   => esc_html__('This will change how many categories will be visible per slide.', 'cariera'),
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
            "description"       => esc_html__("Hides categories that doesn't have any jobs", 'cariera'),
        ),
        array(
            'type'              => 'dropdown',
            'heading'           => esc_html__('Parent id', 'cariera'),
            'param_name'        => 'parent_id',
            'value'             => $box_jobs_categories,
            'dependency'        => array(
                'element'   => 'type',
                'value'     => array('parent'),
            ),
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
            'heading'           => esc_html__('Total items', 'cariera'),
            'param_name'        => 'number',
            'value'             => 10, // default value
            'description'       => esc_html__('Set max limit for items (limited to 1000).', 'cariera'),
        ),
    )
) );

    
    
/*
Shortcode logic how it should be rendered
*/
if ( !function_exists( 'job_categories_slider' ) ) {
    function job_categories_slider( $atts, $content = null ) {

        extract(shortcode_atts(array(
            'category_style'        => 'dark',
            'category_icon'         => 'hide',
            'columns'               => '5',
            'orderby'               => 'count',
            'order'                 => 'DESC',
            'number'                => '99',
            'hide_empty'            => 0,
            'parent_id'             => '',
        ), $atts));

        $output = '';

        $categories = get_terms( array(
            'taxonomy'   => 'job_listing_category',
            'orderby'    => $orderby,
            'order'      => $order,
            'hide_empty' => $hide_empty,
            'number'     => $number,
        ) );


        if ( !is_wp_error( $categories ) ) {
            $output .= '<div class="category-groups category-slider-layout">';
            $chunks = cariera_partition($categories, $columns);
            
            /* Category Layout - Slider */
            $output .= '<div class="job-cat-slider1" data-columns="' . esc_attr($columns) . '">';
            foreach( $chunks as $chunk ) {
                foreach ( $chunk as $term ) {
                    $t_id = $term->term_id;
                    $term_meta = get_option( "taxonomy_$t_id" );

                    $img_icon  = isset($term_meta['image_icon']) ? $term_meta['image_icon'] : '';
                    $font_icon = isset($term_meta['font_icon']) ? $term_meta['font_icon'] : '';


                    $output .= '<a href="' . get_term_link( $term ) . '" class="item">';
                    $output .= '<div class="cat-item ' . $category_style . '-style">';

                    // Category Icon
                    if ( $category_icon == 'show' ) {
                        $output .= '<span class="cat-icon">';
                        if ( !empty($img_icon) ) {
                            $output .= '<img src="' . esc_attr($img_icon) . '" class="category-icon" />';
                        } elseif ( !empty($font_icon) ) { 
                            $output .= ' <i class="' . esc_attr($font_icon) . '"></i>';
                        }
                        $output .= "</span>";
                    }

                    $output .= '<span class="cat-title">' . $term->name . '</span>';

                    $output .= '</div></a>';

                }
            }
            $output .= '</div>';
            
            $output .= '</div>';
        }

        return $output;
    }
}

add_shortcode('job_categories_slider', 'job_categories_slider');