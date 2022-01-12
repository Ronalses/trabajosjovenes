<?php
/**
 * This class is loaded on the back-end since its main job is 
 * to display the Admin to box.
 */

class CFWJM_Frontend {
	public $fieldset_inarr = array();
	public function __construct () {
		$this->fieldset_inarr = array('select','radio','multiselect');
		add_filter( 'submit_job_form_fields', array( $this, 'form_fields' ) );
		add_filter( 'job_manager_job_listing_data_fields', array( $this, 'job_listing_data_fields' ) );
		add_action( 'single_job_listing_meta_end', array( $this, 'job_listing_data_fields_display' )  );
	}

	function form_fields( $fields ) {
		
		$args = array(
					    'post_type' => 'wpjmcf',
						'post_status' => 'publish',
						'posts_per_page' => -1
					);
		$postslist = get_posts( $args );
		if (!empty($postslist)) {
			foreach ($postslist as $postslistk => $postslistv) {
				$post_id = $postslistv->ID;
				$c_type = get_post_meta( $post_id, 'field_type_cfwjm', true );
				$c_location = get_post_meta( $post_id, 'field_location_cfwjm', true );
				$c_key = 'field_cfwjm'.$post_id;
				$fields[ $c_location ][$c_key] = array(
												'label'       => $postslistv->post_title,  
												'type'        => $c_type,          
												'placeholder' => $postslistv->post_title, 
												'priority'    => 20,
											);
				$field_required_cfwjm = get_post_meta( $post_id, 'field_required_cfwjm', true );
				if($field_required_cfwjm=='on'){
					$fields[ $c_location ][$c_key]['required'] = true;
				}
				if (in_array($c_type, $this->fieldset_inarr)) {
					$field_option_cfwjm = get_post_meta( $post_id, 'field_option_cfwjm', true );
					$field_option_cfwjmar = explode("\n", $field_option_cfwjm);
					$field_option_cfwjmarr = array();
					foreach ($field_option_cfwjmar as $keya => $valuea) {
						$field_option_cfwjmarr[$valuea]=$valuea;
					}
					//print_r($field_option_cfwjmarr);
					$fields[ $c_location ][$c_key]['options']=$field_option_cfwjmarr;
				}
			}
		}
		return $fields;
	}
	function job_listing_data_fields( $fields ) {

		$args = array(
					    'post_type' => 'wpjmcf',
						'post_status' => 'publish',
						'posts_per_page' => -1
					);
		$postslist = get_posts( $args );
		if (!empty($postslist)) {
			foreach ($postslist as $postslistk => $postslistv) {
				$post_id = $postslistv->ID;
				$c_type = get_post_meta( $post_id, 'field_type_cfwjm', true );
				$c_key = '_field_cfwjm'.$post_id;
				$fields[$c_key] = array(
												'label'       => $postslistv->post_title,  
												'type'        => $c_type,          
												'placeholder' => $postslistv->post_title, 
											);
				if (in_array($c_type, $this->fieldset_inarr)) {
					$field_option_cfwjm = get_post_meta( $post_id, 'field_option_cfwjm', true );
					$field_option_cfwjmar = explode("\n", $field_option_cfwjm);
					$field_option_cfwjmarr = array();
					foreach ($field_option_cfwjmar as $keya => $valuea) {
						$field_option_cfwjmarr[$valuea]=$valuea;
					}
					//print_r($field_option_cfwjmarr);
					$fields[$c_key]['options']=$field_option_cfwjmarr;
				}
			}
		}
		return $fields;
	}

	function job_listing_data_fields_display() {
	  global $post;

	  $args = array(
					    'post_type' => 'wpjmcf',
						'post_status' => 'publish',
						'posts_per_page' => -1
					);
		$postslist = get_posts( $args );
		if (!empty($postslist)) {
			foreach ($postslist as $postslistk => $postslistv) {
				$post_id = $postslistv->ID;
				$c_key = '_field_cfwjm'.$post_id;
				$c_value = get_post_meta( $post->ID, $c_key, true );
				if ($c_value) {
					echo '<li><strong>' .__($postslistv->post_title.':', 'cfwjm') . '</strong> ' . esc_html( $c_value ) . '</li>';
				}
				
			}
		}

	  
	}
}