<?php
/**
* This class is loaded on the back-end since its main job is
* to display the Admin to box.
*/
class CFWJM_Admin {
	public $fieldset_arr = array();
	public $diable_arr = array();
	public function __construct () {
		$this->fieldset_arr = array(
			'text' => 'Text',
			'select' => 'Select',
			'multiselect' => 'Multi Select',
			'radio' => 'Radio',
			'checkbox' => 'Checkbox',
			'date' => 'Date',
			'file' => 'File',
			'textarea' => 'Textarea',
			'wp-editor' => 'Wp editor'
		);
		$this->diable_arr = array('radio','checkbox');
		add_action( 'init', array( $this, 'CFWJM_init' ) );
		add_action( 'admin_menu', array( $this, 'CFWJM_admin_menu' ) );
		add_action('admin_enqueue_scripts', array( $this, 'CFWJM_admin_script' ));
		if ( is_admin() ) {
			return;
		}
		
	}
	public function CFWJM_admin_script () {
		wp_enqueue_style('cfwjm_admin_css', CFWJM_PLUGINURL.'css/admin-style.css');
		wp_enqueue_script('cfwjm_admin_js', CFWJM_PLUGINURL.'js/admin-script.js');
	}
	public function CFWJM_init () {
		$args = array(
						'label'               => __( 'wpjmcf', 'cfwjm' ),
					'show_ui'             => false,
				'show_in_menu'        => false,
				'show_in_nav_menus'   => false,
				'show_in_admin_bar'   => false,
				'menu_position'       => 5,
				'can_export'          => true,
				'has_archive'         => true,
				'exclude_from_search' => true,
				'publicly_queryable'  => true,
				);
	
		// Registering your Custom Post Type
		register_post_type( 'wpjmcf', $args );
		if( current_user_can('administrator') ) {
			if($_REQUEST['action'] == 'add_new_field_cfwjm'){
				if(!isset( $_REQUEST['cfwjm_nonce_field_add'] ) || !wp_verify_nonce( $_POST['cfwjm_nonce_field_add'], 'cfwjm_nonce_action_add' ) ){
	                print 'Sorry, your nonce did not verify.';
	                exit;
	            }else{
					$post_data = array(
										'post_title' => sanitize_text_field($_REQUEST['field_name_cfwjm']),
										'post_type' => 'wpjmcf',
										'post_status' => 'publish'
										);
						$post_id = wp_insert_post( $post_data );
						update_post_meta( $post_id, 'field_type_cfwjm', sanitize_text_field($_REQUEST['field_type_cfwjm']) );
						update_post_meta( $post_id, 'field_location_cfwjm', sanitize_text_field($_REQUEST['field_location_cfwjm']) );
						update_post_meta( $post_id, 'field_required_cfwjm', sanitize_text_field($_REQUEST['field_required_cfwjm']) );
						$textToStore = htmlentities($_REQUEST['field_option_cfwjm'], ENT_QUOTES, 'UTF-8');
						update_post_meta( $post_id, 'field_option_cfwjm', $textToStore );
						wp_redirect( admin_url( 'admin.php?page=cfwjm-fields&msg=success') );
					exit;
				}
			}
			if($_REQUEST['action'] == 'update_new_field_cfwjm'){
				if(!isset( $_REQUEST['cfwjm_nonce_field_edit'] ) || !wp_verify_nonce( $_POST['cfwjm_nonce_field_edit'], 'cfwjm_nonce_action_edit' ) ){
	                print 'Sorry, your nonce did not verify.';
	                exit;
	            }else{
					$post_id = sanitize_text_field($_REQUEST['id']);
					$post_data = array(
										'ID'           => $post_id,
										'post_title' => sanitize_text_field($_REQUEST['field_name_cfwjm']),
										);
					wp_update_post( $post_data );
					update_post_meta( $post_id, 'field_type_cfwjm', sanitize_text_field($_REQUEST['field_type_cfwjm']) );
					update_post_meta( $post_id, 'field_location_cfwjm', sanitize_text_field($_REQUEST['field_location_cfwjm']) );
					update_post_meta( $post_id, 'field_required_cfwjm', sanitize_text_field($_REQUEST['field_required_cfwjm']) );
					$textToStore = htmlentities($_REQUEST['field_option_cfwjm'], ENT_QUOTES, 'UTF-8');
					update_post_meta( $post_id, 'field_option_cfwjm', $textToStore );
					wp_redirect( admin_url( 'admin.php?page=cfwjm-fields&msg=success') );
				}
				exit;
			}
			if($_REQUEST['action'] == 'delete_field_cfwjm'){
				$post_id = sanitize_text_field($_REQUEST['id']);
				$post_data = array(
									'ID'          => $post_id,
									'post_status' => 'trash'
									);
				wp_update_post( $post_data );
				wp_redirect( admin_url( 'admin.php?page=cfwjm-fields&msg=success') );
				exit;
			}
		}
	}
	public function CFWJM_admin_menu () {
		add_menu_page('Custom Field For WP Job Manager', 'Custom Field For WP Job Manager', 'manage_options', 'cfwjm-fields', array( $this, 'CFWJM_page' ));
		/*add_submenu_page( 'theme-options', 'Settings page title', 'Settings menu label', 'manage_options', 'theme-op-settings', 'wps_theme_func_settings');*/
		/*add_submenu_page( 'theme-options', 'FAQ page title', 'FAQ menu label', 'manage_options', 'theme-op-faq', 'wps_theme_func_faq');
		add_options_page('WP Job Google Location', 'WP Job Google Location', 'manage_options', 'CFWJM', array( $this, 'CFWJM_page' ));*/
	}
	public function CFWJM_page() {
?>
<div class="wrap">
	<div class="headingmc">
		<h1 class="wp-heading-inline"><?php _e('WP Job Manager Custom Field', 'cfwjm'); ?></h1>
		<a href="#" class="page-title-action addnewfielcfqjm"><?php _e('Add New Field', 'cfwjm'); ?></a>
	</div>
	<hr class="wp-header-end">
	<?php if($_REQUEST['msg'] == 'success'){ ?>
        <div class="notice notice-success is-dismissible"> 
            <p><strong><?php _e('WP Job Manager Custom Field Table Updated', 'cfwjm'); ?></strong></p>
        </div>
    <?php } ?>
	<?php
	if($_REQUEST['action']=='edit-cfwjm-fields'){
		$id = sanitize_text_field($_REQUEST['id']);
		$postdata = get_post( $id );
		$field_type_cfwjm = get_post_meta( $id, 'field_type_cfwjm', true );
		$field_location_cfwjm = get_post_meta( $id, 'field_location_cfwjm', true );
		$field_option_cfwjm = get_post_meta( $id, 'field_option_cfwjm', true );
		$field_required_cfwjm = get_post_meta( $id, 'field_required_cfwjm', true );
		
		?>
		<div class="postbox">
				
				<div class="inside">
					<form action="#" method="post" id="wp_job_custom_form">
						<?php wp_nonce_field( 'cfwjm_nonce_action_edit', 'cfwjm_nonce_field_edit' ); ?>
						<h3><?php _e('WP Job Manager Custom Field Edit', 'cfwjm'); ?></h3>
						<table class="form-table">
							<tr>
								<th scope="row"><label>Field Type</label></th>
								<td>
									<select name="field_type_cfwjm" class="field_type_cfwjm" >
										<?php
										
										foreach ($this->fieldset_arr as $fieldset_arrk => $fieldset_arrv) {
											echo '<option '.(in_array($fieldset_arrk, $this->diable_arr)?'disabled':'').' value="'.$fieldset_arrk.'" '.(($field_type_cfwjm==$fieldset_arrk)?'selected':'').'>'.$fieldset_arrv.'</option>';
										}
										?>
										
									</select>
									<a href="https://www.codesmade.com/store/custom-field-for-wp-job-manager-pro/" target="_blank">Get Pro For Radio and checkbox field</a>
								</td>
							</tr>
							<tr>
								<th scope="row"><label>Field Location</label></th>
								<td>
									<select name="field_location_cfwjm" class="field_location_cfwjm" >
										<option value="job" <?php echo (($field_location_cfwjm=='job')?'selected':'')?>>Job</option>
										<option value="company" <?php echo (($field_location_cfwjm=='company')?'selected':'')?>>Company</option>
										
									</select>
								</td>
							</tr>
							<tr>
								<th scope="row"><label>Field Name</label></th>
								<td>
									<input type="text" required value="<?php echo $postdata->post_title;?>" class="regular-text" name="field_name_cfwjm">
								</td>
							</tr>
							<?php
							$opincud = array('select','radio','multiselect');
							?>
							<tr class="cfwjm_option" style="<?php echo (in_array($field_type_cfwjm, $opincud)?'':'display: none;');?>">
								<th scope="row"><label>Field Option</label></th>
								<td>
									<textarea  class="regular-text textheighs" name="field_option_cfwjm" placeholder="Option 1&#10;Option 2"><?php echo $field_option_cfwjm;?></textarea>
									<p class="description">Per Line add one Option</p>
								</td>
							</tr>
							<tr>
								<th scope="row"><label>Field Required</label></th>
								<td>
									<input type="checkbox"  class="regular-text" <?php echo (($field_required_cfwjm=='on')?'checked':'');?> name="field_required_cfwjm" disabled >
									<a href="https://www.codesmade.com/store/custom-field-for-wp-job-manager-pro/" target="_blank">Get Pro For Make Required Field</a>
								</td>
							</tr>
						</table>
						
						<p class="submit">
							<input type="hidden" name="action" value="update_new_field_cfwjm">
							<input type="hidden" name="edit_id" value="<?php echo $id;?>" >
							<input type="submit" name="submit"  class="button button-primary" value="Save">
						</p>
					</form>
				</div>
			</div>
		<?php
	}else{
	?>
	<table class="wp-list-table widefat fixed striped posts">
		<thead>
			<tr>
				<th>Field Name</th>
				<th>Field Type</th>
				<th>Field Location</th>
				<th>Key Meta</th>
				<th>Required</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$args = array(
					'post_type' => 'wpjmcf',
					'post_status' => 'publish',
					'posts_per_page' => -1
					);
			$the_query = new WP_Query( $args );
			while ( $the_query->have_posts() ) : $the_query->the_post();
				$post_id = get_the_ID();
				$field_required_cfwjm = get_post_meta( get_the_ID(), 'field_required_cfwjm', true );
			?>
			<tr>
				<td><?php the_title(); ?></td>
				<td><?php echo get_post_meta( get_the_ID(), 'field_type_cfwjm', true ); ?></td>
				<td><?php echo get_post_meta( get_the_ID(), 'field_location_cfwjm', true ); ?></td>
				<td>_field_cfwjm<?php echo $post_id; ?></td>
				<td><?php echo (($field_required_cfwjm=='on')?'Yes':'No');?></td>
				<td>
					<a class="button button-icon tips icon-edit" href="<?php echo admin_url( 'admin.php?page=cfwjm-fields&action=edit-cfwjm-fields&id='.get_the_ID());?>" ><?php _e('Edit', 'cfwjm'); ?></a>
					<a class="button button-icon tips icon-delete" href="<?php echo admin_url( 'admin.php?action=delete_field_cfwjm&id='.get_the_ID());?>" ><?php _e('Delete', 'cfwjm'); ?></a>
				</td>
			</tr>
			<?php
			endwhile;
			wp_reset_postdata();
			?>
		</tbody>
	</table>
	
	</div>
	<?php
	}
	?>
</div>

<div class="showpopmain">
		<div class="popupinner">
			<div class="postbox">
				<a class="closeicond" href="#"><span class="dashicons dashicons-no"></span></a>
				<div class="inside">
					<form action="#" method="post" id="wp_job_custom_form">
						<?php wp_nonce_field( 'cfwjm_nonce_action_add', 'cfwjm_nonce_field_add' ); ?>
						<h3><?php _e('WP Job Manager Custom Field Add', 'cfwjm'); ?></h3>
						<table class="form-table">
							<tr>
								<th scope="row"><label>Field Type</label></th>
								<td>
									<select name="field_type_cfwjm" class="field_type_cfwjm">
										<?php
										foreach ($this->fieldset_arr as $fieldset_arrk => $fieldset_arrv) {
											echo '<option '.(in_array($fieldset_arrk, $this->diable_arr)?'disabled':'').' value="'.$fieldset_arrk.'">'.$fieldset_arrv.'</option>';
										}
										?>
										
									</select>
									<a href="https://www.codesmade.com/store/custom-field-for-wp-job-manager-pro/" target="_blank">Get Pro For Radio and checkbox field</a>
								</td>
							</tr>
							<tr>
								<th scope="row"><label>Field Location</label></th>
								<td>
									<select name="field_location_cfwjm" class="field_location_cfwjm">
										<option value="job">Job</option>
										<option value="company">Company</option>
									</select>
								</td>
							</tr>
							<tr>
								<th scope="row"><label>Field Name</label></th>
								<td>
									<input type="text" required class="regular-text" name="field_name_cfwjm">
								</td>
							</tr>
							<tr class="cfwjm_option" style="display: none;">
								<th scope="row"><label>Field Option</label></th>
								<td>
									<textarea  class="regular-text textheighs" name="field_option_cfwjm" placeholder="Option 1&#10;Option 2"></textarea>
									<p class="description">Per Line add one Option</p>
								</td>
							</tr>
							<tr>
								<th scope="row"><label>Field Required</label></th>
								<td>
									<input type="checkbox"  class="regular-text" name="field_required_cfwjm" disabled>
									<a href="https://www.codesmade.com/store/custom-field-for-wp-job-manager-pro/" target="_blank">Get Pro For Make Required Field</a>
								</td>
							</tr>
						</table>
						
						<p class="submit">
							<input type="hidden" name="action" value="add_new_field_cfwjm">
							<input type="submit" name="submit"  class="button button-primary" value="Save">
						</p>
					</form>
				</div>
			</div>
			
		</div>
<?php
}



}
?>