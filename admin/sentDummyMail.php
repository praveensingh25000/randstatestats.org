<?php
/******************************************
* @Modified on July 10,2013
* @Package: RAND
* @Developer:Praveen Singh
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."/..";
include_once $basedir."/include/adminHeader.php";

global $db;

$admin				= new admin();
$user				= new admin();
$mail_notification  = 0;
$infoUsers = $infoUsers1 = $userMailArrayList  = $userMailArray		= $chuserMailArray	= array();
$infoUsersstr = $chikogouseridStr	= $noinDbcode = '';

die;

if(isset($_GET['list'])) {

	$sql_all_list	= "SELECT  * FROM rand_admin.database_users where is_active=1 ";
	$resultAllList  = mysql_query($sql_all_list);
	while($rowList  = mysql_fetch_assoc($resultAllList)){

		$userMailArrayList[$rowList['user_id']]['db_id'][]       =	$rowList['expire_time'];
		$userMailArrayList[$rowList['user_id']]['db_id_time'][]  =	$rowList['db_id'].' ( '.$rowList['expire_time'].' ) ';		
		//$userMailArrayList[$rowList['user_id']]['is_trial']    =	$rowList['is_trial'];
		//$userMailArrayList[$rowList['user_id']]['is_active']   =	$rowList['is_active'];
		$userMailArrayList[$rowList['user_id']]['count']         =	count($userMailArrayList[$rowList['user_id']]['db_id_time']);
	}

	//echo '<pre>';print_r($userMailArrayList);echo '</pre>';die;

	foreach($userMailArrayList as $key=>$users){
		if($users['count'] ==4){
			$time1	=	date('Y',strtotime($users['db_id'][0]));
			$time2	=	date('Y',strtotime($users['db_id'][1]));	
			$time3	=	date('Y',strtotime($users['db_id'][2]));
			$time4	=	date('Y',strtotime($users['db_id'][3]));

			$infoUsers[$key]=$users;
			$infoUsers[$key]['expire_time1']=$users['db_id'][0];
			$infoUsers[$key]['expire_time2']=$users['db_id'][1];
			$infoUsers[$key]['expire_time3']=$users['db_id'][2];
			$infoUsers[$key]['expire_time4']=$users['db_id'][3];
			$infoUsers[$key]['time1']=$time1;
			$infoUsers[$key]['time2']=$time2;
			$infoUsers[$key]['time3']=$time3;
			$infoUsers[$key]['time4']=$time4;
		}
	}

	//echo '<pre>';print_r($infoUsers);echo '</pre>';die;

	foreach($infoUsers as $key1=>$user){		
		if($user['time1'] !=$user['time4']) {
			$infoUsers1[$key1]=$user;
			$infoUsersstr[$key1]=$key1;
		}		
	}

	echo implode(',',$infoUsersstr);echo '<br>';echo '<br>';

	echo '<pre>';print_r($infoUsers1);echo '</pre>';die;
	
	foreach($infoUsers1 as $key2=>$user11){
		
		if($key2!=316){		
			$sqlupdate ="UPDATE database_users SET expire_time ='".$user11['expire_time1']."' where user_id='".$key2."'";
		} else {
			$sqlupdate ="UPDATE database_users SET expire_time ='".$user11['expire_time2']."' where user_id='".$key2."'";
		}
		//$resultAll  = mysql_query($sqlupdate);
		echo '<br>';		
	}	

	//echo '<pre>';print_r($infoUsers1);echo '</pre>';die;
}

die;

if(isset($_GET['key']) && isset($_GET['dbcode'])) {

	if(trim($_GET['dbcode']) == 'TX'){  
		$dbcode			= 1;
		$templatekey    = 6;
		$DbcodeArray	= array(2,3,4);
		$noinDbcode		= implode(',',$DbcodeArray);
		$subjectHead    = 'Changes to RAND Texas';
	} else if(trim($_GET['dbcode']) == 'CA'){  //MAIL SEND TO ALL USERS
		$dbcode			= 2;
		$templatekey    = 3;
		$DbcodeArray	= array(1,3,4);
		$noinDbcode		= implode(',',$DbcodeArray);
		$subjectHead    = 'Changes to RAND California';
	} else if(trim($_GET['dbcode']) == 'NY'){
		$dbcode			= 3;
		$templatekey    = 5;
		$DbcodeArray	= array(2,1,4);
		$noinDbcode		= implode(',',$DbcodeArray);
		$subjectHead    = 'Changes to RAND New York';
	} else if(trim($_GET['dbcode']) == 'US'){ //MAIL SEND TO ALL USERS
		$dbcode			= 4;
		$templatekey    = 4;
		$DbcodeArray	= array(2,3,1);
		$noinDbcode		= implode(',',$DbcodeArray);
		$subjectHead    = 'Changes to RAND State Statistics';
	}

	$sql_ch       = "SELECT * FROM rand_admin.users WHERE id > 444 and id <= 692 ";
	$result_ch    = mysql_query($sql_ch);
	while($chrow  = mysql_fetch_assoc($result_ch)){
		$chuserMailArray[]	=	$chrow['id'];
	}
	$chikogouseridStr = implode(',',$chuserMailArray);
	//echo '<br>';echo '<br>';

	$sql_all		= "SELECT  * FROM rand_admin.database_users where user_id NOT IN (".$chikogouseridStr.") and is_trial = 1 and db_id='".$dbcode."' ";
	$resultAll		       = mysql_query($sql_all);
	while($rowUser		   = mysql_fetch_assoc($resultAll)){
		$userMailArray1[$rowUser['user_id']][]   =	$rowUser['db_id'];
	}

	//echo '<pre>';print_r($userMailArray1);echo '<pre>'; die;

	if(!empty($userMailArray1)) {
		foreach($userMailArray1 as $userid => $valuesAll){
			
			$sql_user_send     = "SELECT * FROM rand_admin.users WHERE id = '".$userid."' and id < 692 order by id ASC";
			$result_user_send  = mysql_query($sql_user_send);
			$userMail		   = mysql_fetch_assoc($result_user_send);
			
			if(!empty($userMail)){
				$userMailArrayaa[] = $userid;
				$userMailArray[$userMail['id']]   = $userMail;
			}			
		}
	}

	//echo $userMailArrayaastr=implode(',',$userMailArrayaa);	
	//echo '<pre>';print_r($userMailArrayaa);echo '</pre>';
	//echo '<pre>';print_r($userMailArray);echo '<pre>';die;
	//$userMailArray[] = array('email'=>'shalini@ideafoundation.co.in','name'=> 'Shalini');
	//$userMailArray[] = array('email'=>'nation@randstatestats.org','name'=> 'Nation');
	//$userMailArray[] = array('email'=>'praveensingh2500@gmail.com','name'=> 'Praveen');

	//echo '<pre>';print_r($userMailArray);echo '<pre>';die;

	if(!empty($userMailArray)) {	

		foreach($userMailArray as $userDetail){

			if($userDetail['email'] != '') {
				
					$receivermail		=	$userDetail['email'];
					$receivename		=	ucwords($userDetail['name']);
					$from_name			=	trim(FROM_NAME);
					$from_email			=	trim(FROM_EMAIL);
					$userDefinedvalue   =   array();

					echo $mailbody		=	getMailTemplate($templatekey, $receivename, $receivermail, $from_name, $from_email,$userDefinedvalue);
				
				if(isset($mail_notification) && $mail_notification == '1'){					
					//$subject      = $subjectHead;				
					//$send_mail	  = mail_function($receivename,$receivermail,$from_name,$from_email,$mailbody,$subject, $attachments = array(),$addcc=array());
				}

				echo '<br>';echo '<br>';
				echo '<h2>Mail Sent to <b>'.$receivename.'</b> Sucessfully</h2>';
				echo '<br>';echo '<br>';
			}
		}
		
		echo '<br>';echo '<br>';
		echo '<h1>Mail Sent to all Sucessfully</h1>';
		echo '<br>';echo '<br>';
	}

} else if(isset($_GET['key']) && isset($_GET['id'])) {

	$user_id			=	$_GET['id'];
	$templatekey		=	$_GET['key'];
	$sql				=	"SELECT * FROM rand_admin.users WHERE id = '".$user_id."'";
	$result				=	mysql_query($sql);
	$userDetail			=	mysql_fetch_assoc($result);

	$receivermail		=	$userDetail['email'];
	$receivename		=	ucwords($userDetail['name']);
	$from_name			=	trim(FROM_NAME);
	$from_email			=	trim(FROM_EMAIL);
	$userDefinedvalue   =   array();

	echo $mailbody		=	getMailTemplate($templatekey, $receivename, $receivermail, $from_name, $from_email,$userDefinedvalue);

	if(isset($mail_notification) && $mail_notification == '1'){
		//$subject	  =	'Changes to RAND State Statistics';
		//$subject	  = 'Changes to RAND California';
		//$subject	  =	'Changes to RAND New York';
		//$subject	  =	'Changes to RAND Texas';
		//$send_mail	  = mail_function($receivename,$receivermail,$from_name,$from_email,$mailbody,$subject, $attachments  = array(),$addcc=array());
	}

	echo '<br>';echo '<br>';
	echo '<h2>Mail Sent to <b>'.$receivename.'</b> Sucessfully</h2>';
	echo '<br>';echo '<br>';

} else if(isset($_GET['key'])) {

	$user_id			=	'700';
	$templatekey		=	$_GET['key'];
	$sql				=	"SELECT * FROM rand_admin.users WHERE id = '".$user_id."'";
	$result				=	mysql_query($sql);
	$userDetail			=	mysql_fetch_assoc($result);

	$receivermail		=	$userDetail['email'];
	$receivename		=	ucwords($userDetail['name']);
	$from_name			=	trim(FROM_NAME);
	$from_email			=	trim(FROM_EMAIL);
	$userDefinedvalue   =   array();

	echo $mailbody		=	getMailTemplate($templatekey, $receivename, $receivermail, $from_name, $from_email,$userDefinedvalue);

	if(isset($mail_notification) && $mail_notification == '1'){
		//$subject	  ='Free Trial to RAND State Statistics';				
		//$send_mail	  = mail_function($receivename,$receivermail,$from_name,$from_email,$mailbody,$subject, $attachments  = array(),$addcc=array());
	}

	echo '<br>';echo '<br>';
	echo '<h2>Mail Sent to <b>'.$receivename.'</b> Sucessfully</h2>';
	echo '<br>';echo '<br>';

} else{
	echo '<br>';echo '<br>';echo '<br>';echo '<br>';
	echo '<h2>Enter Template Key and Database Code in the above URl in the format(?key=3&dbcode=CA)</h2>';
	echo '<br>';echo '<br>';echo '<h2>OR</h2>';echo '<br>';echo '<br>';
	echo '<h2>Enter Template Key and userid in the above URl in the format(?key=1&id=205)</h2>';
}
?>