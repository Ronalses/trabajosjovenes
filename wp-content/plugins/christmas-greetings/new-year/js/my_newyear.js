jQuery(document).ready(function()
{
	jQuery("#click-btn img").click(function()
	{
		jQuery("#frame-wrapper").show(),setTimeout(function(){jQuery("#frame-wrapper").hide()},5e4)
	});
	jQuery(".hide-div").click(function()
	{
		jQuery("#frame-wrapper").hide()
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