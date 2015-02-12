<?php
/******************************************
* @Modified on June 27, 2013
* @Package: Rand
* @Developer: Praveen Singh
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."";
include_once $basedir."/include/actionHeader.php";

$admin		= new admin();
$user		= new user();


if(isset($_POST['getresults'])){

	$_SESSION['searchedfieldsonestage'] = $_POST;
	if(isset($_REQUEST['dbnameurl']) && $_REQUEST['dbnameurl']!='') {
		$dbnameurl = $_REQUEST['dbnameurl'];
	}
	
	$user_id		=	$_POST['user_id'];	
	$userDetail		=	$user->getUser($user_id);	
	$validity_on	=	$admin->Validity(trim($userDetail['id']),trim($userDetail['email']));
	
	if(isset($validity_on) && $validity_on == '0') {
		$_SESSION['msgsuccess'] = '0';
		header('location: plansubscriptions.php');
		exit;
	} else {
		if(isset($dbnameurl) && $dbnameurl!='') {
			header('location: showSearchedData.php?dbc='.$dbnameurl.'');			
			exit;
		} else {
			header('location: showSearchedData.php');
			exit;
		}
	}
} else if($_POST['reset']){

	header('location: '.$_SERVER['HTTP_REFERER'].'');
	exit;

}

?>