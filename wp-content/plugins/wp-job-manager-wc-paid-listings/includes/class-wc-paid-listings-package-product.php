<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WPJM Package Product
 */
class WP_Job_Manager_WCPL_Package_Product extends WC_Product {
	/**
	 * Compatibility function for `get_id()` method
	 *
	 * @return int
	 */
	public function get_id() {
		if ( WC_Paid_Listings::is_woocommerce_pre( '3.0.0' ) ) {
			return $this->id;
		}
		return parent::get_id();
	}

	/**
	 * Get product id
	 *
	 * @return int
	 */
	public function get_product_id() {
		return $this->get_id();
	}

	/**
	 * Compatibility function to retrieve product meta.
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function get_product_meta( $key ) {
		if ( WC_Paid_Listings::is_woocommerce_pre( '3.0.0' ) ) {
			return $this->{$key};
		}
		return $this->get_meta( '_' . $key );
	}

	/**
	 * Job Packages can always be purchased regardless of price.
	 *
	 * @return boolean
	 */
	public function is_purchasable() {
		return true;
	}

	/**
	 * Jobs are always virtual
	 *
	 * @return boolean
	 */
	public function is_virtual() {
		return true;
	}

	/**
	 * Job products are downloadable so orders don't require manual processing
	 *
	 * @return boolean
	 */
	public function is_downloadable() {
		return true;
	}

}
