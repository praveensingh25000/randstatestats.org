<?php
/******************************************
* @Modified on Dec 19, 2012
* @Package: Rand
* @Developer: Mamta Sharma
* @URL : http://www.ideafoundation.in
********************************************/
include('include/actionHeader.php');

$user  = new user();
$admin = new admin();

if(isset($_GET['email'])){
	$email	= $_GET['email'];
	$result	= $user->checkEmailExistence($email,$status=1);
	if($result > 0) {
		echo 'false';		       
	} else {
		echo 'true';
	}
}

if(isset($_GET['username'])){
	$username = $_GET['username'];
	$result   = $user->checkEmailExistence($username, $status=2);
	if($result > 0) {
		echo 'false';		       
	} else {
		echo 'true';
	}
}
?>