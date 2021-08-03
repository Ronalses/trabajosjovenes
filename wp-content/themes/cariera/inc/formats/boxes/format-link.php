<?php

/**
*
* @package Cariera
*
* @since 1.0
* 
* ========================
* FORMAT LINK BOX
* ========================
*     
**/

?>

<div id="cariera_box_for_post-format-link" class="cariera_format_field cariera_format_field_link" >
	<label for="cfpf-format-link-url-field"><?php esc_html_e('URL', 'cariera'); ?></label>
	<input type="text" name="_format_link_url" value="<?php echo esc_attr(get_post_meta(get_the_id(), '_format_link_url', true)); ?>" id="cfpf-format-link-url-field" tabindex="1" />
</div>