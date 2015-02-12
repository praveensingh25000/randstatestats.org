<?php
/******************************************
* @Modified on 29 JAN, 2013
* @Package: Rand
* @Developer: Praveen Singh
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."/..";
require_once($basedir.'/include/actionHeader.php');
require_once('config.php');
require_once('paypalfunctions.php');

$admin = new admin();
$user  = new user();
$emailObj = new emailTemp();

/*  ****************************************   */
// ==================================
// PayPal Express Checkout Module
// ==================================

//'------------------------------------
//' The paymentAmount is the total value of
//' the shopping cart, that was set
//' earlier in a session variable
//' by the shopping cart page
//'------------------------------------

if(isset($_SESSION['payment_data']['plan_name']))   $planname = $_SESSION['payment_data']['plan_name'];

if(isset($_SESSION['payment_data']['mode']))     $redirectPaymentURL =	$_SESSION['payment_data']['mode'];

if(isset($_SESSION['payment_data']['plan_id']))  $plan_id =	$_SESSION['payment_data']['plan_id'];

if(isset($_SESSION['payment_data']['total_amount'])) $paymentAmount = $_SESSION['payment_data']['total_amount'];

if(isset($_SESSION['payment_data']['creditCardType'])) $creditCardType =	$_SESSION['payment_data']['creditCardType'];

if(isset($_SESSION['payment_data']['creditCardNumber'])) $creditCardNumber =	$_SESSION['payment_data']['creditCardNumber'];

if(isset($_SESSION['payment_data']['expDateMonth'])) $expDateMonth    =	$_SESSION['payment_data']['expDateMonth'];

if(isset($_SESSION['payment_data']['expDateYear']))	 $expDateYear    =	$_SESSION['payment_data']['expDateYear'];

$padDateMonth = str_pad($expDateMonth, 2, '0', STR_PAD_LEFT);
$expDateYear =urlencode( $expDateYear );
$expDate			=	$_SESSION['payment_data']['expDate']	=	trim($padDateMonth.$expDateYear);
if(isset($_SESSION['payment_data']['cvv2']))	 $cvv2	     =	$_SESSION['payment_data']['cvv2'];
if(isset($_SESSION['payment_data']['firstName'])) $firstName =	$_SESSION['payment_data']['firstName'];
if(isset($_SESSION['payment_data']['lastName']))  $lastName	 =	$_SESSION['payment_data']['lastName'];
if(isset($_SESSION['payment_data']['address1']) && $_SESSION['payment_data']['address1']!=''){ $street1  =	 $_SESSION['payment_data']['address1'];     }
if(isset($_SESSION['payment_data']['address2']) && $_SESSION['payment_data']['address2']!=''){ $street1 .=	 ','.$_SESSION['payment_data']['address2']; }
$street	 =	$_SESSION['payment_data']['street'] = $street1;
if(isset($_SESSION['payment_data']['city']))      $city		 =	$_SESSION['payment_data']['city'];
if(isset($_SESSION['payment_data']['state']))     $state	 =	$_SESSION['payment_data']['state'];
if(isset($_SESSION['payment_data']['zip']))       $zip		 =	$_SESSION['payment_data']['zip'];
if(isset($_SESSION['payment_data']['countryCode'])) $countryCode =	$_SESSION['payment_data']['countryCode'];

$typeDetail = $admin->getUserType($_SESSION['data']['user_type']);

$plan_name = $planname. " year (".ucwords($typeDetail['user_type']).")";

//'------------------------------------
//' The currencyCodeType and paymentType
//' are set to the selections made on the Integration Assistant
//'------------------------------------

$_SESSION['currencyCodeType']	=	$currencyCodeType = "USD";
$paymentType					=	"Sale";
#$paymentType = "Authorization";
#$paymentType = "Order";

//'------------------------------------
//' The returnURL is the location where buyers return to when a
//' payment has been succesfully authorized.
//'
//' This is set to the value entered on the Integration Assistant
//'------------------------------------

$returnURL = PAYPAL_RETURN_URL; 

//'------------------------------------
//' The cancelURL is the location buyers are sent to when they hit the
//' cancel button during authorization of payment during the PayPal flow
//'
//' This is set to the value entered on the Integration Assistant
//'------------------------------------

$cancelURL = PAYPAL_DECLINE_URL;

//'------------------------------------
//' Calls the SetExpressCheckout API call
//'
//' The CallShortcutExpressCheckout function is defined in the file PayPalFunctions.php,
//' it is included at the top of this file.
//'-------------------------------------------------

$paymentAmount = round($paymentAmount,2);

$resArray = DirectPayment ($paymentType, $paymentAmount, $creditCardType, $creditCardNumber, $expDate, $cvv2, $firstName, $lastName, $street, $city, $state, $zip, $countryCode, $currencyCodeType,$plan_name);

$ack = strtoupper($resArray["ACK"]);

if($ack=="SUCCESS" || $ack=="SUCCESSWITHWARNING")
{
	$transactionID = $resArray['TRANSACTIONID'];
	$nvpStr="&TRANSACTIONID=$transactionID";

	/* Make the API call to PayPal, using API signature.
	The API response is stored in an associative array called $resArray */

	$paypalreturnArray=hash_call("gettransactionDetails",$nvpStr);

	if(!empty($paypalreturnArray)) {

			$ack = strtoupper($paypalreturnArray["ACK"]);

			if($ack == "SUCCESS" || $ack == "SUCCESSWITHWARNING") {

				$paypal_before_transaction_id	=	$_SESSION['trans_before_id'];			
				$paypal_return_transaction_id	=	$paypalreturnArray['TRANSACTIONID'];

				$ACK=$paypalreturnArray['ACK'];			
				$amount=$paypalreturnArray['AMT'];
				
				$payment_type=$paypalreturnArray['PAYMENTTYPE'];
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

				$db_valuesArray		=	$dbidArray	=	$array1 = array();
				
				$db_names = $_SESSION['payment_data']['db_name'];

				foreach($db_names as $key => $dbid){
					$db_valuesArray[$dbid] = 0;
				}

				if(isset($_SESSION['user'])) {

					$user_id=$_SESSION['user']['id'];

					$membership_id = $transaction_id;

					$insert	=$admin->insertMembershipDatabaseDetail($membership_id, $user_id, $plan_id, $db_valuesArray, $validity, $is_trial);

					$updatePlanTransaction	=	$admin->updatePlanTransaction($user_id, $paypal_return_transaction_id, $status, $paypal_before_transaction_id, $payment_type, $plan_name, $_SESSION['user']['user_type'], '', $number_of_users);

					$updateCreditCardDetail	=	$admin->updateCreditCardDetailTransaction($creditCardType, $creditCardNumber, $firstName, $lastName, $street, $city, $state, $zip, $countryCode,$paypal_before_transaction_id);
					
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
					
						$membership_id = $transaction_id;

						$insert	=$admin->insertMembershipDatabaseDetail($membership_id,$user_id,$plan_id,$db_valuesArray,$validity,$is_trial);

						$updatePlanTransaction	=	$admin->updatePlanTransaction($user_id, $paypal_return_transaction_id, $status, $paypal_before_transaction_id, $payment_type, $plan_name, $_SESSION['data']['user_type'], "", $number_of_users);

						$updateCreditCardDetail	=	$admin->updateCreditCardDetailTransaction($creditCardType, $creditCardNumber, $firstName, $lastName, $street, $city, $state, $zip, $countryCode,$paypal_before_transaction_id);

						$_SESSION['msgsuccess']='21';
					}
				}					
				
				unset($_SESSION['trans_before_id']);
				unset($_SESSION['payment_data']);
				unset($_SESSION['plan_data']);
				unset($_SESSION['db_membership_id']);
				unset($_SESSION['data']);

				header('location:viewReciept.php?id='.$paypal_before_transaction_id.'');
				
			} else {

				unset($_SESSION['trans_before_id']);
				unset($_SESSION['payment_data']);
				unset($_SESSION['plan_data']);
				unset($_SESSION['db_membership_id']);
				unset($_SESSION['data']);
				
				$_SESSION['msgerror']='16';
				header('location:'.URL_SITE.'/index.php');
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
						
			$_SESSION['msgerror']='16';
			header('location:'.URL_SITE.'/index.php');
		}
} else {

	//Display a user friendly Error on the page using any of the following error information returned by PayPal
	$ErrorCode = urldecode($resArray["L_ERRORCODE0"]);
	$ErrorShortMsg = urldecode($resArray["L_SHORTMESSAGE0"]);
	$ErrorLongMsg = urldecode($resArray["L_LONGMESSAGE0"]);
	//$ErrorSeverityCode = urldecode($resArray["L_SEVERITYCODE0"]);

	echo "SetExpressCheckout API call failed. ";
	echo "Detailed Error Message: " . $ErrorLongMsg;
	echo "Short Error Message: " . $ErrorShortMsg;
	echo "Error Code: " . $ErrorCode;
	echo "Error Severity Code: " . $ErrorSeverityCode;
	
	$transaction_id = (isset($_SESSION['trans_before_id']))?$_SESSION['trans_before_id']:'0';
	if($transaction_id !=0 ){
		$admin->deleteTransaction($transaction_id);
	}

	unset($_SESSION['trans_before_id']);
	unset($_SESSION['payment_data']);
	
	$_SESSION['errormsg'] = $ErrorLongMsg;
	header('location:'.URL_SITE.'/signup.php?step='.base64_encode("PA").'');
}
?>