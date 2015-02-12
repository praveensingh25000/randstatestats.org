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

$admin = new admin();
$user = new user();

if(isset($_SESSION['payment_upgrade']['db_name']) && isset($_SESSION['payment_upgrade']['dbidsown']) && count($_SESSION['payment_upgrade']['db_name']) && count($_SESSION['payment_upgrade']['dbidsown'])) {

	$dbsown = explode(',', $_SESSION['payment_upgrade']['dbidsown']);
	$dbspurchased = array();
	foreach($_SESSION['payment_upgrade']['db_name'] as $keydbm => $dbid){
		if(!in_array($dbid, $dbsown)){
			$dbspurchased[] = $dbid;
		}
	}

	$_SESSION['payment_upgrade']['db_name'] = $dbspurchased;
}

if(isset($_SESSION['payment_upgrade']) && isset($_SESSION['user'])) {

	$made_of_payment		=	$_SESSION['payment_upgrade']['mode'];
	$plan_id				=	$_SESSION['payment_upgrade']['plan_name'];	// Validity one year, 2 year, 3 year
	$total_amount			=	$_SESSION['payment_upgrade']['total_amount'];
	$_SESSION['db_name']	=	$_SESSION['payment_upgrade']['db_name'];
	$discountamount			=	$_SESSION['payment_upgrade']['discount_amount'];
	$surchargeamount		=	$_SESSION['payment_upgrade']['surcharge_amount'];

	$userid = $_SESSION['user']['id'];

	
	if(isset($made_of_payment) && $made_of_payment == 'DoExpressCheckoutPayment') {
		$trans_before_id	=	$admin->insertPlanTransaction($plan_id, $total_amount, $discountamount, $surchargeamount, $userid);
		
		$_SESSION['trans_before_id']	=	$trans_before_id;
		
		header('location:'.URL_SITE.'/paypal/upgradePayment.php');
		
	}else { 

		$creditCardType	  =		$_SESSION['payment_upgrade']['creditCardType']; 
		$creditCardNumber =		$_SESSION['payment_upgrade']['creditCardNumber'];		
		$cvv2		      =		$_SESSION['payment_upgrade']['cvv2']; 
		$firstName		  =		$_SESSION['payment_upgrade']['firstName']; 
		$lastName		  =		$_SESSION['payment_upgrade']['lastName'];		
		if(isset($_SESSION['payment_upgrade']['address1']) && $_SESSION['payment_upgrade']['address1']!=''){ $street  =	 $_SESSION['payment_upgrade']['address1'];     }
		if(isset($_SESSION['payment_upgrade']['address2']) && $_SESSION['payment_upgrade']['address2']!=''){ $street .=	 ','.$_SESSION['payment_upgrade']['address2']; }	
		
		$city		      =		$_SESSION['payment_upgrade']['city']; 
		$state		      =		$_SESSION['payment_upgrade']['state']; 
		$zip		      =		$_SESSION['payment_upgrade']['zip']; 
		$countryCode      =		$_SESSION['payment_upgrade']['countryCode'];

		$expDateMonth		  =		$_SESSION['payment_upgrade']['expDateMonth']; 
		$expDateYear		  =		$_SESSION['payment_upgrade']['expDateYear']; 		
		$expDate= date('my', mktime($expDateMonth, $expDateYear));

		$trans_before_id	=	$admin->insertPlanTransaction($plan_id, $total_amount, $discountamount, $surchargeamount, $userid);

		$_SESSION['trans_before_id']	=	$trans_before_id;
	
		header('location:'.URL_SITE.'/paypal/upgradeDirectPayment.php');
	}
} else {
	header('location:'.URL_SITE.'/accountUpgrade.php');
}
?>