<?php if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.
/**
 *
 * Field: Custom_import
 *
 * @since 1.0.0
 * @version 1.0.0
 */
if ( ! class_exists( 'SPFTESTIMONIAL_Field_custom_import' ) ) {
	class SPFTESTIMONIAL_Field_custom_import extends SPFTESTIMONIAL_Fields {

		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}
		public function render() {
			echo $this->field_before();
			$spt_testimonial = admin_url( 'edit.php?post_type=spt_testimonial' );
			$spt_shortcodes  = admin_url( 'edit.php?post_type=spt_shortcodes' );
				echo '<p><input type="file" id="import" accept=".json"></p>';
				echo '<p><button type="button" class="import">Import</button></p>';
				echo '<a id="spt_shortcode_link_redirect" href="' . $spt_shortcodes . '"></a>';
				echo '<a id="spt_testimonial_link_redirect" href="' . $spt_testimonial . '"></a>';
			echo $this->field_after();
		}
	}
}
