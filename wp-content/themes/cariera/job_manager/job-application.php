<?php
/**
 * Show job application when viewing a single job listing.
 *
 * This template can be overridden by copying it to yourtheme/job_manager/job-application.php.
 *
 * @see         https://wpjobmanager.com/document/template-overrides/
 * @author      Automattic
 * @package     wp-job-manager
 * @category    Template
 * @version     1.31.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $post;

?>

<?php if ( $apply = get_the_job_application_method() ) :
	wp_enqueue_script( 'wp-job-manager-job-application' );
	?>
	
	<?php
		$ws = get_post_meta( $post->ID, '_job_ws', true );
		$_title = get_post_meta( $post->ID, '_job_title', true );
	?>
	<div class="job_application application">
		<?php do_action( 'job_application_start', $apply ); ?>
        <a href="#job-popup" class="application_button btn btn-main btn-effect popup-with-zoom-anim"><?php esc_html_e( 'Apply for job', 'cariera' ); ?></a>
		<?php
            echo '<a  target="_blank" href="https://api.whatsapp.com/send?phone='. $ws . '&text=Hola%2C%20un%20gusto%2C%20en%20trabajosjovenes.cl%20ví%20su%20anuncio%20de%20'. $_title .'%20y%20me%20interesa%20postular%2C%20le%20dejo%20mi%20Curriculum%20saludos." class="application_button application_button_ws btn btn-main btn-effect"><img src="https://trabajosjovenes.cl/wp-content/uploads/2021/04/Whatsapp-Icon-PNG-Image-715x715-1.png" />Postular con Whatsapp</a>';
        ?>
        
        <div id="job-popup" class="small-dialog zoom-anim-dialog mfp-hide">                
            <div class="job-app-msg">
                <div class="small-dialog-headline">
                    <h3 class="title"><?php esc_html_e('Apply for this job','cariera') ?></h3>
                </div>
                
                <div class="small-dialog-content">
                    <?php
                        /**
                         * job_manager_application_details_email or job_manager_application_details_url hook
                         */
                        do_action( 'job_manager_application_details_' . $apply->type, $apply );
                    ?>
                </div>
            </div>
		</div>
        
		<?php do_action( 'job_application_end', $apply ); ?>
	</div>

<?php endif; ?>