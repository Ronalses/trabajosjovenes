<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.
/**
 *
 * Field: Shortcode
 *
 * @since 1.0.0
 * @version 1.0.0
 */
if ( ! class_exists( 'SPFTESTIMONIAL_Field_shortcode' ) ) {
	class SPFTESTIMONIAL_Field_shortcode extends SPFTESTIMONIAL_Fields {


		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {

			parent::__construct( $field, $value, $unique, $where, $parent );
		}
		public function render() {

			$post_id = get_the_ID();
			echo ( ! empty( $post_id ) ) ? '<div class="spftestimonial-scode-wrap-side"><p>To display your show or view, add the following shortcode into your post, custom post types, page, widget or block editor. If adding the show to your theme files, additionally include the surrounding PHP code, <a href="" target="_blank">see how</a>.‎</p><span class="spftestimonial-shortcode-selectable">[sp_testimonial_form id="' . $post_id . '"]</span></div>' : '';
		}

	}
}
