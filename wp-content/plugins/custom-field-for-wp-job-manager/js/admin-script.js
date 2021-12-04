jQuery( document ).ready(function() {
	jQuery(".addnewfielcfqjm").click(function(){
		jQuery(".showpopmain").show();
	  return false;
	});	
	jQuery(".editfield_pop").click(function(){
		jQuery(".showpopmain").show();
	  return false;
	});	
	jQuery(".closeicond").click(function(){
		jQuery(".showpopmain").hide();
	  return false;
	});	
	jQuery(".field_type_cfwjm").change(function(){
		
		var field_type_cfwjm = jQuery(this).val();
		if (field_type_cfwjm=='select' || field_type_cfwjm=='radio' || field_type_cfwjm=='multiselect') {
			jQuery(".cfwjm_option").show();
		}else{
			jQuery(".cfwjm_option").hide();
		}
	  return false;
	});			
});