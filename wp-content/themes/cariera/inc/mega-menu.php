<?php
/**
*
* @package Cariera
*
* @since 1.3.5
*
* ========================
* MEGA MENU FUNTIONALITY
* ========================
*
**/



if ( ! defined('ABSPATH') ) {
	exit;
}



/*
=====================================================
CUSTOM WALKER CLASS FOR THE WP_NAV_MENU
=====================================================
*/
class Cariera_Mega_Menu_Walker extends Walker_Nav_Menu {

    /**
     * What the class handles.
     *
     * @see Walker::$tree_type
     * @since 3.0.0
     * @var string
     */
    var $tree_type = array( 'post_type', 'taxonomy', 'custom' );

    /**
     * Database fields to use.
     *
     * @see Walker::$db_fields
     * @since 3.0.0
     * @todo Decouple this.
     * @var array
     */
    var $db_fields = array( 'parent' => 'menu_item_parent', 'id' => 'db_id' );

    //save current item so it can be used in start level
    private $curItem;
    private $curLvl;
    protected $megamenu = false;



    /**
     * Starts the list before the elements are added.
     *
     * @see Walker::start_lvl()
     *
     * @since 3.0.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param int    $depth  Depth of menu item. Used for padding.
     * @param array  $args   An array of arguments. @see wp_nav_menu()
     */
    function start_lvl( &$output, $depth = 0, $args = array() ) {
        $indent         = str_repeat("\t", $depth);

        $submenu        = ($depth > 0) ? esc_attr('sub-menu') : '';
        $megamenu_width = get_post_meta( $this->curItem->ID, '_menu-item-megamenuwidth', true);
        $style          = '';

        if ( $megamenu_width ) {
			$style = 'style="width:' . esc_attr( $megamenu_width ) . '"';
		}

        if ( ! $this->megamenu ) {
			$output .= "\n$indent<ul class=\"dropdown-menu $submenu depth_$depth\">\n";
		} else {
			if ( $depth == 0 ) {
				$output .= "\n$indent<ul class=\"dropdown-menu\" $style>\n$indent<li>\n$indent<div class=\"mega-menu-inner\">\n$indent<div class=\"row\">\n";
			} elseif ( $depth == 1 ) {
				$output .= "\n$indent<div class=\"mega-menu-submenu\"><ul class=\"sub-menu check\">\n";
			} else {
				$output .= "\n$indent<ul class=\"sub-menu check\">\n";
			}
		}
    }





    /**
     * Ends the list of after the elements are added.
     *
     * @see Walker::end_lvl()
     *
     * @since 3.0.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param int    $depth  Depth of menu item. Used for padding.
     * @param array  $args   An array of arguments. @see wp_nav_menu()
     */
    function end_lvl( &$output, $depth = 0, $args = array() ) {
        $indent     = str_repeat("\t", $depth);

        if ( ! $this->megamenu ) {
			$output .= "\n$indent</ul>\n";
		} else {
			if ( $depth == 0 ) {
				$output .= "\n$indent</div>\n$indent</div>\n$indent</li>\n$indent</ul>\n";
			} elseif ( $depth == 1 ) {
				$output .= "\n$indent</ul>\n$indent</div>";
			} else {
				$output .= "\n$indent</ul>\n";
			}
		}
    }





    /**
     * Start the element output.
     *
     * @see Walker::start_el()
     *
     * @since 3.0.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param object $item   Menu item data object.
     * @param int    $depth  Depth of menu item. Used for padding.
     * @param array  $args   An array of arguments. @see wp_nav_menu()
     * @param int    $id     Current item ID.
     */
    function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

        //save current item to private curItem to use it in start_lvl
        $this->curItem = $item;

        $class_names    = '';
        $classes        = empty( $item->classes ) ? array() : (array) $item->classes;
        $classes[]      = 'menu-item-' . $item->ID;
        $classes[]      = 'parentid_' . get_post_meta( $item->ID,  '_menu_item_menu_item_parent', true);
        $item_is_mega   = apply_filters( 'cariera_menu_item_mega', get_post_meta( $item->ID, '_menu-item-megamenu', true ), $item->ID );

        $args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );



        /**
         * Filter the CSS class(es) applied to a menu item's <li>.
         *
         * @since 3.0.0
         *
         * @see wp_nav_menu()
         *
         * @param array  $classes The CSS classes that are applied to the menu item's <li>.
         * @param object $item    The current menu item.
         * @param array  $args    An array of wp_nav_menu() arguments.
         */

        $hidden_status = get_post_meta( $item->ID, '_menu-item-hiddenonmobile', true);

        if( $hidden_status == 'hide' ) {
            $classes[] = 'hide-on-mobile';
        }


        /**
		 * Check if this is top level and is mega menu
		 */
		if ( ! $depth ) {
			$this->megamenu = $item_is_mega;
		}


        /**
		 * Add active class for current menu item
		 */
		$active_classes = array(
			'current-menu-item',
			'current-menu-parent',
			'current-menu-ancestor',
		);
        $is_active      = array_intersect( $classes, $active_classes );

        if ( ! empty( $is_active ) ) {
			$classes[] = 'active';
		}


        if ( in_array( 'menu-item-has-children', $classes ) ) {
			if ( ! $depth ) {
				$classes[] = 'dropdown';
			}
			if ( ! $depth && $this->megamenu ) {
				$classes[] = 'mega-menu';
			}
			if ( $depth && ! $this->megamenu ) {
				$classes[] = 'dropdown-submenu';
			}
		}

        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
        $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';



        /**
         * Filter the ID applied to a menu item's <li>.
         *
         * @since 3.0.1
         *
         * @see wp_nav_menu()
         *
         * @param string $menu_id The ID that is applied to the menu item's <li>.
         * @param object $item    The current menu item.
         * @param array  $args    An array of wp_nav_menu() arguments.
         */

        $id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
        $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

        //$widthclass = get_post_meta( $item->ID, '_menu-item-columns', true);
        $parent     = get_post_meta( $item->ID, '_menu_item_menu_item_parent', true);
        $widthclass = get_post_meta( $parent, '_menu-item-columns', true);

        if ( $depth == 1 && $this->megamenu ) {
			$output .= $indent . '<div' . $id . ' class="col-md-' . $widthclass . '">' . "\n";
			$output .= $indent . '<div class="menu-item-mega">';

		} else {
			$output .= $indent . '<li' . $id . $class_names . '>';
		}

        $atts = array();
        $atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
        $atts['target'] = ! empty( $item->target )     ? $item->target     : '';
        $atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
        $atts['href']   = ! empty( $item->url )        ? $item->url        : '';

        $atts['class'] = '';

		/**
		 * Add attributes for menu item link when this is not mega menu item
		 */
		if ( in_array( 'menu-item-has-children', $classes ) ) {
			$atts['class']         = 'dropdown-toggle';
			$atts['role']          = 'button';
			$atts['data-toggle']   = 'dropdown';
			$atts['aria-haspopup'] = 'true';
			$atts['aria-expanded'] = 'false';
		}



        /**
         * Filter the HTML attributes applied to a menu item's <a>.
         *
         * @since 3.6.0
         *
         * @see wp_nav_menu()
         *
         * @param array $atts {
         *     The HTML attributes applied to the menu item's <a>, empty strings are ignored.
         *
         *     @type string $title  Title attribute.
         *     @type string $target Target attribute.
         *     @type string $rel    The rel attribute.
         *     @type string $href   The href attribute.
         * }
         * @param object $item The current menu item.
         * @param array  $args An array of wp_nav_menu() arguments.
         */

        $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );

        $attributes = '';
        foreach ( $atts as $attr => $value ) {
            if ( ! empty( $value ) ) {
                $value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        $title = apply_filters( 'the_title', $item->title, $item->ID );



        /**
		 * Filter a menu item's title.
		 *
		 * @since 4.4.0
		 *
		 * @param string $title The menu item's title.
		 * @param object $item  The current menu item.
		 * @param array  $args  An array of {@see wp_nav_menu()} arguments.
		 * @param int    $depth Depth of menu item. Used for padding.
		 */

		$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

        // Assign badges to the menu item if selected
        $badge = get_post_meta( $item->ID, '_menu-item-badge', true);

        if( $badge == 'no-badge') {
            $badge = '';
        } elseif( $badge == 'new-badge') {
            $badge = '<span class="items-badge"><span class="new-badge">' . esc_html__( 'New', 'cariera' ) . '</span></span>';
        } elseif( $badge == 'hot-badge') {
            $badge = '<span class="items-badge"><span class="hot-badge">' . esc_html__( 'Hot', 'cariera' ) . '</span></span>';
        } elseif( $badge == 'trending-badge') {
            $badge = '<span class="items-badge"><span class="trending-badge">' . esc_html__( 'Trending', 'cariera' ) . '</span></span>';
        } else {
            $badge = '';
        }
        
        
        
        // CUSTOM ICONS FOR MENU
        $icons  = get_post_meta( $item->ID, '_menu-item-icons', true); 
        $icon   = get_post_meta( $item->ID, '_menu-item-icon', true); 
        
        if( $icons ) {
            $icon = '<i class="' . esc_attr($icon) . '"></i>';
        } else {
            $icon = '';
        }
        
        


        if ( $depth == 1 && $this->megamenu ) {
			$item_output = '<a ' . $attributes . '>' . $icon . $title . $badge . '</a>';
		} else {
			$item_output = $args->before;
			$item_output .= '<a' . $attributes . '>';
			$item_output .= $args->link_before . $icon . $title . $badge . $args->link_after; //. implode( $badge );
			$item_output .= '</a>';
			$item_output .= $args->after;
		}



        /**
         * Filter a menu item's starting output.
         *
         * The menu item's starting output only includes $args->before, the opening <a>,
         * the menu item's title, the closing </a>, and $args->after. Currently, there is
         * no filter for modifying the opening and closing <li> for a menu item.
         *
         * @since 3.0.0
         *
         * @see wp_nav_menu()
         *
         * @param string $item_output The menu item's starting HTML output.
         * @param object $item        Menu item data object.
         * @param int    $depth       Depth of menu item. Used for padding.
         * @param array  $args        An array of wp_nav_menu() arguments.
         */

        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }





    /**
     * Ends the element output, if needed.
     *
     * @see Walker::end_el()
     *
     * @since 3.0.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param object $item   Page data object. Not used.
     * @param int    $depth  Depth of page. Not Used.
     * @param array  $args   An array of arguments. @see wp_nav_menu()
     */

    function end_el( &$output, $item, $depth = 0, $args = array() ) {
        if ( $depth == 1 && $this->megamenu ) {
			$output .= "</div>\n";
			$output .= "</div>\n";
		} else {
			$output .= "</li>\n";
		}
    }

}





/*
=====================================================
BACKEND MENU OPTIONS
=====================================================
*/

class cariera_walker_nav_edit extends Walker_Nav_Menu {

    /**
     * @see Walker_Nav_Menu::start_lvl()
     * @since 3.0.0
     *
     * @param string $output Passed by reference.
     */
    public function start_lvl( &$output, $depth = 0, $args = null ) {}



    /**
     * @see Walker_Nav_Menu::end_lvl()
     * @since 3.0.0
     *
     * @param string $output Passed by reference.
     */
    public function end_lvl( &$output, $depth = 0, $args = null ) {}



    /**
     * @see Walker::start_el()
     * @since 3.0.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param object $item Menu item data object.
     * @param int $depth Depth of menu item. Used for padding.
     * @param object $args
     */

    public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        global $_wp_nav_menu_max_depth;
        $_wp_nav_menu_max_depth = $depth > $_wp_nav_menu_max_depth ? $depth : $_wp_nav_menu_max_depth;

        ob_start();
        $item_id = esc_attr( $item->ID );
        $removed_args = array(
            'action',
            'customlink-tab',
            'edit-menu-item',
            'menu-item',
            'page-tab',
            '_wpnonce',
        );


        $original_title = false;
        if ( 'taxonomy' == $item->type ) {
            $original_title = get_term_field( 'name', $item->object_id, $item->object, 'raw' );
            if ( is_wp_error( $original_title ) ) {
                $original_title = false;
            }
        } elseif ( 'post_type' == $item->type ) {
            $original_object = get_post( $item->object_id );
            $original_title = get_the_title( $original_object->ID );
        } elseif ( 'post_type_archive' == $item->type ) {
            $original_object = get_post_type_object( $item->object );
            if ( $original_object ) {
                $original_title = $original_object->labels->archives;
            }
        }

        $classes = array(
            'menu-item menu-item-depth-' . $depth,
            'menu-item-' . esc_attr( $item->object ),
            'menu-item-edit-' . ( ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? 'active' : 'inactive'),
        );

        $title = $item->title;

        if ( ! empty( $item->_invalid ) ) {
            $classes[] = 'menu-item-invalid';
            /* translators: %s: title of menu item which is invalid */
            $title = sprintf( esc_html__( '%s (Invalid)', 'cariera' ), $item->title );
        } elseif ( isset( $item->post_status ) && 'draft' == $item->post_status ) {
            $classes[] = 'pending';
            /* translators: %s: title of menu item in draft status */
            $title = sprintf( esc_html__( '%s (Pending)', 'cariera' ), $item->title );
        }

        $title = ( ! isset( $item->label ) || '' == $item->label ) ? $title : $item->label;

        $submenu_text = '';
        if ( 0 == $depth ) {
            $submenu_text = 'style="display: none;"';
        } ?>


        <li id="menu-item-<?php echo esc_attr($item_id); ?>" class="<?php echo implode(' ', $classes ); ?>">
            <div class="menu-item-bar">
                <div class="menu-item-handle">
                    <span class="item-title"><span class="menu-item-title"><?php echo esc_html( $title ); ?></span> <span class="is-submenu" <?php echo esc_attr($submenu_text); ?>><?php esc_html_e( 'sub item', 'cariera' ); ?></span></span>
                    <span class="item-controls">
                        <span class="item-type"><?php echo esc_html( $item->type_label ); ?></span>
                        <span class="item-order hide-if-js">
                            <?php
                            printf(
                                '<a href="%s" class="item-move-up" aria-label="%s">&#8593;</a>',
                                wp_nonce_url(
                                    add_query_arg(
                                        array(
                                            'action'    => 'move-up-menu-item',
                                            'menu-item' => $item_id,
                                        ),
                                        remove_query_arg( $removed_args, admin_url( 'nav-menus.php' ) )
                                    ),
                                    'move-menu_item'
                                ),
                                esc_attr__( 'Move up', 'cariera' )
                            );
                            ?>
                            |
                            <?php
                            printf(
                                '<a href="%s" class="item-move-down" aria-label="%s">&#8595;</a>',
                                wp_nonce_url(
                                    add_query_arg(
                                        array(
                                            'action'    => 'move-down-menu-item',
                                            'menu-item' => $item_id,
                                        ),
                                        remove_query_arg( $removed_args, admin_url( 'nav-menus.php' ) )
                                    ),
                                    'move-menu_item'
                                ),
                                esc_attr__( 'Move down', 'cariera' )
                            );
                            ?>
                        </span>
                        <?php
						if ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) {
							$edit_url = admin_url( 'nav-menus.php' );
						} else {
							$edit_url = add_query_arg(
								array(
									'edit-menu-item' => $item_id,
								),
								remove_query_arg( $removed_args, admin_url( 'nav-menus.php#menu-item-settings-' . $item_id ) )
							);
						}

						printf(
							'<a class="item-edit" id="edit-%s" href="%s" aria-label="%s"><span class="screen-reader-text">%s</span></a>',
							$item_id,
							$edit_url,
							esc_attr__( 'Edit menu item', 'cariera' ),
							esc_html__( 'Edit', 'cariera' )
						);
						?>
                    </span>
                </div>
            </div>


            <div class="menu-item-settings wp-clearfix" id="menu-item-settings-<?php echo esc_attr($item_id); ?>">
                <?php if ( 'custom' == $item->type ) { ?>
                    <p class="field-url description description-wide">
                        <label for="edit-menu-item-url-<?php echo esc_attr($item_id); ?>">
                            <?php esc_html_e( 'URL', 'cariera' ); ?><br />
                            <input type="text" id="edit-menu-item-url-<?php echo esc_attr($item_id); ?>" class="widefat code edit-menu-item-url" name="menu-item-url[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->url ); ?>" />
                        </label>
                    </p>
                <?php } ?>

                <p class="description description-wide">
                    <label for="edit-menu-item-title-<?php echo esc_attr($item_id); ?>">
                        <?php esc_html_e( 'Navigation Label', 'cariera' ); ?><br />
                        <input type="text" id="edit-menu-item-title-<?php echo esc_attr($item_id); ?>" class="widefat edit-menu-item-title" name="menu-item-title[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->title ); ?>" />
                    </label>
                </p>

                <p class="field-title-attribute field-attr-title description description-wide">
                    <label for="edit-menu-item-attr-title-<?php echo esc_attr($item_id); ?>">
                        <?php esc_html_e( 'Title Attribute', 'cariera' ); ?><br />
                        <input type="text" id="edit-menu-item-attr-title-<?php echo esc_attr($item_id); ?>" class="widefat edit-menu-item-attr-title" name="menu-item-attr-title[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->post_excerpt ); ?>" />
                    </label>
                </p>

                <p class="field-link-target description">
                    <label for="edit-menu-item-target-<?php echo esc_attr($item_id); ?>">
                        <input type="checkbox" id="edit-menu-item-target-<?php echo esc_attr($item_id); ?>" value="_blank" name="menu-item-target[<?php echo esc_attr($item_id); ?>]"<?php checked( $item->target, '_blank' ); ?> />
                        <?php esc_html_e( 'Open link in a new tab', 'cariera' ); ?>
                    </label>
                </p>

                <p class="field-css-classes description description-thin">
                    <label for="edit-menu-item-classes-<?php echo esc_attr($item_id); ?>">
                        <?php esc_html_e( 'CSS Classes (optional)', 'cariera' ); ?><br />
                        <input type="text" id="edit-menu-item-classes-<?php echo esc_attr($item_id); ?>" class="widefat code edit-menu-item-classes" name="menu-item-classes[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( implode(' ', $item->classes ) ); ?>" />
                    </label>
                </p>

                <p class="field-xfn description description-thin">
                    <label for="edit-menu-item-xfn-<?php echo esc_attr($item_id); ?>">
                        <?php esc_html_e( 'Link Relationship (XFN)', 'cariera' ); ?><br />
                        <input type="text" id="edit-menu-item-xfn-<?php echo esc_attr($item_id); ?>" class="widefat code edit-menu-item-xfn" name="menu-item-xfn[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->xfn ); ?>" />
                    </label>
                </p>

                <p class="field-description description description-wide">
                    <label for="edit-menu-item-description-<?php echo esc_attr($item_id); ?>">
                        <?php esc_html_e( 'Description', 'cariera' ); ?><br />
                        <textarea id="edit-menu-item-description-<?php echo esc_attr($item_id); ?>" class="widefat edit-menu-item-description" rows="3" cols="20" name="menu-item-description[<?php echo esc_attr($item_id); ?>]"><?php echo esc_html( $item->description ); // textarea_escaped ?></textarea>
                        <span class="description"><?php esc_html_e( 'The description will be displayed in the menu if the current theme supports it.', 'cariera' ); ?></span>
                    </label>
                </p>

                <?php do_action( 'wp_nav_menu_item_custom_fields', $item_id, $item, $depth, $args ); ?>
                
                <!-- Custom Menu Icon Enabler -->
                <p class="field-menu-columns description description-wide">
                    <label for="edit-menu-item-icons-<?php echo esc_attr($item_id); ?>">
                        <?php
                        $statuscheckbox='';
                        $icons = get_post_meta( $item->ID, '_menu-item-icons', true);
                        if( $icons != '' ) {
                            $statuscheckbox = "checked='checked'";
                        } ?>

                        <input type="checkbox" id="edit-menu-item-icons-<?php echo esc_attr($item_id); ?>" value="_blank" name="menu-item-icons[<?php echo esc_attr($item_id); ?>]"<?php echo esc_attr($statuscheckbox); ?> />
                        <?php esc_html_e( 'Enable Icons', 'cariera' ); ?>
                    </label>
                </p>
                
                <!-- Custom Menu Icon Picker -->
                <p class="field-menu-columns description description-wide">
                    <?php $icon = get_post_meta( $item->ID, '_menu-item-icon', true); ?>
                    <label for="edit-menu-item-icon-<?php echo esc_attr($item_id); ?>"><?php esc_html_e( 'Icon', 'cariera' ); ?>
                        <select id="edit-menu-item-icon-<?php echo esc_attr($item_id); ?>" class="cariera-icon-select widefat edit-menu-item-icon" name="menu-item-icon[<?php echo esc_attr($item_id); ?>]">
                            <?php
                            // Fontawesome icons
                            $fa_icons = cariera_fontawesome_icons_list();
                            foreach ( $fa_icons as $key => $value ) {
                                echo '<option value="' . $key . '" ';
                                if( isset($icon) && $icon == $key ) { 
                                    echo ' selected="selected"';
                                }
                                echo '>' . $value . '</option>';
                            }

                            // Simpleline icons
                            $sl_icons = cariera_simpleline_icons_list();
                            foreach ( $sl_icons as $key => $value ) {
                                echo '<option value="icon-' . $key . '" ';
                                if( isset($icon) && $icon == 'icon-' . $key ) { 
                                    echo ' selected="selected"';
                                }
                                echo '>' . $value . '</option>';
                            }
                            
                            // Iconsmind icons
                            if ( get_option('cariera_font_iconsmind') ) {
                                $im_icons = cariera_iconsmind_list();
                                foreach ($im_icons as $key ) {
                                    echo '<option value="iconsmind-' . $key . '" ';
                                    if( isset($icon) && $icon == 'iconsmind-' . $key ) { 
                                        echo ' selected="selected"';
                                    }
                                    echo '>' . $key . '</option>';
                                }
                            } ?>
                        </select>
                    </label>
                </p>
                
                <!-- Mega Menu Elements -->
                <?php if($depth === 0) { ?>
                    <p class="field-megamenu description description-wide">
                        <label for="edit-menu-item-megamenu-<?php echo esc_attr($item_id); ?>">
                            <?php
                            $statuscheckbox='';
                            $megamenu = get_post_meta( $item->ID, '_menu-item-megamenu', true);
                            if($megamenu != "") {
                                $statuscheckbox = "checked='checked'";
                            } ?>

                            <input type="checkbox" id="edit-menu-item-megamenu-<?php echo esc_attr($item_id); ?>" value="_blank" name="menu-item-megamenu[<?php echo esc_attr($item_id); ?>]"<?php echo esc_attr($statuscheckbox); ?> />
                            <?php esc_html_e( 'Enable megamenu', 'cariera' ); ?>
                        </label>
                    </p>

                    <p class="field-megamenu-width description description-wide">
                        <label for="edit-menu-item-megamenuwidth-<?php echo esc_attr($item_id); ?>">
                            <?php
                            $megamenu_width = get_post_meta( $item->ID, '_menu-item-megamenuwidth', true);
                            esc_html_e( 'Mega Menu Width. For example "55%"', 'cariera' ); ?><br />
                            <input type="text" id="edit-menu-item-megamenuwidth-<?php echo esc_attr($item_id); ?>" class="widefat code edit-menu-item-megamenuwidth" name="menu-item-megamenuwidth[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $megamenu_width ); ?>" />
                        </label>
                    </p>

                    <p class="field-menu-columns description description-wide">
                        <label for="edit-menu-item-columns-<?php echo esc_attr($item_id); ?>"><?php esc_html_e( 'Number of columns', 'cariera' ); ?>
                            <select id="edit-menu-item-columns-<?php echo esc_attr($item_id); ?>" class="widefat edit-menu-item-columns" name="menu-item-columns[<?php echo esc_attr($item_id); ?>]">
                                <?php  $col = get_post_meta( $item->ID, '_menu-item-columns', true); ?>

                                <option value="6" <?php if( $col == '6' ) { echo 'selected'; } ?>><?php esc_html_e( '2 columns', 'cariera' ); ?></option>
                                <option value="4" <?php if( $col == '4' ) { echo 'selected'; } ?>><?php esc_html_e( '3 columns', 'cariera' ); ?></option>
                                <option value="3" <?php if( $col == '3' ) { echo 'selected'; } ?>><?php esc_html_e( '4 columns', 'cariera' ); ?></option>
                            </select>
                        </label>
                    </p>
                <?php } ?>

                <!-- Custom Menu Item Badges -->
                <p class="field-menu-columns description description-wide">
                    <label for="edit-menu-item-badge-<?php echo esc_attr($item_id); ?>"><?php esc_html_e( 'Set a badge for your menu item', 'cariera' ); ?>
                        <select id="edit-menu-item-badge-<?php echo esc_attr($item_id); ?>" class="widefat edit-menu-item-badge" name="menu-item-badge[<?php echo esc_attr($item_id); ?>]">
                            <?php  $badge = get_post_meta( $item->ID, '_menu-item-badge', true); ?>

                            <option value="no-badge" <?php if( $badge == 'no-badge' ) { echo 'selected'; } ?>><?php esc_html_e( 'No Badge', 'cariera' ); ?></option>
                            <option value="new-badge" <?php if( $badge == 'new-badge' ) { echo 'selected'; } ?>><?php esc_html_e( 'New Badge', 'cariera' ); ?></option>
                            <option value="hot-badge" <?php if( $badge == 'hot-badge' ) { echo 'selected'; } ?>><?php esc_html_e( 'Hot Badge', 'cariera' ); ?></option>
                            <option value="trending-badge" <?php if( $badge == 'trending-badge' ) { echo 'selected'; } ?>><?php esc_html_e( 'Trending Badge', 'cariera' ); ?></option>
                        </select>
                    </label>
                </p>

                <p class="field-hiddenonmobile description description-wide">
                    <label for="edit-menu-item-hiddenonmobile-<?php echo esc_attr($item_id); ?>">
                        <?php $statuscheckbox=''; $mobilestatus = get_post_meta( $item->ID, '_menu-item-hiddenonmobile', true); if($mobilestatus == "hide") $statuscheckbox = "checked='checked'";?>
                        <input type="checkbox" id="edit-menu-item-hiddenonmobile-<?php echo esc_attr($item_id); ?>" value="hide" name="menu-item-hiddenonmobile[<?php echo esc_attr($item_id); ?>]" <?php echo esc_attr($statuscheckbox); ?> />
                        <?php esc_html_e( 'Hide on mobile navigation', 'cariera' ); ?>
                    </label>
                </p>

                <fieldset class="field-move hide-if-no-js description description-wide">
					<span class="field-move-visual-label" aria-hidden="true"><?php esc_html_e( 'Move', 'cariera' ); ?></span>
					<button type="button" class="button-link menus-move menus-move-up" data-dir="up"><?php esc_html_e( 'Up one', 'cariera' ); ?></button>
					<button type="button" class="button-link menus-move menus-move-down" data-dir="down"><?php esc_html_e( 'Down one', 'cariera' ); ?></button>
					<button type="button" class="button-link menus-move menus-move-left" data-dir="left"></button>
					<button type="button" class="button-link menus-move menus-move-right" data-dir="right"></button>
					<button type="button" class="button-link menus-move menus-move-top" data-dir="top"><?php esc_html_e( 'To the top', 'cariera' ); ?></button>
				</fieldset>

                <div class="menu-item-actions description-wide submitbox">
                    <?php if ( 'custom' != $item->type && $original_title !== false ) : ?>
                        <p class="link-to-original">
                            <?php printf( esc_html__( 'Original: %s', 'cariera' ), '<a href="' . esc_attr( $item->url ) . '">' . esc_html( $original_title ) . '</a>' ); ?>
                        </p>
                    <?php endif; ?>
                    <a class="item-delete submitdelete deletion" id="delete-<?php echo esc_attr($item_id); ?>" href="<?php
                    echo wp_nonce_url(
                        add_query_arg(
                            array(
                                'action' => 'delete-menu-item',
                                'menu-item' => $item_id,
                            ),
                            admin_url( 'nav-menus.php' )
                        ),
                        'delete-menu_item_' . $item_id
                    ); ?>"><?php esc_html_e( 'Remove', 'cariera' ); ?></a> <span class="meta-sep hide-if-no-js"> | </span> <a class="item-cancel submitcancel hide-if-no-js" id="cancel-<?php echo esc_attr($item_id); ?>" href="<?php echo esc_url( add_query_arg( array( 'edit-menu-item' => $item_id, 'cancel' => time() ), admin_url( 'nav-menus.php' ) ) );
                        ?>#menu-item-settings-<?php echo esc_attr($item_id); ?>"><?php esc_html_e( 'Cancel', 'cariera' ); ?></a>
                </div>

                <input class="menu-item-data-db-id" type="hidden" name="menu-item-db-id[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr($item_id); ?>" />
                <input class="menu-item-data-object-id" type="hidden" name="menu-item-object-id[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->object_id ); ?>" />
                <input class="menu-item-data-object" type="hidden" name="menu-item-object[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->object ); ?>" />
                <input class="menu-item-data-parent-id" type="hidden" name="menu-item-parent-id[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->menu_item_parent ); ?>" />
                <input class="menu-item-data-position" type="hidden" name="menu-item-position[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->menu_order ); ?>" />
                <input class="menu-item-data-type" type="hidden" name="menu-item-type[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->type ); ?>" />
            </div><!-- .menu-item-settings-->
            <ul class="menu-item-transport"></ul>
        <?php
        $output .= ob_get_clean();
    }
}





/**
 * Modify backend menu walker if WordPress version is 5.4 or lower
 *
 * @since  1.4.6
 */
function cariera_modify_backend_walker($name) {
    return 'cariera_walker_nav_edit';
}

//if ( ! version_compare( get_bloginfo( 'version' ), '5.4', '>=' ) ) {
    add_filter( 'wp_edit_nav_menu_walker', 'cariera_modify_backend_walker' , 100 );
//}





/*
=====================================================
BACKEND MENU OPTIONS - NEW WP FUNCTIONS
=====================================================
*/

/**
 * Custom menu fields for WordPress version 5.4+
 * 
 * todo: fix this to use the function instead of the extended menu class
 *
 * @since  1.4.6
 */
function cariera_custom_menu_fields( $item_id ) {
    $statuscheckbox = '';
    $icons          = get_post_meta( $item_id, '_menu-item-icons', true );
    if( $icons != '' ) {
        $statuscheckbox = "checked='checked'";
    } ?>

    <p class="field-menu-columns description description-wide">
        <label for="edit-menu-item-icons-<?php echo esc_attr($item_id); ?>">
            <input type="checkbox" id="edit-menu-item-icons-<?php echo esc_attr($item_id); ?>" value="_blank" name="menu-item-icons[<?php echo esc_attr($item_id); ?>]"<?php echo esc_attr($statuscheckbox); ?> />
            <?php esc_html_e( 'Enable Icons', 'cariera' ); ?>
        </label>
    </p>

    <?php
    $icon = get_post_meta( $item_id, '_menu-item-icon', true);
    ?>

    <p class="field-menu-columns description description-wide">
        <label for="edit-menu-item-icon-<?php echo esc_attr($item_id); ?>"><?php esc_html_e( 'Icon', 'cariera' ); ?>
            <select id="edit-menu-item-icon-<?php echo esc_attr($item_id); ?>" class="cariera-icon-select widefat edit-menu-item-icon" name="menu-item-icon[<?php echo esc_attr($item_id); ?>]">
                <?php
                // Fontawesome icons
                $fa_icons = cariera_fontawesome_icons_list();
                foreach ( $fa_icons as $key => $value ) {
                    echo '<option value="' . $key . '" ';
                    if( isset($icon) && $icon == $key ) { 
                        echo ' selected="selected"';
                    }
                    echo '>' . $value . '</option>';
                }

                // Simpleline icons
                $sl_icons = cariera_simpleline_icons_list();
                foreach ( $sl_icons as $key => $value ) {
                    echo '<option value="icon-' . $key . '" ';
                    if( isset($icon) && $icon == 'icon-' . $key ) { 
                        echo ' selected="selected"';
                    }
                    echo '>' . $value . '</option>';
                }
                
                // Iconsmind icons
                if ( get_option('cariera_font_iconsmind') ) {
                    $im_icons = cariera_iconsmind_list();
                    foreach ($im_icons as $key ) {
                        echo '<option value="iconsmind-' . $key . '" ';
                        if( isset($icon) && $icon == 'iconsmind-' . $key ) { 
                            echo ' selected="selected"';
                        }
                        echo '>' . $key . '</option>';
                    }
                } ?>
            </select>
        </label>
    </p>


    <?php
    if( $depth === 0 ) {
        $statuscheckbox = '';
        $megamenu       = get_post_meta( $item_id, '_menu-item-megamenu', true);
        $megamenu_width = get_post_meta( $item_id, '_menu-item-megamenuwidth', true);
        $col            = get_post_meta( $item_id, '_menu-item-columns', true);
        
        if( $megamenu != '' ) {
            $statuscheckbox = "checked='checked'";
        } ?>
        
        <p class="field-megamenu description description-wide">
            <label for="edit-menu-item-megamenu-<?php echo esc_attr($item_id); ?>">
                <input type="checkbox" id="edit-menu-item-megamenu-<?php echo esc_attr($item_id); ?>" value="_blank" name="menu-item-megamenu[<?php echo esc_attr($item_id); ?>]"<?php echo esc_attr( $statuscheckbox ); ?> />
                <?php esc_html_e( 'Enable megamenu', 'cariera' ); ?>
            </label>
        </p>

        <p class="field-megamenu-width description description-wide">
            <label for="edit-menu-item-megamenuwidth-<?php echo esc_attr($item_id); ?>">
                <?php esc_html_e( 'Mega Menu Width. For example "55%"', 'cariera' ); ?><br />
                <input type="text" id="edit-menu-item-megamenuwidth-<?php echo esc_attr($item_id); ?>" class="widefat code edit-menu-item-megamenuwidth" name="menu-item-megamenuwidth[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $megamenu_width ); ?>" />
            </label>
        </p>

        <p class="field-menu-columns description description-wide">
            <label for="edit-menu-item-columns-<?php echo esc_attr($item_id); ?>"><?php esc_html_e( 'Number of columns', 'cariera' ); ?>
                <select id="edit-menu-item-columns-<?php echo esc_attr($item_id); ?>" class="widefat edit-menu-item-columns" name="menu-item-columns[<?php echo esc_attr($item_id); ?>]">
                    <option value="6" <?php if( $col == '6' ) { echo 'selected'; } ?>><?php esc_html_e( '2 columns', 'cariera' ); ?></option>
                    <option value="4" <?php if( $col == '4' ) { echo 'selected'; } ?>><?php esc_html_e( '3 columns', 'cariera' ); ?></option>
                    <option value="3" <?php if( $col == '3' ) { echo 'selected'; } ?>><?php esc_html_e( '4 columns', 'cariera' ); ?></option>
                </select>
            </label>
        </p>
    <?php }

    $badge = get_post_meta( $item_id, '_menu-item-badge', true);
    ?>

    <p class="field-menu-columns description description-wide">
        <label for="edit-menu-item-badge-<?php echo esc_attr($item_id); ?>"><?php esc_html_e( 'Set a badge for your menu item', 'cariera' ); ?>
            <select id="edit-menu-item-badge-<?php echo esc_attr($item_id); ?>" class="widefat edit-menu-item-badge" name="menu-item-badge[<?php echo esc_attr($item_id); ?>]">
                <option value="no-badge" <?php if( $badge == 'no-badge' ) { echo 'selected'; } ?>><?php esc_html_e( 'No Badge', 'cariera' ); ?></option>
                <option value="new-badge" <?php if( $badge == 'new-badge' ) { echo 'selected'; } ?>><?php esc_html_e( 'New Badge', 'cariera' ); ?></option>
                <option value="hot-badge" <?php if( $badge == 'hot-badge' ) { echo 'selected'; } ?>><?php esc_html_e( 'Hot Badge', 'cariera' ); ?></option>
                <option value="trending-badge" <?php if( $badge == 'trending-badge' ) { echo 'selected'; } ?>><?php esc_html_e( 'Trending Badge', 'cariera' ); ?></option>
            </select>
        </label>
    </p>
    


    <?php
    $statuscheckbox = '';
    $mobilestatus   = get_post_meta( $item_id, '_menu-item-hiddenonmobile', true);
    if ( $mobilestatus == 'hide' ) {
        $statuscheckbox = "checked='checked'";
    }
    ?>
    <p class="field-hiddenonmobile description description-wide">
        <label for="edit-menu-item-hiddenonmobile-<?php echo esc_attr($item_id); ?>">
            <input type="checkbox" id="edit-menu-item-hiddenonmobile-<?php echo esc_attr($item_id); ?>" value="hide" name="menu-item-hiddenonmobile[<?php echo esc_attr($item_id); ?>]" <?php echo esc_attr( $statuscheckbox ); ?> />
            <?php esc_html_e( 'Hide on mobile navigation', 'cariera' ); ?>
        </label>
    </p>



<?php
}

//add_action( 'wp_nav_menu_item_custom_fields', 'cariera_custom_menu_field_icons_enable', 10, 2  );





/*
 * Save and Update the Custom Navigation Menu Item Properties by checking all $_POST vars with the name of $check
 * @param int $menu_id
 * @param int $menu_item_db
 */
function cariera_update_menu($menu_id, $menu_item_db) {
    $check = array( 'icons', 'icon', 'megamenu', 'megamenuwidth', 'columns', 'badge', 'hiddenonmobile' );

    foreach ( $check as $key ) {
        if( !isset($_POST['menu-item-' . $key][$menu_item_db]) ) {
            $_POST['menu-item-'.$key][$menu_item_db] = "";
        }

        $value = $_POST['menu-item-' . $key][$menu_item_db];
        update_post_meta( $menu_item_db, '_menu-item-' . $key, $value );
    }
}

add_action( 'wp_update_nav_menu_item', 'cariera_update_menu', 100, 3);
