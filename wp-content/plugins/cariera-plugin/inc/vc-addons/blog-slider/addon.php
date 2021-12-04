<?php

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

	
vc_map( array(
    'name'           => esc_html__( 'Blog Post Slider', 'cariera' ),
    'description'    => esc_html__( 'Blog Slider Element', 'cariera' ),
    'base'           => 'blog_slider',
    'category'       => 'Cariera Custom',
    'group'          => 'Cariera Custom',  
    "params" => array(
        array(
            "type"          => "dropdown",
            "admin_label"	=> false,
            "class"         => "",
            "heading"       => esc_html__("Show Date", 'cariera' ),
            "param_name"    => "show_date",
            "value"         => array(
                "Yes" => "yes",
                "No"  => "no"
            ),
            "description"   => ""
        ),
        array(
            "type"          => "dropdown",
            "admin_label"	=> false,
            "class"         => "",
            "heading"       => esc_html__( "Order By", 'cariera' ),
            "param_name"    => "order_by",
            "value"         => array(
                "Date" => "date",
                "Title" => "title",
            ),
            "description"   => "",
            "group"	        => esc_html__( 'Post Options', 'cariera' ),
        ),
        array(
            "type"          => "dropdown",
            "admin_label"	=> false,
            "class"         => "",
            "heading"       => esc_html__( "Order", 'cariera' ),
            "param_name"    => "order",
            "value"         => array(
                "DESC" => "DESC",
                "ASC" => "ASC",
            ),
            "description"   => "",
            "group"	        => esc_html__( 'Post Options', 'cariera' ),
        ),
        array(
            "type"          => "textfield",
            "admin_label"	=> false,
            "class"         => "",
            "heading"       => esc_html__( "Number", 'cariera' ),
            "param_name"    => "number",
            "value"         => "-1",
            "save_value"    => true,
            "description"   => esc_html__( "Number of blog posts (-1 for all)", 'cariera' ),
            "group"	        => esc_html__( 'Post Options', 'cariera' ),
        ),
        array(
            "type"          => "textfield",
            "admin_label"	=> false,
            "class"         => "",
            "heading"       => esc_html__( "Category", 'cariera' ),
            "param_name"    => "category",
            "value"         => "",
            "description"   => esc_html__( "Category Slug (leave empty for all)", 'cariera' ),
            "group"	        => esc_html__( 'Post Options', 'cariera' ),
        ),
        array(
            "type"          => "textfield",
            "admin_label"	=> false,
            "class"         => "",
            "heading"       => esc_html__( "Selected Posts", 'cariera' ),
            "param_name"    => "selected_posts",
            "value"         => "",
            "description"   => esc_html__( "Selected Posts (leave empty for all, delimit by comma)", 'cariera' ),
            "group"	        => esc_html__( 'Post Options', 'cariera' ),
        )
    )
) );
        
	

    
    
/*
Shortcode logic how it should be rendered
*/
if ( !function_exists( 'blog_slider' ) ) {
    function blog_slider( $atts, $content = null ) {
         $args = array(
            'order_by'            => 'date',
            'order'               => 'desc',
            'number'              => '-1',
            'category'            => '',
            'selected_posts'   	  => '',
            'show_date'           => 'yes',
        );

        extract(shortcode_atts($args, $atts));


        $q = array(
            'post_type'      => 'post',
            'orderby'        => $order_by,
            'order'          => $order,
            'posts_per_page' => $number
        );

        if($category !== '') {
            $q['category_name'] = $category;
        }

        $post_ids = null;
        if($selected_posts != '') {
            $post_ids   = explode(',', $selected_posts);
            $q['post__in'] = $post_ids;
        }

        $blog_query = new WP_Query($q);



        if($blog_query->have_posts()) :

            $html = '<div class="blog-post-slider">';

            while($blog_query->have_posts()) : $blog_query->the_post();
                if(get_the_post_thumbnail(get_the_ID()) != NULL) {

                    $html .= '<div class="item">';
                        $html .= '<div class="blogslider-post-holder">';


                            $html .= '<a href="' . get_permalink() . '" class="blogslider-thumb-link hover-link">';

                                $img = get_the_post_thumbnail_url();
                                $html .='<div class="blogslider-post-thumbnail" style="background: url(' . $img . ')">';

                                $html .= '</div>';
                            $html .= '</a>';



                            $html .= '<div class="blogslider-text-wrapper"><div class="blogslider-text-outer"><div class="blogslider-text-inner">';

                                $html .= '<h4 class="blogslider-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h4>';

                                if($show_date == 'yes') {
                                    $html .= '<div class="blogslider-meta">';
                                    $html .= '<i class="far fa-calendar-alt"></i> ' . get_the_time(get_option('date_format'));
                                    $html .= '</div>';
                                }

                                $html .= '<div class="blogslider-excerpt">';
                                $html .= '<p>' . cariera_string_limit_words(get_the_excerpt(), 20) . '...</p>';
                                $html .= '<a href="' . get_permalink() . '" class="btn btn-main btn-effect">' . esc_html__( 'read more', 'cariera' ) . '</a>';
                                $html .= '</div>';
                            $html .= '</div></div></div>'; //wrapper + outer + inner + inner2
                        $html .= '</div>';
                    $html .= '</div>';
                }
            endwhile;

            $html .= '</div>';
        else:
            $html .= '<p>' . esc_html__( 'Sorry, no posts matched your criteria.', 'cariera' ) . '</p>';
        endif;

        wp_reset_postdata();

        return $html;
    }
}
    
add_shortcode('blog_slider', 'blog_slider');