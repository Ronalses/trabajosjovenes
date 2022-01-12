<?php

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}



vc_map( array(
    'name'                       => esc_html__( 'Testimonials', 'cariera' ),
    'description'                => esc_html__( 'Testimonials carousel', 'cariera' ),
    'base'                       => 'testimonials-carousel',
    'class'                      => '',
    'show_settings_on_create'    => true,
    'weight'                     => 1,
    'category'                   => 'Cariera Custom',
    'group'                      => 'Cariera Custom',
    'content_element'            => true,            
    "params"                     => array(
        array(
            "type"          => "dropdown",
            "class"         => "",
            "heading"       => esc_html__("Testimonial Style", 'cariera'),
            "param_name"    => "style",
            "value"         => array(
                '1' => 'Style 1',
                '2' => 'Style 2',
            ),
        ),
        array(
            "type"          => "dropdown",
            "class"         => "",
            "heading"       => esc_html__("Show", 'cariera'),
            "param_name"    => "show",
            "value"         => array(
                'Random'                => 'random',
                'Latest'                => 'latest',
                'Selected IDs'          => 'selected',
            ),
        ),
        array(
            "type"          => "textfield",
            "heading"       => esc_html__("Posts", 'cariera'),
            "param_name"    => "posts",
            "value"         => 4,
            "description"   => esc_html__("How many posts to get.", 'cariera'),
            'dependency'    => array(
                'element'       => 'show',
                'value'         => array('random', 'latest')
            ),
        ),
        array(
            "type"          => "textfield",
            "heading"       => esc_html__("Posts ID's", 'cariera'),
            "param_name"    => "posts_ids",
            "value"         => 4,
            "description"   => esc_html__("Testimonial posts ID's separated by comma, example: 14,95,164.", 'cariera'),
            'dependency'    => array(
                'element'       => 'show',
                'value'         => array('selected')
            ),
        ),
        array(
            "type"          => "textfield",
            "heading"       => esc_html__("Extra class name", 'cariera'),
            "param_name"    => "el_class",
            "value"         => "",
            "description"   => esc_html__("Style particular content element differently - add a class name and refer to it in custom CSS.", 'cariera'),
        ),
    ),
) );

    

/*
Shortcode logic how it should be rendered
*/
if ( !function_exists( 'testimonials_carousel' ) ) {
	function testimonials_carousel( $atts, $content = null ) {
        extract(shortcode_atts(array(
            "style"     => '1',
            "show"      => "random",
            "posts"     => 4,
            "posts_ids" => "",
            "el_class"  => "",
        ), $atts));
        
        if ( $style == '1' ) {
            $style_class = 'testimonials-carousel-style1';
        } else {
            $style_class = 'testimonials-carousel-style2';
        }
        
        if ($show == 'selected') {
            $show_only_ids = explode(",", $posts_ids);
            $args = array(
                'post_type' => 'testimonial',
                'post__in' => $show_only_ids,
            );
        } else {
            $args = array(
                'post_type' => 'testimonial',
                'posts_per_page' => $posts,
            );
        }

        $output = '';

        $testimonials_query = new WP_Query($args);

        if ($testimonials_query->have_posts()) {

            if ( !empty($el_class) ) {
                $el_class = ' ' . $el_class;
            }

            $output .= '<div class="testimonials-carousel' . $el_class . ' ' . $style_class . '">';

            while ($testimonials_query->have_posts()) {

                $testimonials_query->the_post();

                $output .= '<div class="testimonial-item">';
                
                if ( $style == '1') {
                    ob_start(); 
                
                    get_template_part('/templates/content/content-testimonial1');
                    $output .= ob_get_contents();

                    ob_end_clean();
                } else { 
                    ob_start(); 
                
                    get_template_part('/templates/content/content-testimonial2');
                    $output .= ob_get_contents();

                    ob_end_clean();
                }
                

                $output .= '</div>';
            }

            $output .= '</div>';
        }

        wp_reset_postdata();

        return $output;
	}
}

add_shortcode('testimonials-carousel', 'testimonials_carousel');  