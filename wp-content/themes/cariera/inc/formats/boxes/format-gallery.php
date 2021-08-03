<?php

/**
*
* @package Cariera
*
* @since 1.0
* 
* ========================
* FORMAT GALLERY BOX
* ========================
*     
**/


$gallery_type = cariera_post_gallery_type(); ?>

<div id="cariera_box_for_post-format-gallery" class="cariera_format_field cariera_format_field_gallery" >

	<label><span><?php esc_html_e('Gallery Images', 'cariera'); ?></span></label>

	<div class="cf-elm-container cfpf-gallery-options">
		<p class="cariera_gallrey_shortcode_field">
			<input type="radio" name="_format_gallery_type" value="shortcode" <?php checked($gallery_type, 'shortcode' ); ?> id="cfpf-format-gallery-type-shortcode"  />
			<label for="cfpf-format-gallery-type-shortcode"><?php esc_html_e('Shortcode', 'cariera'); ?></label>
			<input type="text" name="_format_gallery_shortcode" value="<?php echo esc_attr(get_post_meta(get_the_id(), '_format_gallery_shortcode', true)); ?>" id="cfpf-format-gallery-shortcode" />
		</p>

		<p style="display: none; visibility: hidden;">
			<input type="radio" name="_format_gallery_type" value="attached-images" <?php checked($gallery_type, 'attached-images' ); ?> id="cfpf-format-gallery-type-attached" />
			<label for="cfpf-format-gallery-type-attached"><?php esc_html_e('Images uploaded to this post', 'cariera'); ?></label>
		</p>

		<div class="srp-gallery clearfix">

		<?php // running this in the view so it can be used by multiple functions

		if( cariera_post_has_gallery(get_the_id()) ){
			$att_ids = '';
			$arr_shortcode = '';

			$shortcode = get_post_meta(get_the_id(), '_format_gallery_shortcode', true);

			if( $shortcode ){
	            // parse shortcode to get 'ids' param
	            $pattern = get_shortcode_regex();
	            preg_match("/$pattern/s", $shortcode, $match);
	            $arr_shortcode = shortcode_parse_atts($match[3]);
	        }

	        if (isset($arr_shortcode['ids'])) {
		        $att_ids = explode(',',  $arr_shortcode['ids']);
		    }
		    // Shortcodes Ultimate Plugin Gallery
		    elseif (isset ($arr_shortcode['source'])){
		        $su_source_ids = explode(':',  $arr_shortcode['source']);

		        if( count($su_source_ids[1]) > 0 ){
		            $att_ids = explode(',',  $su_source_ids[1]);
		        }
		    }

		    if(is_array($att_ids) && count($att_ids) > 0 ){
		    	$img_attributes = $img_src = $img_title = '';

		    	foreach ($att_ids as $att_id) {
		    		$img_attributes = wp_get_attachment_image_src($att_id);
		    		if( $img_attributes ){
		    			$img_src = $img_attributes[0];

		    			if (is_ssl()) {
			    			$img_src = str_replace('http://', 'https://', $img_src);
						}
		    		}
		    		echo '<span data-id="' . esc_attr( $att_id ) . '" title="' . esc_attr( $img_title ) . '"><img src="' . esc_url($img_src) . '" alt="' . esc_attr( $img_title ) . '" /><i class="srp-dashicons"></i></span>';

		    	}
		    }
		} ?>

		</div>

		<p class="none" style="float: none; clear: both;">
			<a href="#" class="button"><?php esc_html_e('Upload Images', 'cariera'); ?></a>
		</p>
	</div>
</div>
