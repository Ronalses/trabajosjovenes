<?php

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}


vc_map( array(
    'name'           => esc_html__( 'Blog Posts', 'cariera' ),
    'description'    => esc_html__( 'Latest Blog Posts', 'cariera' ),
    'base'           => 'blog_posts',
    'category'       => 'Cariera Custom',
    'group'          => 'Cariera Custom',  
    "params" => array(
        array(
            "type"          => "dropdown",
            "class"         => "",
            "heading"       => esc_html__( 'Style', 'cariera' ),
            "param_name"    => 'style',
            "value"         =>
            array(
                'Style 1' => '1',
                'Style 2' => '2',
                'Style 3' => '3',
            ),
            'save_always'   => true,
        ),
        array(
            "type"			=> "textfield",
            "admin_label"	=> false,
            "class"			=> "",
            "heading"		=> esc_html__( "Number of Posts", 'cariera' ),
            "param_name"	=> "posts",
            "value"			=> "3",
            "description"	=> esc_html__( "Number of Posts you want to display.", 'cariera' )
        ),
        array(
            "type"          => "dropdown",
            "class"         => "",
            "heading"       => esc_html__("Columns", 'cariera' ),
            "param_name"    => "columns",
            "value"         =>
            array(
                '4' => '4',
                '3' => '3',
                '2' => '2',
            ),
            'save_always'   => true,
        ),
        array(
            "type"          => "dropdown",
            "admin_label"	=> false,
            "class"         => "",
            "heading"       => esc_html__("Show Post Thumbnail", 'cariera' ),
            "param_name"    => "show_thumb",
            "value"         => array(
                "Show"  => "show",
                "Hide"  => "hide"
            ),
            "description"   => ""
        ),
         array(
            "type"          => "dropdown",
            "admin_label"	=> false,
            "class"         => "",
            "heading"       => esc_html__("Show Author Avatar", 'cariera' ),
            "param_name"    => "show_avatar",
            "value"         => array(
                "Show"  => "show",
                "Hide"  => "hide"
            ),
            "description"   => "",
            'dependency'    => array( 
                'element' => 'show_thumb', 
                'value' => array('show') 
            ),
        ),
        array(
            "type"          => "dropdown",
            "admin_label"	=> false,
            "class"         => "",
            "heading"       => esc_html__("Show Date", 'cariera' ),
            "param_name"    => "show_date",
            "value"         => array(
                "Show"  => "yes",
                "Hide"  => "no"
            ),
            "description"   => ""
        ),
        array(
            "type"			=> "autocomplete",
            "admin_label"	=> true,
            "class"			=> "",
            "heading"		=> esc_html__( "Categories", 'cariera' ),
            "description"	=> esc_html__( "Category Slugs - For example: sports, business, all", 'cariera' ),
            "param_name"	=> "categories",
            "settings"      => array(
                'multiple' => true,
                'sortable' => true,
                'values'   => cariera_get_categories( 'category' ),
            ),
        ),
    )
) );





/*
Shortcode logic how it should be rendered
*/
if ( !function_exists( 'blog_posts' ) ) {
    function blog_posts( $atts ) {

        extract(shortcode_atts(array(
            'style'         => '1',
            'posts'         => '3',
            'columns'       => '3',
            'categories'    => '',
            'show_thumb'    => 'show',
            'show_avatar'   => 'show',
            'show_date'     => 'show',
        ), $atts));

        global $post;

        $args = array(
            'post_type'      => 'post',
            'posts_per_page' => $posts,
            'order'          => 'DESC',
            'orderby'        => 'date',
            'post_status'    => 'publish'
        );
        
        if ( $categories ) {
			$args['category_name'] = trim( $categories );
		}

        $wp_query = new WP_Query($args);
        $html = '';


        if( $wp_query->have_posts() ) :

            $html .= '<div class="latest-blog clearfix">';  

            while ( $wp_query->have_posts() ) : $wp_query->the_post();

                switch ($columns) {
                    case 2:
                        $column_class = 'vc_col-sm-6';
                        break;

                    case 3:
                        $column_class = 'vc_col-sm-4';
                        break;

                    case 4:
                        $column_class = 'vc_col-sm-3';
                        break;
                }

                // Blog Post Style 1
                if ( $style == '1' ) {
                    if ( $show_thumb == 'show' ) {

                        $blog_thumbnail = get_the_post_thumbnail_url();
                        if ( empty($blog_thumbnail) ) {
                            $blog_thumbnail = get_template_directory_uri() . '/assets/images/default-thumbnail.jpg';                
                        } else {
                            $blog_thumbnail = get_the_post_thumbnail_url();
                        }

                        $post_thumb = ' <a href="' . get_permalink() . '" class="bloglist-thumb-link hover-link">
                                            <div class="bloglist-post-thumbnail" style="background: url(' . $blog_thumbnail . ')"></div>
                                        </a>';

                        if ( $show_avatar == 'show' ) {
                            $user_avatar = '<span class="circle-img bloglist-avatar">' . get_avatar( get_the_author_meta('user_email'), $size = '50') . '</span>';
                        } else {
                            $user_avatar = '';
                        }
                    } else {
                        $post_thumb = '';
                        $user_avatar = '';
                    }
                    
                    
                    $html .= '<div class="' . $column_class . '">
                                  <div class="blog-post-layout shadow-hover">
                                    ' . $post_thumb . '
                                    <div class="bloglist-text-wrapper">
                                        ' . $user_avatar . '
                                        <h4 class="bloglist-title">
                                            <a href="' . esc_url( get_permalink() ) . '" title="' . esc_attr(get_the_title()) . '">' . esc_html( get_the_title() ) . '</a>
                                        </h4>';

                                        if( $show_date == 'show' ) {
                                            $html .= '<div class="bloglist-meta">
                                                        <i class="far fa-calendar-alt"></i> ' . get_the_time(get_option('date_format')) .
                                                     '</div>';
                                        }

                        $html .=        '<div class="bloglist-excerpt">
                                            <p>' . cariera_string_limit_words(get_the_excerpt(), '23') . '...</p>
                                            <a href="' . get_permalink() . '" class="btn btn-main btn-effect">' . esc_html__( 'read more', 'cariera' ) . '</a>
                                        </div>
                                    </div>
                                </div>
                            </div>';
                
                // Blog Post Style 2
                } elseif ( $style == '2' ) {
                    
                    if ( $show_thumb == 'show' ) {

                        $blog_thumbnail = get_the_post_thumbnail_url( get_the_ID(), 'full' );

                        if ( empty($blog_thumbnail) ) {
                            $blog_thumbnail = get_template_directory_uri() . '/assets/images/default-thumbnail.jpg';                
                        } else {
                            $blog_thumbnail = get_the_post_thumbnail_url();
                        }

                        $post_thumb = '<div class="bloglist-post-thumbnail" style="background: url(' . $blog_thumbnail . ')"></div>';
                    } else {
                        $post_thumb = '';
                    }
                    
                    $post_cat = get_the_category();
                    
                    $html .= '<div class="' . $column_class . '">
                              <div class="blog-post-layout2">
                                ' . $post_thumb . '
                                <div class="bloglist-text-wrapper">
                                    <span class="post-category"><a href="' . esc_url(get_category_link(get_cat_id( $post_cat[0]->name ))) . '">' . $post_cat[0]->name . '</a></span>
                                    <h4 class="bloglist-title">
                                        <a href="' . esc_url( get_permalink() ) . '" title="' . esc_attr(get_the_title()) . '">' . esc_html( get_the_title() ) . '</a>
                                    </h4>';

                                    if( $show_date == 'show' ) {
                                        $html .= '<div class="bloglist-meta">
                                                    <i class="far fa-calendar-alt"></i> ' . get_the_time(get_option('date_format')) .
                                                 '</div>';
                                    }

                    $html .=        '<div class="bloglist-excerpt">
                                        <p>' . cariera_string_limit_words(get_the_excerpt(), '15') . '...</p>
                                        <a href="' . get_permalink() . '" class="btn btn-main btn-effect">' . esc_html__( 'read more', 'cariera' ) . '</a>
                                    </div>
                                </div>
                            </div>
                        </div>';

                // Blog Post Style 3
                } elseif ( $style == '3' ) {

                    $post_cat = get_the_category(); ?>
                    
                    <!-- Blog Post Item -->
                    <div class="<?php echo esc_attr($column_class) ?>" id="post-<?php the_ID(); ?>">
                        <a href="<?php the_permalink(); ?>" class="blog-post-layout3">
                            <div class="blog-grid-item">
                                <?php 
                                if ( ! post_password_required() ) {
                                    if(has_post_thumbnail()) { 
                                        the_post_thumbnail(); 
                                    } 
                                }

                                if( has_category() ) { ?>
                                    <span class="item-cat"><?php echo esc_html($post_cat[0]->name ) ?></span>
                                <?php } ?>
                                
                                <div class="blog-grid-item-content">
                                    <?php if( $show_date == 'show' ) { ?>
                                        <ul class="post-meta">
                                            <li><?php the_date(); ?></li>
                                        </ul>
                                    <?php } ?>
                                    
                                    <h3 class="title"><?php the_title(); ?></h3>
                                </div>
                            </div>
                        </a>
                    </div>

                <?php
                }

            endwhile;

            $html .= '</div>';  

            wp_reset_postdata();

        endif;

        wp_reset_postdata();

        return $html;
    }
}

add_shortcode('blog_posts', 'blog_posts');