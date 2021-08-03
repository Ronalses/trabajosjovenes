<?php
/**
*
* @package Cariera
*
* @since    1.3.8
* @version  1.5.1
* 
* ========================
* REPORTS FUNTIONS
* ========================
*     
**/





/**
 * Adding data to the Database for the external redirections
 *
 * @since  1.3.8
 */

if( !function_exists('cariera_set_this_external_job') ) {
    function cariera_set_this_external_job( $listing_id ) {
        
        $table = "cariera_job_external_redirection";
        
        // main function
        cariera_create_external_apply_table();
        
        $listing_title  = get_the_title($listing_id);
        $allCounts      = '';
        
        // check if already have
        $condition      = "listing_id ='$listing_id'";
        $data           = cariera_get_data_from_db($table, '*', $condition);
        
        // already exists
        if( !empty($data) ) {
            foreach( $data as $index=>$val){
                $count = $val->count;
                $allCounts  = $count + 1;

                $where      = array(
                    'listing_id' => $listing_id
                );

                $dataArray  = array(
                    'count' => $allCounts,
                );
                
                cariera_update_data_in_db($table, $dataArray, $where);
            }
            
        } else {
             // new record
            $log_record = array(
                array(
                    'count' => 1,
                )
            );
            $log_record = serialize( $log_record );

            $dataArray = array(
                'listing_id'    => $listing_id,
                'listing_title' => $listing_title,
                'count'         => 1,
            );
            
            cariera_insert_data_in_db($table, $dataArray);
        }

    }
}





/**
 * Update External Redirection when job redirects the user
 *
 * @since  1.3.8
 */

function cariera_external_job_application() {
    
    // Get Job ID from the form    
    $listing_id = absint( $_POST['id'] );

    $table  = 'cariera_job_external_redirection';
    $where  = array( 'listing_id' => $listing_id );
    cariera_update_data_in_db( $table, '*', $where );

    cariera_set_this_external_job( $listing_id );
    
   
    echo json_encode(array(
        'message' => esc_html__( 'Redirected to external job application link.', 'cariera' ),
    ));
    
    die();    
}

add_action( 'wp_ajax_cariera_external_job_application_ajax', 'cariera_external_job_application' );
add_action( 'wp_ajax_nopriv_cariera_external_job_application_ajax', 'cariera_external_job_application' );





/**
 * Employer Reports
 *
 * @since  1.3.8
 */

function cariera_employer_reports() {
    global $post;
    
    // Stop the function if the WPJM plugin is not activated
    if( !class_exists( 'WP_Job_Manager' ) ) {
        return;
    }
    
    $current_user = wp_get_current_user();
    $user_id      = $current_user->ID;
    
    $args = apply_filters(
        'cariera_get_job_args',
        array(
            'post_type'             => 'job_listing',
            'post_status'           => array( 'publish', 'expired' ),
            'posts_per_page'        => 10,
            'paged'                 => get_query_var('paged') ? get_query_var('paged') : 1,
            'ignore_sticky_posts'   => 1,
            'orderby'               => 'date',
            'order'                 => 'desc',
            'author'                => get_current_user_id(),
        )
    );
    
    $jobs = new WP_Query();
    $jobs = $jobs->query( $args );
    ?>


    <div class="job-reports">
        <h3 class="title"><?php esc_html_e( 'My Job Reports:', 'cariera' ); ?></h3>

        <div class="table-responsive">
            <table class="table job-manager-job-reports">
                <thead>
                    <tr>
                        <th><?php esc_html_e( 'Jobs', 'cariera' ); ?></th>
                        <th><?php esc_html_e( 'Posted Date', 'cariera' ); ?></th>
                        <th><?php esc_html_e( 'Views', 'cariera' ); ?></th>
                        <th><?php esc_html_e( 'External Clicks', 'cariera' ); ?></th>
                    </tr>
                </thead>

                <tbody>
                    <?php if( ! $jobs ) { ?>
                        <tr>
                            <td colspan="6"><?php esc_html_e( 'You do not have any active listings.', 'cariera' ); ?></td>
                        </tr>
                    <?php } else {
                        foreach( $jobs as $job) { ?>
                            <tr>
                                <!-- Job Title -->
                                <td>
                                    <a href="<?php echo esc_url( get_permalink( $job->ID ) ); ?>">
                                        <?php echo esc_html($job->post_title); ?>
                                    </a>
                                </td>

                                <!-- Job Date Posted -->
                                <td>
                                    <?php echo date_i18n( get_option( 'date_format' ), strtotime( $job->post_date ) ); ?>
                                </td>

                                <!-- Job views -->
                                <td>
                                    <?php
                                    // LISTING VIEWS
                                    $table      = 'cariera_listing_stats_views';
                                    $condition  = "listing_id='$job->ID'";
                                    $data       = cariera_get_data_from_db( $table, 'count', $condition);

                                    if( !empty($data) ) {
                                        foreach( $data as $index=>$val){
                                            $count = $val->count;
                                            echo $count;
                                        }                             
                                    } else {
                                        echo esc_html('0');
                                    } ?>
                                </td>

                                <!-- Job External click redirections -->
                                <td>
                                    <?php
                                    $table      = 'cariera_job_external_redirection';
                                    $condition  = "listing_id='$job->ID'";
                                    $data       = cariera_get_data_from_db( $table, 'count', $condition);

                                    if( !empty($data) ) {
                                        foreach( $data as $index=>$val){
                                            $count = $val->count;
                                            echo $count;
                                        }                             
                                    } else {
                                        echo esc_html('0');
                                    } ?>
                                </td>
                            </tr>
                        <?php }
                    } ?>
                </tbody>
            </table>
        </div>
        
        <?php
        // Pagination on the end of the reports
        $job_listing = get_job_listings( $args );
        get_job_manager_template( 'pagination.php', array( 'max_num_pages' => $job_listing->max_num_pages ) );
        ?>
    </div>
    <?php
}





/**
 * Candidate Reports
 *
 * @since  1.3.8
 */

function cariera_candidate_reports() {
    global $post;
    
    // Stop the function if the WPRM plugin is not activated
    if( ! class_exists( 'WP_Resume_Manager' ) ) {
        return;
    }
    
    $current_user = wp_get_current_user();
    $user_id      = $current_user->ID;
    
    $args = apply_filters(
        'cariera_get_resume_args',
        array(
            'post_type'             => 'resume',
            'post_status'           => array( 'publish' ),
            'posts_per_page'        => 10,
            'paged'                 => get_query_var('paged') ? get_query_var('paged') : 1,
            'ignore_sticky_posts'   => 1,
            'orderby'               => 'date',
            'order'                 => 'desc',
            'author'                => get_current_user_id(),
        )
    );
    
    $resumes = new WP_Query();
    $resumes = $resumes->query( $args );
    ?>


    <div class="resume-reports">
        <h3 class="title"><?php esc_html_e( 'My Resume Reports:', 'cariera' ); ?></h3>

        <div class="table-responsive">
            <table class="table resume-manager-resume-reports">
                <thead>
                    <tr>
                        <th><?php esc_html_e( 'Resumes', 'cariera' ); ?></th>
                        <th><?php esc_html_e( 'Posted Date', 'cariera' ); ?></th>
                        <th><?php esc_html_e( 'Views', 'cariera' ); ?></th>
                    </tr>
                </thead>

                <tbody>
                    <?php if( ! $resumes ) { ?>
                        <tr>
                            <td colspan="6"><?php esc_html_e( 'You do not have any active listings.', 'cariera' ); ?></td>
                        </tr>
                    <?php } else {
                        foreach( $resumes as $resume ) { ?>
                            <tr>
                                <!-- Resume Title -->
                                <td>
                                    <a href="<?php echo esc_url( get_permalink( $resume->ID ) ); ?>">
                                        <?php echo esc_html($resume->post_title); ?>
                                    </a>
                                </td>

                                <!-- Resume Date Posted -->
                                <td>
                                    <?php echo date_i18n( get_option( 'date_format' ), strtotime( $resume->post_date ) ); ?>
                                </td>

                                <!-- Resume views -->
                                <td>
                                    <?php
                                    // LISTING VIEWS
                                    $table      = 'cariera_listing_stats_views';
                                    $condition  = "listing_id='$resume->ID'";
                                    $data       = cariera_get_data_from_db( $table, 'count', $condition);

                                    if( !empty($data) ) {
                                        foreach( $data as $index=>$val){
                                            $count = $val->count;
                                            echo $count;
                                        }                             
                                    } else {
                                        echo esc_html('0');
                                    } ?>
                                </td>
                            </tr>
                        <?php }
                    } ?>
                </tbody>
            </table>
        </div>
        
        <?php
        // Pagination on the end of the reports
        $resume_listing = get_resumes( $args );
        get_job_manager_template( 'pagination.php', array( 'max_num_pages' => $resume_listing->max_num_pages ) );
        ?>
        
        
    </div>
    <?php
}





/**
 * Reports shortcode
 * Usage: [cariera_listing_reports]
 *
 * @since  1.3.8
 */

if ( !function_exists('cariera_listing_reports') ) {
    function cariera_listing_reports() {
        global $wp_roles;
        $current_user = wp_get_current_user();
        $user_id      = $current_user->ID;
        
        if ( !is_user_logged_in() ) { ?>
            <p class="job-manager-message error">
                <?php esc_html_e( 'You need to be signed in to access your listing reports.', 'cariera' ); ?>
            </p>
        <?php } else { ?>
            
            <!-- Start of Reports -->
            <div class="row mt50">
                <div class="col-md-12 dashboard-content-reports">
                    <div class="dashboard-card-box">
                        <h4 class="title"><?php esc_html_e( 'Listing Reports', 'cariera' ); ?></h4>

                        <div class="dashboard-card-box-inner report-wrapper">
                            <?php
                            if ( in_array( 'administrator', (array) $current_user->roles ) ) {
                                cariera_employer_reports();
                                cariera_candidate_reports();
                            } elseif ( in_array( 'employer', (array) $current_user->roles ) ) {
                                cariera_employer_reports();
                            } elseif ( in_array( 'candidate', (array) $current_user->roles ) ) {
                                cariera_candidate_reports();
                            } else {
                                return;
                            }
                            
                            
                            // Show message if none of the require plugins are activated
                            if( !class_exists( 'WP_Job_Manager' ) && ! class_exists( 'WP_Resume_Manager' ) ) { ?>
                                <p class="job-manager-message error">
                                    <?php esc_html_e( 'Please activate at least WP Job Manager Plugin in order to access your listing reports.', 'cariera' ); ?>
                                </p>
                            <?php } ?>
                        </div>

                    </div>
                </div>
            </div>
            <!-- End of Reports -->
        <?php }
    }
}

add_shortcode( 'cariera_listing_reports', 'cariera_listing_reports' );