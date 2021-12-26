jQuery(document).ready(function()
{
  jQuery("#click-btn img").click(function()
  {
    jQuery('#div2').css("display" , "block");
	  jQuery('#snow').addClass('open');
	   setTimeout(function(){ jQuery('#div2').css("display" , "none"); jQuery('#snow').removeClass('open'); }, 50000);
  });	
	jQuery(".hide-div").click(function()
	{
    jQuery('#div2').css("display" , "none");
	  jQuery('#snow').removeClass('open');
  });
  jQuery("#grab_text").click(function()
  {
    var content = (jQuery("#coupon_text").text());
    copyTextToClipboard(content);
  });
  function copyTextToClipboard(copyText)
  {
    var textArea = jQuery('#clipbord_code');
    textArea.val(copyText);
    textArea.select();
    successful = document.execCommand("Copy");
    if(successful)
    {
      jQuery("#grab_text").text('Copied');           
    }
    else
    {
      jQuery("#grab_text").text('Oops, unable to copy');
    }
  } 
});