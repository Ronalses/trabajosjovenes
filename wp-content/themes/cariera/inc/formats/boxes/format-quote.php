<?php

/**
*
* @package Cariera
*
* @since 1.0
* 
* ========================
* FORMAT QUOTE BOX
* ========================
*     
**/

?>


<div id="cariera_box_for_post-format-quote" class="cariera_format_field cariera_format_field_quote" >
	<div class="cf-elm-block">
		<label for="cfpf-format-quote-source-name"><?php esc_html_e('Quote Author Name', 'cariera'); ?></label>
		<input type="text" name="_format_quote_source_name" value="<?php echo esc_attr(get_post_meta(get_the_id(), '_format_quote_source_name', true)); ?>" id="cfpf-format-quote-source-name" tabindex="1" />
	</div>
	<div class="cf-elm-block">
		<label for="cfpf-format-quote-source-url"><?php esc_html_e('Quote Author URL', 'cariera'); ?></label>
		<input type="text" name="_format_quote_source_url" value="<?php echo esc_attr(get_post_meta(get_the_id(), '_format_quote_source_url', true)); ?>" id="cfpf-format-quote-source-url" tabindex="1" />
	</div>
	<div class="cf-elm-block">
		<label for="cfpf-format-quote-source-content"><?php esc_html_e('Quote Content', 'cariera'); ?></label>
		<textarea name="_format_quote_source_content" id="cfpf-format-quote-source-content" tabindex="1"><?php echo esc_textarea(get_post_meta(get_the_id(), '_format_quote_source_content', true)); ?></textarea>
	</div>
</div>