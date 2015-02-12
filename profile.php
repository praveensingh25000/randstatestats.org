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

//update user profile
if(isset($_POST['updateprofile'])) {

	$status=$user->updateUserProfile($_POST);
	if($status)
	{
		$_SESSION['msgsuccess'] = '18';
		header('location:profile.php?action=view');
		exit;
	}
}
//end

$siteMainDBDetail = $admin->getMainDbDetail($_SESSION['databaseToBeUse']);
if(!empty($siteMainDBDetail)) {
	$dbUsercode = $siteMainDBDetail['id']; 
} else { 
	$dbUsercode = 1; 
}

//to get the user details to view
if(isset($_GET['action']) && ($_GET['action']=='view' || $_GET['action']=='edit')) {

	$userArr			=	$user->selectUserProfile($_SESSION['user']['email']);
	$userTypes			=	$admin->selectuserTypes($userArr['user_type']);
	$user_type			=	trim($userTypes['user_type']);
	$userTypeDetail		= $admin->getUserType($userArr['user_type']);
	$userIPDetail_res	=	$admin->selectUserIPAdressAll($_SESSION['user']['id']);
	$totaluserIPDetail  =   $db->count_rows($userIPDetail_res);
	$typesResult		= $admin->showAllUserTypes($status=0);
	$usertypesAll		= $db->getAll($typesResult);
	$dbUserDetail		= $admin->selectdatabaseUsers($dbUsercode,$userArr['id']);
	
}

if(!empty($userArr['image'])){
	$userimage= stripslashes($userArr['image']);

	} else {
	$userimage="index.jpg";
} 



//end

// to show details of user only for view
if(isset($_GET['action']) && ($_GET['action']=='view'))
{?>

<!-- profile  -->
<div id="mainshell">

	<!-- popup form start -->
	<div class="login-popup" id="profileupload" style="display: none; margin-top: -94.5px; margin-left: -119px;"> <a class="close" href="#"><img alt="Close" title="Close Window" class="btn_close" src="images/close_pop.png"></a> 
	   <form action="" method="post" id="proprofileimage" class="signin" name="proprofileimage" enctype="multipart/form-data">
		 <fieldset class="textbox">
			<label class="username"> <span>Profile Image</span>
			  <input type="file" name="userimage"  id="userimage" accept="jpeg|gif|jpg" class="required accept">
			   <input type="hidden" name="olduserimage" id="olduserimage" value="<?php echo $userimage ?>">
			</label>
			<button type="submit" name="uploadimage" class="button">Update</button>
		 </fieldset>
		</form>
		</div>
		<!-- /popup form end --> 
		
		<!-- main div -->
		<div class="profile">
		<h2>Your Profile</h2>
		<br />
		<!-- left -->
		<div class="profile-outer">
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
						<?php if($_SESSION['user']['parent_user_id']=='0'){ ?>
						<li><a href="<?php echo URL_SITE; ?>/additionalProfile.php?action=edit">Edit Additional Profile</a></li>
						<?php } ?>

						<li><a href="<?php echo URL_SITE; ?>/change_password.php">Change Password</a></li>	
						<?php if($userArr['user_type']!='5' && $userArr['user_type']!='6'){

							$ipuserid = ($_SESSION['user']['parent_user_id']!=0)?$_SESSION['user']['parent_user_id']:$userArr['id'];

							if(isset($totaluserIPDetail) && $totaluserIPDetail > 0) { ?>
								<li><a href="<?php echo URL_SITE; ?>/ipRanges.php?action=edit&id=<?php echo base64_encode($ipuserid)?>">Manage IPs</a></li>
								<?php } else { ?>
								<li><a href="<?php echo URL_SITE; ?>/ipRanges.php?id=<?php echo base64_encode($ipuserid)?>">Add IPs</a></li>
								<?php }
						}?>
					</ul>
				</div>
				<!-- /verfication -->
			</div>
		</div>
		<!-- /left -->
		<!-- right -->
		<div class="profile-right">
			<div class="inputshell">
				<p>First Name<em>*</em></p>
				<input type="text" name="firstname" value="<?php  if(isset($userArr['name'])){echo stripslashes($userArr['name']);} ?>" style="background:#FCF9EE" readonly>
			</div>
			<div class="clear"></div>
			<div class="inputshell">
				<p>Last Name</p>
				<input type="text" name="lastname" value="<?php  if(isset($userArr['last_name'])){echo stripslashes($userArr['last_name']);} ?>" style="background:#FCF9EE" readonly>
			</div>
			<div class="clear"></div>
			<div class="inputshell">
				<p>Email<em>*</em></p>
				<input type="text" name="username" value="<?php  if(isset($userArr['email'])){echo $userArr['email'];} ?>" style="background:#FCF9EE" readonly>
			</div>
			<div class="clear"></div>
			<div class="inputshell">
				<p>Phone</p>
				<input type="text" name="phone" value="<?php  if(isset($userArr['phone'])){echo $userArr['phone'];} ?>" maxlength="10" class="wdthpercent30" style="background:#FCF9EE" readonly>
			</div>
			<div class="clear"></div>
			<div class="inputshell">
				<p>Address</p>
				<textarea rows="3" cols="22" style="background:#FCF9EE" readonly><?php  if(isset($userArr['address'])){echo stripslashes($userArr['address']);} ?></textarea>
			</div>
			<div class="clear"></div>
			<?php if(!empty($userArr['user_type'])){?>
				<div class="inputshell">
					<p>User Type<em>*</em></p>
					<input type="text" name="user_type" value="<?php  if(isset($userArr['user_type'])){echo $userTypeDetail['user_type'];} ?>" style="background:#FCF9EE" readonly>
				</div>
				<div class="clear"></div>
			<?php } ?>
			<div class="inputshell">
				<p>Number of Users<em>*</em></p>
				<input type="text" name="number_of_users" value="<?php  if(isset($userArr['number_of_users'])){echo $userArr['number_of_users'];} ?>" style="background:#FCF9EE" readonly>
			</div>
			<div class="clear"></div>
			<div class="inputshell">
				<p>Organisation</p>
				<input type="text" name="Organisation" value="<?php  if(isset($userArr['organisation'])){echo $userArr['organisation'];} ?>" style="background:#FCF9EE" readonly>
			</div>
			<div class="clear"></div>
			<div class="inputshell">
				<p>Organisation Address</p>
				<textarea rows="3" cols="22" style="background:#FCF9EE" readonly><?php  if(isset($userArr['organisation_address'])){echo stripslashes($userArr['organisation_address']);} ?></textarea>
			</div>

			<div class="inputshell">
				<p><input type="checkbox" name='use_name_as_brand' <?php  if(isset($userArr['use_brand_name']) && $userArr['use_brand_name'] == 'Y'){ echo "checked"; } ?> disabled/></p>
				Use Organisation Name as Brand
			</div>

			<div class="clear"></div>
			<div class="inputshell txtcenter">
				<input onclick="javascript:window.history.go(-1)" type="button" value="Back">
			</div>
			
		</div>
		<!-- /right -->
		</div>
		</div>
		<!-- main div -->
</div>
<!-- /profile -->
<?php } 
//end
//edit mode
if(isset($_GET['action']) && ($_GET['action']=='edit'))
{?>
<?php  if(!empty($userArr['image'])){$userimage= stripslashes($userArr['image']);}else{$userimage="index.jpg";} ?>

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
						<?php if($_SESSION['user']['parent_user_id']=='0'){ ?>
						<li><a href="<?php echo URL_SITE; ?>/additionalProfile.php?action=edit">Edit Additional Profile</a></li>
						<?php } ?>

						<li><a href="<?php echo URL_SITE; ?>/change_password.php">Change Password</a></li>	
						<?php if($userArr['user_type']!='5' && $userArr['user_type']!='6'){

							$ipuserid = ($_SESSION['user']['parent_user_id']!=0)?$_SESSION['user']['parent_user_id']:$userArr['id'];

							if(isset($totaluserIPDetail) && $totaluserIPDetail > 0) { ?>
								<li><a href="<?php echo URL_SITE; ?>/ipRanges.php?action=edit&id=<?php echo base64_encode($ipuserid)?>">Manage IPs</a></li>
								<?php } else { ?>
								<li><a href="<?php echo URL_SITE; ?>/ipRanges.php?id=<?php echo base64_encode($ipuserid)?>">Add IPs</a></li>
								<?php }
						}?>
					</ul>
				</div>
				<!-- /verfication -->
			</div>
		</div>
		<!-- /left -->
		<!-- right -->

		<div class="profile-right">
			<form name='updateuser' action='' method='post' id='updateuser'>
			<div class="inputshell">
				<p>First Name<em>*</em></p>
				<input type='text' name='name' class='required' value="<?php  if(isset($userArr['name'])){echo $userArr['name'];} ?>">
			</div>
			<div class="clear"></div>
			<div class="inputshell">
				<p>Last Name</p>
				<input type='text' name='last_name' value="<?php  if(isset($userArr['last_name'])){echo $userArr['last_name'];} ?>">
			</div>
			<div class="clear"></div>	
			<div class="inputshell">
				<p>Username</p>
				<input type='text'  id='username' name='username' value="<?php  if(isset($userArr['username'])){echo $userArr['username'];} ?>"/>
				<input type='hidden' id='oldusername'  name='oldusername' value="<?php  if(isset($userArr['username'])){ echo $userArr['username']; } ?>"/>
			</div>
			<div class="clear"></div>


			<div class="inputshell">
				<p>Organisation</p>
				<input type='text' name='organistaion' value="<?php  if(isset($userArr['organisation'])){echo $userArr['organisation'];} ?>">
			</div>
			<div class="clear"></div>

			<div class="inputshell">
				<p>Phone<em>*</em></p>
				<input type='text' name='phone' id='phone' class='required' value="<?php  if(isset($userArr['phone'])){echo $userArr['phone'];} ?>" class="wdthpercent30" onchange="chckphone()"/>&nbsp;&nbsp;<em>eg:123-123-1234</em>
			</div>
			<div class="clear"></div>
			
			<?php if(!empty($userArr['user_type'])){?>
				<div class="inputshell">
					<p>User Type<em>*</em></p>
					<?php if(!empty($usertypesAll)) { ?>
											
						<select <?php if(isset($dbUserDetail) & $dbUserDetail['is_trial']!='0'){?>disabled="true"<?php } ?> class="required" id="user_type" name="user_type" style="width:225px;">
						<option value=""> Select User Type </option>
						<?php foreach($usertypesAll as $userTypes) { ?>
							<option value="<?php echo $userTypes['id'];?>" <?php if(isset($userDetail['user_type']) && $userDetail['user_type'] == $userTypes['id']){ echo "selected='selected'"; } ?> ><?php echo ucwords(stripslashes($userTypes['user_type']));?></option>
						<?php } ?>							
					</select>											
				<?php } ?>
					<!-- <input <?php if(isset($dbUserDetail) & $dbUserDetail['is_trial']!='0'){?>disabled="true"<?php } ?> type="text" name="user_type" value="<?php  if(isset($userArr['user_type'])){echo $userTypeDetail['user_type'];} ?>" > -->

					<?php if(isset($dbUserDetail) & $dbUserDetail['is_trial']!='0'){ ?>
					<input type="hidden" name="user_type" value="<?php  if(isset($userDetail['user_type'])){echo $userDetail['user_type'];} ?>">
					<?php } ?>

				</div>
				<div class="clear"></div>
			<?php } ?>
			<div class="inputshell">
				<p>Number of Users<em>*</em></p>
				<input <?php if(isset($dbUserDetail) & $dbUserDetail['is_trial']!='0'){?>disabled="true"<?php } ?> type="text" name="number_of_users" value="<?php  if(isset($userArr['number_of_users'])){echo $userArr['number_of_users'];} ?>">

				<?php if(isset($dbUserDetail) & $dbUserDetail['is_trial']!='0'){?>
				<input type="hidden" name="number_of_users" value="<?php  if(isset($userArr['number_of_users'])){echo $userArr['number_of_users'];} ?>">
				<?php } ?>

			</div>
			<div class="clear"></div>

			<div class="inputshell">
				<p>Address<em>*</em></p>
				<textarea rows="3" cols="22" name='address' class='required'><?php  if(isset($userArr['address'])){echo $userArr['address'];} ?></textarea>
			</div>
			<div class="clear"></div>

			<div class="inputshell">
				<p><input type="checkbox" name='use_name_as_brand' <?php  if(isset($userArr['use_brand_name']) && $userArr['use_brand_name'] == 'Y'){ echo "checked"; } ?>/></p>
				Use Organization Name in Welcome Message
				
			</div>
			<div class="clear"></div>
			
			<!-- <div class="inputshell">
				<p>Organisation Address</p>
				<textarea rows="3" cols="22"  name='organistaion_status'><?php  if(isset($userArr['organisation_address'])){echo $userArr['organisation_address'];} ?></textarea>							
			</div>
			<div class="inputshell"> -->
			<input type='hidden' name='organistaion_status' />

				<p>&nbsp;</p>
				<input type='hidden' name='updateprofile' value="updateprofile">&nbsp;&nbsp;&nbsp;
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

<?php } ?>

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
<script>
function chckphone(){
	var filter = /^[0-9]{3}-[0-9]{3}-[0-9]{4}$/;
	var number = $("#phone").val();
	
	 var test_bool = filter.test(number);

	 if(test_bool==false){
	  alert('Please enter phone number in US format');
	  $("#phone").val('');
	  return false; 
	 }
}
</script>

<?php include($basedir.'/include/footerHtml.php'); ?>