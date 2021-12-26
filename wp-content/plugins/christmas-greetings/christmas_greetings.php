<?php
/*
	Plugin Name: Christmas Greetings 
	Plugin URI: https://wordpress.org/plugins/christmas-greetings/
	Description: Christmas greetings plugin provides greeting to your visitors With awesome customizable options. It provides up-sell promotion to woo-commerce Store products and coupons with personalized message.
	Date: 28/10/2021
	Version: 1.2.5
	Author: TechnoCrackers
	Author URI: https://technocrackers.com/
*/
define('PLUGIN_URL','https://technocrackers.com/');
if(!defined('ABSPATH')){ exit; }
function christmas_greetings_default(){
	$get_set=get_option('genral_settings');
	if($get_set==''){
		$display_on_pages=array();
		update_option('christmas_display_on', 'all');
		@update_option('display_on_pages', json_encode($display_on_pages));
		$christmas_greetings_settings = array('active' => 'Inactive' , 'snow' => 'dark' , 'greetings' => 'message' , 'start_date' => '' , 'end_date' => '', 'santa' => 'floating' , 'gifteffect' => 'move' , 'box' => 'center_align' , 'snow_radio_tc' => 'enable' , 'santa_radio_tc' => 'enable_santa','snow_font' => '❄','snow_color' => '#FFF','decoraation_radio_tc' => 'disable_decoration','tc_gift_radio'=>'gift_enable','song_radio_tc' => 'enable_song');
		$christmas_greetings_settings = json_encode($christmas_greetings_settings);
		update_option('genral_settings', $christmas_greetings_settings);
		$christmas_greetings_advance_settings = array('fontfamily' => 'Great_Vibes', 'color' => 'color1', 'msg' => 'Merry Christmas' , 'code' => 'FREE' , 'res_product_select' => '' , 'layout' => 'layout1',);
		$christmas_greetings_advance_settings = json_encode($christmas_greetings_advance_settings);
		update_option('advance_settings', $christmas_greetings_advance_settings);
	}
}
register_activation_hook( __FILE__ , 'christmas_greetings_default' );
function christmas_greetings_menu(){	
	$path = plugin_dir_url( __FILE__ );
	add_menu_page('christmas_greetings','Christmas-Greeting', 'add_users', 'christmas_greetings', 'christmas_greetings_options' , $path.'img/santa.png',null, 555);
}
add_action( 'admin_menu', 'christmas_greetings_menu' );
function custom_toolbar_link($wp_admin_bar){
	$path = plugin_dir_url( __FILE__ );
    $args = array('id' => 'christmas_greetings',
        'title' => _( '<img style="margin-top:4px;float:left;" src="'.$path.'img/santa.png"/>'.'Christmas-Greetings'), 
        'href' => esc_url( admin_url( '/options-general.php?page=christmas_greetings' ) ), 
        'meta' => array( 'class' => 'christmas_greetings', 'title' => 'Christmas-Greetings')
    );
    $wp_admin_bar->add_node($args);
}
add_action('admin_bar_menu', 'custom_toolbar_link', 999);
function christmas_greetings_setting( $links ){
	return array_merge( array('<a href="' . esc_url( admin_url( '/options-general.php?page=christmas_greetings' ) ) . '">' . __( 'Settings', 'textdomain' ) . '</a>'), $links );
}
add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'christmas_greetings_setting' );
function christmas_greetings_options(){
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('jquery-ui-datepicker');
	$path = plugin_dir_url( __FILE__ );					
	wp_enqueue_style('mycss1', $path.'css/style.css');
	wp_enqueue_style('mycss2', $path.'css/jquery-ui.css');	
	wp_enqueue_style('mycss5', $path.'css/jquery.dropdown.min.css');	
	wp_enqueue_style('mycss6', $path.'css/style2.css');
	wp_enqueue_script('myjs0', $path.'js/jscolor.js'); 
   	wp_enqueue_script('myjs2', $path.'js/tech_js.js'); 
   	wp_enqueue_script('myjs3', $path.'js/jquery.dropdown.js'); 	 
   	$settings_saved = '';
   	$settings_saved1 = '';
	$christmas_greetings_settings = array();
	$christmas_greetings_advance_settings = array();
	$template_gift = array();
	$admin_manager = current_user_can('administrator');
	$lic_obj = new christmas_greetings_lic_class();
	if ( ($admin_manager == 1) && ( isset( $_POST[ 'save' ] ) )){
		$nonce = $_REQUEST['_wpnonce'];
		if(wp_verify_nonce($nonce,'Save_Changes')){
			extract($_POST);
			update_option('christmas_display_on', $display_on);
			@update_option('display_on_pages', json_encode($display_on_pages));
			$christmas_greetings_settings = array('active' => $active , 'snow' => $snow , 'greetings' => $greetings , 'snow_font' => $snow_font , 'snow_color' => $snow_color , 'start_date' => $start_date , 'end_date' => $end_date, 'santa' => $santa , 'gifteffect' => $gifteffect , 'box' => $box , 'snow_radio_tc' => $snow_radio_tc , 'song_radio_tc' => $song_radio_tc, 'decoraation_radio_tc' => $decoraation_radio_tc , 'santa_radio_tc' => $santa_radio_tc,'tc_gift_radio'=>$gift_radio_tc);
			$christmas_greetings_settings = json_encode($christmas_greetings_settings);
			update_option('genral_settings', $christmas_greetings_settings);
			$christmas_greetings_advance_settings = array('fontfamily' => $fontfamily, 'color' => $color, 'msg' => $msg , 'code' => $code , 'res_product_select' => @$res_product_select , 'layout' => $layout,);
			$christmas_greetings_advance_settings = json_encode($christmas_greetings_advance_settings);
			update_option('advance_settings', $christmas_greetings_advance_settings);
			if($lic_obj->is_technocrackers()){
				update_option('newyear_display_on', $new_display_on);
				@update_option('display_on_pages_new', json_encode($display_on_pages_new));
				$newyear_greetings_settings = array('new_active' => $new_active , 'new_snow_radio_tc' => $new_snow_radio_tc , 'new_firework' => $new_firework , 'new_santa_radio_tc' => $new_santa_radio_tc , 'new_santa' => $new_santa, 'new_box' => $new_box , 'new_start_date' => $new_start_date , 'new_song_radio_tc' => $new_song_radio_tc, 'new_decoraation_radio_tc' => $new_decoraation_radio_tc ,'new_end_date' => $new_end_date , 'new_greetings' => $new_greetings , 'new_gifteffect' => $new_gifteffect,'new_tc_gift_radio'=>$new_gift_radio_tc);
				$newyear_greetings_settings = json_encode($newyear_greetings_settings);
				update_option('new_genral_settings', $newyear_greetings_settings);
				$newyear_greetings_advance_settings = array('new_fontfamily' => $new_fontfamily, 'new_color' => $new_color, 'new_msg' => $new_msg , 'new_code' => $new_code , 'new_res_product_select' => @$new_res_product_select , 'new_layout' => $new_layout,);
				$newyear_greetings_advance_settings = json_encode($newyear_greetings_advance_settings);
				update_option('new_advance_settings', $newyear_greetings_advance_settings);				
			}
			$settings_saved = true;
		}else{
			echo '<div id="message" class="updated fade" style="border-left-color:#a00;"><p><strong>'; _e( 'wp nonce key can not verify...!!!' ); echo'</strong></p></div>';
		}	 
	}
	if($admin_manager == 0){
		$settings_saved1 = 'false';
	}
	if (isset($_REQUEST['activate_license'])){
        if($lic_obj->christmas_greetings_active($_REQUEST['sample_license_key'])){        	
    		$newyear_greetings_settings = array('new_active' => 'Inactive'  , 'new_snow_radio_tc' => 'new_enable' , 'new_firework' => 'firework1' , 'new_santa_radio_tc' => 'new_enable_santa' , 'new_santa' => 'new_floating', 'new_box' => 'center_align' , 'new_start_date' => '' , 'new_end_date' => '' , 'new_greetings' => 'message' , 'new_gifteffect' => 'move','new_decoraation_radio_tc'=>'disable_decoration','new_song_radio_tc'=>'enable_song','new_tc_gift_radio'=>'new_gift_enable');			
			$newyear_greetings_settings = json_encode($newyear_greetings_settings);
			update_option('new_genral_settings', $newyear_greetings_settings,'','yes' );
			$newyear_greetings_advance_settings = array('new_fontfamily' => 'Great_Vibes', 'new_color' => 'color1', 'new_msg' => 'Happy New Year!', 'new_code' => 'FREE' , 'new_res_product_select' => '' , 'new_layout' => 'layout1',);
			$newyear_greetings_advance_settings = json_encode($newyear_greetings_advance_settings);
			update_option('new_advance_settings', $newyear_greetings_advance_settings,'','yes' );
        	echo '<div id="message" class="updated fade"><p><strong>'; _e( 'You license Activated successfuly...!!!' ); echo'</strong></p></div>';
        } else {
        	echo '<div id="message" class="updated fade" style="border-left-color:#a00;"><p><strong>'.$lic_obj->err.'</strong></p></div>';
        }
    }
	if (isset($_REQUEST['deactivate_license']) && $_REQUEST['deactivate_license'] == 'deactivate_license'){
        if($lic_obj->christmas_greetings_deactive()){
        	echo '<div id="message" class="updated fade"><p><strong>You license Deactivated successfuly...!!!</strong></p></div>';
        } else {
        	echo '<div id="message" class="updated fade" style="border-left-color:#a00;"><p><strong>'.$lic_obj->err.'</strong></p></div>';
        }
    }	
	echo '<div class="wrap"><h1>Christmas Greetings</h1>';
	if ($settings_saved == 'true'){ 
		echo '<div id="message" class="updated fade"><p><strong>'; _e( 'Options saved.' ); echo'</strong></p></div>';
	}
	if($settings_saved1 == 'false'){
		echo '<div id="message" class="updated fade" style="border-left-color:#a00;"><p><strong>'; _e( 'Options save Allow Only For Admin.' ); echo'</strong></p></div>';
	}
	$new_sett=get_option('genral_settings');
	if($new_sett!=''){
		$new_sett_res=json_decode($new_sett);
		$radio_active = $new_sett_res->active;
		$radio_snow = $new_sett_res->snow;
		$radio_font_snow = $new_sett_res->snow_font;
		$color_snow = $new_sett_res->snow_color;
		$radio_santa = $new_sett_res->santa;
		$tc_decoration_radio = $new_sett_res->decoraation_radio_tc;
		$tc_song_radio = $new_sett_res->song_radio_tc;
		$radio_greetings = $new_sett_res->greetings;
		$srt_date = $new_sett_res->start_date;
		$en_date = $new_sett_res->end_date;
		$gifteffect_res = $new_sett_res->gifteffect;
		$box_type = $new_sett_res->box;		
		$tc_snow_radio = $new_sett_res->snow_radio_tc;
		$tc_santa_radio = $new_sett_res->santa_radio_tc;
		$tc_gift_radio = $new_sett_res->tc_gift_radio;
		$set_temp=get_option('advance_settings');
		$sett_res1=json_decode($set_temp);
		$radio_fontfamily = $sett_res1->fontfamily;
		$radio_color = $sett_res1->color;
		$message_res = $sett_res1->msg;
		$coupon_code = $sett_res1->code;
		$product_res = $sett_res1->res_product_select;
		$radio_layout = $sett_res1->layout;			
	}
	if(get_option('new_advance_settings')){
		$new_set_temp=get_option('new_advance_settings');
		$new_sett_res1=json_decode($new_set_temp);
		$new_product_res = $new_sett_res1->new_res_product_select;	
	}
	$path =  plugin_dir_url( __FILE__ );	
	$produc_value=array();
	$new_produc_value=array();
	$product_found=false;
	if ( class_exists( 'WooCommerce' ) ){
		$args = array( 'post_type' => 'product','orderby'=> 'menu_order','order'=> 'ASC','posts_per_page'=> -1 ,'suppress_filters' => false);
		$the_query = new WP_Query( $args );        	
		while ( $the_query->have_posts() ) : $the_query->the_post();
			if ( !have_posts() ):
				$product_found=true;
				if((!empty($product_res)) && (in_array(get_the_ID(), $product_res))) 
				{					
					$produc_value[]=array('id'=>get_the_ID(),'name'=>get_the_title(),'selected'=>true);
				}
				else
				{
					$produc_value[]=array('id'=>get_the_ID(),'name'=>get_the_title(),'selected'=>false);
				}
				if((!empty($new_product_res)) && (in_array(get_the_ID(), $new_product_res))) 
				{					
					$new_produc_value[]=array('id'=>get_the_ID(),'name'=>get_the_title(),'selected'=>true);
				}
				else
				{
					$new_produc_value[]=array('id'=>get_the_ID(),'name'=>get_the_title(),'selected'=>false);
				}
			endif;			
		endwhile;
		wp_reset_query();
	}		
	$product_json=json_encode($produc_value);
	$new_product_json=json_encode($new_produc_value);
	$pages_array=array();
	$new_pages_array=array();
	$pages_selcetion=json_decode(get_option('display_on_pages'));
	$new_pages_selcetion=json_decode(get_option('display_on_pages_new'));
	foreach (get_pages() as $page_key => $page_value){
		if((!empty($pages_selcetion)) && (in_array($page_value->ID, $pages_selcetion))){
			$pages_array[]=array('id'=>$page_value->ID,'name'=>$page_value->post_title,'selected'=>true);
		}
		else{
			$pages_array[]=array('id'=>$page_value->ID,'name'=>$page_value->post_title,'selected'=>false);
		}
		if((!empty($new_pages_selcetion)) && (in_array($page_value->ID, $new_pages_selcetion))){
			$new_pages_array[]=array('id'=>$page_value->ID,'name'=>$page_value->post_title,'selected'=>true);
		}
		else{
			$new_pages_array[]=array('id'=>$page_value->ID,'name'=>$page_value->post_title,'selected'=>false);
		}
	}
	echo '
		<form method="post" id="gift_form"action="">
			<div class="main__wrap">
				<div class="tab_wrapper">
					<div data-tabs class="main-panel">
						<div id="tab_christmas" class="techno_main_tabs active"><h4><a href="#christmas">Christmas</a></h4></div>
						<div id="tab_newyear" class="techno_main_tabs "><h4><a href="#newyear">New Year</a></h4></div>
						<div id="tab_premium" class="techno_main_tabs "><h4><a href="#premium">Premium</a></h4></div>
					</div>
					<div data-panes>
						<div class="tab_christmas techno_sub_tabs active">
							<div class="vertical-tab" data-tabs>
								<div id="tab_christmas_general" class="techno_sub_christmas_tabs active">General Setting</div>
								<div id="teb_christmas_advance" class="techno_sub_christmas_tabs">Advanced Settings</div>
							</div>						
							<div data-panes class="vertical-tab-content">
								<div class="techno_sub_tabs_christmas_con sitedecoration tab_christmas_general active">							
									<div class="select_option-wrap">
										<p class="btn-switch">
											<input type="hidden" name="siteurl" value="'.get_site_url().'">					
										  	<input type="radio" id="no" name="active" value="Inactive" checked id="Inactive"'; if ( isset ( $radio_active ) ) checked( $radio_active, "Inactive" ); echo'class="btn-switch__radio btn-switch__radio_no" />		
										  	<input type="radio" id="yes" name="active" value="active"';if ( isset ( $radio_active ) ) checked( $radio_active, "active" ); echo' class="btn-switch__radio btn-switch__radio_yes" />
										 	<label for="yes" class="btn-switch__label btn-switch__label_yes"><span class="btn-switch__txt">Active</span></label>	
										  	<label for="no" class="btn-switch__label btn-switch__label_no"><span class="btn-switch__txt">Inactive</span></label>							
										</p>
										<div class="preview-btn"><label>preview</label></div>
										<div class="preview-popop">
											<div class="iframe-popop">
												<div class="iframe-popopwrap">
													<iframe width="100%" height="99%" scrolling="no" id="preview_iframe" src="" frameborder="0" allowfullscreen></iframe>
												</div>
											</div>
											<div class="close-overlay"></div>
										</div>
									</div>
									<div class="tabcontent01 li">
										<div class="tc_bg1">
											<div class="tc_head">	
												<h3>Snow</h3>	
												<div class="switch">
													<input type="radio" class="switch-input_snow switch-input" name="snow_radio_tc" value="enable" id="snow_enable" checked '; if ( isset ( $tc_snow_radio ) ) checked( $tc_snow_radio, "enable" ); echo'>
													<label for="snow_enable" class="switch-label switch-label-off">enable</label>
													<input type="radio" class="switch-input" name="snow_radio_tc" value="disable" id="snow_disable"'; if ( isset ( $tc_snow_radio ) ) checked( $tc_snow_radio, "disable" ); echo'>
													<label for="snow_disable" class="switch-label switch-label-on">disable</label>
													<span class="switch-selection"></span>
												</div>	
												<div class="clr"></div>
											</div>	
											<div class="tc_body">
												<div id="snow_show-me" class="single-tab-content-wrap image-wrap hide-option ';if($tc_snow_radio=='enable'){ echo "show-option";	} echo'">										
													<div class="tc_padding">
														<div> 
															<h5>snow 1</h5>
															<input type="radio" checked name="snow" value="dark" id="one"';if(isset($radio_snow)) checked($radio_snow,"dark"); echo'>
															<label id="one_label" class="role " for="one"><img id="one_img" class="role unselect ';if ($radio_snow=="dark"){echo 'selected';} echo'" src="'.$path.'img/dark-snow.png"></label>
														</div>
														<div>
															<h5>snow 2</h5>
															<input type="radio" name="snow" value="light" id="two"'; if(isset($radio_snow)) checked($radio_snow,"light"); echo'>
															<label id="two_label" class="role" for="two"><img id="two_img" class="role unselect ';if ($radio_snow=="light"){echo 'selected';} echo'" src="'.$path.'img/light-snow.png"></label>
														</div>
														<div>
															<h5>snow 3</h5>
															<input type="radio" name="snow" value="three" id="three"'; if(isset($radio_snow)) checked($radio_snow,"three"); echo'>
															<label for="three">
																<div id="three_img" class="role unselect ';if ( $radio_snow=="three" ){echo 'selected';} echo'" for="three">
																	<input style="display:none" checked class="radio_snow_font" type="radio" name="snow_font" id="snow_font1" value="❄" '; if(isset($radio_font_snow)) checked( $radio_font_snow, "❄" ); echo'><label id="snow_lable1" class="snow_lable '; if($radio_font_snow=="❄"){ echo'selected_snow'; } echo ' " for="snow_font1"style=" margin-left:3px; margin-top:3px;" ><img draggable="false" style="width: 26px !important;height: 28px !important;" role="img" class="emoji" alt="❄" src="https://s.w.org/images/core/emoji/12.0.0-1/svg/2744.svg"></label>
																	<input style="display:none"  class="radio_snow_font" type="radio" name="snow_font" id="snow_font2" value="❅" '; if(isset($radio_font_snow)) checked( $radio_font_snow, "❅" ); echo'><label id="snow_lable2" class="snow_lable '; if($radio_font_snow=="❅"){ echo'selected_snow'; } echo ' " for="snow_font2">❅</label>
																	<input style="display:none" class="radio_snow_font" type="radio" name="snow_font" id="snow_font3" value="❆" '; if(isset($radio_font_snow)) checked( $radio_font_snow, "❆" ); echo'><label id="snow_lable3" class="snow_lable '; if($radio_font_snow=="❆"){ echo'selected_snow'; } echo ' " for="snow_font3" >❆</label>
																	<input type="text" value="'.$color_snow.'" name="snow_color" class="jscolor">
																</div>
															</label>
														</div>	
													</div>
												</div>	
											</div>	
										</div>
										<div class="tabcontent03">
											<div class="tc_bg1">
												<div class="tc_head">	
													<h3> santa</h3>
													<div class="switch">
														<input type="radio" class="switch-input_santa switch-input" name="santa_radio_tc" value="enable_santa" id="enable_santa" checked '; if( isset ( $tc_santa_radio ) ) checked( $tc_santa_radio, "enable_santa" ); echo'>
														<label for="enable_santa" class="switch-label switch-label-off">enable</label>
														<input type="radio" class="switch-input" name="santa_radio_tc" value="disable_santa" id="disable_santa"'; if( isset ( $tc_santa_radio ) ) checked( $tc_santa_radio, "disable_santa" ); echo'>
														<label for="disable_santa" class="switch-label switch-label-on">disable</label>
														<span class="switch-selection"></span>
													</div>		
													<div class="clr"></div>
												</div>						
												<div id="santa_show-me"class="single-tab-content-wrap image-wrap hide-option ';if($tc_santa_radio=='enable_santa'){ echo "show-option";	} echo'">
													<div class="tc_padding">
														<div> 
															<h5>santa floating</h5>
															<input type="radio" name="santa" checked value="floating" id="one2"'; if(isset($radio_santa)) checked($radio_santa,"floating"); echo'>
															<label class="role2 " for="one2"><img id="santa_one" class="unselect '; if($radio_santa=="floating" ){echo 'selected';} echo'" src="'.$path.'img/santa-hello.gif"></label>
														</div>
														<div>
															<h5>santa moving</h5>
															<input type="radio" name="santa" value="moving" id="two2"'; if(isset($radio_santa)) checked($radio_santa, "moving"); echo'>
															<label class="role2 " for="two2"><img id="santa_two" class="unselect ';if($radio_santa=="moving"){echo 'selected';} echo'" src="'.$path.'img/santa.gif"></label>
														</div>
													</div>
												</div>
											</div>		
										</div>
										<div class="giftbox-position">
											<div class="tc_bg">	
												<div class="tc_head">	
													<h5>Giftbox</h5>
													<div class="switch">
														<input type="radio" class="switch-input_snow switch-input" name="gift_radio_tc" value="gift_enable" id="gift_enable" checked '; if(isset($tc_gift_radio)) checked($tc_gift_radio,"gift_enable"); echo'>
														<label for="gift_enable" class="switch-label switch-label-off">enable</label>
														<input type="radio" class="switch-input" name="gift_radio_tc" value="gift_disable" id="gift_disable"'; if (isset($tc_gift_radio)) checked($tc_gift_radio,"gift_disable"); echo'>
														<label for="gift_disable" class="switch-label switch-label-on">disable</label>
														<span class="switch-selection"></span>
													</div>	
													<div class="clr"></div>
												</div>		
												<div class="select-type">
													<span class="gift_box_tc hide-gift ';if($tc_gift_radio=='gift_enable'){ echo "show-gift"; } echo'">
														<h5>select giftbox position</h5>
														<div class="select-type-left">
															<input  checked type="radio" name="box" value="left_align" id="boxleft"'; if(isset($box_type)) checked( $box_type, "left_align" ); echo'>
															<label class="" for="boxleft">left</label>
														</div>						 
														<div class="select-type-center">
															<input  type="radio" name="box" value="center_align" id="boxcenter"'; if(isset($box_type)) checked( $box_type, "center_align" ); echo'>
															<label  for="boxcenter">center</label>
														</div>
														<div class="select-type-right">
															<input type="radio" name="box" value="right_align" id="boxright" '; if(isset($box_type)) checked( $box_type, "right_align" ); echo'>
															<label class="" for="boxright">right</label>
														</div>
													</span>
													<span class="gift_box_tc_msg hide-gift ';if($tc_gift_radio=='gift_disable'){ echo "show-gift"; } echo'">
														<div class="select-type-left">
															<h5 style="color: rgb(255, 0, 0);">you can'."'".'t use additional greeting type because gift box is disable</h5>
														</div>	
													</span>
												</div>
											</div>
										</div>
										<div class="tabcontent03">
											<div class="tc_bg_aa">
												<div class="tc_head">	
													<h3> decoration image </h3>
													<div class="switch">
														<input type="radio" class="switch-input_santa switch-input" name="decoraation_radio_tc" value="enable_decoration" id="enable_decoration" checked '; if ( isset ( $tc_decoration_radio ) ) checked( $tc_decoration_radio, "enable_decoration" ); echo'>
														<label for="enable_decoration" class="switch-label switch-label-off">enable</label>
														<input type="radio" class="switch-input" name="decoraation_radio_tc" value="disable_decoration" id="disable_decoration"'; if ( isset ( $tc_decoration_radio ) ) checked( $tc_decoration_radio, "disable_decoration" ); echo'>
														<label for="disable_decoration" class="switch-label switch-label-on">disable</label>
														<span class="switch-selection"></span>
													</div>
													<div class="clr"></div>												
												</div>
												<div class="tc_body">			
													<div id="decoration_img_show" class="single-tab-content-wrap image-wrap hide-option ';if($tc_decoration_radio=='enable_decoration'){ echo "show-option";	} echo'">										
														<div class="tc_padding">
															<div> 														
																<label class="new_role" for=""><img id="" style="width:100% !important;" class="unselect" src="'.$path.'img/decoration.png"></label>
															</div>
														</div>							
													</div>	
												</div>
											</div>
										</div>
									</div>
									<div class="tabcontent02 li">
										<div class="duration-date">
											<div class="tc_bg">
												<div class="tc_head">
													<h5>select Duration</h5>
													<input class="clear_btn" type="button" name="clear" value="clear date">
												</div>
												<div class="tc_body">	
													<div class="select-type-date">
														<div class="">
															<label for="date_s" >Start Date</label>
															<input type="text" value="'.$srt_date.'" class="sdate" name="start_date" id="txtFrom"/>
														</div>						 
														<div class="">
															<label for="date_e" >End Date</label>
															<input type="text" class="edate" value="'.$en_date.'" name="end_date" id="txtTo"/>
														</div>						
													</div>		
												</div>	
											</div>		
										</div>
										<div class="greetings-type">
											<div class="tc_bg gift_box_tc hide-gift ';if($tc_gift_radio=='gift_enable'){ echo "show-gift"; } echo'">
												<div class="tc_head">
													<h5>select greetings type</h5>
												</div>
												<div class="tc_body">
													<div class="select-type">';
														if($lic_obj->is_technocrackers())
														{ echo '
															<div class="select-type-message">
																<input class="check_msg" type="radio" name="greetings" value="message" checked id="greetings1"'; if ( isset ( $radio_greetings ) ) checked( $radio_greetings, "message" ); echo'>
																<label class="" for="greetings1">MESSAGE</label>
																<div class="single-msj">
																	<div>
																		<div id="ch_adv_btn">
																			<label><h1>Advance Settings</h1></label>
																		</div>
																	</div>
																	<div></div>
																</div>											
															</div>
															<div class="select-type-coupon">
																<input class="check_coupon" type="radio" name="greetings" value="coupon" id="greetings2"'; if ( isset ( $radio_greetings ) ) checked( $radio_greetings, "coupon" ); echo'>
																<label class="" for="greetings2">COUPON CODE</label>
															</div>
															<div class="select-type-product">';
															if(class_exists('WooCommerce')) 
															{
																if($product_found==true) 
																{
																	echo '<input class="check_product" type="radio" name="greetings" value="product_promo" id="greetings3" '; if ( isset ( $radio_greetings ) ) checked( $radio_greetings, "product_promo" ); echo'>
																	<label class="" for="greetings3">PRODUCT PROMOTION</label>';
																	if($radio_greetings=='product_promo'){ global $product_show; $product_show ="show-option";	}
																} 
																else 
																{
																	echo '<input style="opacity:0.3" class="check_product" disabled type="radio" name="greetings" value="product_promo" id="greetings3" >
																	<label style="opacity:0.3" for="greetings3">PRODUCT PROMOTION </label>
																	<label  style="color:red;"> (NO PRODUCT AVAILABLE....!!!)</label>';
																}
															} 
															else 
															{
																echo '<input style="opacity:0.3" class="check_product" disabled type="radio" name="greetings" value="product_promo" id="greetings3" >
																<label style="opacity:0.3" for="greetings3">PRODUCT PROMOTION </label>
																<label style="color:red;"> (WOOCOMMERCE IS NOT ACTIVE OR AVAILABLE....!!!)</label>';
															} echo '
															</div>';  
														}
														else
														{
															$path = plugin_dir_url( __FILE__ ); echo'
															<div class="select-type-message">
																<input class="new_check_msg" type="radio" name="greetings" value="new_message" checked id="greetings1">
																<label class="" for="greetings1">MESSAGE</label>
																<div class="single-msj" id="activate_premium">
																	<div><h2>Comming Soon Available in premium version</h2></div>
																	<div><img src="'.$path.'img/premium.png"></div>
																</div>
															</div>						 
															<div class="select-type-coupon">
																<input class="new_check_coupon" disabled style="opacity:0.3" type="radio" name="greetings" value="greetings2" id="greetings2">
																<label style="opacity:0.3" for="greetings2">COUPON CODE</label>
															</div>
															<div class="select-type-product">
																<input class="new_check_product" disabled style="opacity:0.3" type="radio" name="greetings" value="greetings3" id="greetings3">
																<label style="opacity:0.3" for="greetings3">PRODUCT PROMOTION</label>
															</div>';
														} echo '
													</div>
												</div>
											</div>
											<div class="giftbox-effect">
												<div class="tc_bg">
													<span class="gift_box_tc hide-gift ';if($tc_gift_radio=='gift_enable'){ echo "show-gift"; } echo'">
														<div class="tc_head">
															<h5>select giftbox effect </h5>
														</div>
														<div class="tc_body">
															<div class="select-type">
																<div class="select-type-message">
																	<input checked type="radio" name="gifteffect" value="move" id="movingeffect"'; if ( isset ( $gifteffect_res ) ) checked( $gifteffect_res, "move" ); echo'>
																	<label for="movingeffect"><img id="move_gift" class="unselect '; if ( $gifteffect_res=="move" ){echo 'selected';} echo'" src="'.$path.'img/zomm.gif"></label>
																</div>
																<div class="select-type-coupon">
																	<input  type="radio" name="gifteffect" value="bell" id="belleffect"'; if ( isset ( $gifteffect_res ) ) checked( $gifteffect_res, "bell" ); echo'>
																	<label  for="belleffect"><img id="bell_gift" class="unselect '; if ( $gifteffect_res=="bell" ){echo 'selected';} echo'" src="'.$path.'img/bell.gif"></label>
																</div>
																<div class="select-type-product">
																	<input type="radio" name="gifteffect" value="sparkalffect" id="sparkalffect" '; if ( isset ( $gifteffect_res ) ) checked( $gifteffect_res, "sparkalffect" ); echo'>
																	<label for="sparkalffect"><img id="sparkalffect_gift" class="unselect ' ;if ( $gifteffect_res=="sparkalffect" ){echo 'selected';} echo'" src="'.$path.'img/sparker.gif"></label>
																</div>
															</div>
														</div>	
													</span>
												</div>
											</div>
											<div class="tabcontent03">
												<div class="tc_bg_aa">
													<div class="tc_head">	
														<h3> Christmas Music</h3>
														<div class="switch">
															<input type="radio" class="switch-input_santa switch-input" name="song_radio_tc" value="enable_song" id="enable_song" checked '; if ( isset ( $tc_song_radio ) ) checked( $tc_song_radio, "enable_song" ); echo'>
															<label for="enable_song" class="switch-label switch-label-off">enable</label>
															<input type="radio" class="switch-input" name="song_radio_tc" value="disable_song" id="disable_song"'; if ( isset ( $tc_song_radio ) ) checked( $tc_song_radio, "disable_song" ); echo'>
															<label for="disable_song" class="switch-label switch-label-on">disable</label>
															<span class="switch-selection"></span>
														</div>
														<div class="clr"></div>
													</div>						
													<div class="single-tab-content-wrap image-wrap">
														<div class="tc_padding">
														</div>
													</div>
												</div>
											</div>
											<div class="display-on">
												<div class="tc_bg">
													<span class="gift_box_tc hide-gift show-gift">
														<div class="tc_head">
															<h5>Display On</h5>
														</div>
														<div class="tc_body">
															<div class="select-type">																
																<div class="select-type-all">
																	<input type="radio" checked name="display_on" '.checked(get_option('christmas_display_on'),'all',false).' value="all" id="display_on_all">
																	<label for="display_on_all">All Pages</label>
																</div>
																<div class="select-type-home">
																	<input type="radio" name="display_on" '.checked(get_option('christmas_display_on'),'home',false).' value="home" id="display_on_home">
																	<label for="display_on_home">Home Page</label>
																</div>';if($lic_obj->is_technocrackers()){ echo '
																<div class="select-type-pages">
																	<input type="radio" name="display_on" '.checked(get_option('christmas_display_on'),'pages',false).' value="pages" id="display_on_pages">
																	<label for="display_on_pages">Selected Pages</label>
																	<span style="display:'.((get_option('christmas_display_on')=='pages')?'inline':'none').';" class="select_pagis_aria">
																		(<small>leave empty for display on all pages</small>)
																		<div class="display_on_dropdown">
																			<select name="display_on_pages[]" style="" id="display_on_page" multiple placeholder="Select Pages"></select>
																		</div>
																	</span>
																</div>'; } echo'
															</div>
														</div>	
													</span>
												</div>
											</div>									
										</div>
									</div>					
								</div>		
								<div class="teb_christmas_advance techno_sub_tabs_christmas_con template">				
									<span class="gift_box_tc_msg hide-gift ';if($tc_gift_radio=='gift_disable'){ echo "show-gift"; } echo'">
										<div class="select-type-left gift_box_alert_msg">
											<h5 style="color: rgb(255, 0, 0);">!you can'."'".'t use additional greeting type because gift box is disable</h5>
										</div>	
									</span>	
									<div class="template-tab" >
										<div class="active">
											<h2>greetings</h2>
										</div>
										<P class="preview-btn_tc"><label>preview</label></p>
										<div class="preview-popop_tc">
											<div class="iframe-popop">
												<div class="iframe-popopwrap">
													<iframe width="100%" height="99%" scrolling="no" id="preview_iframe_tc" src="" frameborder="0" allowfullscreen></iframe>
												</div>
											</div>
											<div class="close-overlay_1"></div>
										</div>
									</div>					
									<div class="template-tab-content" >	
										<div>
											<div class="title-setting"> 
												<div class="tc_bg">
													<div class="tc_head">
														<h3>Title</h3>
														<div class="clr"></div>
													</div>
													<div class="tc_body">
														<div class="part-wrap">
															<h5>
																select title font family
															</h5>
															<div class="input-gp"><input type="radio" checked name="fontfamily" value="Great_Vibes" id="fontfamily1" '; if ( isset ( $radio_fontfamily ) ) checked( $radio_fontfamily, "Great_Vibes" ); echo'>
																<label class="fontfamily1 " for="fontfamily1">Merry Christmas</label>
															</div>						 
															<div class="input-gp"><input type="radio" name="fontfamily" value="Lobster" id="fontfamily2" '; if ( isset ( $radio_fontfamily ) ) checked( $radio_fontfamily, "Lobster" ); echo'>
																<label class="fontfamily2 " for="fontfamily2">Merry Christmas</label>
															</div>
															<div class="input-gp"><input type="radio" name="fontfamily" value="Dancing_Scrip" id="fontfamily3"'; if ( isset ( $radio_fontfamily ) ) checked( $radio_fontfamily, "Dancing_Scrip" ); echo'>
																<label class="fontfamily3 " for="fontfamily3">Merry Christmas</label>
															</div>						
														</div>
														<div class="part-wrap">
															<h5>
																select title color
															</h5>
															<div class="input-gp"><input type="radio" checked name="color" value="color1" id="color1"'; if ( isset ( $radio_color ) ) checked( $radio_color, "color1" ); echo'>
																<label class="color1 " for="color1">Merry Christmas</label>
															</div>						 
															<div class="input-gp"><input type="radio" name="color" value="color2" id="color2"'; if ( isset ( $radio_color ) ) checked( $radio_color, "color2" ); echo'>
																<label class="color2 " for="color2">Merry Christmas</label>
															</div>
															<div class="input-gp"><input type="radio" name="color" value="color3" id="color3"';if ( isset ( $radio_color ) ) checked( $radio_color, "color3" ); echo'>
																<label class="color3 " for="color3">Merry Christmas</label>
															</div>						
														</div>
													</div>
												</div>
											</div>
											<div id="msg" class="message-setting ';if($radio_greetings=='message'){ echo "show-option";	} echo'">
												<div class="tc_bg">
													<div class="tc_head">
														<h3>message</h3>
														<div class="clr"></div>
													</div>	
													<div class="tc_body">					
														<div class="part-wrap ">
															<textarea class="unselect" name="msg" maxlength="85" rows="4" cols="50" id="text_tc" placeholder="Enter message...">'. $message_res.'</textarea>
														</div>
													</div>
												</div>
											</div>
											<div id="coupon" class="coupon-code hide-option ';if($radio_greetings=='coupon'){ echo "show-option";	} echo'">				
												<div class="tc_bg">	
													<div class="tc_head">
														<h3>COUPON CODE</h3>
														<div class="clr"></div>
													</div>	
													<div class="tc_body">
														<div class="code-input">
															<input class="unselect" type="text" maxlength="10" value="'. $coupon_code .'"name="code" placeholder="Enter code">
														</div>	
													</div>			
												</div>
											</div>
											<div id="product" class="product-setting hide-option '.@$product_show.'">
												<div class="tc_bg">
													<div class="tc_head">	
														<h3>Product</h3>
														<div class="clr"></div>
													</div>	
													<div class="tc_body">				
														<div class="part-wrap">
															<div class="type-product">
																<div class="row">     
																	<div class="dropdown-mul-1">
																		<select name="res_product_select[]" style="" id="res_product_select" multiple placeholder="Select"></select>
																	</div>  
																</div>
															</div>
														</div>
														<div class="part-wrap" style="margin-top:0"><h3>Layout</h3>
															<div class="layout-wrap">
																<div>								
																	<input type="radio" name="layout" checked value="layout1" id="layout1"'; if ( isset ( $radio_layout ) ) checked( $radio_layout, "layout1" ); echo'>
																	<label class="role " for="layout1"><img id="layout1_img" class="unselect ' ;if ( $radio_layout=="layout1" ){echo 'selected';} echo'" src="'.$path.'img/product-img1.png"></label>
																</div>
																<div>									
																	<input type="radio" name="layout" value="layout2" id="layout2" '; if ( isset ( $radio_layout ) ) checked( $radio_layout, "layout2" ); echo'>
																	<label class="role " for="layout2"><img id="layout2_img" class="unselect ' ;if ( $radio_layout=="layout2" ){echo 'selected';} echo'" src="'.$path.'img/product-img2.png"></label>
																</div>
																<div>									
																	<input type="radio" name="layout" value="layout3" id="layout3" '; if ( isset ( $radio_layout ) ) checked( $radio_layout, "layout3" ); echo'>
																	<label class="role " for="layout3"><img id="layout3_img" class="unselect ' ;if ( $radio_layout=="layout3" ){echo 'selected';} echo'" src="'.$path.'img/product-img3.png"></label>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>					
									</div>
								</div>
							</div>
						</div>
						<div class="tab_newyear techno_sub_tabs" data-panes2>';
						if($lic_obj->is_technocrackers()){ 	
							$sett=get_option('new_genral_settings');
							$sett_res=json_decode($sett);
							$new_radio_active = $sett_res->new_active;
							$new_tc_snow_radio = $sett_res->new_snow_radio_tc;
							$radio_firework = $sett_res->new_firework;
							$new_tc_santa_radio = $sett_res->new_santa_radio_tc;
							$new_tc_decoration_radio = $sett_res->new_decoraation_radio_tc;
							$new_tc_song_radio = $sett_res->new_song_radio_tc;
							$new_tc_gift_radio = $sett_res->new_tc_gift_radio;
							$new_radio_santa = $sett_res->new_santa;
							$new_box_type = $sett_res->new_box;
							$new_srt_date = $sett_res->new_start_date;
							$new_en_date = $sett_res->new_end_date;
							$new_radio_greetings = $sett_res->new_greetings;
							$new_gifteffect_res = $sett_res->new_gifteffect;
							$new_radio_fontfamily = $new_sett_res1->new_fontfamily;
							$new_radio_color = $new_sett_res1->new_color;
							$new_message_res = $new_sett_res1->new_msg;
							$new_coupon_code = $new_sett_res1->new_code;
							$new_radio_layout = $new_sett_res1->new_layout;	
							echo '				        			
							<div class="vertical-tab" data-tabs>
								<div id="tab_newyear_general" class="techno_sub_newyear_tabs active">General Setting</div>
								<div id="teb_newyear_advance" class="techno_sub_newyear_tabs">Advanced Settings</div>
							</div>
							<div data-panes class="vertical-tab-content">
								<div class="sitedecoration tab_newyear_general techno_sub_tabs_new_con active">							
									<div class="new_select_option-wrap">
										<p class="new_btn-switch">				
										  	<input type="radio" id="new_no" name="new_active" value="new_Inactive" checked id="Inactive"'; if ( isset ( $new_radio_active ) ) checked( $new_radio_active, "new_Inactive" ); echo'class="new_btn-switch__radio new_btn-switch__radio_no" />		
										  	<input type="radio" id="new_yes" name="new_active" value="new_active"';if ( isset ( $new_radio_active ) ) checked( $new_radio_active, "new_active" ); echo' class="new_btn-switch__radio new_btn-switch__radio_yes" />
										 	<label for="new_yes" class="new_btn-switch__label new_btn-switch__label_yes"><span class="new_btn-switch__txt">Active</span></label>	
										  	<label for="new_no" class="new_btn-switch__label new_btn-switch__label_no"><span class="new_btn-switch__txt">Inactive</span></label>							
										</p>
										<div class="new_preview-btn">
											<label>preview</label>
										</div>
										<div class="new_preview-popop">
											<div class="new_iframe-popop">
												<div class="new_iframe-popopwrap">
													<iframe width="100%" height="99%" scrolling="no" id="new_preview_iframe" src="" frameborder="0" allowfullscreen></iframe>
												</div>
											</div>
											<div class="close-overlay"></div>
										</div>
									</div>
									<div class="tabcontent01 li">
										<div class="tc_bg1">
											<div class="tc_head">	
												<h3>Fireworks</h3>	
												<div class="new_switch">
												  	<input type="radio" class="new_switch-input_snow new_switch-input" name="new_snow_radio_tc" value="new_enable" id="new_snow_enable" checked '; if ( isset ( $new_tc_snow_radio ) ) checked( $new_tc_snow_radio, "enable" ); echo'>
												  	<label for="new_snow_enable" class="new_switch-label new_switch-label-off">enable</label>
												  	<input type="radio" class="new_switch-input" name="new_snow_radio_tc" value="disable" id="new_snow_disable"'; if ( isset ( $new_tc_snow_radio ) ) checked( $new_tc_snow_radio, "disable" ); echo'>
												  	<label for="new_snow_disable" class="new_switch-label new_switch-label-on">disable</label>
												  	<span class="new_switch-selection"></span>
												</div>	
												<div class="clr"></div>
											</div>	
											<div class="tc_body">
												<div id="new_snow_show-me" class="single-tab-content-wrap image-wrap hide-option ';if($new_tc_snow_radio=='new_enable'){ echo "show-option";	} echo'">										
													<div class="tc_padding">
														<div>
															<h5>firework 1</h5>
															<input type="radio" checked name="new_firework" value="firework1" id="firework_one"';if ( isset ( $radio_firework ) ) checked( $radio_firework, "firework1" ); echo'>
															<label class="new_role" for="firework_one"><img id="firework1_id" class="unselect ';if($radio_firework=='firework1'){ echo "selected";	} echo'" src="'.$path.'img/dark-snow.png"></label>
														</div>
													<!--<div>
															<h5>firework 2</h5>
															<input type="radio" name="new_firework" value="firework2" id="firework_two"'; if ( isset ( $radio_firework ) ) checked( $radio_firework, "firework2" ); echo'>
															<label class="new_role" for="firework_two"><img id="firework2_id" class="unselect ';if($radio_firework=='firework2'){ echo "selected";	} echo'" src="'.$path.'img/light-snow.png"></label>
														</div>-->
													</div>
												</div>	
											</div>	
										</div>							
										<div class="tabcontent03">
											<div class="tc_bg1">
												<div class="tc_head">	
													<h3> santa</h3>
													<div class="new_switch">
													 	<input type="radio" class="new_switch-input_snow new_switch-input" name="new_santa_radio_tc" value="new_enable_santa" id="new_enable_santa" checked '; if ( isset ( $new_tc_santa_radio ) ) checked( $new_tc_santa_radio, "new_enable_santa" ); echo'>
													  	<label for="new_enable_santa" class="new_switch-label new_switch-label-off">enable</label>
													  	<input type="radio" class="new_switch-input" name="new_santa_radio_tc" value="new_disable_santa" id="new_disable_santa"'; if ( isset ( $new_tc_santa_radio ) ) checked( $new_tc_santa_radio, "new_disable_santa" ); echo'>
													  	<label for="new_disable_santa" class="new_switch-label new_switch-label-on">disable</label>
													  	<span class="new_switch-selection"></span>
													</div>		
													<div class="clr"></div>
												</div>						
												<div id="new_santa_show-me"class="single-tab-content-wrap image-wrap hide-option ';if($new_tc_santa_radio=='new_enable_santa'){ echo "show-option";	} echo'">
													<div class="tc_padding">
														<div> 
															<h5>santa floating</h5>
															<input type="radio" name="new_santa" checked value="new_floating" id="new_one2"'; if ( isset ( $new_radio_santa ) ) checked( $new_radio_santa, "new_floating" ); echo'>
															<label class="new_role2" for="new_one2"><img id="new_santa_one" class="unselect ';if($new_radio_santa=='new_floating'){ echo "selected"; } echo' " src="'.$path.'img/santa-hello.gif"></label>
														</div>
														<div>
															<h5>santa moving</h5>
															<input type="radio" name="new_santa" value="new_moving" id="new_two2"'; if ( isset ( $new_radio_santa ) ) checked( $new_radio_santa, "new_moving" ); echo'>
															<label class="new_role2" for="new_two2"><img id="new_santa_two" class="unselect ';if($new_radio_santa=='new_moving'){ echo "selected";	} echo'" src="'.$path.'img/santa.gif"></label>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="giftbox-position">
											<div class="tc_bg">	
												<div class="tc_head">
													<h5>Giftbox</h5>
													<div class="switch">
														<input type="radio" class="switch-input_snow switch-input" name="new_gift_radio_tc" value="new_gift_enable" id="new_gift_enable" checked '; if ( isset ( $new_tc_gift_radio ) ) checked( $new_tc_gift_radio, "new_gift_enable" ); echo'>
														<label for="new_gift_enable" class="switch-label switch-label-off">enable</label>
														<input type="radio" class="switch-input" name="new_gift_radio_tc" value="new_gift_disable" id="new_gift_disable"'; if ( isset ( $new_tc_gift_radio ) ) checked( $new_tc_gift_radio, "new_gift_disable" ); echo'>
														<label for="new_gift_disable" class="switch-label switch-label-on">disable</label>
														<span class="switch-selection"></span>
													</div>	
													<div class="clr"></div>
												</div>
												<div class="select-type">
													<span class="new_gift_box_tc hide-gift ';if($new_tc_gift_radio=='new_gift_enable'){ echo "show-gift"; } echo'">
														<h5>select giftbox position</h5>
														<div class="new_select-type-left">
															<input  checked type="radio" name="new_box" value="left_align" id="new_boxleft"'; if ( isset ( $new_box_type ) ) checked( $new_box_type, "left_align" ); echo'>
															<label for="new_boxleft">left</label>
														</div>
														<div class="new_select-type-center">
															<input  type="radio" name="new_box" value="center_align" id="new_boxcenter"'; if ( isset ( $new_box_type ) ) checked( $new_box_type, "center_align" ); echo'>
															<label for="new_boxcenter">center</label>
														</div>
														<div class="new_select-type-right">
															<input type="radio" name="new_box" value="right_align" id="new_boxright" '; if ( isset ( $new_box_type ) ) checked( $new_box_type, "right_align" ); echo'>
															<label for="new_boxright">right</label>
														</div>
													</span>
													<span class="new_gift_box_tc_msg hide-gift ';if($new_tc_gift_radio=='new_gift_disable'){ echo "show-gift"; } echo'">
														<div class="select-type-left">
															<h5 style="color: rgb(255, 0, 0);">you can'."'".'t use additional greeting type because gift box is disable</h5>
														</div>	
													</span>
												</div>
											</div>
										</div>
										<div class="tabcontent03">
											<div class="tc_bg_aa">
												<div class="tc_head">	
													<h3> decoration image </h3>
													<div class="switch">
												  		<input type="radio" class="switch-input_santa switch-input" name="new_decoraation_radio_tc" value="new_enable_decoration" id="new_enable_decoration" checked '; if ( isset ( $new_tc_decoration_radio ) ) checked( $new_tc_decoration_radio, "enable_decoration" ); echo'>
												  		<label for="new_enable_decoration" class="switch-label switch-label-off">enable</label>
												  		<input type="radio" class="switch-input" name="new_decoraation_radio_tc" value="new_disable_decoration" id="new_disable_decoration"'; if ( isset ( $new_tc_decoration_radio ) ) checked( $new_tc_decoration_radio, "disable_decoration" ); echo'>
												  		<label for="new_disable_decoration" class="switch-label switch-label-on">disable</label>
												  		<span class="switch-selection"></span>
													</div>
													<div class="clr"></div>
												</div>
												<div class="tc_body">			
													<div id="new_decoration_img_show" class="single-tab-content-wrap image-wrap hide-option ';if($new_tc_decoration_radio=='new_enable_decoration'){ echo "show-option";	} echo'">										
														<div class="tc_padding">
															<div> 														
																<label class="new_role" for=""><img id="" style="width:100% !important;" class="unselect" src="'.$path.'img/decoration.png"></label>
															</div>
														</div>							
													</div>	
												</div>
											</div>
										</div>
									</div>
									<div class="tabcontent02 li">
										<div class="duration-date">
											<div class="tc_bg">
												<div class="tc_head">
													<h5>select Duration</h5>
													<input class="clear_btn" type="button" name="clear" value="clear date">
												</div>
												<div class="tc_body">	
													<div class="select-type-date">
														<div class="">
															<label for="new_date_s" >Start Date</label>
															<input type="text" value="'. $new_srt_date .'" class="new_sdate" name="new_start_date" id="new_txtFrom"/>
														</div>						 
														<div class="">
															<label for="new_date_e" >End Date</label>
															<input type="text" class="new_edate" value="'. $new_en_date .'" name="new_end_date" id="new_txtTo"/>
														</div>					
													</div>		
												</div>	
											</div>		
										</div>
										<div class="greetings-type">
											<div class="tc_bg new_gift_box_tc hide-gift ';if($new_tc_gift_radio=='new_gift_enable'){ echo "show-gift"; } echo'">
												<div class="tc_head">
													<h5>select greetings type</h5>
												</div>
												<div class="tc_body">
													<div class="select-type">
														<div class="select-type-message">
														<input class="new_check_msg" type="radio" name="new_greetings" value="new_message" checked id="new_greetings1"'; if ( isset ( $new_radio_greetings ) ) checked( $new_radio_greetings, "new_message" ); echo'>
															<label for="new_greetings1">MESSAGE</label>
															<div class="single-msj">
																<div>
																	<div id="new_adv_btn">
																		<label>
																			<h1>Advance Settings</h1>
																		</label>
																	</div>
																</div>
																<div></div>
															</div>
														</div>	
														<div class="select-type-coupon">
															<input class="new_check_coupon" type="radio" name="new_greetings" value="new_coupon" id="new_greetings2"'; if ( isset ( $new_radio_greetings ) ) checked( $new_radio_greetings, "new_coupon" ); echo'>
															<label for="new_greetings2">COUPON CODE</label>
														</div>
														<div class="select-type-product">';
															if(class_exists('WooCommerce')){
																if ($product_found==true){ echo '
																	<input class="new_check_product" type="radio" name="new_greetings" value="new_product_promo" id="new_greetings3" '; if ( isset ( $new_radio_greetings ) ) checked( $new_radio_greetings, "new_product_promo" ); echo'>
																	<label for="new_greetings3">PRODUCT PROMOTION</label>';
																	if($new_radio_greetings=='new_product_promo'){ $new_product_show="show-option";	}
																} else { echo '
																	<input style="opacity:0.3" class="new_check_product" disabled type="radio" name="new_greetings" value="new_product_promo" id="new_greetings3" >
																	<label style="opacity:0.3" for="new_greetings3">PRODUCT PROMOTION </label>
																	<label style="color:red;"> (NO PRODUCT AVAILABLE....!!!)</label>';
																}
															} else { echo '
																<input style="opacity:0.3" class="new_check_product" disabled type="radio" name="new_greetings" value="new_product_promo" id="new_greetings3" >
																<label style="opacity:0.3" for="new_greetings3">PRODUCT PROMOTION </label>
																<label style="color:red;"> (WOOCOMMERCE IS NOT ACTIVE OR AVAILABLE....!!!)</label>';
															} echo '
														</div>
													</div>	
												</div>
											</div>
											<div class="giftbox-effect">
												<div class="tc_bg new_gift_box_tc hide-gift ';if($new_tc_gift_radio=='new_gift_enable'){ echo "show-gift"; } echo'">
													<div class="tc_head">
														<h5>
															select giftbox effect
														</h5>
													</div>
													<div class="tc_body">
														<div class="select-type">
															<div class="new_select-type-message">
																<input checked type="radio" name="new_gifteffect" value="move" id="new_movingeffect"'; if ( isset ( $new_gifteffect_res ) ) checked( $new_gifteffect_res, "move" ); echo'>
																<label for="new_movingeffect"><img id="new_move_gift" class="unselect ';if($new_gifteffect_res=='move'){ echo "selected";	} echo'" src="'.$path.'img/zomm.gif"></label>
															</div>						 
															<div class="new_select-type-coupon">
																<input  type="radio" name="new_gifteffect" value="bell" id="new_belleffect"'; if ( isset ( $new_gifteffect_res ) ) checked( $new_gifteffect_res, "bell" ); echo'>
																<label for="new_belleffect"><img id="new_bell_gift" class="unselect ';if($new_gifteffect_res=='bell'){ echo "selected";	} echo'" src="'.$path.'img/bell.gif"></label>
															</div>
															<div class="new_select-type-product">
																<input type="radio" name="new_gifteffect" value="sparkalffect" id="new_sparkalffect" '; if ( isset ( $new_gifteffect_res ) ) checked( $new_gifteffect_res, "sparkalffect" ); echo'>
																<label for="new_sparkalffect"><img id="new_sparkalffect_gift" class="unselect ';if($new_gifteffect_res=='sparkalffect'){ echo "selected";	} echo'" src="'.$path.'img/sparker.gif"></label>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="tabcontent03">
												<div class="tc_bg_aa">
													<div class="tc_head">	
														<h3> NewYear Music</h3>
														<div class="switch">
															<input type="radio" class="switch-input_santa switch-input" name="new_song_radio_tc" value="enable_song" id="new_enable_song" checked '; if ( isset ( $new_tc_song_radio ) ) checked( $new_tc_song_radio, "enable_song" ); echo'>
															<label for="new_enable_song" class="switch-label switch-label-off">enable</label>
															<input type="radio" class="switch-input" name="new_song_radio_tc" value="disable_song" id="new_disable_song"'; if ( isset ( $new_tc_song_radio ) ) checked( $new_tc_song_radio, "disable_song" ); echo'>
															<label for="new_disable_song" class="switch-label switch-label-on">disable</label>
															<span class="switch-selection"></span>
														</div>
														<div class="clr"></div>
													</div>						
													<div class="single-tab-content-wrap image-wrap">
														<div class="tc_padding">	
														</div>
													</div>
												</div>
											</div>													
											<div class="display-on">
												<div class="tc_bg">
													<span class="gift_box_tc hide-gift show-gift">
														<div class="tc_head">
															<h5>Display On</h5>
														</div>
														<div class="tc_body">
															<div class="select-type">																
																<div class="select-type-all">
																	<input type="radio" checked name="new_display_on" '.checked(get_option('newyear_display_on'),'all',false).' value="all" id="new_display_on_all">
																	<label for="new_display_on_all">All Pages</label>
																</div>
																<div class="select-type-home">
																	<input type="radio" name="new_display_on" '.checked(get_option('newyear_display_on'),'home',false).' value="home" id="new_display_on_home">
																	<label for="new_display_on_home">Home Page</label>
																</div>																
																<div class="select-type-pages">
																	<input type="radio" name="new_display_on" '.checked(get_option('newyear_display_on'),'pages',false).' value="pages" id="new_display_on_pages">
																	<label for="new_display_on_pages">Selected Pages</label>
																	<span style="display:'.((get_option('newyear_display_on')=='pages')?'inline':'none').';" class="new_select_pages_aria">
																		(<small>Leave empty for display on all pages</small>)
																		<div class="new_display_on_dropdown">
																			<select name="display_on_pages_new[]" style="" id="display_on_pages_new" multiple placeholder="Select Pages"></select>
																		</div>
																	</span>
																</div>
															</div>
														</div>	
													</span>
												</div>
											</div>
										</div>	
									</div>
								</div>
								<div class="template teb_newyear_advance techno_sub_tabs_new_con">
									<span class="new_gift_box_tc_msg hide-gift ';if($new_tc_gift_radio=='new_gift_disable'){ echo "show-gift"; } echo'">
										<div class="select-type-left gift_box_alert_msg">
											<h5 style="color: rgb(255, 0, 0);">!you can'."'".'t use additional greeting type because gift box is disable</h5>
										</div>	
									</span>	
									<div class="template-tab" >
										<div class="active">
											<h2>greetings</h2>
										</div>	
										<P class="new_preview-btn_tc">
											<label>preview</label>
										</p>
										<div class="new_preview-popop_tc">
											<div class="new_iframe-popop">
												<div class="new_iframe-popopwrap">
													<iframe width="100%" height="99%" scrolling="no" id="new_preview_iframe_tc" src="" frameborder="0" allowfullscreen></iframe>
												</div>
											</div>
											<div class="close-overlay_1"></div>
										</div>
									</div>
									<div class="template-tab-content">
										<div>
											<div class="title-setting"> 
												<div class="tc_bg">
													<div class="tc_head">
														<h3>Title</h3>
														<div class="clr"></div>
													</div>
													<div class="tc_body">
														<div class="part-wrap">
															<h5>
																select title font family
															</h5>
															<div class="new_input-gp">
																<input type="radio" checked name="new_fontfamily" value="Great_Vibes" id="new_fontfamily1" '; if ( isset ( $new_radio_fontfamily ) ) checked( $new_radio_fontfamily, "Great_Vibes" ); echo'>
																<label class="new_fontfamily1" for="new_fontfamily1">Happy New Year</label>
															</div>						 
															<div class="new_input-gp">
																<input type="radio" name="new_fontfamily" value="Lobster" id="new_fontfamily2" '; if ( isset ( $new_radio_fontfamily ) ) checked( $new_radio_fontfamily, "Lobster" ); echo'>
																<label class="new_fontfamily2" for="new_fontfamily2">Happy New Year</label>
															</div>
															<div class="new_input-gp">
																<input type="radio" name="new_fontfamily" value="Dancing_Scrip" id="new_fontfamily3"'; if ( isset ( $new_radio_fontfamily ) ) checked( $new_radio_fontfamily, "Dancing_Scrip" ); echo'>
																<label class="new_fontfamily3" for="new_fontfamily3">Happy New Year</label>
															</div>						
														</div>
														<div class="part-wrap">
															<h5>
																select title color
															</h5>
															<div class="new_input-gp">
																<input type="radio" checked name="new_color" value="new_color1" id="new_color1"'; if ( isset ( $new_radio_color ) ) checked( $new_radio_color, "new_color1" ); echo'>
																<label class="new_color1" for="new_color1">Happy New Year</label>
															</div>						 
															<div class="new_input-gp">
																<input type="radio" name="new_color" value="new_color2" id="new_color2"'; if ( isset ( $new_radio_color ) ) checked( $new_radio_color, "new_color2" ); echo'>
																<label class="new_color2" for="new_color2">Happy New Year</label>
															</div>
															<div class="new_input-gp">
																<input type="radio" name="new_color" value="new_color3" id="new_color3"';if ( isset ( $new_radio_color ) ) checked( $new_radio_color, "new_color3" ); echo'>
																<label class="new_color3" for="new_color3">Happy New Year</label>
															</div>	
														</div>
													</div>
												</div>
											</div>
											<div id="new_msg" class="message-setting ';if($new_radio_greetings=='new_message'){ echo "show-option";	} echo'">
												<div class="tc_bg">
													<div class="tc_head">
														<h3>message</h3>
														<div class="clr"></div>
													</div>	
													<div class="tc_body">					
														<div class="part-wrap">
															<textarea name="new_msg" maxlength="85" rows="4" cols="50" id="new_text_tc" placeholder="Enter message...">'. $new_message_res.'</textarea>
														</div>
													</div>
												</div>
											</div>
											<div id="new_coupon" class="coupon-code hide-option ';if($new_radio_greetings=='new_coupon'){ echo "show-option";	} echo'">				
												<div class="tc_bg">	
													<div class="tc_head">
														<h3>COUPON CODE</h3>
														<div class="clr"></div>
													</div>
													<div class="tc_body">
														<div class="code-input">
															<input type="text" maxlength="10" value="'. $new_coupon_code .'"name="new_code" placeholder="Enter code">
														</div>	
													</div>
												</div>
											</div>
											<div id="new_product" class="product-setting hide-option '.@$new_product_show.'">
												<div class="tc_bg">
													<div class="tc_head">	
														<h3>Product</h3>
														<div class="clr"></div>
													</div>	
													<div class="tc_body">				
														<div class="part-wrap">
															<div class="type-product">
																<div class="row">     
																	<div class="new_dropdown-mul-1">
																		<select name="new_res_product_select[]" style="" id="new_res_product_select" multiple placeholder="Select"></select>
																	</div>
																</div>
															</div>
														</div>
														<div class="part-wrap" style="margin-top:0"><h3>Layout</h3>
															<div class="layout-wrap">
																<div>								
																	<input type="radio" name="new_layout" checked value="layout1" id="new_layout1"'; if ( isset ( $new_radio_layout ) ) checked( $new_radio_layout, "layout1" ); echo'>
																	<label for="new_layout1"><img id="new_layout1_img" class="new_role unselect ';if($new_radio_layout=='layout1'){ echo "selected";	} echo'" src="'.$path.'img/product-img1.png"></label>
																</div>
																<div>									
																	<input type="radio" name="new_layout" value="layout2" id="new_layout2" '; if ( isset ( $new_radio_layout ) ) checked( $new_radio_layout, "layout2" ); echo'>
																	<label  for="new_layout2"><img id="new_layout2_img" class="new_role unselect ';if($new_radio_layout=='layout2'){ echo "selected";	} echo'" src="'.$path.'img/product-img2.png"></label>
																</div>
																<div>					
																	<input type="radio" name="new_layout" value="layout3" id="new_layout3" '; if ( isset ( $new_radio_layout ) ) checked( $new_radio_layout, "layout3" ); echo'>
																	<label for="new_layout3"><img id="new_layout3_img" class="new_role unselect ';if($new_radio_layout=='layout3'){ echo "selected";	} echo'" src="'.$path.'img/product-img3.png"></label>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>';
							} else { echo'
								<div class="vertical-tab" data-tabs></div>
								<div data-panes class="vertical-tab-content">
									<div class="active pains_content">
										<div class="col-50">
											<h2>Christmas & NewYear Greetings</h2>
											<h4 class="paid_color">	Woo-commerce/PremiumFeatures:</h4>
											<p class="paid_color">01.All features of free plugin.</p>
											<p class="paid_color">02.Christmas and New year theme.</p>
											<p class="paid_color">03.Fireworks effects for New year theme.</p>
											<p class="paid_color">04.Coupon code promotion with greeting message on both themes</p>
											<p class="paid_color">05.Featured product listing with greeting message from multiple options for both themes.</p>
										</div>
										<div class="col-50">
											<a href="https://technocrackers.com/christmas-greetings/" target="_blank">
												<img src="'.$path.'/img/premium.png">
											</a>
											<div class="content_right">
												<p>Buy Activation Key form Here..</p>
												<p><a href="https://technocrackers.com/christmas-greetings/" target="_blank">Buy Now...</a></p>
											</div>
										</div>
									</div>
								</div>';
							} echo '
						</div>
						<div class="tab_premium techno_sub_tabs" data-panes3>
							<div class="vertical-tab" data-tabs></div>						
							<div data-panes class="vertical-tab-content">
								<div class="tab_premium_general sitedecoration active">';
	    							if($lic_obj->is_technocrackers()){ echo'
										<div class="col-50">
											<h2> Thank You Phurchasing ...!!!</h2>
											<h4 class="paid_color">Deactivate Yore License:</h4>
											<p class="submit">
								               	<label id="lic_deactive_btn" class="button-primary">Deactive</label>
								               	<input type="hidden" id="deactivate_license" name="deactivate_license">
								           	</p>
										</div>';			
							        } else {
							            echo'
										<div class="col-50">
											<h2>Christmas & NewYear Greetings</h2>
											<h4 class="paid_color">	Woo-commerce/PremiumFeatures:</h4>
											<p class="paid_color">01.All features of free plugin.</p>
											<p class="paid_color">02.Christmas and New year theme.</p>
											<p class="paid_color">03.Fireworks effects for New year theme.</p>
											<p class="paid_color">04.Coupon code promotion with greeting message on both themes</p>
											<p class="paid_color">05.Featured product listing with greeting message from multiple options for both themes.</p>
											<p><label for="sample_license_key">License Key :</label><input class="regular-text" type="text" id="sample_license_key" name="sample_license_key"  value="'.get_option('sample_license_key').'" ></p>
											<p class="submit">
								               	<input type="submit" name="activate_license" value="Activate" class="button-primary-active" />
								           	</p>
										</div>
										<div class="col-50">
											<a href="https://technocrackers.com/christmas-greetings/" target="_blank">
												<img src="'.plugin_dir_url( __FILE__ ).'/img/premium.png">
											</a>
											<div class="content_right">
												<p>Buy Activation Key form Here..</p>
												<p><a href="https://technocrackers.com/christmas-greetings/" target="_blank">Buy Now...</a></p>
											</div>
										</div>';
							        } echo '
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>			
			<p class="submit"><input class="button-primary" name="save" type="submit" value="Save"></p>';wp_nonce_field('Save_Changes'); echo '
		</form>
		<p style="text-align:center;">If you like Christmas-Greeting please leave us a <a href="https://wordpress.org/support/plugin/christmas-greetings/reviews/" target="_blank" style="text-decoration:none;">★★★★★</a> rating. A huge thanks in advance! Version 1.2.1</p>
		<p style="text-align:center;">Author <a href="https://wordpress.org/support/users/technocrackers/" target="_blank">Technocrackers</a></p>
	</div>
	<script type="text/javascript">
		jQuery(window).ready(function(e){
			var term = '.$product_json.';
			var new_term = '.$new_product_json.';
			var page_data = '.json_encode($pages_array).';
			var new_page_data = '.json_encode($new_pages_array).';
			jQuery(".dropdown-mul-1").dropdown({data:term,limitCount:40,multipleMode:"label",choice: function(e){
	 			//console.log(this);
			}});
			jQuery(".new_dropdown-mul-1").dropdown({data:new_term,limitCount:40,multipleMode:"label"});
			jQuery(".display_on_dropdown").dropdown({data:page_data,limitCount:40,multipleMode:"label", placeholder:"Select Pages"});
			jQuery(".new_display_on_dropdown").dropdown({data:new_page_data,limitCount:40,multipleMode:"label", placeholder:"Select Pages"});
			jQuery("#lic_deactive_btn").click(function(e){
				jQuery("#deactivate_license").val("deactivate_license");
				jQuery("#gift_form").submit();
			});
		});	
	</script>';
}
class christmas_greetings_lic_class {
   	public $err;
    private $wp_option  = 'product_1450';
    public function is_technocrackers()
    {
        $lic = get_option($this->wp_option);
        if(!empty( $lic )){
           $var_res=unserialize(base64_decode($lic));
            if( $var_res['d'] == strtotime(date('d-m-Y'))){
                return true;               
            } else {
                return $this->chack_lic_status($var_res['l']);
            }
        } else {
            delete_option($this->wp_option);
            return false;
        }
    }
    public function christmas_greetings_active($lic_key)
    {        
        return $this->chack_lic_status($lic_key);
    }    
    public function chack_lic_status($key)
    {
        $tc_site_url = preg_replace( "#^[^:/.]*[:/]+#i", "", get_site_url());        
        $lic_src = PLUGIN_URL.'?license_key='.$key.'&slm_action=slm_activate&registered_domain='.$tc_site_url.'&item_reference=christmas_greetings';
        $lic_res = wp_remote_get($lic_src, array('timeout' => 20, 'sslverify' => false));
        if(is_array($lic_res))
        {
            $lic_res = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', utf8_encode($lic_res['body']));
            $lic_res_data = json_decode($lic_res);
            if($lic_res_data->result == 'success' || $lic_res_data->error_code == 40 || $lic_res_data->error_code ==110)
            {
                $lic_key = base64_encode(serialize(array('l'=>$key,'d'=>strtotime(date('d-m-Y')),'s'=>((isset($lic_res_data->error_code)) ? $lic_res_data->error_code : ''))));
                update_option($this->wp_option, $lic_key);
                return true;
            }
            else
            {
                $this->err = $lic_res_data->message;
                delete_option($this->wp_option);
                return false;
            }
        }
    } 
    public function christmas_greetings_deactive(){
        $tc_site_url = preg_replace( "#^[^:/.]*[:/]+#i", "", get_site_url());        
    	$lic_data = unserialize(base64_decode(get_option($this->wp_option)));
        $deact_url = PLUGIN_URL.'?registered_domain='.$tc_site_url.'&slm_action=slm_deactivate&license_key=' . $lic_data['l'];
        $response = wp_remote_get($deact_url, array('timeout' => 20, 'sslverify' => false));
        if(is_array($response))
        {
            $json = $response['body']; 
            $json = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', utf8_encode($json));
            $license_data = json_decode($json);
        	delete_option($this->wp_option);
        	delete_option('new_genral_settings');
        	delete_option('new_advance_settings');
	        if($license_data->result == 'success'){
	            return true;
	        }else{
                $this->err = $license_data->message;
	            return false;
	        }
        }
    }   
    public function show_christmas_gift_status(){
    	$pages_selcetion=json_decode(get_option('display_on_pages'));
		$new_pages_selcetion=json_decode(get_option('display_on_pages_new'));
    	if((get_option('christmas_display_on')=='home' && is_front_page()) || (get_option('christmas_display_on')=='all') || (@in_array(get_the_ID(), $pages_selcetion) || (is_home() && @in_array(get_option('page_for_posts'), $pages_selcetion))) || (empty($pages_selcetion) && get_option('christmas_display_on')=='pages')){
	       	include('christmas/christmas.php');
			new techno_cristmas();
		}
		elseif(class_exists('WooCommerce'))
		{
			if(is_shop() && @in_array(woocommerce_get_page_id('shop'), $pages_selcetion)){
				include('christmas/christmas.php');
				new techno_cristmas();
			}
		}
	    if($this->is_technocrackers()){
			if((get_option('newyear_display_on')=='home' && is_front_page()) || (get_option('newyear_display_on')=='all') || (@in_array(get_the_ID(), $new_pages_selcetion) || (is_home() && @in_array(get_option('page_for_posts'), $new_pages_selcetion))) || (empty($new_pages_selcetion) && get_option('newyear_display_on')=='pages')){
				include('new-year/new-year.php');
				new newyear_greeting();
			}
	        elseif(class_exists('WooCommerce'))
	        {
	        	if(is_shop() && @in_array(woocommerce_get_page_id('shop'), $new_pages_selcetion)){
	        		include('new-year/new-year.php');
					new newyear_greeting();
	        	}
	        }
        }
    }
}
function show_christmas_gift(){
	$techno_run = new christmas_greetings_lic_class();
	$techno_run->show_christmas_gift_status();
}
add_action('wp_head', 'show_christmas_gift' );?>