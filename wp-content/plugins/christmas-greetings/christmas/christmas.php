<?php
class techno_cristmas
{
	function __construct()
    {
       	$this->cristmas_gift();
    }
	function cristmas_gift()
	{
		$new_preview='';
		$radio_active='';
		$preview_techno='';
		$preview_gift_tc='';
		$date=false;
		$path =  plugin_dir_url( __FILE__ );
		$song_path = $path.'../song/christmas-music.mp3';		
		extract($_GET);
		if( $new_preview!='new_preview') 
		{
			if( $preview_techno=='preview') 
			{
				echo '<script type="text/javascript">jQuery(document).ready(function(){jQuery("#wpadminbar").addClass("hide-option");});</script>';
				if(isset($preview_tc))
				{
					$preview_gift_tc=$preview_tc;
				}
				if($preview_gift_tc=='preview_gift')
				{
					echo '<script type="text/javascript">jQuery(document).ready(function(){jQuery("#div2").css("display" , "block");jQuery("#snow").addClass("open");});</script>';
				}
				$product_res ='';
				$date=true;
				$radio_active = 'active';
				$radio_snow = $snow;
				$radio_santa = $santa;
				$radio_font_snow = $snow_font;
				$color_snow = $snow_color;
				$radio_greetings = $greetings;
				$srt_date = $start_date;
				$en_date = $end_date;
				$gifteffect_res = $gifteffect;
				$box_type = $box;
				$radio_fontfamily = $fontfamily;
				$radio_color = $color;
				$message_res = $msg;
				$coupon_code = $code;
				if(isset($res_product_select)){
					$product_res = $res_product_select;
				}
				$radio_layout = $layout;
				$tc_decoration_radio = $decoraation_radio_tc;
				$tc_song_radio = $song_radio_tc;
				$tc_snow_radio = $snow_radio_tc;
				$tc_santa_radio = $santa_radio_tc;
				$tc_gift_radio = $gift_radio_tc;
			}
			else
			{	/****get value from database*****/
				$sett=get_option('genral_settings');
				$sett_res=json_decode($sett);
				$radio_active = $sett_res->active;
				$radio_snow = $sett_res->snow;
				$radio_font_snow = $sett_res->snow_font;
				$color_snow = $sett_res->snow_color;
				$radio_santa = $sett_res->santa;
				$radio_greetings = $sett_res->greetings;
				$srt_date = $sett_res->start_date;
				$en_date = $sett_res->end_date;
				$gifteffect_res = $sett_res->gifteffect;
				$box_type = $sett_res->box;
				$tc_snow_radio = $sett_res->snow_radio_tc;
				$tc_decoration_radio = $sett_res->decoraation_radio_tc;
				$tc_song_radio = $sett_res->song_radio_tc;
				$tc_santa_radio = $sett_res->santa_radio_tc;
				$tc_gift_radio = $sett_res->tc_gift_radio;
				$set_temp=get_option('advance_settings');
				$sett_res1=json_decode($set_temp);
				$radio_fontfamily = $sett_res1->fontfamily;
				$radio_color = $sett_res1->color;
				$message_res = $sett_res1->msg;
				$coupon_code = $sett_res1->code;
				$product_res = $sett_res1->res_product_select;
				$radio_layout = $sett_res1->layout;
			}
			$date_start=strtotime(str_replace('-', '/', $srt_date));
			$date_end=strtotime(str_replace('-', '/', $en_date));
			$current_date = strtotime(date('d-m-Y'));
			if(($current_date >= $date_start) && ($current_date <= $date_end)){ $date=true; }
		}		
		if($radio_active=="active")
		{
			if($date==true)
			{	
				wp_enqueue_style('mycss2', $path.'css/christmus.css');
				echo '<script type="text/javascript" src="'.$path.'js/cristmas.js" defer></script>';
				if($tc_song_radio=='enable_song')
				{ echo '
					<script type="text/javascript">
						if(!window.jQuery){
							console.log("jQuery dynamic loded")
						   var script = document.createElement("script");
						   script.type = "text/javascript";
						   script.src = "https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.1.min.js";
						   document.getElementsByTagName("head")[0].appendChild(script);
						}
						jQuery(document).ready(function() 
						{
							var xmasMusic = document.createElement("audio");
							xmasMusic.setAttribute("src", "'.$song_path.'");
							xmasMusic.setAttribute("autoplay", "autoplay");
							xmasMusic.volume=0.3;
							jQuery.get();
							xmasMusic.addEventListener("load", function() 
							{
								xmasMusic.play();
							}, true);
							xmasMusic.addEventListener("ended", function() 
							{
								this.currentTime = 0;
								this.play();
							}, false);  
							jQuery("body").append("<div id='."'".'sound_switcher'."'".'><i class='."'".'fa fa-play'."'".'></i><i class='."'".'fa fa-pause'."'".'></i></div>");
							jQuery("#sound_switcher").click(function() 
							{
								if (xmasMusic.paused == false) 
								{
									xmasMusic.pause();
									jQuery("#sound_switcher").addClass("play_sound_switcher");
								} 
								else 
								{
									xmasMusic.play();
									jQuery("#sound_switcher").removeClass("play_sound_switcher");
								}
							});
						});
					</script>';	
				}
				if($tc_snow_radio=='enable')
				{
					if($radio_snow=='light')
					{
						echo '<script type="text/javascript" src="'.$path.'js/snowstorm.js" defer></script>';
					}
					else
					{
					 	if($radio_snow=='dark')
					 	{
							echo '<div id="snowflakeContainer"><p class="snowflake" style="color: rgb(239, 239, 239);">*</p></div>';
					 	}
					 	else
					 	{
					 		echo '<div id="snowflakeContainer"><p class="snowflake" style="color:#'.$color_snow.' !important;">'.$radio_font_snow.'</p></div>';
					 	}
						echo '<script type="text/javascript" src="'.$path.'js/snow01.js" defer></script>';
					}					
				}
				echo '<div class="template_style">';
					if( $tc_decoration_radio=="enable_decoration")
					{
						echo '<div class="decoration_img"><img src="'.$path.'../img/decoration.png"></div>';
					}
					if($tc_santa_radio=='disable_santa') { $radio_santa=''; }
					if($radio_santa=='floating')
					{ 
						echo '<div id="christmas-greetings-santa"><img src="'.$path.'img/santa-hello.gif"/></div>';
					}
					if($radio_santa=="moving")
					{ 
						echo' <div id="santa-moving" class="banner-wrap "><div class="cloude-img"></div><div class="santa"><img src="'.$path.'img/santas.gif"/></div></div>';
					} 
					if($tc_gift_radio=='gift_enable')
					{   echo '		
						<div id="div2" style="display:'.$preview_gift_tc.'">
							<div class="hide-div" >
								<i aria-hidden="true">x</i>
							</div>
							<div class="message-box-part">
								<div class="tree-prt">
									<div class="tree-img img01"><img src="'.$path.'img/sonw-flex-a.png"/></div><div class="tree-img img02"><img src="'.$path.'img/sonw-flex-a.png"/></div>
									<div class="tree-img img03"><img src="'.$path.'img/sonw-flex-b.png"/></div><div class="tree-img img04"><img src="'.$path.'img/sonw-flex-a.png"/></div>
									<div class="tree-img img05"><img src="'.$path.'img/sonw-flex-a.png"/></div><div class="tree-img img06"><img src="'.$path.'img/sonw-flex-a.png"/></div>
									<div class="tree-img img07"><img src="'.$path.'img/sonw-flex-b.png"/></div><div class="tree-img img08"><img src="'.$path.'img/sonw-flex-a.png"/></div>
									<div class="tree-img img09"><img src="'.$path.'img/sonw-flex-b.png"/></div><div class="tree-img img10"><img src="'.$path.'img/sonw-flex-a.png"/></div>
									<div class="tree-img img11"><img src="'.$path.'img/sonw-flex-a.png"/></div><div class="tree-img img12"><img src="'.$path.'img/sonw-flex-b.png"/></div>
									<div class="tree-img img13"><img src="'.$path.'img/sonw-flex-b.png"/></div><div class="tree-img img14"><img src="'.$path.'img/sonw-flex-a.png"/></div>
									<div class="tree-img img15"><img src="'.$path.'img/sonw-flex-b.png"/></div><div class="tree-img img16"><img src="'.$path.'img/sonw-flex-a.png"/></div>
								</div>
								<div class="circle-wrap">
									<img class="cr01" src="'.$path.'img/glow-b.png"/><img class="cr02" src="'.$path.'img/glow-b.png"/><img class="cr03" src="'.$path.'img/glow-b.png"/>
									<img class="cr04" src="'.$path.'img/glow-b.png"/><img class="cr05" src="'.$path.'img/glow-b.png"/><img class="cr06" src="'.$path.'img/glow-b.png"/>
									<img class="cr07" src="'.$path.'img/glow-b.png"/><img class="cr08" src="'.$path.'img/glow-b.png"/><img class="cr09" src="'.$path.'img/glow-b.png"/>
									<img class="cr10" src="'.$path.'img/glow-b.png"/><img class="cr11" src="'.$path.'img/glow-b.png"/><img class="cr12" src="'.$path.'img/glow-b.png"/>
									<img class="cr13" src="'.$path.'img/glow-b.png"/><img class="cr14" src="'.$path.'img/glow-b.png"/><img class="cr15" src="'.$path.'img/glow-b.png"/>
									<img class="cr16" src="'.$path.'img/glow-b.png"/><img class="cr17" src="'.$path.'img/glow-b.png"/><img class="cr18" src="'.$path.'img/glow-b.png"/>
									<img class="cr19" src="'.$path.'img/glow-b.png"/><img class="cr20" src="'.$path.'img/glow-b.png"/><img class="cr21" src="'.$path.'img/glow-b.png"/>
									<img class="cr22" src="'.$path.'img/glow-b.png"/><img class="cr23" src="'.$path.'img/glow-b.png"/><img class="cr24" src="'.$path.'img/glow-b.png"/>
									<img class="cr25" src="'.$path.'img/glow-b.png"/><img class="cr26" src="'.$path.'img/glow-b.png"/><img class="cr27" src="'.$path.'img/glow-b.png"/>
									<img class="cr28" src="'.$path.'img/glow-b.png"/><img class="cr29" src="'.$path.'img/glow-b.png"/>
								</div>
								<div class="baloon-prt">
									<div class="baloon baloon-left"><img src="'.$path.'img/baloon.png"/></div>
									<div class="glow left-glow"><img src="'.$path.'img/glow.png"/></div>
									<div class="baloon baloon-right"><img src="'.$path.'img/baloon.png"/></div>
									<div class="glow right-glow"><img src="'.$path.'img/glow.png"/></div>
								</div>		
								<div class="frame-content">
									<div class="frame-inner-wrap">
										<div class="frame-inner-wrap01">
											<h2 class="'.$radio_fontfamily.' '.$radio_color.'">merry christmas</h2>			
											<p class="msg_txt">'.$message_res.' </p>';
											if($radio_greetings=='coupon'){ echo '
												<ul class="button_01" >
													<li>
														<div class="cuppon-code">
															<a href="#">code:<span id="coupon_text">'.$coupon_code.'</span></a>
															<input style="opacity: 0;" type="text" name="clipbord_code" value="" id="clipbord_code">
														</div>
													</li>
													<li>
														<div class="grab-style2"><a id="grab_text" href="#">grab it</a></div> 
													</li>
												</ul>';
											}
											if($radio_greetings=='product_promo' && class_exists('WooCommerce') && isset($product_res) && $product_res!='')
											{ 
												wp_enqueue_style('mycss3', $path.'css/product.css');
												$product_style='';
												$args = array( 'post_type' => 'product','post__in'=>$product_res, 'orderby'=> 'menu_order','order'=> 'ASC','posts_per_page'=> -1 ,'suppress_filters' => false);
												echo '<div class="show-option">';
												switch ($radio_layout) 
												{
													case 'layout1':
														$product_style='product-style4';
														break;
													case 'layout2':
														$product_style='product-style1';
														break;
													case 'layout3':
														$product_style='product-style3';
														break;
												}
												echo '<ul id="product-box" class="'.$product_style.'">';
									    		$the_query = new WP_Query( $args ); 	       	
												while ( $the_query->have_posts() ) : $the_query->the_post();
													$featured_img_url = get_the_post_thumbnail_url(get_the_ID(),'full');
													echo'<li>';
													if($radio_layout=='layout1')
													{ 
														echo '<div class="contain"><div class="card"><div class="shine1"></div><div class="img_01"><img src="'.$featured_img_url.'"/></div><p><a href="#">'.get_the_title().'</a></p></div></div><div class="addtocart">'; woocommerce_template_loop_add_to_cart(); echo '</div>';
													} 
													if($radio_layout=='layout2')
													{ 
														echo '<div class="li01"><div class="img_01"><img src="'.$featured_img_url.'"/></div><a href="#">'.get_the_title().'</a></div><div class="button12">'; woocommerce_template_loop_add_to_cart(); echo '</div>';
													}
													if($radio_layout=='layout3')
													{ 
														echo '<div class="li01"><div class="img_01"><img src="'.$featured_img_url.'"/></div><a href="#">'.get_the_title().'</a></div><div class="button20">'; woocommerce_template_loop_add_to_cart(); echo '</div>';
													}
													echo '</li>';
												endwhile;
												wp_reset_query();
												echo '</ul></div>';
											} echo'
										</div>
									</div>
								</div>
							</div>
						</div> 
						<div id="click-btn" class="'.$gifteffect_res.' '.$box_type.'">';
							if($gifteffect_res=='sparkalffect')
							{
								echo '<img class="btn_img02" src="'.$path.'img/sparker.gif"/>';						
							}
							else
							{
								echo '<img class="btn_img01" src="'.$path.'img/Gift-icon3.png"/>';						
							}echo '
						</div>';
					} 
				echo '</div>';
			}
		}				
	}
}?>