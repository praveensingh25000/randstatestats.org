<?php
/******************************************
* @Modified on 15 July, 2013
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."/..";
require_once($basedir.'/include/actionHeader.php');
require_once('config.php');
require_once('paypalfunctions.php');

$admin = new admin();
$user  = new user();

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

if(isset($_SESSION['payment_upgrade']['plan_name']))   $planname = $_SESSION['payment_upgrade']['plan_name'];

if(isset($_SESSION['payment_upgrade']['mode']))     $redirectPaymentURL =	$_SESSION['payment_upgrade']['mode'];

if(isset($_SESSION['payment_upgrade']['total_amount'])) $paymentAmount = $_SESSION['payment_upgrade']['total_amount'];

if(isset($_SESSION['payment_upgrade']['creditCardType'])) $creditCardType =	$_SESSION['payment_upgrade']['creditCardType'];

if(isset($_SESSION['payment_upgrade']['creditCardNumber'])) $creditCardNumber =	$_SESSION['payment_upgrade']['creditCardNumber'];

if(isset($_SESSION['payment_upgrade']['expDateMonth'])) $expDateMonth    =	$_SESSION['payment_upgrade']['expDateMonth'];

if(isset($_SESSION['payment_upgrade']['expDateYear']))	 $expDateYear    =	$_SESSION['payment_upgrade']['expDateYear'];

$padDateMonth = str_pad($expDateMonth, 2, '0', STR_PAD_LEFT);
$expDateYear =urlencode( $expDateYear );
$expDate			=	$_SESSION['payment_upgrade']['expDate']	=	trim($padDateMonth.$expDateYear);
if(isset($_SESSION['payment_upgrade']['cvv2']))	 $cvv2	     =	$_SESSION['payment_upgrade']['cvv2'];
if(isset($_SESSION['payment_upgrade']['firstName'])) $firstName =	$_SESSION['payment_upgrade']['firstName'];
if(isset($_SESSION['payment_upgrade']['lastName']))  $lastName	 =	$_SESSION['payment_upgrade']['lastName'];
if(isset($_SESSION['payment_upgrade']['address1']) && $_SESSION['payment_upgrade']['address1']!=''){ $street1  =	 $_SESSION['payment_upgrade']['address1'];     }
if(isset($_SESSION['payment_upgrade']['address2']) && $_SESSION['payment_upgrade']['address2']!=''){ $street1 .=	 ','.$_SESSION['payment_upgrade']['address2']; }
$street	 =	$_SESSION['payment_upgrade']['street'] = $street1;
if(isset($_SESSION['payment_upgrade']['city']))      $city		 =	$_SESSION['payment_upgrade']['city'];
if(isset($_SESSION['payment_upgrade']['state']))     $state	 =	$_SESSION['payment_upgrade']['state'];
if(isset($_SESSION['payment_upgrade']['zip']))       $zip		 =	$_SESSION['payment_upgrade']['zip'];
if(isset($_SESSION['payment_upgrade']['countryCode'])) $countryCode =	$_SESSION['payment_upgrade']['countryCode'];

$typeDetail = $admin->getUserType($_SESSION['payment_upgrade']['user_type']);

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

				$plan_id				=	$_SESSION['payment_upgrade']['plan_name'];
				$plan_name				=	$plan_id . " year (".ucwords($typeDetail['user_type']).")";

				$validity				=	$plan_id * 365;

				$db_amount				=	$_SESSION['payment_upgrade']['total_amount'];
				$transaction_id			=	$_SESSION['trans_before_id'];
		
				$discounts				=	0;
				$institution_type_id	=	'';
				$purchase_amt			=	$db_amount;
				$number_of_users		= (isset($_SESSION['data']['number_of_users']))?trim(addslashes($_SESSION['data']['number_of_users'])):'0';

				$is_trial				=	1;

				$db_valuesArray		=	$dbidArray	=	$array1 = array();
				
				$db_names = $_SESSION['payment_upgrade']['db_name'];

				foreach($db_names as $key => $dbid){
					$db_valuesArray[$dbid] = 0;
				}

				if(isset($_SESSION['user'])) {

					$user_id=$_SESSION['user']['id'];

					$membership_id = $transaction_id;

					$insert	=$admin->insertMembershipDatabaseDetail($membership_id,$user_id,$plan_id,$db_valuesArray,$validity,$is_trial);

					$updatePlanTransaction	=	$admin->updatePlanTransaction($user_id,$paypal_return_transaction_id,$status,$paypal_before_transaction_id,$payment_type, $plan_name, $_SESSION['payment_upgrade']['user_type'], '', $number_of_users);

					$updateCreditCardDetail	=	$admin->updateCreditCardDetailTransaction($creditCardType, $creditCardNumber, $firstName, $lastName, $street, $city, $state, $zip, $countryCode,$paypal_before_transaction_id);
					
					$_SESSION['msgsuccess']='17';

				} 
				
				unset($_SESSION['trans_before_id']);
				unset($_SESSION['payment_upgrade']);
				unset($_SESSION['plan_data']);
				unset($_SESSION['db_membership_id']);
				unset($_SESSION['data']);

				header('location:viewReciept.php?id='.$paypal_before_transaction_id.'');
				
			} else {

				unset($_SESSION['trans_before_id']);
				unset($_SESSION['payment_upgrade']);
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
			unset($_SESSION['payment_upgrade']);
			unset($_SESSION['plan_data']);
			unset($_SESSION['db_membership_id']);
			unset($_SESSION['data']);
						
			$_SESSION['msgerror']='16';
			$_SESSION['creditcarderror'] = $ErrorLongMsg;
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
	unset($_SESSION['payment_upgrade']);
	
	$_SESSION['errormsg'] = $ErrorLongMsg;
	$_SESSION['creditcarderror'] = $ErrorLongMsg;
	header('location:'.URL_SITE.'/accountUpgrade.php?step='.base64_encode("PA").'');
}
?>