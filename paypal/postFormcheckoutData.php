<?php
/******************************************
* @Modified on 17 July, 2013
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
********************************************/
$basedir=dirname(__FILE__)."/..";
require_once($basedir.'/include/actionHeader.php');
require_once('config.php');

$admin = new admin();
$user = new user();

//echo "<pre>";print_r($_POST);echo "</pre>";die;

if(isset($_POST['mode'])) {

	$made_of_payment			=	$_POST['mode'];
	$plan_id					=	$_POST['plan_name'];	// Validity one year, 2 year, 3 year
	$total_amount				=	$_POST['total_amount'];
	$_SESSION['db_name']		=	$_POST['db_name'];
	$discountamount				=	$_POST['discount_amount'];
	$surchargeamount			=	$_POST['surcharge_amount'];


	$_SESSION['payment_data']	= $_POST;

	$_SESSION['billing']		=	$_POST['billing'];
	$_SESSION['technical']		=	$_POST['technical'];
	$_SESSION['admincontact']	=	$_POST['admincontact'];

	
	$_SESSION['billing']['copy_address']	= (isset($_POST['copy_address']))?'Y':'N';

	if($_SESSION['billing']['copy_address'] == 'Y'){
		$_SESSION['technical']['t_firstname']	= $_SESSION['billing']['b_firstname'];
		$_SESSION['technical']['t_lastname']	= $_SESSION['billing']['b_lastname'];
		$_SESSION['technical']['t_title']		= $_SESSION['billing']['b_title'];
		$_SESSION['technical']['t_phone']		= $_SESSION['billing']['b_phone'];
		$_SESSION['technical']['t_email']		= $_SESSION['billing']['b_email'];
		$_SESSION['technical']['t_address']		= $_SESSION['billing']['b_address'];

		$_SESSION['admincontact']['a_firstname']	= $_SESSION['billing']['b_firstname'];
		$_SESSION['admincontact']['a_lastname']		= $_SESSION['billing']['b_lastname'];
		$_SESSION['admincontact']['a_title']		= $_SESSION['billing']['b_title'];
		$_SESSION['admincontact']['a_phone']		= $_SESSION['billing']['b_phone'];
		$_SESSION['admincontact']['a_email']		= $_SESSION['billing']['b_email'];
	}

	
	if(isset($made_of_payment) && $made_of_payment == 'DoExpressCheckoutPayment') {
		$trans_before_id	=	$admin->insertPlanTransaction($plan_id, $total_amount, $discountamount, $surchargeamount);
		
		$_SESSION['trans_before_id']	=	$trans_before_id;
		
		header('location:'.URL_SITE.'/paypal/makepayment.php');
		
	}else { 

		$creditCardType	  =		$_POST['creditCardType']; 
		$creditCardNumber =		$_POST['creditCardNumber'];		
		$cvv2		      =		$_POST['cvv2']; 
		$firstName		  =		$_POST['firstName']; 
		$lastName		  =		$_POST['lastName'];		
		if(isset($_POST['address1']) && $_POST['address1']!=''){ $street  =	 $_POST['address1'];     }
		if(isset($_POST['address2']) && $_POST['address2']!=''){ $street .=	 ','.$_POST['address2']; }	
		
		$city		      =		$_POST['city']; 
		$state		      =		$_POST['state']; 
		$zip		      =		$_POST['zip']; 
		$countryCode      =		$_POST['countryCode'];

		$expDateMonth		  =		$_POST['expDateMonth']; 
		$expDateYear		  =		$_POST['expDateYear']; 		
		$expDate= date('my', mktime($expDateMonth, $expDateYear));

		$trans_before_id	=	$admin->insertPlanTransaction($plan_id, $total_amount, $discountamount, $surchargeamount);

		$_SESSION['trans_before_id']	=	$trans_before_id;
	
		header('location:'.URL_SITE.'/paypal/dodirectPayment.php');
	}
}
?>