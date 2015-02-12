<?php
/******************************************
* @Created on April 22 2013.
* @Package: RAND
* @Developer: Praveen Singh
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."";
include_once $basedir."/include/headerHtml.php";

checkSession(false);

$admin = new admin();
$user = new user();

$paymenttypes_res	= $admin->getPaymentTypes($active=1);
$paymenttypes		= $db->getAll($paymenttypes_res);

if(isset($_SESSION['user']['id']) && $_SESSION['user']['id']!='') {
	$userDetail	= $user->getUser($_SESSION['user']['id']);

	if(isset($userDetail['user_type']) && $userDetail['user_type']!='') {
		$instutionArray = explode('.',$userDetail['user_type']);
		
		if(isset($instutionArray[0]) && isset($instutionArray[1]) && $instutionArray[0]=='Institution') {	
			$instution_id   = $instutionArray[1];
			$UserTypeDetail	= $admin->getUserType($instution_id);
		}
	}
}

if(isset($_REQUEST['submitconfirmpaymentdetailform']) && $_REQUEST['submitconfirmpaymentdetailform']!=''){
	
	$userid			=	$_POST['user_id'];
	$instution_id	=	$_POST['instution_id'];
	$verify			=	$_POST['verify'];

	$paymentDetails = $admin->addconfirmpaymentdetail($userid,$instution_id);
		
	if(!empty($paymentDetails)) {

		$userDetail		 = $user->getUser($paymentDetails['user_id']);	
		$UserTypeDetail  = $admin->getUserType($paymentDetails['instution_id']);

		$receivermail	 =	FROM_EMAIL;
		//$receivermail	 =	$userDetail['email'];

		$receivename	 =	FROM_NAME;
		$institutionname =	stripslashes($UserTypeDetail['user_type']);
		$fromname		 =	$userDetail['name'];
		$fromemail		 =	$userDetail['email'];

		if(!empty($paymenttypes)){
			foreach($paymenttypes as $payments) {
				if($payments['payment_code'] == stripslashes($paymentDetails['mode_of_payment'])) {
					$payment_type = $payments['payment_type']; 
				}
			}
		}

		if($paymentDetails['is_verified'] == '0') {
			$is_verified='<font color="red">Pending</font>';
		} else if($paymentDetails['is_verified'] == '1') { 
			$is_verified= '<font color="green">Verified</font>'; 
		} else {
			$is_verified= '<font color="red">unverified</font>'; 
		}
		
		$mailbody	=	'Hi '.$receivename.', <br />
		<p>'.$fromname.' had send you a payment detail.So,Please Verify the payment Detail given below:</p>
		<div>
			<table cellspacing="0" cellpadding="5" border="1" width="100%" id="grid_view" class="collapse">
				<tr class="txtcenter">
					<th bgcolor="#eeeeee">Username</th>
					<th bgcolor="#eeeeee">Email</th>
					<th bgcolor="#eeeeee">Instution Name</th>
					<th bgcolor="#eeeeee">Is Verified </th>
					<th bgcolor="#eeeeee">Plan Selected</th>
					<th bgcolor="#eeeeee">Mode of Payment</th>							
					<th bgcolor="#eeeeee">Check Number</th>
					<th bgcolor="#eeeeee">Bank Drawn</th>
					<th bgcolor="#eeeeee">Date of Payment</th>
					<th bgcolor="#eeeeee">Added On </th>												
					<th bgcolor="#eeeeee">Action</th>
				</tr>

				<tr>
					<td align="left">'.ucwords($userDetail['name']).'</a></td>							
					<td>'.stripslashes($userDetail['email']).'</td>
					<td>'.$institutionname.'</td>
					<td>'.$is_verified.'</td>
					<td>'.stripslashes($paymentDetails['plan_selected']).'</td>
					<td>'.$payment_type.'</td>
					<td>'.stripslashes($paymentDetails['check_no']).'</td>
					<td>'.ucwords(stripslashes($paymentDetails['bank_drawn'])).'</td>
					<td>'.date('d F, Y',strtotime($paymentDetails['date_of_payment'])).'</td>
					<td>'.date('d F, Y',strtotime($paymentDetails['added_on'])).'</td>
					<td><a href="'.URL_SITE.'/admin/index.php">Click Here to Verify</a></td>
				</tr>

			</table>
		</div>
		<p></p>
		<p>Thank you </p>
		<p>Rand Team </p>';		

		$subject='Payment Verfication Mail';	
		$send_mail= mail_function($receivename,$receivermail,$fromname,$fromemail,$mailbody,$subject, $attachments = array(),$addcc=array());	

		$_SESSION['successmsg'] = "Your details have been recorded and we shall get back to you soon. Please continue using Rand State Stats.";
		header('location: index.php');
		exit;
	} else {
		$_SESSION['errormsg'] = "Sorry!.please reenter your details.";
		header('location: ipRangesConfirmation.php?verify='.base64_encode($verify).'');
		exit;
	}
}

if(isset($_REQUEST['verify']) && $_REQUEST['verify'] == '1') { 
	$_SESSION['infomsg'] = "Please fill the following details.";
	header('location: ipRangesConfirmation.php?verify='.base64_encode($_REQUEST['verify']).'');
	exit;
}
?>
<!-- Container -->
<section id="container">
	<div id="mainshell">
		
		<div class="profile" style="width:1001px;">
			<h2>Account Confirmation</h2><br />
			<div class="profile-outer wdthpercent100">
				<table cellspacing="0" cellpadding="10" border="1" width="96%" class="collapse">
						
					<tr class="txtcenter">
						<th colspan="2" class="txtcenter font14" bgcolor="#eeeeee">Your Trial Period ends on <span style="color:red;"><?php echo date('m/d/Y',strtotime($userDetail['expire_time']));?></span>.</th>	
					</tr>

					<tr class="txtcenter">
						<td>Please choose a Plan and Proceed to make a Payment.</th>			
						<td>If already subscribed and paid, please Verify payment details. </th>
					</tr>

					<tr class="txtcenter">
						<td><input onclick="javascript: window.location=URL_SITE+'/plansubscriptions.php?confirmed=1'" type="submit" value="Payment" name="submitpayment" class=""></th>	
						<td><input onclick="javascript: window.location=URL_SITE+'/ipRangesConfirmation.php?verify=1'" type="submit" value="Verify" name="submitverify" class=""></th>
					</tr>

				</table>
			</div>
		</div>

		<?php if(isset($_REQUEST['verify']) && trim(base64_decode($_REQUEST['verify'])) == '1') { ?>

		<br class="clear pB10" />
		<div class="profile" style="width:1001px;">
			<h2>Please fill the following details.</h2><br />

				<div class="profile-outer wdthpercent100">
				
				  <form action="" id="addconfirmpaymentdetailform" name="addconfirmpaymentdetailform" method="post">				
					<div style="wdthpercent100 pT10 pB10">
						<div id="" class="wdthpercent20 left">User Type</div>
						<div id="" class="wdthpercent50 left">
							<h3><?php if(isset($UserTypeDetail)){ echo $UserTypeDetail['user_type']; } ?></h3>	
						</div>
					</div>	
					<div id="" class="clear pB10"></div>
					
					<div style="wdthpercent100 pT10 pB10">
						<div id="" class="wdthpercent20 left">Plan Selected</div>
						<div id="" class="wdthpercent50 left">
							<input placeholder="Enter the plan name" type="text" id="plan_selected" name="plan_selected" class="required" value=""/>							
						</div>
					</div>	
					<div id="" class="clear pB10"></div>

					<div style="wdthpercent100 pT10 pB10">
						<div id="" class="wdthpercent20 left">Mode of Payment</div>
						<div id="" class="wdthpercent50 left">
							<select class="wdthpercent40 required" name="mode_of_payment">
								<option value="" selected>Select Mode of Payment</option>
								<?php if(!empty($paymenttypes)){?>
									<?php foreach($paymenttypes as $payments) { ?>
									<option value="<?php echo $payments['payment_code']; ?>"><?php echo $payments['payment_type']; ?></option>
									<?php } ?>
								<?php } ?>
							</select>							
						</div>
					</div>	
					<div id="" class="clear pB10"></div>
					
					<div style="wdthpercent100 pT10 pB10">
						<div id="" class="wdthpercent20 left">Date of Payment</div>
						<div id="" class="wdthpercent80 left">
							<input maxlength="4" placeholder="Enter year" type="text" id="payment_yr" name="payment_yr" class="digits required" value="<?php if(isset($user_type)){ echo $user_type; } ?>"/>&nbsp;-&nbsp;
							<input maxlength="2" placeholder="Enter month" type="text" id="payment_month" name="payment_month" class="digits required" value="<?php if(isset($user_type)){ echo $user_type; } ?>"/>&nbsp;-&nbsp;
							<input maxlength="2" placeholder="Enter date" type="text" id="payment_day" name="payment_day" class="digits required" value="<?php if(isset($user_type)){ echo $user_type; } ?>"/>							
							<br><span class="fontbld">Format: yyyy-mm-dd </span>
						</div>
					</div>	
					<div id="" class="clear pB10"></div>

					<div style="wdthpercent100 pT10 pB10">
						<div id="" class="wdthpercent20 left">Check No.</div>
						<div id="" class="wdthpercent50 left">
							<input placeholder="Enter Check Number" type="text" name="check_no" class="required digits" value="<?php if(isset($user_type)){ echo $user_type; } ?>"/>
						</div>
					</div>	
					<div id="" class="clear pB10"></div>

					<div style="wdthpercent100 pT10 pB10">
						<div id="" class="wdthpercent20 left">Bank drawn</div>
						<div id="" class="wdthpercent50 left">
							<input placeholder="Enter Bank Name" type="text" name="bank_drawn" class="required" value="<?php if(isset($user_type)){ echo $user_type; } ?>"/>
						</div>
					</div>	
					<div id="" class="clear pB20"></div>

					<div style="wdthpercent100">
						<div id="" class="wdthpercent20 left">&nbsp;</div>
						<div id="" class="wdthpercent70 left">						
							<input type="submit" value="Submit" name="submitconfirmpaymentdetailform" class="submitbtn" >						
							<input onclick="javascript:window.history.go(-1)" type="button" value="Back" class="submitbtn">
							<input type="hidden" name="user_id" value="<?php echo $_SESSION['user']['id']; ?>">
							<input type="hidden" name="instution_id" value="<?php echo $instution_id;?>">
							<input type="hidden" name="verify" value="<?php echo trim(base64_decode($_REQUEST['verify']));?>">						
						</div>
					</div>	
					<div id="" class="clear"></div>
				 </form>
			</div>
		</div>
		<?php } ?>

	</div>
	<div class="clear pT30"></div>

</section>
<!-- /Container -->
<div class="clear"></div>

<?php include_once $basedir."/include/footerHtml.php"; ?>