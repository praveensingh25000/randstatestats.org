<?php
/******************************************
* @Modified on 29 JAN, 2013
* @Package: Rand
* @Developer: Praveen Singh
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."/..";
require_once($basedir.'/include/actionHeader.php');

$admin = new admin();
$user = new user();
$emailObj = new emailTemp();

$typeDetail = $admin->getUserType($_SESSION['data']['user_type']);

/*==================================================================
PayPal Express Checkout Call
===================================================================
*/
// Check to see if the Request object contains a variable named 'token'

$token = "";
if(isset($_REQUEST['token'])){

	$token = $_REQUEST['token'];
	$PayerID=$_REQUEST['PayerID'];
}

// If the Request object contains the variable 'token' then it means that the user is coming from PayPal site.

if($token!="") {

	require_once('config.php');
	require_once ("paypalfunctions.php");

	/*
	'------------------------------------
	' Calls the GetExpressCheckoutDetails API call
	'
	' The GetShippingDetails function is defined in PayPalFunctions.jsp
	' included at the top of this file.
	'-------------------------------------------------
	*/

	$payment_Amount		= round($_SESSION['payment_data']['total_amount'],2);
	$paymentmethod		= trim($_SESSION['payment_data']['mode']);	
	$paypalreturnArray  = ConfirmPayment($payment_Amount, $token, $PayerID, $paymentmethod);
	
	if(!empty($paypalreturnArray)) {

		$ack = strtoupper($paypalreturnArray["ACK"]);

		if($ack == "SUCCESS" || $ack == "SUCCESSWITHWARNING") {

			$paypal_before_transaction_id	=	$_SESSION['trans_before_id'];			
			$paypal_return_transaction_id	=	$paypalreturnArray['TRANSACTIONID'];

			$ACK=$paypalreturnArray['ACK'];			
			$amount=$paypalreturnArray['AMT'];
			
			$payment_type=$paypalreturnArray['PAYMENTINFO_0_TRANSACTIONTYPE'];
			$status=1;	

			$plan_id				=	$_SESSION['payment_data']['plan_name'];
			$plan_name				=	$plan_id . " year (".ucwords($typeDetail['user_type']).")";

			$validity				=	$plan_id * 365;

			$db_amount				=	$_SESSION['payment_data']['total_amount'];
			$transaction_id			=	$_SESSION['trans_before_id'];
	
			$discounts				=	0;
			$institution_type_id	=	'';
			$purchase_amt			=	$db_amount;
			$number_of_users		= (isset($_SESSION['data']['number_of_users']))?trim(addslashes($_SESSION['data']['number_of_users'])):'0';

			$is_trial				=	1;

			$db_valuesArray		=	$dbidArray	=	$db_idArray = array();
	
			$db_names = $_SESSION['payment_data']['db_name'];

			foreach($db_names as $key => $dbid){
				$db_valuesArray[$dbid] = 0;
			}

			if(isset($_SESSION['user'])) {

				$user_id =	$_SESSION['user']['id'];

				$membership_id = $transaction_id;

				$insert	=$admin->insertMembershipDatabaseDetail($membership_id,$user_id,$plan_id,$db_valuesArray,$validity,$is_trial);
							
				$updatePlanTransaction	=	$admin->updatePlanTransaction($user_id, $paypal_return_transaction_id, $status, $paypal_before_transaction_id, $payment_type, $plan_name, $_SESSION['user']['user_type'], "", $number_of_users);
				
				$_SESSION['msgsuccess']='17';

			} else {

				//from user registration
				if(!empty($_SESSION['data'])) {

					$name				=	mysql_real_escape_string($_SESSION['data']['name']);
					$last_name			=	mysql_real_escape_string($_SESSION['data']['last_name']);
					$email				=	mysql_real_escape_string($_SESSION['data']['email']);
					$endode_email		=	base64_encode($email);
					$password			=	mysql_real_escape_string($_SESSION['data']['password']);
					$phone				=	$_SESSION['data']['phone'];
					$address			=	mysql_real_escape_string($_SESSION['data']['address']);
					$organisation		=	mysql_real_escape_string($_SESSION['data']['organisation']);
					$org_address		=	mysql_real_escape_string($_SESSION['data']['organisation_address']);
					$user_type			=	$_SESSION['data']['user_type'];
					
					$user_id	= $user ->userRegistration($name,$email,$password,$phone,$address,$organisation,$org_address,$user_type, 0, $last_name);
					$update_numbersofusers = $user ->updateNumberofUsers($user_id, $number_of_users);
					
					$billingcontactarray	= $_SESSION['billing'];
					$technicalcontactarray	= $_SESSION['technical'];
					$admincontactarray		= $_SESSION['admincontact'];

					$additionalinformation = $user->updateUserAdditionalFields($billingcontactarray, $admincontactarray, $technicalcontactarray, $user_id);

					//$membership_id	=$admin->insertuserMembership($user_id,$plan_id,$plan_name,$validity,$db_amount_main,$transaction_id,$number_of_users,$discounts,$purchase_amt,$institution_type_id);

					$membership_id = $transaction_id;

					$insert	=$admin->insertMembershipDatabaseDetail($membership_id,$user_id,$plan_id,$db_valuesArray,$validity,$is_trial);

					$updatePlanTransaction	=	$admin->updatePlanTransaction($user_id,$paypal_return_transaction_id,$status,$paypal_before_transaction_id,$payment_type, $plan_name, $_SESSION['data']['user_type'], "", $number_of_users);
	
					if(isset($mail_notification) && $mail_notification == '1'){	
						$templateKey		=	2;
						$receivermail		=	trim($email);
						$receivename		=	ucwords($name." ".$last_name);
						$from_name			=	FROM_NAME;
						$from_email			=	FROM_EMAIL;
						$userDefinedArray	=   array(array('receivername' => $receivename),array('endode_email' => $endode_email));	
						
						$mailbody			=	getMailTemplate($templateKey, $receivename, $receivermail, $from_name, $from_email,$userDefinedArray);	
						
						$tempid = '2';
						$tempDetail = $emailObj->getTemp($tempid);
						if(!empty($tempDetail)){
							$emailSubject	 = stripslashes($tempDetail['subject']);
						}
						
						$subject     = $emailSubject;
						
						//$subject='Registration Verfication Mail';	
						$send_mail= mail_function($receivename,$receivermail,$from_name,$from_email,$mailbody,$subject, $attachments = array(),$addcc=array());
					}

					$_SESSION['msgsuccess']='21';
				}
			}					
			
			unset($_SESSION['trans_before_id']);
			unset($_SESSION['payment_data']);
			unset($_SESSION['plan_data']);			
			unset($_SESSION['db_membership_id']);
			unset($_SESSION['data']);
			unset($_SESSION['nvpReqArray']);
			unset($_SESSION['nvpResArray']);
			unset($_SESSION['db_name']);
		
			
			header('location:viewReciept.php?id='.$paypal_before_transaction_id.'');
			
		} else {
			
			$_SESSION['msgerror']='16';
			header('location:'.URL_SITE.'/paypal/cancel.php');
		}
	} else {

		//Display a user friendly Error on the page using any of the following error information returned by PayPal
		$ErrorCode = urldecode($paypalreturnArray["L_ERRORCODE0"]);
		$ErrorShortMsg = urldecode($paypalreturnArray["L_SHORTMESSAGE0"]);
		$ErrorLongMsg = urldecode($paypalreturnArray["L_LONGMESSAGE0"]);
		$ErrorSeverityCode = urldecode($paypalreturnArray["L_SEVERITYCODE0"]);

		echo "GetExpressCheckoutDetails API call failed. ";
		echo "Detailed Error Message: " . $ErrorLongMsg;
		echo "Short Error Message: " . $ErrorShortMsg;
		echo "Error Code: " . $ErrorCode;
		echo "Error Severity Code: " . $ErrorSeverityCode;	
		
		unset($_SESSION['trans_before_id']);
		unset($_SESSION['payment_data']);
		unset($_SESSION['plan_data']);
		unset($_SESSION['db_membership_id']);
		unset($_SESSION['data']);
		unset($_SESSION['nvpReqArray']);
		unset($_SESSION['nvpResArray']);
		unset($_SESSION['db_name']);
		
		$_SESSION['msgerror']='16';
		header('location:'.URL_SITE.'/paypal/cancel.php');
	}
}
?>