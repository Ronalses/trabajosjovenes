<?php

/**
*
* @package Cariera
*
* @since 1.0
* 
* ========================
* FORMAT VIDEO BOX
* ========================
*     
**/

?>

<div id="cariera_box_for_post-format-video" class="cariera_format_field cariera_format_field_video" >
	<label for="cfpf-format-video-embed"><?php esc_html_e('Video URL (oEmbed) or Embed Code', 'cariera'); ?></label>
	<textarea name="_format_video_embed" id="cfpf-format-video-embed" tabindex="1"><?php echo esc_textarea(get_post_meta(get_the_id(), '_format_video_embed', true)); ?></textarea>
</div>