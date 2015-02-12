<?php
/******************************************
* @Modified on Dec 25, 2012
* @Package: Rand
* @Developer: Mamta sharma
* @URL : http://www.ideafoundation.in
********************************************/
$basedir=dirname(__FILE__)."/..";
include_once $basedir."/include/adminHeader.php";

checkSession(true);

$admin = new admin();

//add single subscriptions
if(isset($_POST['single']) || isset($_POST['multiple']) || isset($_POST['plan_type'])){

	$array	= $array1	=	$db_amount	= array();
	
	foreach ($_POST as $key => $element) {
		if(is_numeric($key)) {
		$array[$key]=$element;
		}
	}

	if(!empty($array)){
		foreach ($array as $key => $values) {
			$array1[]=$key.'-'.$values;
		}
		$db_amount=implode('/',$array1);
	}

	$submission_type=$_POST['submission_type'];

	//addition
	if(isset($submission_type) && ($submission_type=='add_single' || $submission_type=='add_multiple' || $submission_type=='add_'.$_POST['plan_type'].'')) {	
		
		$planid = $admin->insertsubscriptionsPlan($db_amount);
		
		if($planid > 0){
			$_SESSION['msgsuccess'] = "Subscription Plan has been added successfully.";
			if($_POST['plan_type']=='single' || $_POST['plan_type']=='multiple'){
				header('location: subscriptions.php?type='.$_POST['plan_type'].'&action=viewall&id='.base64_encode($_POST['subscriptionid']).'');
			}else{
				header('location: subscription_others.php?type='.$_POST['plan_type'].'&action=viewall&id='.base64_encode($_POST['subscriptionid']).'');
			}
			exit;
		}
		else {
			$_SESSION['msgerror'] = "Subscription Plan has not been added.";
			if($_POST['plan_type']=='single' || $_POST['plan_type']=='multiple'){
				header('location: subscriptions.php?type='.$_POST['plan_type'].'&action=viewall&id='.base64_encode($_POST['subscriptionid']).'');
			}else{
				header('location: subscription_others.php?type='.$_POST['plan_type'].'&action=viewall&id='.base64_encode($_POST['subscriptionid']).'');
			}
			exit;
		}
	}
	
	//updation
	if(isset($submission_type) && ($submission_type=='edit_single' || $submission_type=='edit_multiple' || $submission_type=='edit_'.$_POST['plan_type'].'')) {	
		
		$planid = $admin->updatesubscriptionsPlan($db_amount);
		if($planid > 0){
			$_SESSION['msgsuccess'] = "Subscription Plan has been Updated successfully.";
			if($_POST['plan_type']=='single' || $_POST['plan_type']=='multiple'){
				header('location: subscriptions.php?type='.$_POST['plan_type'].'&action=view&id='.base64_encode($_POST['subscriptionid']).'&planid='.base64_encode($_POST['planid']).'');
			}else{
				header('location: subscription_others.php?type='.$_POST['plan_type'].'&action=view&id='.base64_encode($_POST['subscriptionid']).'&planid='.base64_encode($_POST['planid']).'');
			}
			exit;
		}
		else {
			$_SESSION['msgerror'] = "Subscription Plan has not been Updated.";
			if($_POST['plan_type']=='single' || $_POST['plan_type']=='multiple'){
				header('location: subscriptions.php?type='.$_POST['plan_type'].'&action=view&id='.base64_encode($_POST['subscriptionid']).'&planid='.base64_encode($_POST['planid']).'');
			}else{
				header('location: subscription_others.php?type='.$_POST['plan_type'].'&action=view&id='.base64_encode($_POST['subscriptionid']).'&planid='.base64_encode($_POST['planid']).'');
			}
			exit;
		}
	}	
	
	
}
?>