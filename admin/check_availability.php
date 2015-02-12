<?php
/******************************************
* @Modified on Mar 7, 2013
* @Package: Rand
* @Developer: Pragati Garg
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."/..";
include_once $basedir."/include/actionHeader.php";

$admin = new admin();
$user  = new user();

//checking News title availability
if(isset($_REQUEST['news_title']) && isset($_REQUEST['action'])) {

	$news_title		= $_REQUEST['news_title'];
	$action_type	= $_REQUEST['action'];

	$newsDetail	= $admin->checkNewsTitleAvailability($news_title);

	if($action_type == 'add'){

		if(empty($newsDetail)) {
			echo 'true';
		} else {
			echo 'false';
		}

	} else {

		$current_news_title	= $_REQUEST['current_news_title'];
		
		if(!empty($newsDetail) && $newsDetail['news_title'] == trim($current_news_title)) {
			echo 'true';
		} else if(empty($newsDetail)) {
			echo 'true';
		} else {
			echo 'false';
		}
	}
} 

if(isset($_GET['page_name']))
{
	$page_name=$_GET['page_name'];
	$pageName_exixt=$admin->checkPageNameAvailability($page_name);

	if($pageName_exixt > 0)
	{
		echo 'false';
	}
	else
	{
		echo 'true';
	}
} 

//checking News title availability
if(isset($_REQUEST['plan_name']) && isset($_REQUEST['action'])) {

	$action_type	= $_REQUEST['action'];
	$planname		= $_REQUEST['plan_name'];
	$plan_type	= $_REQUEST['plan_type'];

	$planDetail	= $admin->checkplannameAvailability($planname,$plan_type);

	if($action_type == 'add'){

		if(empty($planDetail)) {
			echo 'true';
		} else {
			echo 'false';
		}
	} else {

		$curent_plan_name	= $_REQUEST['curent_plan_name'];
		
		if(!empty($planDetail) && $planDetail['plan_name']== trim($curent_plan_name)) {
			echo 'true';
		} else if(empty($planDetail)) {
			echo 'true';
		} else {
			echo 'false';
		}
	}
}

if(isset($_REQUEST['email'])){
	$email=$_REQUEST['email'];
	if(isset($_REQUEST['oldemail']) && $_REQUEST['oldemail']!='' && trim($_REQUEST['oldemail']) == trim($email)){
		echo 'true';
		exit;
	}

	$result=$user->checkEmailExistence($email, 1);
	
	if($result > 0) {
	   	echo 'false';		       
	} else {
		echo 'true';
	}
}

if(isset($_REQUEST['username'])){
	$username = $_REQUEST['username'];
	if(isset($_REQUEST['oldusername']) && $_REQUEST['oldusername']!='' && trim($_REQUEST['oldusername']) == trim($username)){
		echo 'true';
		exit;
	}
	$result   = $user->checkEmailExistence($username, 2);
	if($result > 0) {
		echo 'false';		       
	} else {
		echo 'true';
	}
}

//checking News title availability
if(isset($_REQUEST['ip_range_from']) && isset($_REQUEST['fromip'])) {

	$action_type	= $_REQUEST['action'];
	$ip_range_from	= $_REQUEST['ip_range_from'];

	if(isset($_REQUEST['ip_range_to'])) { $ip_range_to = $_REQUEST['ip_range_to']; };

	if($action_type == 'add'){
		
		if(preg_match("'[0-9]+[.]+[0-9]+[.][0-9]+[.][0-9]+'",trim($ip_range_from))) {
			echo 'true';
		} else {
			echo 'false';
		}
		
	} else {

		if(preg_match("'[0-9]+[.]+[0-9]+[.][0-9]+[.][0-9]+'",trim($ip_range_from))) {
			echo 'true';
		} else {
			echo 'false';
		}
	}
}

if(isset($_REQUEST['ip_range_to']) && isset($_REQUEST['toip'])) {

	$action_type	= $_REQUEST['action'];
	$ip_range_to	= $_REQUEST['ip_range_to'];

	if(isset($_REQUEST['ip_range_from'])) { $ip_range_from = $_REQUEST['ip_range_from']; };

	$toipaddress   = array_sum(explode('.',$ip_range_to));
	$fromipaddress = array_sum(explode('.',$ip_range_from));

	if($toipaddress > $fromipaddress) {

		if($action_type == 'add'){
			
			if(preg_match("'[1-9]+[.]+[0-9]+[.][0-9]+[.][0-9]+'",trim($ip_range_to))) {
				echo 'true';
			} else {
				echo 'false';
			}		

		} else {

			if(preg_match("'[1-9]+[.]+[0-9]+[.][0-9]+[.][0-9]+'",trim($ip_range_to))) {
				echo 'true';
			} else {
				echo 'false';
			}
		}
	} else {

		echo 'false';
	}
}
?>