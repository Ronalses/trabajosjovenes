<?php
/**
 * Notice when no jobs were found in `[jobs]` shortcode.
 *
 * This template can be overridden by copying it to yourtheme/job_manager/content-no-jobs-found.php.
 *
 * @see         https://wpjobmanager.com/document/template-overrides/
 * @author      Automattic
 * @package     wp-job-manager
 * @category    Template
 * @since       1.0.0
 * @version     1.31.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<?php if ( defined( 'DOING_AJAX' ) ) : ?>
	<li class="no_job_listings_found"><?php esc_html_e( 'Ups😬 no hemos podido encontrar tu búsqueda. Pero tenemos muchas ofertas para ti👀!', 'wp-job-manager' ); ?></li>´
	<?php echo do_shortcode("[jobs per_page='10' orderby='featured' show_categories='false' show_pagination='true' show_filters='false']"); ?>
<?php else : ?>
	<p class="no_job_listings_found"><?php esc_html_e( 'There are currently no vacancies.', 'wp-job-manager' ); ?></p>
<?php endif; ?>
