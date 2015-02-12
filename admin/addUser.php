<?php
/******************************************
* @Modified on July 11, 2013
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."/..";
include_once $basedir."/include/adminHeader.php";

echo '<link href="<?php echo URL_SITE; ?>/css/ui.css" type="text/css" rel="stylesheet">';

checkSession(true);

$admin = new admin();
$user  = new user();

$typesResult	 = $admin->showAllUserTypes();
$usertypesAll	 = $db->getAll($typesResult);

if(isset($_POST['user_registration_submit']) || isset($_POST['user_registration_hidden'])) {
	

	$name				=   mysql_real_escape_string($_POST['name']);
	$last_name			=	mysql_real_escape_string($_POST['last_name']);
	$email				=   mysql_real_escape_string($_POST['email']);
	$endode_email		=   base64_encode($email);
	$pw					=   mysql_real_escape_string($_POST['password']);
	$phone				=   $_POST['phone'];
	$address			=   mysql_real_escape_string($_POST['address']);
	$organisation		=	mysql_real_escape_string($_POST['organisation']);
	$org_address		=	mysql_real_escape_string($_POST['organisation_address']);
	$user_type			=	$_POST['user_type'];
	$active_status		=   1;

	if($user_type == 5){
		$number_of_users	= 1;
	} else {
		$number_of_users	= (isset($_POST['number_of_users']))?trim(addslashes($_POST['number_of_users'])):'0';
	}

	$payment_status		=	$_POST['payment_status'];
	$payment_details	=	mysql_real_escape_string($_POST['payment_details']);
	$total_amount		=	(isset($_POST['total_amount']))?$_POST['total_amount']:0;
	$surchargeamount	=	(isset($_POST['surcharge_amount']))?$_POST['surcharge_amount']:0;
	$discountamount		=	(isset($_POST['discount_amount']))?$_POST['discount_amount']:0;

	$userid	= $user ->userRegistration($name,$email,$pw,$phone,$address,$organisation,$org_address,$user_type,$active_status, $last_name);

	$update_numbersofusers = $user ->updateNumberofUsers($userid, $number_of_users);

	$receivermail		=	$email;
	$receivename		=	$name;
	$fromname			=	FROM_NAME;
	$fromemail			=	FROM_EMAIL;
	$mailbody			=	"";

	if(isset($_POST['account_type']) && $_POST['account_type'] == 'PA') {
		if(!isset($_POST['db_name']) || (isset($_POST['db_name']) && count($_POST['db_name']) <= 0)){
			$_SESSION['errormsg']="All details related to subscription are necessary. Please try again";
			header("location: addUser.php");
			exit;
		}
		

		$plan_id = $_POST['plan_name'];
		
		$validity = 365 * $_POST['plan_name'];

		$db_valuesArray = array();
		foreach($_POST['db_name'] as $keydb => $dbid){
			$db_valuesArray[$dbid] = 0;
		}

		$typeDetail = $admin->getUserType($user_type);

		$plan_name = $plan_id." year ( ".$typeDetail['user_type']." )";

		$trans_before_id	=	$admin->insertPlanTransaction($plan_id, $total_amount, $discountamount, $surchargeamount);

		$insert				= $admin->insertMembershipDatabaseDetail($trans_before_id, $userid, $plan_id, $db_valuesArray,$validity, '1');

		$txn_id = "OFFLINE".$trans_before_id;

		$payment_type = "offline";

		$updatePlanTransaction	=	$admin->updatePlanTransaction($userid, $txn_id, $payment_status, $trans_before_id, $payment_type,  $plan_name, $user_type, $payment_details, $number_of_users);
		$userDefinedvalue	= array(array('receivername'=>$receivename),array('email'=>$email),array('pswrd'=>$pw),array('accvalidity'=>$validity));

		$mailbody		= getMailTemplate($key=9, $receivename, $receivermail, $fromname, $fromemail,$userDefinedvalue);
	}

	if(isset($_POST['account_type']) && $_POST['account_type'] == 'TP') {

			$db_valuesArray = array(1 => '0', 2 => "0", 3 => "0", 4 => "0");
	
			$validity = VALIDITY;
			
			$insert	= $admin->insertMembershipDatabaseDetail('0',$userid, '0', $db_valuesArray,$validity, '0');
			$userDefinedvalue	= array(array('receivername'=>$receivename),array('email'=>$email),array('pswrd'=>$pw),array('accvalidity'=>$validity));

			$mailbody		= getMailTemplate($key=9, $receivename, $receivermail, $fromname, $fromemail,$userDefinedvalue);
			
	}

	if(isset($mail_notification) && $mail_notification == '1'){
		$subject='Registration Detail Mail';	
		$send_mail= mail_function($receivename,$receivermail,$fromname,$fromemail,$mailbody,$subject, $attachments = array(),$addcc=array());
	}

	$_SESSION['successmsg']="User has been added successfully.";
	header("location:users.php");
}
if(isset($_GET['cancel'])){
	unset($_SESSION['data']);
	header("location:admin/addUser.php");
}
?>


<!-- container -->
<section id="container">
	 <!-- main div -->
	 <div class="main-cell">
		 <aside class="containerRadmin">
			<?php include_once $basedir."/include/adminLeft.php"; ?>
		</aside>

		<!-- left side -->
		<div class="containerLadmin">
			
			<div style="clear: both;">
				<fieldset style="border: 1px solid #cccccc;">
					
					<legend style="background: #cccccc; font-size: 14px; padding: 5px;">Add User</legend>
					<br class="clear" />

					<form method="POST" id="user_registrationadmin" name="user_registrationadmin" action="" style="width:350px;float:left;">

						<div style="width:700px;">
							<p class="pB5">First Name<em>*</em></p>							
							<input placeholder="Enter your first name" name="name" type="text" value="<?php if(isset($_SESSION['data']['name'])){ echo $_SESSION['data']['name']; }?>" class="required" id="name" />							
						</div>
						<div class="clear"></div>

						<div style="width:700px;">
							<p class="pB5">Last Name</p>							
							<input placeholder="Enter your last name" name="last_name" type="text" value="<?php if(isset($_SESSION['data']['last_name'])){ echo $_SESSION['data']['last_name']; }?>"  />							
						</div>
						<div class="clear"></div>

						<div style="padding: 10px 0px;width:700px;">
							<p class="pB5">Username<!-- <em>*</em> --></p>
							<input placeholder="Enter your username" name="username" value="<?php if(isset($_SESSION['data']['username'])){ echo $_SESSION['data']['username']; }?>" type="text" class="" id="username" />							
						</div>
						<div class="clear"></div>

						<div style="padding: 10px 0px;width:700px;">
							<p class="pB5">Organization<em>*</em></p>
							<input placeholder="Enter your organization name" name="organisation" type="text" value="<?php if(isset($_SESSION['data']['organisation'])){ echo $_SESSION['data']['organisation']; }?>" class="required" id="organisation" />	
						</div>
						<div class="clear"></div>
											
						<div style="padding: 10px 0px;width:700px;">
							<p class="pB5">Email<em>*</em></p>							
							<input placeholder="Enter your email" name="email" value="<?php if(isset($_SESSION['data']['email'])){ echo $_SESSION['data']['email']; }?>" type="text" class="email required" id="email" />							
						</div>
						<div class="clear"></div>

						<div style="padding: 10px 0px;width:700px;">
							<p class="pB5">Password<em>*</em></p>							
							<input placeholder="Enter a password" name="password" value="<?php if(isset($_SESSION['data']['password'])){ echo $_SESSION['data']['password']; }?>" type="password" class="required" id="password" />							
						</div>
						<div class="clear"></div>

						<div style="padding: 10px 0px;width:700px;">
							<p class="pB5">Phone<em>*</em></p>														
							<input placeholder="Enter your phone Number" name="phone" type="text" value="<?php if(isset($_SESSION['data']['phone'])){ echo $_SESSION['data']['phone'];}?>" class="required" id="phone" onchange="chckphone('phone')"/>&nbsp;&nbsp;<em>eg:123-123-1234</em>	
						</div>
						<div class="clear"></div>

						<div style="padding: 10px 0px;width:700px;">
							<p class="pB5">User Type<em>*</em></p>
							<?php if(!empty($usertypesAll)) { ?>
								<select class="required" id="user_type" name="user_type" style="width:355px;">
									<option value=""> Select User Type </option>
									<?php foreach($usertypesAll as $userTypes) { ?>
										<option value="<?php echo $userTypes['id'];?>" <?php if(isset($_SESSION['data']['user_type']) && $_SESSION['data']['user_type'] == $userTypes['user_type']){ echo "selected='selected'"; } ?> ><?php echo ucwords($userTypes['user_type']);?></option>
									<?php } ?>							
								</select>
								<?php
							if(!isset($_GET['step'])) { ?>								
							<script type="text/javascript">
							jQuery(document).ready(function(){
								jQuery("#user_type").change(function(){								
									var user_type  = jQuery("#user_type").val();
									var number_of_users  = jQuery("#number_of_users").val();
									if(user_type != ''){
										if(user_type != '5'){	
											jQuery("#number_of_users_show_div").show();
											jQuery("#number_of_users").val("");
										} else {
											jQuery("#number_of_users_show_div").hide();
											jQuery("#number_of_users").val("1");
										}
										
										var user_type  = jQuery("#user_type").val();

										var number_of_users1  = jQuery("#number_of_users").val();
										if(user_type != '' && number_of_users1 > 0){
											planDetail();
										}

									} else {
										jQuery("#number_of_users_show_div").hide();
										jQuery("#number_of_users").val("1");
									}
								});	
							});
							</script>
							<?php } } ?>
						</div>
						<?php 
					if(isset($_SESSION['data']['user_type']) && $_SESSION['data']['user_type']!=5){ 
						$showNoOfUsers = "block";
					} else {
						$showNoOfUsers = "none";
					}
					?>
			
					<div  id="number_of_users_show_div" style="padding: 10px 0px;width:700px;display:<?php echo $showNoOfUsers; ?>;">
						<p>Number of users<em>*</em></p>
						<span class="">
						<input placeholder="Enter number of users" name="number_of_users" value="<?php if(isset($_SESSION['data']['number_of_users'])){ echo $_SESSION['data']['number_of_users']; } else { echo "1"; }?>" type="text" class="digits required" id="number_of_users" />&nbsp;&nbsp;<a href="javascript:;" id="popupnoofusers"><img src="/images/question16x16.png"></a><span >Value should be greater than 0.</span>
						<label style="display:none;" for="number_of_users" generated="true" class="error">This field is required.</label>
						</span>

						<SCRIPT LANGUAGE="JavaScript">
						$(document).ready(function() { 
							$('#popupnoofusers').click(function() { 
								 $.blockUI({ 
									message: $("<div class='login-popup' style='color: #ffffff;top:20%;left:30%;'><ul><li>Academic libraries: fall Full-Time Equivalent (FTE) students.</li><li>Public libraries: city or county population or the population in your service area.</li><li>K-12 libraries: FTE students.</li><li>Multiple users: the number of login IDs required.</li><li>All others: Total number of users with access to the site(s).</li></ul></div>")
								}); 
								$('.blockOverlay').attr('title','Click to unblock').click($.unblockUI); 
							}); 
						}); 
						</SCRIPT>
					</div>
					<div class="clear"></div>

					<div style="padding: 10px 0px;width:700px;">
						<p class="pB5">Address</p>
						<textarea rows="3" cols="24" placeholder="Enter your address" name="address" class="" id="address" /><?php if(isset($_SESSION['data']['address'])){ echo $_SESSION['data']['address']; }?></textarea>
					</div>
					<div class="clear"></div>

					<div style="padding: 10px 0px;width:700px;">
						<p class="pB5">Discount(%)</p>														
						<input placeholder="Enter discount to offer this user" name="discount" type="text" value="<?php if(isset($_SESSION['data']['discount'])){ echo $_SESSION['data']['discount'];}?>" class="" id="discount" onchange=" javascript: getTotalFunctionAdmin();"/>	
					</div>

						
						<input type="hidden" name="organisation_address" value="">

						<!-- <div style="padding: 10px 0px;width:700px;">
							<p class="pB5">Organization Address</p>
							<textarea placeholder="Enter your organisation address" name="organisation_address" class="" id="organisation_address" rows="3" cols="24" /><?php if(isset($_SESSION['data']['organisation_address'])){ echo $_SESSION['data']['organisation_address']; }?></textarea>
						</div>
						<div class="clear"></div> -->
						
						<div style="padding: 10px 0px;width:700px;">
						<p class="pB5">Select account Type<em>*</em></p>
							<input class="required" type="radio"  value="TP" name="account_type">
							<span class="pL5" > Trial Account </span>&nbsp;&nbsp;&nbsp;&nbsp;
							<input  type="radio" value="PA" name="account_type" id="PA">
							<span class="pL5"> Purchase Subscription Plan</span>	
						</div>
					
						<div id="purchasedetails" style="display:none;">
							<div id="plandetails">
							</div>

							<div style="padding: 10px 0px;width:700px;">
								<p class="pB5">Status of payment</p>
								<div class="selectplan">
									<input type="radio"  name="payment_status" value="1" checked><span class="pL5"> Paid</span>&nbsp;&nbsp;&nbsp;&nbsp;

									<input type="radio"  name="payment_status" value="0" checked><span class="pL5"> Pending</span>&nbsp;&nbsp;&nbsp;&nbsp;

									<input type="radio" name="payment_status" value="2">
									<span class="pL5">In-Abeyance</span>
								</div>

							</div>
							<div class="clear"></div>
							
							<div style="padding: 10px 0px;width:700px;">
								<p class="pB5">Details of payment</p>
								<textarea rows="3" cols="24" placeholder="e.g cheque no. , date, payer etc." name="payment_details" class=""  /></textarea>
							</div>
							<div class="clear"></div>

						</div>

						<script type="text/javascript">
						jQuery(document).ready(function(){
							jQuery('input:radio[name="account_type"]').change(function(){	
								
								var account_type = $('input:radio[name="account_type"]:checked').val();
								
								if(account_type == 'PA'){
									planDetail();
								} else {
									jQuery('#plandetails').html("");
									jQuery('#purchasedetails').hide();
								}
							});	
						});
						</script>
						
						<div style="padding: 10px 0px;width:700px;">
							<label for="submit" class="left">
								<input type="submit" value="Submit" name="user_registration_submit" class="" />
								<input type="hidden" value="user_registration_hidden" name="user_registration_hidden">
								<input onclick="window.location=URL_SITE+'/admin/addUser.php?cancel=1'" type="button" value="Reset" name="cancel" class="mL20" />
							</label>							
						</div>
						<div class="clear"></div>
						
						
					</form>

				</fieldset>
			</div>
			
		</div>
	</div>
	<!-- left side -->		
</section>
<!-- /container -->

<?php include_once $basedir."/include/adminFooter.php"; ?>