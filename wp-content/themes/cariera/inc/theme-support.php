<?php
/**
*
* @package Cariera
*
* @since   1.0.0
* @version 1.5.1
* 
* ========================
* THEME SUPPORT OPTIONS
* ========================
*     
**/




// Themes width
if ( ! isset( $content_width ) ) {
    $content_width = 980;
}





/* 
=====================================================
CARIERA THEME SETUP
=====================================================
*/

function cariera_setup() {
    
    /* Make theme available for translation */
	load_theme_textdomain( 'cariera', get_template_directory() . '/lang' );
    
    /* Enable Support for Post Thumbnails */
    add_theme_support( 'post-thumbnails' );
    set_post_thumbnail_size( 840, 350, true );
    add_image_size( 'blog', 1000, 563, true );
    
    /* Enable Support for Post Formats */
    add_theme_support('post-formats', array('aside', 'audio', 'image', 'gallery', 'quote', 'video'));

    /* Change default markup to output valid HTML5  */
    add_theme_support( 'html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
    
    /* Add default posts and comments RSS feed links to head. */
	add_theme_support( 'automatic-feed-links' );
    
    /*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
     */
	add_theme_support( 'title-tag' );
    
    /* Enable Support for WP Job Manager Templates */
    add_theme_support( 'job-manager-templates' );
    add_theme_support( 'resume-manager-templates' );
    
    /* Enable Support for WooCommerce */
    add_theme_support( 'woocommerce' );
    
    /* Support images for Gutenberg */ 
    add_theme_support('align-wide');
    
    /* Enable WooCommerce support for lightbox, zoom and gallery slider */
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
    
    /* === REGISTER MENUS === */
    register_nav_menus(
        array(
            'primary'           => esc_html__( 'Primary Menu', 'cariera' ),
            'employer-dash'     => esc_html__( 'Extra Menu for Employer Dashboard', 'cariera' ),
            'candidate-dash'    => esc_html__( 'Extra Menu for Candidate Dashboard', 'cariera' ),
        )
    );

}

add_action('after_setup_theme', 'cariera_setup');





/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 * 
 * @since 1.5.0
 */
function cariera_pingback_header() {
	if ( is_singular() && pings_open() ) {
		echo '<link rel="pingback" href="' . esc_url( get_bloginfo( 'pingback_url' ) ) . '">';
	}
}

add_action( 'wp_head', 'cariera_pingback_header' );





/**
 * Sets up theme default settings.
 *
 * @since  1.3.0
 */
function cariera_switch_theme() {
    update_option( 'job_manager_enable_categories', 1 );
    update_option( 'job_manager_enable_types', 1 );
    update_option( 'resume_manager_enable_categories', 1 );
    update_option( 'resume_manager_enable_skills', 1 );
}

add_action( 'after_switch_theme', 'cariera_switch_theme' );





/* 
=====================================================
REGISTER WIDGET AREAS
=====================================================
*/

function cariera_widgets_areas() {
    
    for ( $i = 1; $i <= 2; $i++ ) {
        register_sidebar( array(
            'name'          => sprintf( esc_html__( 'Top Header Widget Area Column %d', 'cariera' ), absint( $i ) ),
            'id'            => 'top-header-widget-area' . ( $i > 1 ? ( '-' . absint( $i ) ) : '' ),
            'description'   => esc_html__( 'Choose what should display in this top header widget column.', 'cariera' ),
            'before_widget' => '<aside id="%1$s" class="widget top-header-widget %2$s">',
            'after_widget'  => '</aside>',
            'before_title'  => '<h3 class="widget-title widget-title-top-header">',
            'after_title'   => '</h3>',
        ) );
    }
    
    register_sidebar( array(
        'id'              => 'sidebar-1',
        'name'            => esc_html__( 'Sidebar', 'cariera' ),
        'description'     => esc_html__('The primary widget area', 'cariera'),
        'before_widget'   => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'    => '</aside>',
        'before_title'    => '<h5 class="widget-title">',
        'after_title'     => '</h5>',
    ) );
    
    register_sidebar( array(
        'id'              => 'sidebar-jobs',
        'name'            => esc_html__( 'Jobs - Sidebar', 'cariera' ),
        'description'     => esc_html__('The sidebar widget area that can be used on general job pages.', 'cariera'),
        'before_widget'   => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'    => '</aside>',
        'before_title'    => '<h5 class="widget-title">',
        'after_title'     => '</h5>',
    ) );

    register_sidebar( array(
        'id'              => 'sidebar-single-job',
        'name'            => esc_html__( 'Single Job - Sidebar', 'cariera' ),
        'description'     => esc_html__('The sidebar widget area for single job page.', 'cariera'),
        'before_widget'   => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'    => '</aside>',
        'before_title'    => '<h5 class="widget-title">',
        'after_title'     => '</h5>',
    ) );
    
    register_sidebar( array(
        'id'              => 'sidebar-company',
        'name'            => esc_html__( 'Company - Sidebar', 'cariera' ),
        'description'     => esc_html__('The sidebar widget area that can be used on general company pages.', 'cariera'),
        'before_widget'   => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'    => '</aside>',
        'before_title'    => '<h5 class="widget-title">',
        'after_title'     => '</h5>',
    ) );

    register_sidebar( array(
        'id'              => 'sidebar-single-company',
        'name'            => esc_html__( 'Single Company - Sidebar', 'cariera' ),
        'description'     => esc_html__('The sidebar widget area for single company page.', 'cariera'),
        'before_widget'   => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'    => '</aside>',
        'before_title'    => '<h5 class="widget-title">',
        'after_title'     => '</h5>',
    ) );
    
    register_sidebar( array(
        'id'              => 'sidebar-resumes',
        'name'            => esc_html__( 'Resumes - Sidebar', 'cariera' ),
        'description'     => esc_html__('The sidebar widget area that can be used on general resume pages.', 'cariera'),
        'before_widget'   => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'    => '</aside>',
        'before_title'    => '<h5 class="widget-title">',
        'after_title'     => '</h5>',
    ) );

    register_sidebar( array(
        'id'              => 'sidebar-single-resume',
        'name'            => esc_html__( 'Single Resume - Sidebar', 'cariera' ),
        'description'     => esc_html__('The sidebar widget area for single resume page', 'cariera'),
        'before_widget'   => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'    => '</aside>',
        'before_title'    => '<h5 class="widget-title">',
        'after_title'     => '</h5>',
    ) );
    
    register_sidebar( array(
        'id'              => 'sidebar-shop',
        'name'            => esc_html__( 'Shop - Sidebar', 'cariera' ),
        'description'     => esc_html__('The shop sidebar widget area', 'cariera'),
        'before_widget'   => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'    => '</aside>',
        'before_title'    => '<h5 class="widget-title">',
        'after_title'     => '</h5>',
    ) );

    for ( $i = 1; $i <= 4; $i++ ) {
        register_sidebar( array(
            'name'          => sprintf( esc_html__( 'Footer Widget Area Column %d', 'cariera' ), absint( $i ) ),
            'id'            => 'footer-widget-area' . ( $i > 1 ? ( '-' . absint( $i ) ) : '' ),
            'description'   => esc_html__( 'Choose what should display in this footer widget column.', 'cariera' ),
            'before_widget' => '<aside id="%1$s" class="widget footer-widget %2$s">',
            'after_widget'  => '</aside>',
            'before_title'  => '<h3 class="widget-title widget-title-footer pb40">',
            'after_title'   => '</h3>',
        ) );
    }

}

add_action( 'widgets_init', 'cariera_widgets_areas' );





/* 
=====================================================
CARIERA BODY CLASS
=====================================================
*/

function cariera_body_class($classes) {
    
    if ( is_user_logged_in() ) {
        $classes[] = 'user-logged-in';
    }
    
    return $classes;
}

add_filter( 'body_class', 'cariera_body_class' );





/* 
=====================================================
MAIN MENU FALLBACK
=====================================================
*/

function cariera_menu_fallback() {
    if ( current_user_can('administrator') ) {
        echo( '
        <ul id="menu-main-menu" class="main-menu main-nav">
        <li class="menu-item"><a href="' . admin_url( 'nav-menus.php' ) . '">' . esc_html__('Add a menu', 'cariera') . '</a></li>
        </ul>' );
    } else {
        echo( '
        <ul id="menu-main-menu" class="main-menu main-nav">
        <li class="menu-item"></li>
        </ul>' );
    }
}





/* 
=====================================================
    UNNEEDED ASSETS
=====================================================
*/

/**
 * Deregister/remove unneeded scripts & styles
 *
 * @since   1.3.0
 * @version 1.5.1
 */
function cariera_remove_unneeded_assets() {
    if ( wp_script_is( 'job-regions' ) ) {
        wp_dequeue_script( 'job-regions' );
    }

    $styles = [
        'wc-block-style',
        'wp-job-manager-job-listings',
        'job-alerts-frontend',
        'jm-application-deadline',
        'wp-job-manager-applications-frontend',
        'wc-paid-listings-packages',
        'wp-job-manager-tags-frontend'
    ];


    foreach ( $styles as $style ) {
        if ( wp_style_is( $style, 'enqueued' ) ) {
            wp_dequeue_style( $style );
        } elseif ( wp_style_is( $style, 'registered' ) ) {
            wp_deregister_style( $style );
        }
    }

}

add_action( 'wp_enqueue_scripts', 'cariera_remove_unneeded_assets', 20 );





/* 
=====================================================
    PAGING NAVIGATION
=====================================================
*/

/**
 * Navigation function for pagination.
 *
 * @since  1.0
 */
if ( ! function_exists( 'cariera_paging_nav' ) ) {
    function cariera_paging_nav() {

        $pagination = cariera_get_option('cariera_blog_pagination');

        if ( $pagination == 'numeric' ) {
            cariera_numeric_pagination();
        } else {
            cariera_posts_navigation(array(
                'prev_text'  => ' ',
                'next_text'  => ' ',
            )); 
        }
    }
}




/**
 * Plain Pagination
 *
 * @since  1.0.0
 */
if ( ! function_exists( 'cariera_posts_navigation' ) ) {
    // Display navigation to next/previous set of posts when applicable.
    function cariera_posts_navigation() {
        require_once( get_template_directory() . '/templates/extra/pagination.php' );
    }
}




/**
 * Numeric Pagination
 *
 * @since  1.2.7
 */
if ( ! function_exists( 'cariera_numeric_pagination' ) ) {
	function cariera_numeric_pagination() {
		global $wp_query;

		if ( $wp_query->max_num_pages < 2 ) {
			return;
		}

		?>
		<div class="col-md-12 pagination">
			<?php
			$big  = 999999999;
			$args = array(
				'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
				'total'     => $wp_query->max_num_pages,
				'current'   => max( 1, get_query_var( 'paged' ) ),
				'prev_text' => esc_html__( 'Previous', 'cariera' ),
				'next_text' => esc_html__( 'Next', 'cariera' ),
				'type'      => 'list',
			);

			echo paginate_links( $args );
			?>
		</div>
		<?php
	}
}





/*
=====================================================
    BLOG POST THUMBNAIL
=====================================================
*/

/**
 * Post Thumbnail
 *
 * @since  1.3.3
 */

if (!function_exists('cariera_post_thumb')) {
    function cariera_post_thumb($args = array()) {
        global $post;
        
        $defaults = array(
            'size' => 'large',
            'class' => 'post-image',
        );
        
        $args = wp_parse_args($args, $defaults);
        $post_format = get_post_format();
        
    
        // Standard or Image Post
        if ( $post_format === false || $post_format == 'standard' || $post_format == 'image' ) {
            if( has_post_thumbnail() ) { ?>
            <!-- Blog Post Thumbnail -->
                <div class="blog-thumbnail mb40">

                    <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                        <?php the_post_thumbnail(); ?>
                    </a>

                </div>
            <?php }
        }

    
        // Gallery Post
        if ( $post_format == 'gallery' ) {
            if ( function_exists( 'cariera_plugin_functions' ) ) {

                $images = rwmb_meta( 'cariera_blog_gallery', 'type=image_advanced&size=blog' );

                if ( !empty($images) ) { ?>
                    <!-- Blog Post Gallery Thumbnail -->
                    <div class="gallery-post-wrapper">
                        <div class="gallery-post mb40">

                            <?php foreach ( $images as $image ) {
                                echo '<div class="item"><img src="' . esc_url($image["url"]) . '" alt="' . esc_attr($image["alt"]) . '" width="' . esc_attr($image["width"]) . '" height="' . esc_attr($image["height"]) . '"/></div>';
                            } ?>

                        </div>
                    </div>
                <?php }
            }
        }
        
        
        // Video Post
        if ( $post_format == 'video' ) {
            $video = get_post_meta($post->ID, 'cariera_blog_video_embed', true);
            if ($video !== '') { ?>
                <!-- Embed Video -->
                <div class="embed-responsive embed-responsive-16by9 mb40">
                    <?php
                      if(wp_oembed_get($video)) { 
                          echo wp_oembed_get($video); 
                      } else {
                        $allowed_tags = wp_kses_allowed_html( 'post' );
                        echo wp_kses($video,$allowed_tags);
                      }
                    ?>
                </div>
            <?php }
        }

        
        // Audio Post
        if ( $post_format == 'audio' ) { 
            $audio = get_post_meta($post->ID, 'cariera_blog_audio', true);
            if ($audio !== '') { ?>
                <!-- Embed Audio -->
                <div class="audio-wrapper mb40">
                    <?php
                    if(wp_oembed_get($audio)) { 
                        echo wp_oembed_get($audio);
                    } else {
                        $allowed_tags = wp_kses_allowed_html( 'post' );
                        echo wp_kses($audio,$allowed_tags);
                    } ?>
                </div>
            <?php }
        }
   

    }
}





/**
 * Single Post Thumbnail
 *
 * @since  1.3.3
 */

if (!function_exists('cariera_single_post_thumb')) {
    function cariera_single_post_thumb() {
        global $post;

        $post_format = get_post_format();
        
        
        // Standard or Image Post
        if ( $post_format == false  || $post_format == 'image' ) {
            if( has_post_thumbnail() ) { ?>
                <!-- Blog Post Thumbnail -->
                <div class="blog-thumbnail">
                    <?php the_post_thumbnail(); ?>
                </div>
            <?php }
        } 
        
        
        // Gallery Post
        if ( $post_format == 'gallery' ) {
            if ( function_exists( 'cariera_plugin_functions' ) ) { ?>
                
                <div class="gallery-post mb40">
                    <?php $images = rwmb_meta( 'cariera_blog_gallery', 'type=image_advanced&size=blog' );

                    foreach ( $images as $image ) {
                        echo '<div class="item"><img src="' . esc_url($image["url"]) . '" alt="' . esc_attr($image["alt"]) . '" width="' . esc_attr($image["width"]) . '" height="' . esc_attr($image["height"]) . '"/></div>';
                    } ?>
                </div>
            <?php 
            }
        }
        
        
        // Quote Post
        if ( $post_format == 'quote' ) {
            $quote_content = get_post_meta($post->ID, 'cariera_blog_quote_content', TRUE);
            $quote_author  = get_post_meta($post->ID, 'cariera_blog_quote_author', TRUE);
            $quote_source  = get_post_meta($post->ID, 'cariera_blog_quote_source', TRUE);
            $allowed_tags = wp_kses_allowed_html( 'post' ); 

            if (!empty($quote_content) && !empty($quote_author) ) { ?>
                <!-- Blog Post Quote -->
                <figure class="post-quote mb40">
                    <span class="icon"></span>
                    <blockquote>

                        <h4><?php echo esc_html( $quote_content ); ?></h4>

                        <?php if(!empty($quote_source)) { ?>
                            <a href="<?php echo esc_url( $quote_source ); ?>">
                        <?php } ?>
                                <h6 class="pt20"><?php echo esc_html('- '); echo wp_kses($quote_author,$allowed_tags); ?></h6>
                        <?php if(!empty($quote_source)) { ?>
                            </a> 
                        <?php } ?>

                    </blockquote>
                </figure>
            <?php }
        } 
        
        
        // Audio Post
        if ( $post_format == 'audio' ) {
            $audio = get_post_meta($post->ID, 'cariera_blog_audio', true); 
            if (!empty($audio)) { ?>

                <!-- Embed Audio -->
                <div class="audio-wrapper mb40">
                    <?php if(wp_oembed_get($audio)) { 
                            echo wp_oembed_get($audio);
                        } else {
                            $allowed_tags = wp_kses_allowed_html( 'post' );
                            echo wp_kses($audio,$allowed_tags);
                        } 
                    ?>
                </div>
            <?php }
        }
        
        
        // Video Post
        if ( $post_format == 'video' ) {
            $video_embed = get_post_meta( $post->ID, 'cariera_blog_video_embed', true );
            if ( !empty($video_embed) ) { ?>

                <!-- Embed Video -->
                <div class="embed-responsive embed-responsive-16by9 mb40">
                    <?php if(wp_oembed_get($video_embed)) { 
                        echo wp_oembed_get($video_embed); 
                    } else {
                        $allowed_tags = wp_kses_allowed_html( 'post' );
                        echo wp_kses( $video_embed, $allowed_tags );
                    } ?>
                </div>

            <?php }
        }

    }
}





/* 
=====================================================
    BLOG LOOP CUSTOM FUNCTION
=====================================================
*/

/**
 *  Meta informations for blog posts
 *
 * @since  1.0
 */
function cariera_posted_meta() {
	echo '<div class="meta-tags">';

	if(is_single()) {
	    $metas =  cariera_get_option( 'cariera_meta_single' );
        
	    if (in_array("author", $metas)) {
	        echo '<span class="author"><i class="far fa-keyboard"></i>';
	        echo esc_html__('By','cariera') . ' <a class="author-link" rel="author" href="' . esc_url(get_author_posts_url(get_the_author_meta( 'ID' ))) . '">'; the_author_meta('display_name'); echo'</a>';
	        echo '</span>';
	    }
	    if (in_array("date", $metas)) {
		    $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
			if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
				$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
			}

			$time_string = sprintf( $time_string,
				esc_attr( get_the_date( 'c' ) ),
				esc_html( get_the_date() ),
				esc_attr( get_the_modified_date( 'c' ) ),
				esc_html( get_the_modified_date() )
			);

		    echo '<span class="published"><i class="far fa-clock"></i>' . $time_string . '</span>';
		    
		}
	    if (in_array("cat", $metas)) {
	      if(has_category()) { echo '<span class="category"><i class="far fa-folder-open"></i>'; the_category(', '); echo '</span>'; }
	    }
	    if (in_array("tags", $metas)) {
	      if(has_tag()) { echo '<span class="tags"><i class="fas fa-tags"></i>'; the_tags('',', '); echo '</span>'; }
	    }
	    if (in_array("com", $metas)) {
	      echo '<span class="comments"><i class="far fa-comment"></i>'; comments_popup_link( esc_html__('0 comments','cariera'), esc_html__('1 comment','cariera'), esc_html__('% comments','cariera'), 'comments-link', esc_html__('Comments are off','cariera')); echo '</span>';
	    }
        
  	} else {
	    $metas =  cariera_get_option( 'cariera_blog_meta' );
        
        if (in_array("author", $metas)) {
	        echo '<span class="author"><i class="far fa-keyboard"></i>';
	        echo esc_html__('By','cariera') . ' <a class="author-link" rel="author" href="'.esc_url(get_author_posts_url(get_the_author_meta('ID' ))).'">'; the_author_meta('display_name'); echo'</a>';
	        echo '</span>';
	    }
	    if (in_array("date", $metas)) {
		    $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
			if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
				$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
			}

			$time_string = sprintf( $time_string,
				esc_attr( get_the_date( 'c' ) ),
				esc_html( get_the_date() ),
				esc_attr( get_the_modified_date( 'c' ) ),
				esc_html( get_the_modified_date() )
			);

		    echo '<span class="published"><i class="far fa-clock"></i>' . $time_string . '</span>';
		    
		}
	    if (in_array("cat", $metas)) {
	      if(has_category()) { echo '<span class="category"><i class="far fa-folder-open"></i>'; the_category(', '); echo '</span>'; }
	    }
	    if (in_array("tags", $metas)) {
	      if(has_tag()) { echo '<span class="tags"><i class="fas fa-tags"></i>'; the_tags('',', '); echo '</span>'; }
	    }
	    if (in_array("com", $metas)) {
	      echo '<span class="comments"><i class="far fa-comment"></i>'; comments_popup_link( esc_html__('0 comments','cariera'), esc_html__('1 comment','cariera'), esc_html__('% comments','cariera'), 'comments-link', esc_html__('Comments are off','cariera')); echo '</span>';
	    }
	   
  	}
	echo "</div>";
}





/* 
=====================================================
    SINGLE POST CUSTOM FUNCTIONS
=====================================================
*/

/**
 *  Display navigation to next/previous set of posts when applicable.
 *
 * @since  1.0
 */
function cariera_get_post_navigation(){
    require_once( get_template_directory() . '/templates/extra/post-nav.php' );
}



/**
 *  Display comment navigation.
 *
 * @since  1.0
 */
function cariera_get_comment_navigation(){
    if( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ):
		require_once( get_template_directory() . '/templates/comments/comment-nav.php' );
	endif;
}




/**
 *  Add support for Vertical Featured Images.
 *
 * @since  1.0
 */
if ( !function_exists('cariera_vertical_featured_image') ) {
	function cariera_vertical_featured_image( $html, $post_id, $post_thumbnail_id, $size, $attr ) {
		$image_data = wp_get_attachment_image_src( $post_thumbnail_id , 'large' );
        
		//Get the image width and height from the data provided by wp_get_attachment_image_src()
		$width  = $image_data[1];
		$height = $image_data[2];
        
		if ( $height > $width ) {
			$html = str_replace( 'attachment-', 'vertical-image attachment-', $html );
		}
		return $html;
	}
    
    add_filter( 'post_thumbnail_html', 'cariera_vertical_featured_image', 10, 5 );
}



/* 
=====================================================
    COMMENT FUNCTIONS
=====================================================
*/

/**
 * Comment Callback Function.
 *
 * @since 1.0.0
 */
if ( !function_exists('cariera_comment') ) {
    require_once( get_template_directory() . '/templates/comments/comments.php' );
} // ends check for cariera_comment()



/**
 * Comment Form.
 *
 * @since   1.0.0
 * @version 1.5.1
 */
function cariera_comment_form($args) {

	$commenter      = wp_get_current_commenter();
	$current_user   = wp_get_current_user();
	$req            = get_option( 'require_name_email' );
	$aria_req       = ( $req ? " aria-required='true'" : '' );

	$comment_author         = esc_attr( $commenter['comment_author'] );
	$comment_author_email   = esc_attr( $commenter['comment_author_email'] );
	$comment_author_url     = esc_attr( $commenter['comment_author_url'] );

	$name       = ($comment_author) ? '' : esc_html__('Name *','cariera');
	$email      = ($comment_author_email) ? '' : esc_html__('Email *','cariera');
	$website    = ($comment_author_url) ? '' : esc_html__('Website','cariera');

	$fields =  [
	   'author' => '<div class="col-md-6 form-group"><input id="author" name="author" type="text" class="form-control" value="' . esc_attr( $commenter['comment_author'] ) . '" placeholder="' . esc_attr__('Your Name', 'cariera') . '" required="required" /></div>',

        'email' => '<div class="col-md-6 form-group"><input id="email" name="email" class="form-control" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" placeholder="' . esc_attr__('Your Email', 'cariera') . '" required="required" /></div>'     
    ];

	$args = [
        'class_form'            => esc_attr( 'comment-form row' ),
		'title_reply'           => esc_html__( 'Leave a Comment' , 'cariera'),
        'title_reply_to'        => esc_html__('Leave a Reply to %s','cariera'),
        'title_reply_before'    => '<h4 id="reply-title" class="comment-reply-title nomargin">',
		'title_reply_after'     => '</h4>',
        'submit_button'         => '<input name="%1$s" type="submit" id="%2$s" class="%3$s" value="%4$s" />',
        'submit_field'          => '<div class="col-md-12">%1$s %2$s</div>',
        'class_submit'          => esc_attr( 'btn btn-main btn-effect' ),
        'label_submit'          => esc_attr__( 'send comment', 'cariera' ),
        'comment_field'         => '<div class="col-md-12 form-group"><textarea id="comment" class="form-control" name="comment" rows="8" placeholder="' . esc_attr__('Type your comment...', 'cariera') . '" required="required"></textarea></div>',        
        'comment_notes_before'  => '<div class="col-md-12 mb10"><p class="mtb10"><em>' . esc_html__( 'Your email address will not be published.', 'cariera' ) . '</em></p></div>',
        'logged_in_as'          => '<div class="col-md-12"><p class="logged-in-as">' . 
            sprintf(
                esc_html__( 'Logged in as ', 'cariera' ) . '<a href="%1$s">%2$s</a>. <a href="%3$s" title="' . esc_html__( 'Log out of this account', 'cariera' ) . '">' . esc_html__( 'Log out?', 'cariera' ) . '</a>',
                esc_url(admin_url( 'profile.php' )), 
                $current_user->user_login, 
                wp_logout_url( apply_filters( 'the_permalink', get_permalink() ) )
            ) . '</p></div>', 
		'cancel_reply_link'     => esc_html__('Cancel Reply','cariera'),
        'fields'                => apply_filters('comment_form_default_fields', $fields)
        ];

    return $args;
}

add_filter( 'comment_form_defaults', 'cariera_comment_form' );





/**
 * Nav Menu Role workaround.
 *
 * @since 1.2.3
 */
function cariera_navmenu_role_nmr( $walker ) {
    if( function_exists( 'Nav_Menu_Roles' )) {
        $walker = 'Walker_Nav_Menu_Edit_Roles';
    }
    
    return $walker;
}

add_filter( 'wp_edit_nav_menu_walker', 'cariera_navmenu_role_nmr', 999999 );




/**
 * Redirect on logout.
 *
 * @since 1.2.7
 */

function cariera_logout_redirect() {
    wp_redirect( home_url() );
    
    exit;
}

add_action('wp_logout', 'cariera_logout_redirect');








/* 
=====================================================
    ACTIVATION FUNCTIONS
=====================================================
*/



/**
 * Check if Cariera Core plugin is activated or not.
 *
 * @since 1.3.5
 */

if ( ! function_exists( 'cariera_core_is_activated' ) ) {
    function cariera_core_is_activated() {
        return function_exists( 'cariera_plugin_functions' ) ? true : false;
    }
}





/**
 * Check if WooCommerce is activated or not.
 *
 * @since 1.3.0
 */

if ( ! function_exists( 'cariera_wc_is_activated' ) ) {
	function cariera_wc_is_activated() {
		return class_exists( 'WooCommerce' ) ? true : false;
	}
}





/**
 * Check if WP Job Manager is activated or not.
 *
 * @since 1.3.0
 */

if ( ! function_exists( 'cariera_wp_job_manager_is_activated' ) ) {
    function cariera_wp_job_manager_is_activated() {
        return class_exists( 'WP_Job_Manager' ) ? true : false;
    }
}





/**
 * Check if WP Resume Manager is activated or not.
 *
 * @since 1.3.0
 */

if ( ! function_exists( 'cariera_wp_resume_manager_is_activated' ) ) {
    function cariera_wp_resume_manager_is_activated() {
        return class_exists( 'WP_Resume_Manager' ) ? true : false;
    }
}





/**
 * Check if Cariera Company Manager is activated or not.
 *
 * @since 1.3.0
 */

if ( ! function_exists( 'cariera_wp_company_manager_is_activated' ) ) {
    function cariera_wp_company_manager_is_activated() {
        return class_exists( 'Cariera_Company_Manager' ) ? true : false;
    }
}





/*
=====================================================
    PAGE HEADER
=====================================================
*/

/**
 * Fetching the pages titles.
 *
 * @since 1.3.3
 */

if (!function_exists('cariera_get_the_title')) {
    function cariera_get_the_title() {

        // Blog Page
        if ( is_home() ) {
            $blog_title = cariera_get_option('cariera_blog_title');

            return $blog_title;
        }

        // WooCommerce Page
        if ( cariera_wc_is_activated() ) {
            if (is_woocommerce()) {
                if (is_single() && !is_attachment()) {
                    echo get_the_title();
                } elseif (!is_single()) {
                    woocommerce_page_title();
                }

                return;
            }
        }

        // 404 Page
        if (is_404()) {
            return esc_html__('Error 404', 'cariera');
        }

        // Homepage and Single Page
        if (is_home() || is_single() || is_404()) {
            return get_the_title();
        }

        // Search Page
        if (is_search()) {
            return sprintf( esc_html__( 'Search Results for: %s', 'cariera' ), '<span>' . get_search_query() . '</span>' );
        }

        // Archive Pages
        if (is_archive()) {
            if (is_author()) {
                return sprintf( esc_html__( 'All posts by %s', 'cariera'), get_the_author() );
            } elseif (is_day()) {
                return sprintf( esc_html__( 'Day: %s', 'cariera'), get_the_date() );
            } elseif (is_month()) {
                return sprintf( esc_html__('Month: %s', 'cariera'), get_the_date(_x('F Y', 'monthly archives date format', 'cariera')) );
            } elseif (is_year()) {
                return sprintf( esc_html__('Year: %s', 'cariera'), get_the_date(_x('Y', 'yearly archives date format', 'cariera')) );
            } elseif (is_tag()) {
                return sprintf( esc_html__('Tag: %s', 'cariera'), single_tag_title('', false) );
            } elseif (is_category()) {
                return sprintf( esc_html__('Category: %s', 'cariera'), single_cat_title('', false) );
            } elseif (is_tax('post_format', 'post-format-aside')) {
                return esc_html__('Asides', 'cariera');
            } elseif (is_tax('post_format', 'post-format-video')) {
                return esc_html__('Videos', 'cariera');
            } elseif (is_tax('post_format', 'post-format-audio')) {
                return esc_html__('Audio', 'cariera');
            } elseif (is_tax('post_format', 'post-format-quote')) {
                return esc_html__('Quotes', 'cariera');
            } elseif (is_tax('post_format', 'post-format-gallery')) {
                return esc_html__('Galleries', 'cariera');
            } else {
                return esc_html__('Archives', 'cariera');
            }
        }

        return get_the_title();
    }
}





/*
=====================================================
    COOKIE BAR - LAW INFO
=====================================================
*/

/**
 * Cookie Law Info
 *
 * @since  1.3.0
 */

if( ! function_exists( 'cariera_cookie_bar' ) ) {
	function cariera_cookie_bar() {

        if( cariera_get_option( 'cariera_cookie_notice' ) == '0' ) {
            return;
        }
        
        $text_msg       = cariera_get_option( 'cariera_notice_message' );
		$policy_page    = cariera_get_option( 'cariera_policy_page' ); ?>
        
        <div class="cariera-cookies-bar">
            <div class="cariera-cookies-inner">
                <div class="cookies-info-text">
                    <?php echo esc_html($text_msg); ?>
                </div>
                <div class="cookies-buttons">
                    <a href="#" class="btn btn-main cookies-accept-btn"><?php esc_html_e( 'Accept' , 'cariera' ); ?></a>
                    <?php if( $policy_page ) { ?>
                        <a href="<?php echo esc_url(get_page_link($policy_page)); ?>" class="cariera-more-btn" target="_blank">
                            <?php esc_html_e( 'More info' , 'cariera' ); ?>
                        </a>
                    <?php } ?>
                </div>
            </div>
        </div>
		<?php

	}    
    
	add_action( 'wp_footer', 'cariera_cookie_bar' );
}





/*
=====================================================
    EXTRA FUNCTIONS
=====================================================
*/

/**
 * Clean variables using sanitize_text_field
 *
 * @since  1.3.0
 */

if ( ! function_exists( 'cariera_clean' ) ) {
    function cariera_clean( $var ) {
        if ( is_array( $var ) ) {
            return array_map( 'cariera_clean', $var );
        } else {
            return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
        }
    }
}





/*
 * Disable WooCommerce page creation on first activate
 *
 * @since 1.5.0
 */
function cariera_disable_wc_page_creation() {
    $pages = [];

    return $pages;
}

add_filter( 'woocommerce_create_pages', 'cariera_disable_wc_page_creation' );