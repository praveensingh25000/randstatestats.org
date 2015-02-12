<?php
/******************************************
* @Modified on Dec 06, 2012
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
********************************************/

ob_start();
session_start();
ini_set("display_errors","2");
ERROR_REPORTING(E_ALL);

require_once('connect.php');
require_once($DOC_ROOT.'classes/database.class.php');
require_once($DOC_ROOT.'classes/searchCriteriaClass.php');
require_once($DOC_ROOT.'classes/TempAdmin.php');
require_once($DOC_ROOT.'classes/admin.class.php');
require_once($DOC_ROOT.'classes/user.class.php');
require_once($DOC_ROOT.'classes/fish.class.php');
require_once($DOC_ROOT.'classes/mailer.class.php');
require_once($DOC_ROOT.'classes/categoryClass.php');
require_once($DOC_ROOT.'include/functions.php');
require_once($DOC_ROOT.'include/mail_template.php');
require_once($DOC_ROOT.'classes/emailTemp.class.php');

$db = new db(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DB);
$admin = new admin();

$generalSettings = fetchGenralSettings();

if(!isset($_SESSION['generalSettings'])){	
	foreach($generalSettings as $key => $groups){
		foreach($groups as $key => $setting){
			$name = strtoupper($setting['name']);
			define($name,$setting['value']);		
		}
	}
}

if(isset($_GET['db']) && $_GET['db']!=''){

	if(isset($_SESSION['databaseToBeUse'])) { unset($_SESSION['databaseToBeUse']);}
	if(isset($_SESSION['categoryid'])) { unset($_SESSION['categoryid']);}
	
	$_SESSION['databaseToBeUse'] = $_GET['db'];
	header('location: '.URL_SITE.'/changeDatabase.php');
}

if(isset($_POST['databaseToBeUse']) && $_POST['databaseToBeUse']!='') {

	if(isset($_SESSION['databaseToBeUse'])) { unset($_SESSION['databaseToBeUse']);}
	if(isset($_SESSION['categoryid'])) { unset($_SESSION['categoryid']);}

	$_SESSION['databaseToBeUse'] = trim($_POST['databaseToBeUse']);
}

if(isset($_SESSION['databaseToBeUse']) && $_SESSION['databaseToBeUse']!=''){
	$_SESSION['databaseToBeUse'] = trim($_SESSION['databaseToBeUse']);
} else {
	$_SESSION['databaseToBeUse'] = 'rand_texas';
}

if(isset($_SESSION['databaseToBeUseajax']) && $_SESSION['databaseToBeUseajax']!=''){
	$siteMainDBDetail = $admin->getMainDbDetail($_SESSION['databaseToBeUseajax']);
} else {
	$siteMainDBDetail = $admin->getMainDbDetail($_SESSION['databaseToBeUse']);
}

$dbDatabase = $dbtaxas =  new db(DATABASE_HOST, $siteMainDBDetail['databaseusername'], $siteMainDBDetail['databasepassword'], $siteMainDBDetail['databasename']);

$groupsArray = array(0 => 'Site Settings', 1 => 'Email Settings', 2 => 'Contact Settings', 3 => 'Facebook Settings', 4 => 'Twitter Settings', 5 => 'Google Settings', '6' => 'Linked In Settings','7' => 'Group Plan Setting');

$googleKey = 'AIzaSyAgmdNJbGuHyDDQYns0RZxwVb1o8VBtADU';

$mail_notification = MAIL_NOTIFICATION;
?>