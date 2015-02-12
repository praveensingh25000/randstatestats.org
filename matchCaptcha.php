<?php   

?>
<script language="javascript" type="text/javascript">
	/* this is just a simple reload; you can safely remove it; remember to remove it from the image too */
	function reloadCaptcha()
	{
		document.getElementById('captcha').src = document.getElementById('captcha').src+ '?' +new Date();
	}
</script>

  <input type="text" name="secure" value="what's the result?" onclick="this.value=''" class="required" /><br/>
  <span class="explain">click on the image to reload it</span><br/>
  <img src="image.php" alt="Click to reload image" title="Click to reload image" id="captcha" onclick="javascript:reloadCaptcha()" />
