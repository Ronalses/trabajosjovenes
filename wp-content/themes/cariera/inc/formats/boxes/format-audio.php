<?php

/**
*
* @package Cariera
*
* @since 1.0
* 
* ========================
* FORMAT AUDIO BOX
* ========================
*     
**/

?>

<div id="cariera_box_for_post-format-audio" class="cariera_format_field cariera_format_field_audio" >
	<label for="cfpf-format-audio-embed"><?php esc_html_e('Audio URL (oEmbed) or Embed Code', 'cariera'); ?></label>
	<textarea name="_format_audio_embed" id="cfpf-format-audio-embed" tabindex="1"><?php echo esc_textarea(get_post_meta(get_the_id(), '_format_audio_embed', true)); ?></textarea>
</div>