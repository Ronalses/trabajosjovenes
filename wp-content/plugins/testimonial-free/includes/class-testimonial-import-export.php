<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Custom import export.
 *
 * @link http://shapedplugin.com
 * @since 2.0.0
 *
 * @package Testimonial_free.
 * @subpackage Testimonial_free/includes.
 */

/**
 * Custom import export.
 */
class Testimonial_Import_Export {

	/**
	 * Export
	 *
	 * @param  mixed $shortcode_ids Export testimonials and shortcode ids.
	 * @return array
	 */
	public function export( $shortcode_ids ) {
		$export = array();
		if ( ! empty( $shortcode_ids ) ) {
			$post_type  = 'all_testimonial' === $shortcode_ids ? 'spt_testimonial' : 'spt_shortcodes';
			$post_in    = 'all_spt_shortcodes' === $shortcode_ids || 'all_testimonial' === $shortcode_ids ? '' : $shortcode_ids;
			$args       = array(
				'post_type'        => $post_type,
				'post_status'      => array( 'inherit', 'publish' ),
				'orderby'          => 'modified',
				'suppress_filters' => 1, // wpml, ignore language filter.
				'posts_per_page'   => -1,
				'post__in'         => $post_in,
			);
			$shortcodes = get_posts( $args );
			if ( ! empty( $shortcodes ) ) {
				foreach ( $shortcodes as $shortcode ) {
						$shortcode_export = array(
							'title'       => $shortcode->post_title,
							'original_id' => $shortcode->ID,
							'spt_post'    => $post_type,
							'meta'        => array(),
						);
						if ( 'all_testimonial' === $shortcode_ids ) {
							$shortcode_export['content']         = $shortcode->post_content;
							$shortcode_export['image']           = get_the_post_thumbnail_url( $shortcode->ID, 'single-post-thumbnail' );
							$shortcode_export['all_testimonial'] = 'all_testimonial';
						}

						foreach ( get_post_meta( $shortcode->ID ) as $metakey => $value ) {
							$shortcode_export['meta'][ $metakey ] = $value[0];
						}
						$export['shortcode'][] = $shortcode_export;

						unset( $shortcode_export );
				}
				$export['metadata'] = array(
					'version' => SP_TFREE_VERSION,
					'date'    => date( 'Y/m/d' ),
				);
			}
			return $export;
		}
	}

	/**
	 * Export Testimonial by ajax.
	 *
	 * @return void
	 */
	public function export_shortcodes() {
		if ( ! wp_verify_nonce( $_POST['nonce'], 'spftestimonial_options_nonce' ) ) {
			die();
		}
		$shortcode_ids = isset( $_POST['lcp_ids'] ) ? $_POST['lcp_ids'] : '';
		$export        = $this->export( $shortcode_ids );

		if ( is_wp_error( $export ) ) {
			wp_send_json_error(
				array(
					'message' => $export->get_error_message(),
				),
				400
			);
		}

		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
            // @codingStandardsIgnoreLine
            echo wp_json_encode($export, JSON_PRETTY_PRINT);
			die;
		}

		wp_send_json( $export, 200 );
	}
	/**
	 * Insert an attachment from an URL address.
	 *
	 * @param  String $url remote url.
	 * @param  Int    $parent_post_id parent post id.
	 * @return Int    Attachment ID
	 */
	public function insert_attachment_from_url( $url, $parent_post_id = null ) {

		if ( ! class_exists( 'WP_Http' ) ) {
			include_once ABSPATH . WPINC . '/class-http.php';
		}
		$attachment_title = sanitize_file_name( pathinfo( $url, PATHINFO_FILENAME ) );
		// Does the attachment already exist ?
		if ( post_exists( $attachment_title, '', '', 'attachment' ) ) {
			$attachment = get_page_by_title( $attachment_title, OBJECT, 'attachment' );
			if ( ! empty( $attachment ) ) {
				$attachment_id = $attachment->ID;
				return $attachment_id;
			}
		}
		$http     = new WP_Http();
		$response = $http->request( $url );
		if ( $response['response']['code'] != 200 ) {
			return false;
		}
		$upload = wp_upload_bits( basename( $url ), null, $response['body'] );
		if ( ! empty( $upload['error'] ) ) {
			return false;
		}

		$file_path     = $upload['file'];
		$file_name     = basename( $file_path );
		$file_type     = wp_check_filetype( $file_name, null );
		$wp_upload_dir = wp_upload_dir();

		$post_info = array(
			'guid'           => $wp_upload_dir['url'] . '/' . $file_name,
			'post_mime_type' => $file_type['type'],
			'post_title'     => $attachment_title,
			'post_content'   => '',
			'post_status'    => 'inherit',
		);

		// Create the attachment.
		$attach_id = wp_insert_attachment( $post_info, $file_path, $parent_post_id );

		// Include image.php.
		require_once ABSPATH . 'wp-admin/includes/image.php';

		// Define attachment metadata.
		$attach_data = wp_generate_attachment_metadata( $attach_id, $file_path );

		// Assign metadata to attachment.
		wp_update_attachment_metadata( $attach_id, $attach_data );

		return $attach_id;

	}

	/**
	 * Import Testimonial ans shortcode.
	 *
	 * @param  mixed $shortcodes Import Testimonials shortcode array.
	 *
	 * @return string
	 */
	public function import( $shortcodes ) {
		$errors        = array();
		$spt_post_type = 'spt_testimonial';
		foreach ( $shortcodes as $index => $shortcode ) {
			$errors[ $index ] = array();
			$new_shortcode_id = 0;

			$spt_post_type = isset( $shortcode['spt_post'] ) ? $shortcode['spt_post'] : '';
			try {
				$new_shortcode_id = wp_insert_post(
					array(
						'post_title'   => isset( $shortcode['title'] ) ? $shortcode['title'] : '',
						'post_content' => isset( $shortcode['content'] ) ? $shortcode['content'] : '',
						'post_status'  => 'publish',
						'post_type'    => $spt_post_type,
					),
					true
				);
				if ( isset( $shortcode['all_testimonial'] ) ) {
					$url = isset( $shortcode['image'] ) && ! empty( $shortcode['image'] ) ? $shortcode['image'] : '';
					// Insert attachment id.
					$thumb_id                           = $this->insert_attachment_from_url( $url, $new_shortcode_id );
					$shortcode['meta']['_thumbnail_id'] = $thumb_id;
				}
				if ( is_wp_error( $new_shortcode_id ) ) {
					throw new Exception( $new_shortcode_id->get_error_message() );
				}

				if ( isset( $shortcode['meta'] ) && is_array( $shortcode['meta'] ) ) {
					foreach ( $shortcode['meta'] as $key => $value ) {
						update_post_meta(
							$new_shortcode_id,
							$key,
							maybe_unserialize( str_replace( '{#ID#}', $new_shortcode_id, $value ) )
						);
					}
				}
			} catch ( Exception $e ) {
				array_push( $errors[ $index ], $e->getMessage() );

				// If there was a failure somewhere, clean up.
				wp_trash_post( $new_shortcode_id );
			}

			// If no errors, remove the index.
			if ( ! count( $errors[ $index ] ) ) {
				unset( $errors[ $index ] );
			}

			// External modules manipulate data here.
			do_action( 'testimonial_shortcode_imported', $new_shortcode_id );
		}

		$errors = reset( $errors );
		return isset( $errors[0] ) ? new WP_Error( 'import_testimonials_error', $errors[0] ) : $spt_post_type;
	}

	/**
	 * Import Testimonial by ajax.
	 *
	 * @return void
	 */
	public function import_shortcodes() {
		if ( ! wp_verify_nonce( $_POST['nonce'], 'spftestimonial_options_nonce' ) ) {
			die();
		}
		$data       = isset( $_POST['shortcode'] ) ? $_POST['shortcode'] : '';
		$data       = json_decode( stripslashes( $data ) );
		$data       = json_decode( $data, true );
		$shortcodes = $data['shortcode'];
		if ( ! $data ) {
			wp_send_json_error(
				array(
					'message' => __( 'Nothing to import.', 'testimonial-free' ),
				),
				400
			);
		}

		$status = $this->import( $shortcodes );

		if ( is_wp_error( $status ) ) {
			wp_send_json_error(
				array(
					'message' => $status->get_error_message(),
				),
				400
			);
		}

		wp_send_json_success( $status, 200 );
	}
}
