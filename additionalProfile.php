<?php
/******************************************
* @created on FEb 5, 2013.
* @updated on FEb 15, 2013.
* @Package: RAND
* @Developer: Sandeep kumar
********************************************/

$basedir=dirname(__FILE__)."";
include_once $basedir."/include/headerHtml.php";

//echo "<pre>";print_r($_SESSION['user']);echo "</pre>";

/*This function check session exist or not. if not then redirect to index.php file*/
checkSession(false);

$user  = new user();
$admin = new admin();
//upload image

if(isset($_POST['uploadimage'])){

	$status=$user->updateImage($_SESSION['user']['id']);

	if($status >= 1){
		$_SESSION['msgsuccess']="19";
		header('location:profile.php?action=view');
		exit;
	} else {
		$_SESSION['errormsg']="19";
		header('location:profile.php?action=view');
		exit;
	}
}

IF(isset($_POST['update'])){

	$billingcontactarray	=	$_POST['billing'];
	$technicalcontactarray	=	$_POST['technical'];
	$admincontactarray		=	$_POST['admincontact'];

	
	$copy_address	= (isset($_POST['copy_address']))?'Y':'N';

	if($copy_address == 'Y'){
		$technicalcontactarray['t_firstname']	= $billingcontactarray['b_firstname'];
		$technicalcontactarray['t_lastname']	= $billingcontactarray['b_lastname'];
		$technicalcontactarray['t_title']		= $billingcontactarray['b_title'];
		$technicalcontactarray['t_phone']		= $billingcontactarray['b_phone'];
		$technicalcontactarray['t_email']		= $billingcontactarray['b_email'];
		$technicalcontactarray['t_address']		= $billingcontactarray['b_address'];

		$admincontactarray['a_firstname']	= $billingcontactarray['b_firstname'];
		$admincontactarray['a_lastname']		= $billingcontactarray['b_lastname'];
		$admincontactarray['a_title']		= $billingcontactarray['b_title'];
		$admincontactarray['a_phone']		= $billingcontactarray['b_phone'];
		$admincontactarray['a_email']		= $billingcontactarray['b_email'];
	}

	$additionalinformation = $user->updateUserAdditionalFields($billingcontactarray, $admincontactarray, $technicalcontactarray, $_SESSION['user']['id']);
	if($additionalinformation >0){
		$_SESSION['successmsg'] = "Additional profile fields are updates.";
	} else {
		$_SESSION['errormsg'] = "Please try again sue to system error action cannot be performed.";
	}
	header('location: additionalProfile.php');
	exit;
}

$additionalFields = $user->getUserAdditionalFields($_SESSION['user']['id']);

$b_firstname = $b_lastname = $b_title = $b_phone = $b_email = $t_firstname = $t_lastname = $t_title = $t_phone = $t_email = $a_firstname = $a_lastname	= $a_title = $a_phone = $a_email = $b_firstname	= $b_lastname = $b_title = $b_phone = $b_email = $t_firstname = $t_lastname	= $t_title = $t_phone	= $t_email = $a_firstname = $a_lastname	= $a_title	= $a_phone = $a_email = "";

if(!empty($additionalFields)){
	$b_firstname	= stripslashes($additionalFields['bill_contact']);
	$b_lastname		= stripslashes($additionalFields['bill_contact_lastname']);
	$b_title		= stripslashes($additionalFields['bill_title']);
	$b_phone		= stripslashes($additionalFields['bill_phone']);
	$b_email		= stripslashes($additionalFields['bill_email']);
	//$b_address	= stripslashes($additionalFields['b_address']);

	$t_firstname	= stripslashes($additionalFields['tech_contact']);
	$t_lastname		= stripslashes($additionalFields['tech_contact_lastname']);
	$t_title		= stripslashes($additionalFields['tech_title']);
	$t_phone		= stripslashes($additionalFields['tech_phone']);
	$t_email		= stripslashes($additionalFields['tech_email']);
	//$t_address	= stripslashes($additionalFields['t_address']);

	$a_firstname	= stripslashes($additionalFields['admin_contact']);
	$a_lastname		= stripslashes($additionalFields['admin_contact_lastname']);
	$a_title		= stripslashes($additionalFields['admin_title']);
	$a_phone		= stripslashes($additionalFields['admin_phone']);
	$a_email		= stripslashes($additionalFields['admin_email']);
}


if(!empty($userArr['image'])){$userimage= stripslashes($userArr['image']);}else{$userimage="index.jpg";} ?>

<!-- profile  -->
<div class="login-popup" id="profileupload" style="display: none; margin-top: -94.5px; margin-left: -119px;"> <a class="close" href="#"><img alt="Close" title="Close Window" class="btn_close" src="images/close_pop.png"></a> 
  <form action="" method="post" id="editprofileimage" class="signin" name="editprofileimage" enctype="multipart/form-data">
	 <fieldset class="textbox">
		<label class="username"> <span>Profile Image</span>
		  <input type="file" name="userimage" id="userimage" accept="jpeg|gif|jpg" class="required accept">
		   <input type="hidden" name="olduserimage" id="olduserimage" value="<?php echo $userimage ?>">
		</label>
		<button type="submit" name="uploadimage" class="button">Update</button>
	 </fieldset>
	</form>
</div>

<div id="mainshell">
		<!-- main div -->
		<div class="profile">
		<h2>Edit Profile</h2>
		<br />
		<div class="profile-outer">
		<!-- left -->
		<div class="prfl-left">
			<div class="profile-pic">
				<!-- profile pic -->
				<img title="<?php echo ucwords($userArr['name']);?>" alt="<?php echo ucwords($userArr['name']);?>" width="116px" height="116px" <?php if(!empty($userArr['image'])){ ?> src="<?php echo URL_SITE;?>/uploads/profiles/<?php echo $userArr['id']?>/<?php echo $userArr['image']?>" <?php } else { ?> src="<?php echo URL_SITE;?>/images/profile.png" <?php } ?> />

				<br class="clear" >
				<span class="pT20">
					<a class="profileupload" href="#profileupload">Change profile picture</a>
				</span>	
				<!-- /profile pic -->
				<!-- verfication -->
				<div class="verfication_box">
					<ul>
						<li><a href="<?php echo URL_SITE; ?>/profile.php?action=edit">Edit Profile</a></li>
						<li><a href="<?php echo URL_SITE; ?>/change_password.php">Change Password</a></li>	
						<?php if(isset($totaluserIPDetail) && $totaluserIPDetail > 0) { ?>
						<li><a href="<?php echo URL_SITE; ?>/ipRanges.php?action=edit&id=<?php echo base64_encode($userArr['id'])?>">Manage IPs</a></li>
						<?php } else { ?>
						<li><a href="<?php echo URL_SITE; ?>/ipRanges.php?id=<?php echo base64_encode($userArr['id'])?>">Add IPs</a></li>
						<?php } ?>
					</ul>
				</div>
				<!-- /verfication -->
			</div>
		</div>
		<!-- /left -->
		<!-- right -->

		<div class="profile-right">
			<form name='updateuser' action='' method='post' id='updateuser'>
			<h2>Billing Contact</h2><br>
			<div class="inputshell">
				<p>First Name<em>*</em></p>
				<input placeholder="Enter billing first name" name="billing[b_firstname]" type="text" value="<?php if(isset($b_firstname)){ echo $b_firstname; }?>" class="required" id="b_name" />
			</div>
			
			<div class="clear"></div>

			<div class="inputshell">
				<p>Last Name</p>
				<input placeholder="Enter billing last name" name="billing[b_lastname]" type="text" value="<?php if(isset($b_lastname)){ echo $b_lastname; }?>" class=""  />
			</div>
			<div class="clear"></div>

			<div class="inputshell">
				<p>Title</p>
				<input placeholder="Enter billing title" name="billing[b_title]" type="text" value="<?php if(isset($b_title)){ echo $b_title; }?>" class=""  />
			</div>
			<div class="clear"></div>

				
			<div class="inputshell">
				<p>Phone<em>*</em></p>
				<input placeholder="Enter billing phone" name="billing[b_phone]" type="text" value="<?php if(isset($b_phone)){ echo $b_phone; }?>" class="required" id="b_phone" onchange="javascript: return chckphone('b_phone');"/>&nbsp;&nbsp;<em>eg:123-123-1234</em>
			</div>
			<div class="clear"></div>

			<div class="inputshell">
				<p>Email<em>*</em></p>
				<input placeholder="Enter billing email" name="billing[b_email]" value="<?php if(isset($b_email)){ echo $b_email; }?>" type="text" class="email required" />							
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
					<input placeholder="Enter technical person first name" name="technical[t_firstname]" type="text" value="<?php if(isset($t_firstname)){ echo $t_firstname; }?>" class="required inputrequireds" id="t_name" />
				</div>
				<div class="clear"></div>

				<div class="inputshell">
					<p>Last Name</p>
					<input placeholder="Enter technical person last name" name="technical[t_lastname]" type="text" value="<?php if(isset($t_lastname)){ echo $t_lastname; }?>" class=""  />
				</div>
				<div class="clear"></div>

				<div class="inputshell">
					<p>Title</p>
					<input placeholder="Enter technical person title" name="technical[t_title]" type="text" value="<?php if(isset($t_title)){ echo $t_title; }?>" class=""  />
				</div>
				<div class="clear"></div>

				
				<div class="inputshell">
					<p>Phone<em>*</em></p>
					<input placeholder="Enter technical person phone" name="technical[t_phone]" type="text" value="<?php if(isset($t_phone)){ echo $t_phone; }?>" class="required inputrequireds"  id="t_phone" onchange="javascript: return chckphone('t_phone');"/>&nbsp;&nbsp;<em>eg:123-123-1234</em>
				</div>
				<div class="clear"></div>

				<div class="inputshell">
					<p>Email<em>*</em></p>
					<input placeholder="Enter technical person email" name="technical[t_email]" value="<?php if(isset($t_email)){ echo $t_email; }?>" type="text" class="email required" />							
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
					<input placeholder="Enter admin person first name" name="admincontact[a_firstname]" type="text" value="<?php if(isset($a_firstname)){ echo $a_firstname; }?>" class="required inputrequireds" id="a_name" />
				</div>
				<div class="clear"></div>

				<div class="inputshell">
					<p>Last Name</p>
					<input placeholder="Enter admin person last name" name="admincontact[a_lastname]" type="text" value="<?php if(isset($a_lastname)){ echo $a_lastname; }?>" class=""  />
				</div>
				<div class="clear"></div>

				<div class="inputshell">
					<p>Title</p>
					<input placeholder="Enter admin person title" name="admincontact[a_title]" type="text" value="<?php if(isset($a_title)){ echo $a_title; }?>" class=""  />
				</div>
				<div class="clear"></div>

				
				<div class="inputshell">
					<p>Phone<em>*</em></p>
					<input placeholder="Enter admin person phone" name="admincontact[a_phone]" type="text" value="<?php if(isset($a_phone)){ echo $a_phone; }?>" class="required inputrequireds"   id="a_phone" onchange="javascript: return chckphone('a_phone');"/>&nbsp;&nbsp;<em>eg:123-123-1234</em>
				</div>
				<div class="clear"></div>

				<div class="inputshell">
					<p>Email<em>*</em></p>
					<input placeholder="Enter admin person email" name="admincontact[a_email]" value="<?php if(isset($a_email)){ echo $a_email; }?>" type="text" class="email required inputrequireds" />							
				</div>
				<div class="clear"></div>

				
			</div>

			<div class="inputshell">
				<p>&nbsp;</p>
				<input type='submit' name='update' value="Update">&nbsp;&nbsp;&nbsp;	
				<input onclick="javascript:window.history.go(-1)" type="button" value="Back">
			</div>
			
			</form>
		</div>
		<!-- /right -->
		</div>
		</div>
		<!-- main div -->
</div>
<!-- /profile -->

<script>
$(document).ready(function() {
	$('a.profileupload').click(function() {
		
		// Getting the variable's value from a link 
		var loginBox = $(this).attr('href');
		
		//Fade in the Popup and add close button
		$(loginBox).fadeIn(300);
		//Set the center alignment padding + border
		var popMargTop = ($(loginBox).height() + 24) / 2; 
		var popMargLeft = ($(loginBox).width() + 24) / 2; 
		
		$(loginBox).css({ 
			'margin-top' : -popMargTop,
			'margin-left' : -popMargLeft
		});
		
		// Add the mask to body
		$('body').append('<div id="mask"> </div>');
		$('#mask').fadeIn(300);
		
		return false;
	});
	
	// When clicking on the button close or the mask layer the popup closed
	$('a.close, #mask').live('click', function() { 
	  $('#mask , .login-popup').fadeOut(300 , function() {
		$('#mask').remove();  
	}); 
	return false;
	});
});
</script>


<?php include($basedir.'/include/footerHtml.php'); ?>