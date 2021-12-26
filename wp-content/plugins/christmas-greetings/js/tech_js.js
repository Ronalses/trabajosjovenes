jQuery(function() 
{
  jQuery('.display_on_dropdown.dropdown-multiple-label input[type="text"]').click(function(e){
    jQuery("#display_on_pages").prop("checked", true);
  });
	jQuery(document).on("click", 'input.jscolor', function(e) 
	{
  	jQuery("#three").prop("checked", true);
  	jQuery('#three_img').addClass('selected');
      jQuery('#two_img').removeClass('selected');
      jQuery('#one_img').removeClass('selected'); 
  });
	jQuery('input[name=snow_font]').click (function(e) 
  {
  	jQuery("#three").prop("checked", true);
  	jQuery('#three_img').addClass('selected');
      jQuery('#two_img').removeClass('selected');
      jQuery('#one_img').removeClass('selected'); 
  });			
	jQuery(".clear_btn").click(function()
	{
		var startDate ="", endDate = "" ,dateRange = [];
		var new_startDate = "", new_endDate="", new_dateRange = [];
		jQuery("#txtTo").val("");
		jQuery("#txtFrom").val("");
		jQuery("#new_txtTo").val("");
		jQuery("#new_txtFrom").val("");
		jQuery("#txtTo").datepicker("option", "minDate", null);
 		jQuery("#txtFrom").datepicker("option", "maxDate", null);
  	jQuery("#new_txtTo").datepicker("option", "minDate", null);
 		jQuery("#new_txtFrom").datepicker("option", "maxDate", null);
 		jQuery.datepicker._clearDate("#txtTo");
 		jQuery.datepicker._clearDate("#new_txtTo");
 	});
  jQuery("#txtFrom").focus(function()
  {
  	if(jQuery('input[name=new_active]:checked').val() == 'new_active')
   	{
     	new_startDate=jQuery("#new_txtFrom").datepicker("getDate");
	    new_endDate=jQuery("#new_txtTo").datepicker("getDate"),new_dateRange = [];
      for (var new_d = new Date(new_startDate); new_d <= new Date(new_endDate); new_d.setDate(new_d.getDate() + 1)) 
      {				        	
        new_dateRange.push(jQuery.datepicker.formatDate('mm-dd-yy', new_d));				            
      }
    }
  });
  jQuery("#new_txtFrom").focus(function()
  {
  	if(jQuery('input[name=active]:checked').val() == 'active')
  	{
    	startDate = jQuery("#txtFrom").datepicker("getDate");
    	endDate=jQuery("#txtTo").datepicker("getDate"),dateRange = [];
      for (var d = new Date(startDate); d <= new Date(endDate); d.setDate(d.getDate() + 1)) 
      {				        	
        dateRange.push(jQuery.datepicker.formatDate('mm-dd-yy', d));				            
      }
    }
  });
	jQuery(document).on("change", '#txtFrom', function(e) 
	{
		var startDate = jQuery("#txtFrom").datepicker("getDate");
  	jQuery("#txtTo").datepicker("option", "minDate", startDate);	
	});
	jQuery(document).on("change", '#txtTo', function(e) 
	{
		startDate = jQuery("#txtFrom").datepicker("getDate");
  	var endDate=jQuery("#txtTo").datepicker("getDate"),dateRange = [];
    for (var d = new Date(startDate); d <= new Date(endDate); d.setDate(d.getDate() + 1)) 
    {				        	
      dateRange.push(jQuery.datepicker.formatDate('mm-dd-yy', d));				            
    }
	});
	jQuery(document).on("change", '#new_txtFrom', function(e) 
	{
		new_startDate=jQuery("#new_txtFrom").datepicker("getDate");
		jQuery("#new_txtTo").datepicker("option", "minDate", new_startDate);
	});
	jQuery(document).on("change", '#new_txtTo', function(e) 
	{
		new_startDate=jQuery("#new_txtFrom").datepicker("getDate");
		var new_endDate=jQuery("#new_txtTo").datepicker("getDate"),new_dateRange = [];
        for (var new_d = new Date(new_startDate); new_d <= new Date(new_endDate); new_d.setDate(new_d.getDate() + 1)) 
        {				        	
            new_dateRange.push(jQuery.datepicker.formatDate('mm-dd-yy', new_d));				            
        }
	});
	jQuery("#txtFrom").datepicker(
	{
    numberOfMonths: 1,dateFormat: "mm-dd-yy",
    beforeShowDay: function (selected) 
    {	    		
    	if(jQuery('input[name=new_active]:checked').val() == 'new_active')
    	{
    		new_dateString = jQuery.datepicker.formatDate('mm-dd-yy', selected);
      	return [new_dateRange.indexOf(new_dateString) == -1];
      }
      else
      {
      	new_dateString = jQuery.datepicker.formatDate('mm-dd-yy', null);
      	return [new_dateRange.indexOf(new_dateString) == -1];
      }
    },
    onSelect: function (selected) 
    {
      startDate = new Date(selected);
      if (jQuery("#new_txtTo").val()=='')
      {
      	new_startDate='';
      }
      if(new_startDate >= startDate && new_startDate <= endDate)
     	{
     		jQuery("#txtTo").val("");
     		startDate.setDate(startDate.getDate() + 1);
  		  jQuery("#txtTo").datepicker("option", "minDate", startDate);
     		return false;
     	}
     	else
      {
     		jQuery("#txtTo").val("");
    		startDate.setDate(startDate.getDate() + 1);
    		jQuery("#txtTo").datepicker("option", "minDate", startDate);
    		startDate = jQuery("#txtFrom").datepicker("getDate");
    	}
    }
	});
	jQuery("#txtTo").datepicker(
	{
    numberOfMonths: 1,dateFormat: "mm-dd-yy",
    beforeShowDay: function (selected) 
    {	   
    	if(jQuery('input[name=new_active]:checked').val() == 'new_active')
    	{
    		new_dateString = jQuery.datepicker.formatDate('mm-dd-yy', selected);
      	return [new_dateRange.indexOf(new_dateString) == -1];
      }
      else
      {
      	new_dateString = jQuery.datepicker.formatDate('mm-dd-yy', null);
    		return [new_dateRange.indexOf(new_dateString) == -1];
      }        	        		
    },
    onSelect: function (selected) 
    {
    	endDate = new Date(selected);
    	if (jQuery("#new_txtTo").val()=='')
      {
      	new_startDate='';
      }				        
    	if(new_startDate >= startDate && new_startDate <= endDate && jQuery('input[name=new_active]:checked').val() == 'new_active')
     	{
     		alert('plase choose out of range date between'+new_dateRange[0]+ ' To ' + new_dateRange[new_dateRange.length-1] );
     		jQuery("#txtTo").val("");
     		jQuery("#txtTo").datepicker("refresh");
     		return false;
     	}
     	else
     	{
 				endDate=jQuery("#txtTo").datepicker("getDate");
 				dateRange = [];               					                			
      	for (var d = new Date(startDate); d <= new Date(endDate); d.setDate(d.getDate() + 1)) 
      	{				        	
      	    dateRange.push(jQuery.datepicker.formatDate('mm-dd-yy', d));				            
      	}
		  }
    }
	});
	jQuery('#new_txtFrom').datepicker(
	{
		numberOfMonths: 1,dateFormat: "mm-dd-yy",
    beforeShowDay: function (selected) 
    {	
    	if(jQuery('input[name=active]:checked').val() == 'active')
    	{
    		dateString = jQuery.datepicker.formatDate('mm-dd-yy', selected);
      	return [dateRange.indexOf(dateString) == -1];
      }
      else
      {
      	dateString = jQuery.datepicker.formatDate('mm-dd-yy', null);
      	return [dateRange.indexOf(dateString) == -1];
      }
    },
    onSelect: function (selected) 
    {      
  		new_startDate = new Date(selected);
  		if (jQuery("#txtTo").val()=='')
      {
      	startDate='';
      }
  		if(startDate >= new_startDate && startDate <= new_endDate)
      {
     		jQuery("#new_txtTo").val("");
     		new_startDate.setDate(new_startDate.getDate() + 1);
     		jQuery("#new_txtTo").datepicker("option", "minDate", new_startDate);
     		return false;
			}
     	else
     	{
     		jQuery("#new_txtTo").val("");
  			new_startDate.setDate(new_startDate.getDate() + 1);
  			jQuery("#new_txtTo").datepicker("option", "minDate", new_startDate);	
  			new_startDate=jQuery("#new_txtFrom").datepicker("getDate");
  		}		                    	        
    }				    
	});
	jQuery("#new_txtTo").datepicker(
  {
    numberOfMonths: 1,dateFormat: "mm-dd-yy",
    beforeShowDay: function (selected) 
    {	
    	if(jQuery('input[name=active]:checked').val() == 'active')
    	{
    		dateString = jQuery.datepicker.formatDate('mm-dd-yy', selected);
      	return [dateRange.indexOf(dateString) == -1];
      }
      else
      {
    	 dateString = jQuery.datepicker.formatDate('mm-dd-yy', null);
    	 return [dateRange.indexOf(dateString) == -1];
      }
    },
    onSelect: function (selected) 
    {
      new_endDate = new Date(selected);
      if (jQuery("#txtTo").val()=='')
      {
      	startDate='';
      }
      if(startDate >= new_startDate && startDate <= new_endDate)
      {
     		if(jQuery('input[name=active]:checked').val() == 'active')
    		{
     			alert('plase choose out of range date between'+dateRange[0]+ ' To ' + dateRange[dateRange.length-1] );
     			jQuery("#new_txtTo").val("");
     			jQuery("#new_txtTo").datepicker("refresh");
     			return false;
     		}
      }
      else
      {
       	new_endDate=jQuery("#new_txtTo").datepicker("getDate");
        new_dateRange = [];
        for (var new_d = new Date(new_startDate); new_d <= new Date(new_endDate); new_d.setDate(new_d.getDate() + 1)) 
        {				        	
          new_dateRange.push(jQuery.datepicker.formatDate('mm-dd-yy', new_d));				            
        }
      }		                   
    }
  });
  jQuery(document).on("change", 'input[name=active]', function(e) 
	{		
		if(jQuery(this).val()=='active' && jQuery('input[name=new_active]:checked').val() == 'new_active')
  	{
  		if (jQuery("#new_txtTo").val()==''){ new_startDate='';}
      if((startDate >= new_startDate || startDate <= new_endDate) && (endDate <= new_startDate || endDate >= new_endDate))
     	{
     		jQuery.datepicker._clearDate("#txtFrom");
     		jQuery.datepicker._clearDate("#txtTo");
        alert('Plase choose out of range date between'+new_dateRange[0]+ ' To ' + new_dateRange[new_dateRange.length-1] );
     	}	       
   	}    	
	});	
  jQuery(document).on("change", 'input[name=new_active]', function(e) 
  { 
    if(jQuery(this).val()=='new_active' && jQuery('input[name=active]:checked').val() == 'active')
    {
      if (jQuery("#txtTo").val()==''){ startDate='';}
      if((startDate >= new_startDate || startDate <= new_endDate) && (endDate <= new_startDate || endDate >= new_endDate) )
      {
        jQuery.datepicker._clearDate("#new_txtFrom");
        jQuery.datepicker._clearDate("#new_txtTo");
        alert('Plase choose out of range date between'+dateRange[0]+ ' To ' + dateRange[dateRange.length-1] );
      }        
    }     
  }); 			
  startDate = jQuery("#txtFrom").datepicker("getDate"), endDate=jQuery("#txtTo").datepicker("getDate"),dateRange = [];
  new_startDate=jQuery("#new_txtFrom").datepicker("getDate"), new_endDate=jQuery("#new_txtTo").datepicker("getDate"),new_dateRange = [];
  jQuery("#txtTo").datepicker("option", "minDate", startDate);
  jQuery("#new_txtTo").datepicker("option", "minDate", new_startDate);
  for (var d = new Date(startDate); d <= new Date(endDate); d.setDate(d.getDate() + 1)) 
  {				        	
      dateRange.push(jQuery.datepicker.formatDate('mm-dd-yy', d));				            
  }
  for (var new_d = new Date(new_startDate); new_d <= new Date(new_endDate); new_d.setDate(new_d.getDate() + 1)) 
  {				        	
      new_dateRange.push(jQuery.datepicker.formatDate('mm-dd-yy', new_d));				            
  }
});
jQuery('input[name=layout]').hide();
jQuery('input[name=gifteffect]').hide();
jQuery('input[name=santa]').hide();
jQuery('input[name=snow]').hide();
jQuery('input[name=new_layout]').hide();
jQuery('input[name=new_gifteffect]').hide();
jQuery('input[name=new_santa]').hide();
jQuery('input[name=new_firework]').hide();
jQuery(document).on("change", 'input[type=radio]', function(e) 
{
  if(jQuery('input[name="display_on"]:checked').val()=='pages'){
    jQuery('.select_pagis_aria').show();
  }
  else{
    jQuery('.select_pagis_aria').hide();    
  }
  if(jQuery('input[name="new_display_on"]:checked').val()=='pages'){
    jQuery('.new_select_pages_aria').show();
  }
  else{
    jQuery('.new_select_pages_aria').hide();    
  }
	if(jQuery(this).val()=='firework1')
  {
    jQuery('#firework1_id').addClass('selected');
    jQuery('#firework2_id').removeClass('selected');
 	}
 	if(jQuery(this).val()=='firework2')
  {
    jQuery('#firework1_id').removeClass('selected');
    jQuery('#firework2_id').addClass('selected');
 	}
 	if(jQuery(this).val()=='dark')
  {
      jQuery('#one_img').addClass('selected');
      jQuery('#two_img').removeClass('selected');  
      jQuery('#three_img').removeClass('selected');  
 	}
	if(jQuery(this).val()=='light')      
 	{
      jQuery('#two_img').addClass('selected');
      jQuery('#three_img').removeClass('selected');
      jQuery('#one_img').removeClass('selected');    
 	}
 	if(jQuery(this).val()=='three')      
 	{
      jQuery('#three_img').addClass('selected');
      jQuery('#two_img').removeClass('selected');
      jQuery('#one_img').removeClass('selected');    
 	}
	if(jQuery(this).val()=='floating')
  {
      jQuery('#santa_one').addClass('selected');
      jQuery('#santa_two').removeClass('selected');   
 	}
	if(jQuery(this).val()=='moving')      
 	{
      jQuery('#santa_two').addClass('selected');
      jQuery('#santa_one').removeClass('selected'); 
 	}
 	if(jQuery(this).val()=='new_floating')
  {
      jQuery('#new_santa_one').addClass('selected');
      jQuery('#new_santa_two').removeClass('selected');   
 	}
	if(jQuery(this).val()=='new_moving')      
 	{
      jQuery('#new_santa_two').addClass('selected');
      jQuery('#new_santa_one').removeClass('selected'); 
 	}
	if(jQuery(this).val()=='move')
  {
      jQuery('#move_gift').addClass('selected');
      jQuery('#bell_gift').removeClass('selected');  
      jQuery('#sparkalffect_gift').removeClass('selected');  
 	}
	if(jQuery(this).val()=='bell')      
 	{
      jQuery('#move_gift').removeClass('selected');
      jQuery('#bell_gift').addClass('selected');
      jQuery('#sparkalffect_gift').removeClass('selected');    
 	}
 	if(jQuery(this).val()=='sparkalffect')      
 	{
      jQuery('#move_gift').removeClass('selected');
      jQuery('#bell_gift').removeClass('selected');
      jQuery('#sparkalffect_gift').addClass('selected');    
 	}
 	if(jQuery(this).attr('id')=='new_movingeffect')
  {
      jQuery('#new_move_gift').addClass('selected');
      jQuery('#new_bell_gift').removeClass('selected');  
      jQuery('#new_sparkalffect_gift').removeClass('selected');  
 	}
	if(jQuery(this).attr('id')=='new_belleffect')      
 	{
      jQuery('#new_move_gift').removeClass('selected');
      jQuery('#new_bell_gift').addClass('selected');
      jQuery('#new_sparkalffect_gift').removeClass('selected');    
 	}
 	if(jQuery(this).attr('id')=='new_sparkalffect')      
 	{
      jQuery('#new_move_gift').removeClass('selected');
      jQuery('#new_bell_gift').removeClass('selected');
      jQuery('#new_sparkalffect_gift').addClass('selected');    
 	}
	if(jQuery(this).attr('id')=='new_layout1')
  {
      jQuery('#new_layout1_img').addClass('selected');
      jQuery('#new_layout2_img').removeClass('selected');  
      jQuery('#new_layout3_img').removeClass('selected');  
 	}
	if(jQuery(this).attr('id')=='new_layout2')      
 	{
      jQuery('#new_layout1_img').removeClass('selected');
      jQuery('#new_layout2_img').addClass('selected');
      jQuery('#new_layout3_img').removeClass('selected');    
 	}
 	if(jQuery(this).attr('id')=='new_layout3')      
 	{
      jQuery('#new_layout1_img').removeClass('selected');
      jQuery('#new_layout2_img').removeClass('selected');
      jQuery('#new_layout3_img').addClass('selected');    
 	}
 	if(jQuery(this).val()=='layout1')
  {
      jQuery('#layout1_img').addClass('selected');
      jQuery('#layout2_img').removeClass('selected');  
      jQuery('#layout3_img').removeClass('selected');  
 	}
	if(jQuery(this).val()=='layout2')      
 	{
      jQuery('#layout1_img').removeClass('selected');
      jQuery('#layout2_img').addClass('selected');
      jQuery('#layout3_img').removeClass('selected');    
 	}
 	if(jQuery(this).val()=='layout3')      
 	{
      jQuery('#layout1_img').removeClass('selected');
      jQuery('#layout2_img').removeClass('selected');
      jQuery('#layout3_img').addClass('selected');    
 	}
	if(jQuery(this).val()=='❄')
  {
  	jQuery("#three").prop("checked", true);
      jQuery('#snow_lable1').addClass('selected_snow');
      jQuery('#snow_lable2').removeClass('selected_snow');  
      jQuery('#snow_lable3').removeClass('selected_snow');  
 	}
	if(jQuery(this).val()=='❅')      
 	{
      jQuery('#snow_lable1').removeClass('selected_snow');
      jQuery('#snow_lable2').addClass('selected_snow');
      jQuery('#snow_lable3').removeClass('selected_snow');    
 	}
 	if(jQuery(this).val()=='❆')      
 	{
      jQuery('#snow_lable1').removeClass('selected_snow');
      jQuery('#snow_lable2').removeClass('selected_snow');
      jQuery('#snow_lable3').addClass('selected_snow');    
 	}
  if(jQuery(this).val()=='message')  
	{
		jQuery('#msg').addClass('show-option');
		jQuery('#coupon').removeClass('show-option');
		jQuery('#product').removeClass('show-option');
	}
	if(jQuery(this).val()=='coupon') 
	{
		jQuery('#msg').removeClass('show-option');
		jQuery('#coupon').addClass('show-option');	
		jQuery('#product').removeClass('show-option');
	}
	if(jQuery(this).val()=='product_promo') 
	{
		jQuery('#msg').removeClass('show-option');
		jQuery('#coupon').removeClass('show-option');
		jQuery('#product').addClass('show-option');
	}
	if(jQuery(this).val()=='new_message') 
	{
		jQuery('#new_msg').addClass('show-option');
		jQuery('#new_coupon').removeClass('show-option');
		jQuery('#new_product').removeClass('show-option');
	}
	if(jQuery(this).val()=='new_coupon') 
	{
		jQuery('#new_msg').removeClass('show-option');
		jQuery('#new_coupon').addClass('show-option');
		jQuery('#new_product').removeClass('show-option');
	}
	if(jQuery(this).val()=='new_product_promo') 
	{
		jQuery('#new_msg').removeClass('show-option');
		jQuery('#new_coupon').removeClass('show-option');
		jQuery('#new_product').addClass('show-option');
	} 
	if(jQuery(this).val()=='enable')
  {
      jQuery('#snow_show-me').addClass('show-option'); 
 	}
	if(jQuery(this).val()=='disable')
 	{
      jQuery('#snow_show-me').removeClass('show-option');  
 	}
	if(jQuery(this).val()=='enable_santa')
  {
      jQuery('#santa_show-me').addClass('show-option');          
 	}
	if(jQuery(this).val()=='disable_santa')
 	{
      jQuery('#santa_show-me').removeClass('show-option');   
 	}
	if(jQuery(this).val()=='new_enable')
  {
      jQuery('#new_snow_show-me').addClass('show-option'); 
 	}      
	if(jQuery(this).val()=='new_disable')
 	{
      jQuery('#new_snow_show-me').removeClass('show-option');  
 	}
	if(jQuery(this).val()=='new_enable_santa')
  {
      jQuery('#new_santa_show-me').addClass('show-option');
 	}
	if(jQuery(this).val()=='new_disable_santa')
 	{
      jQuery('#new_santa_show-me').removeClass('show-option');   
 	}
	if(jQuery(this).val()=='enable_decoration')
  {
      jQuery('#decoration_img_show').addClass('show-option');
 	}
	if(jQuery(this).val()=='disable_decoration')
 	{
      jQuery('#decoration_img_show').removeClass('show-option');   
 	}
	if(jQuery(this).val()=='new_enable_decoration')
  {
      jQuery('#new_decoration_img_show').addClass('show-option');
 	}
	if(jQuery(this).val()=='new_disable_decoration')
 	{
      jQuery('#new_decoration_img_show').removeClass('show-option');   
 	}
  if(jQuery(this).val()=='gift_enable')
  {
      jQuery('.gift_box_tc').addClass('show-gift');
      jQuery('.gift_box_tc_msg').removeClass('show-gift');   
  }
  if(jQuery(this).val()=='gift_disable')
  {
      jQuery('.gift_box_tc_msg').addClass('show-gift');
      jQuery('.gift_box_tc').removeClass('show-gift');   
  }
	if(jQuery(this).val()=='new_gift_enable')
  {
      jQuery('.new_gift_box_tc').addClass('show-gift');
      jQuery('.new_gift_box_tc_msg').removeClass('show-gift');   
 	}
	if(jQuery(this).val()=='new_gift_disable')
 	{
      jQuery('.new_gift_box_tc_msg').addClass('show-gift');
      jQuery('.new_gift_box_tc').removeClass('show-gift');   
 	}
});
jQuery(document).ready(function()
{	
  if(window.location.hash)
  {
    var tab_active=window.location.hash.substring(1);
    jQuery('.techno_main_tabs').removeClass('active');
    jQuery('.techno_sub_tabs').removeClass('active');
    jQuery("#tab_"+tab_active).addClass('active');
    jQuery(".tab_"+tab_active).addClass('active');    
  } 
  jQuery(".techno_main_tabs").click(function()
  {
    jQuery('.techno_main_tabs').removeClass('active');
    jQuery('.techno_sub_tabs').removeClass('active');
    jQuery(this).addClass('active');
    jQuery("."+this.id).addClass('active');
  }); 
  jQuery(".techno_sub_christmas_tabs").click(function()
  {
    jQuery('.techno_sub_christmas_tabs').removeClass('active');
    jQuery('.techno_sub_tabs_christmas_con').removeClass('active');
    jQuery(this).addClass('active');
    jQuery("."+this.id).addClass('active');
  });
  jQuery(".techno_sub_newyear_tabs").click(function()
  {
    jQuery('.techno_sub_newyear_tabs').removeClass('active');
    jQuery('.techno_sub_tabs_new_con').removeClass('active');
    jQuery(this).addClass('active');
    jQuery("."+this.id).addClass('active');
  });
 	jQuery("#ch_adv_btn").click(function()
	{
		jQuery("#teb_christmas_advance").trigger( "click" );
	});
	jQuery("#new_adv_btn").click(function()
	{
		jQuery("#teb_newyear_advance").trigger( "click" );
	});
	jQuery("#activate_premium").click(function()
	{
		jQuery("#tab_premium").trigger( "click" );
	});
	jQuery(".preview-btn").click(function()
	{
	 	jQuery(".preview-popop").css("display", "block");
	 	var preview_data=jQuery('#gift_form').serialize();
	 	var url_site=jQuery('input[name="siteurl"]').val();
		test1 = url_site+"/?preview_techno=preview&"+preview_data;
		jQuery('#preview_iframe').attr('src', test1)
	});
	jQuery(".close-overlay").click(function()
	{
		 jQuery(".preview-popop").css("display", "none");
		 jQuery('#preview_iframe').attr('src', '')
	});
	jQuery(".preview-btn_tc").click(function()
	{
	 	jQuery(".preview-popop_tc").css("display", "block");
	 	var preview_data=jQuery('#gift_form').serialize();
	 	var url_site=jQuery('input[name="siteurl"]').val();
		test1 = url_site+"/?preview_techno=preview&preview_tc=preview_gift&"+preview_data;
		jQuery('#preview_iframe_tc').attr('src', test1)
	});
	jQuery(".new_preview-btn").click(function()
	{
	 	jQuery(".new_preview-popop").css("display", "block");
	 	var preview_data=jQuery('#gift_form').serialize();
	 	var url_site=jQuery('input[name="siteurl"]').val();
		test2 = url_site+"/?new_preview=new_preview&"+preview_data;
		jQuery('#new_preview_iframe').attr('src', test2)
	});
	jQuery(".close-overlay").click(function()
	{
		 jQuery(".new_preview-popop").css("display", "none");
		 jQuery('#new_preview_iframe').attr('src', '')
	});
	jQuery(".new_preview-btn_tc").click(function()
	{
	 	jQuery(".new_preview-popop_tc").css("display", "block");
	 	var preview_data=jQuery('#gift_form').serialize();
	 	var url_site=jQuery('input[name="siteurl"]').val();
		test3 = url_site+"/?new_preview=new_preview&new_preview_gift_tc=new_preview_gift&"+preview_data;
		jQuery('#new_preview_iframe_tc').attr('src', test3)
	});
	jQuery(".close-overlay_1").click(function()
	{      
    jQuery(".preview-popop_tc").css("display", "none");
    jQuery('#preview_iframe_tc').attr('src', '')
    jQuery(".new_preview-popop_tc").css("display", "none");
    jQuery('#new_preview_iframe_tc').attr('src', '');
	});
});
function maxLength(el) 
{	
	if (!('maxLength' in el)) 
	{
		var max = el.attributes.maxLength.value;
		el.onkeypress = function () 
		{
			if (this.value.length >= max) return false;
		};
	}
}maxLength(document.getElementById("text_tc"));