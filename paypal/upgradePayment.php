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

//echo "<pre>";print_r($_SESSION);echo "</pre>";die;


if(!isset($_SESSION['user']) && !isset($_SESSION['payment_upgrade']) && !isset($_SESSION['trans_before_id'])){
	header('location: '.URL_SITE.'/cancel.php');
	exit;
}


if(isset($_SESSION['payment_upgrade']['plan_name']))   $planname = $_SESSION['payment_upgrade']['plan_name'];
if(isset($_SESSION['payment_upgrade']['mode']))     $redirectPaymentURL =	$_SESSION['payment_upgrade']['mode'];
if(isset($_SESSION['payment_upgrade']['total_amount'])) $paymentAmount = $_SESSION['payment_upgrade']['total_amount'];

$paymentAmount = round($paymentAmount, 2);

$typeDetail = $admin->getUserType($_SESSION['payment_upgrade']['user_type']);

$plan_name = $planname. " year (".ucwords($typeDetail['user_type']).")";

//
//echo "<pre>";print_r($_SESSION);echo "</pre>";die;
//die;
//'------------------------------------
//' The currencyCodeType and paymentType
//' are set to the selections made on the Integration Assistant
//'------------------------------------

$_SESSION['currencyCodeType']	=	$currencyCodeType = "USD";
$paymentType					=	"S";
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

$returnURL = URL_SITE.'/paypal/upgradeTransaction.php';

$resArray = CallShortcutExpressCheckout ($paymentAmount, $currencyCodeType, $paymentType, $returnURL,$cancelURL,$plan_name);

$ack = strtoupper($resArray["ACK"]);

if($ack=="SUCCESS" || $ack=="SUCCESSWITHWARNING")
{
	RedirectToPayPal ($resArray["TOKEN"] );	
}
else
{
	//Display a user friendly Error on the page using any of the following error information returned by PayPal
	$ErrorCode = urldecode($resArray["L_ERRORCODE0"]);
	$ErrorShortMsg = urldecode($resArray["L_SHORTMESSAGE0"]);
	$ErrorLongMsg = urldecode($resArray["L_LONGMESSAGE0"]);
	$ErrorSeverityCode = urldecode($resArray["L_SEVERITYCODE0"]);

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

	$_SESSION['msgerror']='16';
	header('location:'.URL_SITE.'/accountUpgrade.php');
}
?>