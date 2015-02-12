<?php
/******************************************
* @Modified on Dec 26, 2012,01 JAN 2013
* @Package: Rand
* @Developer: Praveen Singh
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."";
include_once $basedir."/include/headerHtml.php";

if(isset($_REQUEST['provider'])){ 

	$admin = new admin();
	$user = new user();

	$provider	=	$_REQUEST['provider'];
	$uid		=	$_REQUEST['UID'];
	
	if($provider=='twitter'){
		$user_email =	trim($_REQUEST['nickname']);
	} else {
		$user_email =	trim($_REQUEST['email']);
	}
	$password=uniqid();
	$enct_password=md5($password);
	
	$first_name =	mysql_real_escape_string($_REQUEST['firstName'].' '.$_REQUEST['lastName']);
	$user_image =	mysql_real_escape_string($_REQUEST['photoURL']);
	$address	=	mysql_real_escape_string($_REQUEST['city'].','.$_REQUEST['state'].'-'.$_REQUEST['zip'].','.$_REQUEST['country']);
	$user_type='Other';

	if($provider=='facebook'){ $login_type='F';}
	if($provider=='twitter'){ $login_type='T';}
	if($provider=='google'){ $login_type='G';}
	if($provider=='linkedin'){ $login_type='L';}
	
	$userDetailArray = $user->registerSocialNetUser($user_email,$enct_password, $first_name, $user_image, $address, $user_type,$provider,$login_type);
	
	if($userDetailArray[0] == 0) {	//New user get registered then he/she receive the Mail.

		$receivermail	=	$user_email;
		$password		=	$password;
		$receivename	=	$first_name;
		$fromname		=	'RAND Project';
		$fromemail		=	'RAND Project';
		$mailbody		=	'Hi '.$first_name.', <br /><p>You have successfully created a Rand Account! Please Check your login Detail. </p><br><p><b>Email</b>: '.$receivermail.' </p></p><p><b>password</b>: '.$password.' </p><br><p>Thank you </p><p>Rand Team </p>';
		$subject='Sinup Verfication Mail through Social Newwork';		
		//$send_mail= mail_function($receivename,$receivermail,$fromname,$fromemail,$mailbody,$subject, $attachments = array(),$addcc=array());
	}

	$userDetail			=	$user->selectUserProfile($user_email);
	$_SESSION['user']	=   $userDetail;	//setting session
	$validity_on		=	$admin->Validity($userDetail['id'],$user_email);
		
	if(isset($validity_on) && $validity_on == '0') {
		$_SESSION['msgsuccess'] = '0';
		header('location: plansubscriptions.php');
		exit;
	} else if(isset($_SESSION['twostageurlset']) && $_SESSION['twostageurlset'] !='') {

		$twostageurlstr		=	$_SESSION['twostageurlset'];			
		$twostageurlArray	=	explode('/',$twostageurlstr);
		$postVararray		=	explode('.',$twostageurlArray[2]);
		$data_post_var		=	trim($postVararray[0].'Data'.'.'.$postVararray[1]);
		$redirect=URL_SITE.'/'.$twostageurlArray[0].'/'.$twostageurlArray[1].'/'.$data_post_var;

		if(isset($_SESSION['dbnameurl']) && $_SESSION['dbnameurl']!='') {
			$redirect=URL_SITE.'/'.$twostageurlArray[0].'/'.$twostageurlArray[1].'/'.$data_post_var.'?dbc='.$_SESSION['dbnameurl'].'';
		} else {
			$redirect=URL_SITE.'/'.$twostageurlArray[0].'/'.$twostageurlArray[1].'/'.$data_post_var;
		}

		$_SESSION['msgsuccess'] = '1';
		header('location: '.$redirect.'');
		exit;

	} else if(!empty($_SESSION['searchedfieldsonestage'])){

		$_SESSION['msgsuccess'] = '1';
		if(isset($_SESSION['dbnameurl']) && $_SESSION['dbnameurl']!='') {
			header('location: showSearchedData.php?dbc='.$_SESSION['dbnameurl'].'');
			exit;
		} else {
			header('location: showSearchedData.php');
			exit;
		}

	} else {
		$_SESSION['msgsuccess'] = '1';
		header('location: index.php');
		exit;
	}
}