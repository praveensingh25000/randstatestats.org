<div class="registr" style="width:550px;">

	<h2>Billing Contact</h2><br>
	<div class="inputshell">
		<p>First Name<em>*</em></p>
		<input placeholder="Enter billing first name" name="billing[b_firstname]" type="text" value="<?php if(isset($_SESSION['billing']['b_firstname'])){ echo $_SESSION['billing']['b_firstname']; }?>" class="required" id="b_name" />
	</div>
	<div class="clear"></div>

	<div class="inputshell">
		<p>Last Name</p>
		<input placeholder="Enter billing last name" name="billing[b_lastname]" type="text" value="<?php if(isset($_SESSION['billing']['b_lastname'])){ echo $_SESSION['billing']['b_lastname']; }?>" class=""  />
	</div>
	<div class="clear"></div>

	<div class="inputshell">
		<p>Title</p>
		<input placeholder="Enter billing title" name="billing[b_title]" type="text" value="<?php if(isset($_SESSION['billing']['b_title'])){ echo $_SESSION['billing']['b_title']; }?>" class=""  />
	</div>
	<div class="clear"></div>

		
	<div class="inputshell">
		<p>Phone<em>*</em></p>
		<input placeholder="Enter billing phone" name="billing[b_phone]" type="text" value="<?php if(isset($_SESSION['billing']['b_phone'])){ echo $_SESSION['billing']['b_phone']; }?>" class="required" id="b_phone" onchange="javascript: return chckphone('b_phone');"/>&nbsp;&nbsp;<em>eg:123-123-1234</em>
	</div>
	<div class="clear"></div>

	<div class="inputshell">
		<p>Email<em>*</em></p>
		<input placeholder="Enter billing email" name="billing[b_email]" value="<?php if(isset($_SESSION['billing']['b_email'])){ echo $_SESSION['billing']['b_email']; }?>" type="text" class="email required" />							
	</div>
	<div class="clear"></div>

	<!-- <div class="inputshell">
		<p>Address</p>
		<textarea rows="3" cols="24" placeholder="Enter your billing address" name="billing[b_address]" class="" id="b_address" /><?php if(isset($_SESSION['billing']['b_address'])){ echo $_SESSION['billing']['b_address']; }?></textarea>						
	</div>
	<div class="clear"></div> -->

	<div class="inputshell">
		<p><input type="checkbox" id="checkbox_ok"  value="Y" name="copy_address" /></p>
		<label>Billing, technical, and admin. information the same?</label>				
	</div>
	<div class="clear"></div>

	<script>
	jQuery(document).ready(function(){
		jQuery('#checkbox_ok').change(function(){
			if($(this).is(':checked')){
				jQuery('#shippingDetail').hide();
				jQuery('#adminDetail').hide();
				jQuery(".inputrequireds").removeClass('required');
			} else {
				jQuery('#shippingDetail').show();
				jQuery('#adminDetail').show();
				jQuery(".inputrequireds").addClass('required');
			}
		});
	});
	</script>

	<div id="shippingDetail">
		<h2>Technical Contact</h2><br>
		<div class="inputshell">
			<p>First Name<em>*</em></p>
			<input placeholder="Enter technical person first name" name="technical[t_firstname]" type="text" value="<?php if(isset($_SESSION['technical']['t_firstname'])){ echo $_SESSION['technical']['t_firstname']; }?>" class="required inputrequireds" id="t_name" />
		</div>
		<div class="clear"></div>

		<div class="inputshell">
			<p>Last Name</p>
			<input placeholder="Enter technical person last name" name="technical[t_lastname]" type="text" value="<?php if(isset($_SESSION['technical']['t_lastname'])){ echo $_SESSION['technical']['t_lastname']; }?>" class=""  />
		</div>
		<div class="clear"></div>

		<div class="inputshell">
			<p>Title</p>
			<input placeholder="Enter technical person title" name="technical[t_title]" type="text" value="<?php if(isset($_SESSION['technical']['t_title'])){ echo $_SESSION['technical']['t_title']; }?>" class=""  />
		</div>
		<div class="clear"></div>

		
		<div class="inputshell">
			<p>Phone<em>*</em></p>
			<input placeholder="Enter technical person phone" name="technical[t_phone]" type="text" value="<?php if(isset($_SESSION['technical']['t_phone'])){ echo $_SESSION['technical']['t_phone']; }?>" class="required inputrequireds"  id="t_phone" onchange="javascript: return chckphone('t_phone');"/>&nbsp;&nbsp;<em>eg:123-123-1234</em>
		</div>
		<div class="clear"></div>

		<div class="inputshell">
			<p>Email<em>*</em></p>
			<input placeholder="Enter technical person email" name="technical[t_email]" value="<?php if(isset($_SESSION['technical']['t_email'])){ echo $_SESSION['technical']['t_email']; }?>" type="text" class="email required" />							
		</div>
		<div class="clear"></div>
<!-- 
		<div class="inputshell">
			<p>Address</p>
			<textarea rows="3" cols="24" placeholder="Enter technical person address" name="technical[t_address]" class="" id="b_address" /><?php if(isset($_SESSION['technical']['t_address'])){ echo $_SESSION['technical']['t_address']; }?></textarea>						
		</div> -->
		<div class="clear"></div>
	</div>


	<div id="adminDetail">
		<h2>Admin Contact</h2><br>
		<div class="inputshell">
			<p>First Name<em>*</em></p>
			<input placeholder="Enter admin person first name" name="admincontact[a_firstname]" type="text" value="<?php if(isset($_SESSION['admincontact']['a_firstname'])){ echo $_SESSION['admincontact']['a_firstname']; }?>" class="required inputrequireds" id="a_name" />
		</div>
		<div class="clear"></div>

		<div class="inputshell">
			<p>Last Name</p>
			<input placeholder="Enter admin person last name" name="admincontact[a_lastname]" type="text" value="<?php if(isset($_SESSION['admincontact']['a_lastname'])){ echo $_SESSION['admincontact']['a_lastname']; }?>" class=""  />
		</div>
		<div class="clear"></div>

		<div class="inputshell">
			<p>Title</p>
			<input placeholder="Enter admin person title" name="admincontact[a_title]" type="text" value="<?php if(isset($_SESSION['admincontact']['a_title'])){ echo $_SESSION['admincontact']['a_title']; }?>" class=""  />
		</div>
		<div class="clear"></div>

		
		<div class="inputshell">
			<p>Phone<em>*</em></p>
			<input placeholder="Enter admin person phone" name="admincontact[a_phone]" type="text" value="<?php if(isset($_SESSION['admincontact']['a_phone'])){ echo $_SESSION['admincontact']['a_phone']; }?>" class="required inputrequireds"   id="a_phone" onchange="javascript: return chckphone('a_phone');"/>&nbsp;&nbsp;<em>eg:123-123-1234</em>
		</div>
		<div class="clear"></div>

		<div class="inputshell">
			<p>Email<em>*</em></p>
			<input placeholder="Enter admin person email" name="admincontact[a_email]" value="<?php if(isset($_SESSION['admincontact']['a_email'])){ echo $_SESSION['admincontact']['a_email']; }?>" type="text" class="email required inputrequireds" />							
		</div>
		<div class="clear"></div>

		
	</div>
</div>