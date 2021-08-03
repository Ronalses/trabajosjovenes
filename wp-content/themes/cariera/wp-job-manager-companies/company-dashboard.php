<?php
/**
*
* @package Cariera
*
* @since    1.4.4
* @version  1.5.0
* 
* ========================
* COMPANY DASHBOARD
* ========================
*     
**/



if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}



$submission_limit     = get_option( 'cariera_company_submission_limit' );
$submit_company_page  = get_option( 'cariera_submit_company_page' );
$singular             = cariera_get_company_manager_singular_label();
$plural               = cariera_get_company_manager_plural_label();
$total_companies      = cariera_count_user_companies();
?>


<div id="company-manager-company-dashboard">
	<p><?php printf( esc_html__( 'Your %s can be viewed, edited or removed below.', 'cariera' ), $total_companies > 1 ? $plural : $singular ) ?></p>
    
    <div class="table-responsive mt30">
        <table class="company-manager-companies table">
            <thead>
                <tr>
                    <?php foreach ( $company_dashboard_columns as $key => $column ) : ?>
                        <th class="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $column ); ?></th>
                    <?php endforeach; ?>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                <?php if ( ! $companies ) : ?>
                    <tr>
                        <td colspan="6"><?php printf( esc_html__( 'You do not have any active %s listings.', 'cariera' ), $singular ); ?></td>
                    </tr>
                <?php else : ?>
                    <?php foreach ( $companies as $company ) : ?>
                        <tr>
                            <?php foreach ( $company_dashboard_columns as $key => $column ) : ?>
                                <td class="<?php echo esc_attr( $key ); ?>">
                                    <?php if ('company-name' === $key ) : ?>
                                        <?php if ( $company->post_status == 'publish' ) : ?>
                                            <a href="<?php echo esc_url( get_permalink( $company->ID ) ); ?>"><?php echo esc_html($company->post_title); ?></a>
                                        <?php else : ?>
                                            <?php echo esc_html($company->post_title); ?> <small>(<?php echo cariera_company_status( $company ); ?>)</small>
                                        <?php endif; ?>
                                        <?php echo cariera_is_company_featured( $company ) ? '<span class="fas fa-star pl5" title="' . esc_attr__( 'Featured Job', 'cariera' ) . '"></span>' : ''; ?>
									
									<?php elseif ( 'company-location' === $key ) : ?>
										<?php echo cariera_get_the_company_location( $company ); ?></td>
									<?php elseif ( 'company-category' === $key ) : ?>
                                        <?php cariera_the_company_category_array( $company ); ?>
									<?php elseif ( 'status' === $key ) : ?>
										<?php echo cariera_get_company_status( $company ); ?>
									<?php elseif ( 'company-jobs' === $key ) : ?>
                                        <?php echo cariera_get_the_company_job_listing_count($company); ?>
									<?php elseif ( 'date' === $key ) : ?>
										<?php echo date_i18n( get_option( 'date_format' ), strtotime( $company->post_date ) ); ?>
                                    <?php else : ?>
                                        <?php do_action( 'cariera_company_dashboard_column_' . $key, $company ); ?>
                                    <?php endif; ?>
                                </td>
                            <?php endforeach; ?>

                            <td class="action">                                
                                <?php do_action( 'cariera_company_dashboard_action_start', $company->ID ); ?>
                                <?php
                                    $actions = array();

                                    switch ( $company->post_status ) {
                                        case 'publish' :
                                            $actions['edit'] = array( 'label' => esc_html__( 'Edit', 'cariera' ), 'nonce' => false );
                                            $actions['hide'] = array( 'label' => esc_html__( 'Hide', 'cariera' ), 'nonce' => true );
                                        break;
                                        case 'private' :
                                            $actions['edit'] = array( 'label' => esc_html__( 'Edit', 'cariera' ), 'nonce' => false );
                                            $actions['publish'] = array( 'label' => esc_html__( 'Publish', 'cariera' ), 'nonce' => true );
                                        break;
                                        case 'hidden' :
                                            $actions['edit']    = array( 'label' => esc_html__( 'Edit', 'cariera' ), 'nonce' => false );
                                            $actions['publish'] = array( 'label' => esc_html__( 'Publish', 'cariera' ), 'nonce' => true );
                                        break;
                                    }

                                    $actions['delete'] = array( 'label' => esc_html__( 'Delete', 'cariera' ), 'nonce' => true );
                                    $actions           = apply_filters( 'cariera_my_company_actions', $actions, $company );

                                    foreach ( $actions as $action => $value ) {
                                        $action_url = add_query_arg( array( 'action' => $action, 'company_id' => $company->ID ) );
                                        if ( $value['nonce'] ) {
                                            $action_url = wp_nonce_url( $action_url, 'cariera_my_company_actions' );
                                        }
                                        echo '<a href="' . esc_url( $action_url ) . '" class="company-dashboard-action-' . esc_attr( $action ) . '">' . esc_html( $value['label'] ) . '</a>';
                                    }
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
	<?php get_job_manager_template( 'pagination.php', array( 'max_num_pages' => $max_num_pages ) ); ?>
    
    <?php
    if ( $submit_company_page && ( $total_companies < $submission_limit || ! $submission_limit ) ) { ?>
        <a href="<?php echo esc_url(get_permalink( $submit_company_page )); ?>" class="btn btn-main btn-effect mt20"><?php esc_html_e('Add Company','cariera'); ?></a>
	<?php } ?>
</div>