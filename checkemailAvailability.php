<?php
/******************************************
* @Modified on 01 JAN, 2013
* @Package: Rand
* @Developer: Praveen Singh
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."";
include_once $basedir."/include/actionHeader.php";

$admin = new admin();
$user = new user();

if(isset($_GET['number_of_users_type'])) {

	$numberofuser=$_GET['numberofuser'];	
	$number_of_users_type=$_GET['number_of_users_type'];	
	
	$plansResult			= $admin->checknumberofusersPlanAvailability($numberofuser,$number_of_users_type);
	$countplanAvailability	= $dbDatabase->count_rows($plansResult);

	if($countplanAvailability > 0) {
		echo 'false';
	}
	else{
		echo 'true';
	}
}

if(isset($_GET['old_password'])) {

	$old_password=md5($_GET['old_password']);	
	$user_id=$_SESSION['user']['id'];
	
	//$userDetail = $user->checkePasswordAvailability($user_id);
	$userDetail = $user->getUser($user_id);
	

	if($userDetail['password'] == $old_password) {
		echo 'true';
	}
	else{
		echo 'false';
	}
}

if(isset($_GET['validity_type'])) {

	$validity=$_GET['validity'];	
	$validity_type=$_GET['validity_type'];	
	
	$countplanAvailability = $admin->checkPlanAvailability($validity,$validity_type);

	if($countplanAvailability > 0) {
		echo 'false';
	}
	else{
		echo 'true';
	}
}
?>