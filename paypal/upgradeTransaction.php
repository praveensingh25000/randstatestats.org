<?php
/******************************************
* @Modified on 15 July, 2013
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."/..";
require_once($basedir.'/include/actionHeader.php');

$admin = new admin();
$user = new user();

$typeDetail = $admin->getUserType($_SESSION['payment_upgrade']['user_type']);

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

	$payment_Amount		= round($_SESSION['payment_upgrade']['total_amount'],2);
	$paymentmethod		= trim($_SESSION['payment_upgrade']['mode']);	
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

			$plan_id				=	$_SESSION['payment_upgrade']['plan_name'];
			$plan_name				=	$plan_id . " year (".ucwords($typeDetail['user_type']).")";

			$validity				=	$plan_id * 365;

			$db_amount				=	round($_SESSION['payment_upgrade']['total_amount'],2);
			$transaction_id			=	$_SESSION['trans_before_id'];
	
			$discounts				=	0;
			$institution_type_id	=	'';
			$purchase_amt			=	$db_amount;
			$number_of_users		= (isset($_SESSION['payment_upgrade']['number_of_users']))?trim(addslashes($_SESSION['payment_upgrade']['number_of_users'])):'0';

			$is_trial				=	1;

			$db_valuesArray		=	$dbidArray	=	$db_idArray = array();
	
			$db_names = $_SESSION['payment_upgrade']['db_name'];

			foreach($db_names as $key => $dbid){
				$db_valuesArray[$dbid] = 0;
			}

			if(isset($_SESSION['user'])) {

				$user_id =	$_SESSION['user']['id'];

				$membership_id = $transaction_id;

				$insert	=$admin->upgradeMembershipDatabaseDetail($membership_id,$user_id,$plan_id,$db_valuesArray,$validity,$is_trial);
							
				$updatePlanTransaction	=	$admin->updatePlanTransaction($user_id, $paypal_return_transaction_id, $status, $paypal_before_transaction_id, $payment_type, $plan_name, $_SESSION['payment_upgrade']['user_type'], '', $number_of_users);
				
				$_SESSION['msgsuccess']='17';

			} 					
			
			unset($_SESSION['trans_before_id']);
			unset($_SESSION['payment_upgrade']);
			unset($_SESSION['plan_data']);			
			unset($_SESSION['db_membership_id']);
			unset($_SESSION['data']);
			unset($_SESSION['nvpReqArray']);
			unset($_SESSION['nvpResArray']);
			unset($_SESSION['db_name']);
		
			
			header('location:viewReciept.php?id='.$paypal_before_transaction_id.'');
			
		} else {

			unset($_SESSION['trans_before_id']);
			unset($_SESSION['payment_data']);
			unset($_SESSION['plan_data']);
			unset($_SESSION['db_membership_id']);
			unset($_SESSION['data']);
			unset($_SESSION['nvpReqArray']);
			unset($_SESSION['nvpResArray']);
			unset($_SESSION['db_name']);
			
			$_SESSION['msgerror']='16';
			header('location:'.URL_SITE.'/cancel.php');
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
		header('location:'.URL_SITE.'/index.php');
	}
}
?>