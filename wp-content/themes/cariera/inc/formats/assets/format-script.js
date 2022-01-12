jQuery( document ).ready(function() {

	jQuery('.cariera_format_field').insertAfter(jQuery('#titlediv'));

    jQuery('#post-formats-select input').each(function() {
        if(jQuery(this).is(':checked')) {
            boxClass = '.cariera_format_field_' + jQuery(this).attr('value');
            jQuery(boxClass).show();
        }
    });

    jQuery('#post-formats-select input').on('change', function() {
        jQuery('.cariera_format_field').hide();
    	boxClass = '.cariera_format_field_' + jQuery(this).attr('value');
    	jQuery(boxClass).show();
    });

});