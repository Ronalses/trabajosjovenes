<?php

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}




vc_map( array(
    'name'           => esc_html__( 'Icon Box', 'cariera' ),
    'description'    => esc_html__( 'Box with Icon and Content.', 'cariera' ),
    'base'           => 'icon_box',
    'class'          => '',
    'category'       => 'Cariera Custom',
    'group'          => 'Cariera Custom',        
    "params"         => array(       
        array(
            "type"			=>	"dropdown",
            "heading"		=>	esc_html__("Display icon", "cariera"),
            "param_name"	=>	"icon_type",
            "value"			=>	array(
                "Icon browser"  => "icon_browser",
                "Custom icon"   => "custom_icon",
                "No icon"       => "no_icon",
            ),
            "save_always"   => true,
            "description"	=>	esc_html__("Select icon source.", "cariera"),
        ),
        array(
            "type"          => "iconpicker",
            "heading"       => esc_html__( "Icon", "cariera" ),
            "param_name"    => "icon_iconsmind",
            "settings"      => array(
                "type"              => "iconsmind",
                "iconsPerPage"      => 50,
            ),
            "dependency"    => array(
                "element"           => "icon_type",
                "value"             => "icon_browser",
            ),
            "description"   => esc_html__( "Select icon from library.", "cariera" ),
        ),
        array(
            "type"          => "colorpicker",
            "heading"       => esc_html__("Icon color", "cariera"),
            "param_name"    => "icon_color",
            "value"         => "",
            "dependency"    =>	array(
                "element"           => "icon_type",
                "value"             => array("icon_browser")
            ),
            "description"   => esc_html__("Choose icon color. If none selected, the default theme color will be used.", "cariera"),
        ),
        array(
            "type"          => "textfield",
            "heading"       => esc_html__("Icon size", "cariera"),
            "param_name"    => "icon_size",
            "value"         => "",
            "dependency"    =>	array(
                "element"           => "icon_type",
                "value"             => array("icon_browser")
            ),
            "description"   => esc_html__("Enter icon size. (eg. 10px, 1em, 1rem)", "cariera"),
        ),
        array(
            "type"          => "attach_image",
            "heading"       => esc_html__("Upload image icon", "cariera"),
            "param_name"    => "icon_img",
            "value"         => "",
            "description"   => esc_html__("Upload your own custom image.", "cariera"),
            "dependency"    => array(
                "element"           => "icon_type",
                "value"             => array("custom_icon"),
            ),
        ),
        
        array(
            "type"			=> "dropdown",
            "admin_label"	=> false,
            "class"			=> "",
            "heading"		=> esc_html__( "Style", 'cariera' ),
            "param_name"	=> "style",
            "value"			=> array(
                'Icon next to Title'        => '1',
                'Icon next to Box'          => '2',
                'Icon above Box'            => '3',
                'Circle Icon above to Box'  => '4',
                'Circle Icon next to Box'   => '5',
                'Flipping Box'              => '6',
                'Description Box'           => '7',
                'Description Box 2'         => '8',
            ),
        ),
        array(
            "type"			=> "textfield",
            "admin_label"	=> false,
            "class"			=> "",
            "heading"		=> esc_html__( "Custom Class", 'cariera' ),
            "param_name"	=> "class",
            "value"			=> "",
            "description"	=> esc_html__( "Use this field to add a custom class.", 'cariera' ),
        ),
        array(
            "type"			=> "textfield",
            "admin_label"	=> true,
            "class"			=> "",
            "heading"		=> esc_html__( "Title", 'cariera' ),
            "param_name"	=> "title",
            "value"			=> "",
            "group"	        => esc_html__( 'Content', 'cariera' ),
        ),
        array(
            "type"			=> "textarea_html",
            "admin_label"	=> false,
            "class"			=> "",
            "heading"		=> esc_html__( "Content", 'cariera' ),
            "param_name"	=> "content",
            "value"			=> "Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.",
            "group"	        => esc_html__( 'Content', 'cariera' ),
        ),
        array(
            "type"			=> "textfield",
            "admin_label"	=> true,
            "class"			=> "",
            "heading"		=> esc_html__( "Iconbox URL (links the complete iconbox to this url)", 'cariera' ),
            "param_name"	=> "url",
            "value"			=> "",
            "description"	=> "Enter URL or leave empty. Please note: This link works only if JavaScript is enabled.",
            "group"	        => esc_html__( 'Content', 'cariera' ),
        ),        
    ),
) );
	

    
    
/*
Shortcode logic how it should be rendered
*/
if ( !function_exists( 'icon_box' ) ) {
	function icon_box( $atts, $content = null ) {
		 $args = array(             
            'icon_type'                 => '',
            'icon_iconsmind'            => '',
            'icon_color'                => '',
            'icon_size'                 => '',
            'icon_img'                  => '',             
			'title'			 => '',
			'textcolor'		 => 'dark',
			'url'			 => '',
			'style'			 => '1',
			'class'			 => ''
        );
	        
		extract(shortcode_atts($args, $atts));
        
        
        
        if($url == '' || $url == 'http://') {
			$link1 = '';
			$link2 = '';
		} else {
			$link1 = '<div onclick="location.href=\''.$url.'\';" style="cursor:pointer;">';
			$link2 = '</div>';
		}
        
        
        $output_css = '';
        
        if ( strlen($icon_iconsmind) !== '' ) {
            $icons = $icon_iconsmind;
        }
        if ( $icon_color !== '' ) {
            $output_css .= 'color: ' . $icon_color . ';';
        }
        if ( $icon_size !== '' ) {
            $output_css .= 'font-size: ' . $icon_size . ';';
        }
        
        
        if ( $icon_type == 'icon_browser' && !empty($icons) ) {
            $symbol = '<i class="' . $icons . ' boxicon" style="' . esc_attr($output_css) . '"></i> ';
        } elseif ( $icon_type == 'custom_icon' && !empty($icon_img) ) {
            $iconbox_img_array = wpb_getImageBySize ( $params = array( 'post_id' => NULL, 'attach_id' => $icon_img, 'thumb_size' => 'full', 'class' => "" ) );
            $symbol = $iconbox_img_array['thumbnail'];
        } else {
            $symbol = '';
        }


        if( $style == '1' ) {
			$output = '<div class="iconbox ' . esc_attr($class) . ' wpb_content_element iconbox-style-' . esc_attr($style) . ' color-' . esc_attr($textcolor) . '"><h3>'.$symbol.'<span>'. esc_html($title) . '</span></h3><p>' . do_shortcode($content) . '</p></div>';
		}
		elseif( $style == '2' ) {
			$output = '<div class="iconbox ' . esc_attr($class) . ' wpb_content_element iconbox-style-' . esc_attr($style) . ' color-' . esc_attr($textcolor) . ' clearfix"><div class="iconbox-icon">' . $symbol . '</div><div class="iconbox-content"><h3>' . esc_html($title) . '</h3><p>' . do_shortcode($content) . '</p></div></div>';
		}
		elseif( $style == '3' ) {
			$output = '<div class="iconbox ' . esc_attr($class) . ' wpb_content_element iconbox-style-' . esc_attr($style) . ' color-' . esc_attr($textcolor) . '">' . $symbol . '<h3>' . esc_html($title) . '</h3><p>' . do_shortcode($content) . '</p></div>';
		}
		elseif( $style == '4' ) {
			$output = '<div class="iconbox ' . esc_attr($class) . ' wpb_content_element iconbox-style-' . esc_attr($style) . ' color-' . esc_attr($textcolor) . ' clearfix"><div class="iconbox-icon">' . $symbol . '</div><div class="iconbox-content"><h3>' . esc_html($title) . '</h3><p>' . do_shortcode($content) . '</p></div></div>';
		}
		elseif( $style == '5' ) {
			$output = '<div class="iconbox ' . esc_attr($class) . ' wpb_content_element iconbox-style-' . esc_attr($style) . ' color-' . esc_attr($textcolor)  . ' clearfix"><div class="iconbox-icon">' . $symbol . '</div><div class="iconbox-content"><h3>' . esc_html($title) . '</h3><p>' . do_shortcode($content) . '</p></div></div>';
		}
		elseif( $style == '6' ) {
			$output = '<div class="flip"><div class="iconbox ' . esc_attr($class) . ' iconbox-style-' . esc_attr($style) . ' color-' . esc_attr($textcolor) . ' card clearfix"><div class="iconbox-box1 face front"><table><tr><td>' . $symbol . '<h3>'. esc_html($title) .'</h3></td></tr></table></div><div class="iconbox-box2 face back"><table><tr><td><h3>' . esc_html($title) . '</h3><p>' . do_shortcode($content) . '</p></td></tr></table></div></div></div>';
		}
		elseif( $style == '7' ) {
			$output = '<div class="iconbox ' . esc_attr($class) . ' iconbox-style-' . esc_attr($style) . ' color-' . esc_attr($textcolor) . ' clearfix">' . $symbol . '<h3>' . esc_html($title) . '</h3><p>' . do_shortcode($content) . '</p></div>';
		}
        elseif( $style == '8' ) {
			$output = '<div class="iconbox ' . esc_attr($class) . ' iconbox-style-' . esc_attr($style) . ' color-' . esc_attr($textcolor) . ' clearfix">' . $symbol . '<h3>' . esc_html($title) . '</h3><p>' . do_shortcode($content) . '</p></div>';
		}
		else{
			$output = '';
		}

		$output = $link1.$output.$link2;
		
		return $output;
	}
}


add_shortcode('icon_box', 'icon_box');