<?php
/**
 * Lists the job applications for a particular job listing.
 *
 * This template can be overridden by copying it to yourtheme/wp-job-manager-applications/job-applications.php.
 *
 * @see         https://wpjobmanager.com/document/template-overrides/
 * @author      Automattic
 * @package     WP Job Manager - Applications
 * @category    Template
 * @version     1.7.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div id="job-manager-job-applications">
    <div>
        <strong>
            <a href="<?php echo esc_url( add_query_arg( 'download-csv', true ) ); ?>" class="job-applications-download-csv">
           <?php esc_html_e( 'Download CSV', 'cariera' ); ?>
            </a>
        </strong>
        <p>
           <?php printf( esc_html__( 'The job applications for "%s" are listed below.', 'cariera' ), '<a href="' . get_permalink( $job_id ) . '"><strong>' . get_the_title( $job_id ) . '</strong></a>' ); ?>
        </p>
    </div>
    
    
    
    
	<div class="cariera-job-applications">
        
        <!-- Start of Filter Form -->
		<form class="filter-job-applications row mb40" method="GET">
			<div class="col-md-6 col-xs-6">
				<select name="application_status" class="cariera-select2">
					<option value=""><?php esc_html_e( 'Filter by status...', 'cariera' ); ?></option>
					<?php foreach ( get_job_application_statuses() as $name => $label ) : ?>
						<option value="<?php echo esc_attr( $name ); ?>" <?php selected( $application_status, $name ); ?>><?php echo esc_html( $label ); ?></option>
					<?php endforeach; ?>
				</select>
			</div>
            
			<div class="col-md-6 col-xs-6">
				<select name="application_orderby" class="cariera-select2">
					<option value=""><?php esc_html_e( 'Newest first', 'cariera' ); ?></option>
					<option value="name" <?php selected( $application_orderby, 'name' ); ?>><?php esc_html_e( 'Sort by name', 'cariera' ); ?></option>
					<option value="rating" <?php selected( $application_orderby, 'rating' ); ?>><?php esc_html_e( 'Sort by rating', 'cariera' ); ?></option>
				</select>
				<input type="hidden" name="action" value="show_applications" />
				<input type="hidden" name="job_id" value="<?php echo absint( $_GET['job_id'] ); ?>" />
				<?php if ( ! empty( $_GET['page_id'] ) ) : ?>
					<input type="hidden" name="page_id" value="<?php echo absint( $_GET['page_id'] ); ?>" />
				<?php endif; ?>
			</div>
		</form>
        <!-- End of Filter Form -->
        
        
        
        <!-- Start of Applications -->
		<div class="job-applications">
			<?php foreach ( $applications as $application ) : ?>
                <!-- Start of Job Application -->
				<div class="application job-application shadow-hover" id="application-<?php esc_attr( $application->ID ); ?>">
                    
                    <!-- Start of Application Content -->
                    <div class="application-content">
                        
                        <div class="info">
                            <?php echo get_job_application_avatar( $application->ID, 90 ) ?>
                            
                            <h4>
                                <?php if ( ( $resume_id = get_job_application_resume_id( $application->ID ) ) && 'publish' === get_post_status( $resume_id ) && function_exists( 'get_resume_share_link' ) && ( $share_link = get_resume_share_link( $resume_id ) ) ) { ?>
									<a href="<?php echo esc_attr( $share_link ); ?>">
                                        <?php echo esc_html($application->post_title); ?>
                                    </a>
                                <?php } else {
                                    echo esc_html($application->post_title);
                                } ?>
                            </h4>
                            
                            <ul>
                                <?php if ( $attachments = get_job_application_attachments( $application->ID ) ) : ?>
                                    <?php foreach ( $attachments as $attachment ) : ?>
                                        <li>
                                            <a href="<?php echo esc_url( $attachment ); ?>" title="<?php echo esc_attr( get_job_application_attachment_name( $attachment ) ); ?>" class=" job-application-attachment"><i class="far fa-file-alt"></i> <?php echo esc_html( get_job_application_attachment_name( $attachment, 15 ) ); ?></a>
                                        </li>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                
                                <?php if ( $email = get_job_application_email( $application->ID ) ) { ?>
                                    <li><a href="mailto:<?php echo esc_attr( $email ); ?>?subject=<?php echo esc_attr( sprintf( esc_html__( 'Your job application for %s', 'cariera' ), strip_tags( get_the_title( $job_id ) ) ) ); ?>&amp;body=<?php echo esc_attr( sprintf( esc_html__( 'Hello %s', 'cariera' ), get_the_title( $application->ID ) ) ); ?>" title="<?php esc_html_e( 'Email', 'cariera' ); ?>" class="job-application-contact"><i class="far fa-envelope"></i>  <?php esc_html_e( 'Email', 'cariera' ); ?></a></li>
                                <?php } ?>                                
                                
                                <?php 
                                if ( ( $resume_id = get_job_application_resume_id( $application->ID ) ) && 'publish' === get_post_status( $resume_id ) && function_exists( 'get_resume_share_link' ) && ( $share_link = get_resume_share_link( $resume_id ) ) ) { ?>
                                    <li><a href="<?php echo esc_attr( $share_link ); ?>" target="_blank" class="job-application-resume">
                                    <i class="fas fa-download" aria-hidden="true"></i><?php esc_html_e('View Resume', 'cariera' ); ?></a></li>
                                <?php } ?>
                            </ul>
                            
                            <!-- Buttons -->
							<div class="buttons">
				                
                                <!-- Edit -->
								<a href="#edit-<?php echo esc_attr($application->ID );?>" class="button application-link job-application-toggle-edit">
                                    <i class="far fa-edit"></i><?php esc_html_e( 'Edit', 'cariera' ); ?>
                                </a>
                                
                                <!-- Notes -->
								<a href="#notes-<?php echo esc_attr($application->ID );?>" class="button application-link job-application-toggle-notes">
                                    <i class="fas fa-sticky-note"></i> <?php esc_html_e( 'Notes', 'cariera' ); ?>
                                </a>
                                
                                <!-- Details -->
								<a href="#details-<?php echo esc_attr($application->ID );?>" class="button application-link job-application-toggle-content">
                                    <i class="fas fa-plus-circle"></i> <?php esc_html_e( 'Details', 'cariera' ); ?>
                                </a>
							
							</div>
                            <div class="clearfix"></div>
                        </div> <!-- end of .info -->
                    
                    </div>
                    <!-- End of Application Content -->
                    
                    
                    
                    <!-- Start of Hidden Tabs -->
                    <div class="application-tabs">

                        <a href="#" class="close-tab button gray"><i class="icon-close"></i></a>

                        <!-- First Tab -->
                        <div class="app-tab-content" id="edit-<?php echo esc_attr($application->ID );?>">
                            <form class="job-manager-application-edit-form job-manager-form" method="post">

                                <fieldset class="select-grid fieldset-status">
                                    <label for="application-status-<?php echo esc_attr( $application->ID ); ?>"><?php esc_html_e( 'Application status', 'cariera' ); ?>:</label>
                                    <div class="field">
                                        <select class="cariera-select2" id="application-status-<?php echo esc_attr( $application->ID ); ?>" name="application_status">
                                            <?php foreach ( get_job_application_statuses() as $name => $label ) : ?>
                                                <option value="<?php echo esc_attr( $name ); ?>" <?php selected( $application->post_status, $name ); ?>><?php echo esc_html( $label ); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </fieldset>

                                <fieldset class="select-grid fieldset-rating">
                                    <label for="application-rating-<?php echo esc_attr( $application->ID ); ?>"><?php esc_html_e( 'Rating (out of 5)', 'cariera' ); ?>:</label>
                                    <div class="field">
                                        <input type="number" id="application-rating-<?php echo esc_attr( $application->ID ); ?>" name="application_rating" step="0.5" max="5" min="0" placeholder="0" value="<?php echo esc_attr( get_job_application_rating( $application->ID ) ); ?>" />
                                    </div>
                                </fieldset>
                                <div class="clearfix"></div>
                                <p class="application-action-buttons">
                                    <a class="button btn-effect delete-application delete_job_application" href="<?php echo wp_nonce_url( add_query_arg( 'delete_job_application', $application->ID ), 'delete_job_application' ); ?>"><?php esc_html_e( 'Delete this application', 'cariera' ); ?></a>
                                    <input class="btn btn-main btn-effect" type="submit" name="wp_job_manager_edit_application" value="<?php esc_attr_e( 'Save changes', 'cariera' ); ?>" />
                                    <input type="hidden" name="application_id" value="<?php echo absint( $application->ID ); ?>" />
                                    <?php wp_nonce_field( 'edit_job_application' ); ?>
                                </p>
                            </form>
                        </div>

                        <!-- Second Tab -->
                        <div class="app-tab-content"  id="notes-<?php echo esc_attr($application->ID );?>">
                            <?php job_application_notes( $application ); ?>
                        </div>

                        <!-- Third Tab -->
                        <div class="app-tab-content"  id="details-<?php echo esc_attr($application->ID );?>">
                            <?php job_application_meta( $application ); ?>
                            <?php //job_application_content( $application ); ?>
                        </div>

                    </div>
                    <!-- End of Hidden Tabs -->
                    
                    
                                        
                    <!-- Start of Application Footer -->
                    <div class="application-footer">
                        <?php $rating = get_job_application_rating( $application->ID ); ?>
                        <div class="rating <?php echo cariera_get_rating_class($rating); ?>">
                            <div class="star-rating"></div>
                            <div class="star-bg"></div>
                        </div>

                        <?php global $wp_post_statuses; ?>
                        <ul class="meta">
                            <li><i class="far fa-file-alt"></i><?php echo esc_html( $wp_post_statuses[ $application->post_status ]->label ); ?></li>
                            <li><i class="far fa-calendar-alt"></i> <?php echo date_i18n( get_option( 'date_format' ), strtotime( $application->post_date ) ); ?></li>
                        </ul>
                        <div class="clearfix"></div>

                    </div>
                    <!-- End of Application Footer -->
                    
                    					
				</div>
                <!-- End of Job Application -->
			<?php endforeach; ?>
		</div>
        <!-- End of Applications -->
                
        
		<?php get_job_manager_template( 'pagination.php', array( 'max_num_pages' => $max_num_pages ) ); ?>
	</div>
</div>