<?php
/******************************************
* @Modified on 29 JAN, 2013
* @Package: Rand
* @Developer: Praveen Singh
* @URL : http://www.ideafoundation.in
********************************************/

//Paypal settings
$PAYPAL_MODE			=	PAYPAL_MODE;
$paypal_email			=	PAYPAL_USERNAME;
$paypal_password		=	PAYPAL_PASSWORD;
$paypal_signature		=	PAYPAL_SIGNATURE;
$paypal_shipping_cost	=	0;
//Paypal settings

if($PAYPAL_MODE == 0){
	$paypal_email = "nation_api1.randstatestats.org";
	$paypal_password = "1372943007";
	$paypal_signature = "AKI1gUD7zhpzgKjgLA9HCSH2CTI.AdpheqIqhEydwfj3muUPqVyC.X64";
}


define('PAYPAL_TEST_MODE',trim($PAYPAL_MODE));
define('PAYPAL_API_USERNAME',trim($paypal_email));
define('PAYPAL_API_PASSWORD',trim($paypal_password));
define('PAYPAL_API_SIGNATURE',trim($paypal_signature));
define('PAYPAL_RETURN_URL',URL_SITE.'/paypal/saveTransaction.php');
define('PAYPAL_DECLINE_URL',URL_SITE.'/paypal/cancel.php');
?>